<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController {

    public function __construct() {
        helper(['form']);
    }

    public function index() {
        $db = \Config\Database::connect();
        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        $modo_cadastro = $dadosCorp ? (int)($dadosCorp->modo_cadastro ?? 1) : 1;
        return view('Login/escolha', ['titulo' => 'Escolha uma opção', 'modo_cadastro' => $modo_cadastro]);
    }

    public function novo() {
        $db = \Config\Database::connect();
        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        $modo_cadastro = $dadosCorp ? (int)($dadosCorp->modo_cadastro ?? 1) : 1;

        $data = [
            'titulo' => "Realize o login",
            'modo_cadastro' => $modo_cadastro
        ];
        return view('Login/novo', $data);
    }

    /**
     * Cadastrar cliente (modo 3): nome + celular → cria cliente e entra na home
     */
    public function cadastrar() {
        $db = \Config\Database::connect();
        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        $modo_cadastro = $dadosCorp ? (int)($dadosCorp->modo_cadastro ?? 1) : 1;

        if ($this->request->getMethod() === 'GET') {
            return view('Login/cadastrar', ['titulo' => 'Criar conta', 'modo_cadastro' => $modo_cadastro]);
        }

        // POST: criar cliente
        $nome   = trim($this->request->getPost('nome') ?? '');
        $celular = trim($this->request->getPost('celular') ?? '');

        if (empty($nome)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, informe seu nome');
        }
        if (empty($celular)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, informe seu celular');
        }

        $telefoneNumeros = preg_replace("/[^0-9]/", "", $celular);

        // Verificar se já existe
        $clienteExistente = $db->query(
            "SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
            [$telefoneNumeros]
        )->getRow();

        if ($clienteExistente) {
            // Já cadastrado: logar direto
            session()->set('cliente_id', $clienteExistente->id);
            session()->set('cliente_telefone', $celular);
            session()->set('cliente_nome', $clienteExistente->nome);
            return redirect()->to('/')->with('sucesso', "Bem-vindo(a) de volta, {$clienteExistente->nome}!");
        }

        try {
            $db->table('clientes')->insert([
                'nome'         => $nome,
                'telefone'     => $celular,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
            $clienteId = $db->insertID();
            session()->set('cliente_id', $clienteId);
            session()->set('cliente_telefone', $celular);
            session()->set('cliente_nome', $nome);
            return redirect()->to('/')->with('sucesso', "Bem-vindo(a), {$nome}! Conta criada com sucesso.");
        } catch (\Exception $e) {
            log_message('error', 'Login::cadastrar - Erro: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('atencao', 'Erro ao cadastrar. Tente novamente.');
        }
    }

    public function criar() {
        log_message('info', '=== Login::criar INÍCIO ===');

        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->back()->with('atencao', 'Método não permitido');
        }

        $login_tipo = $this->request->getPost('login_tipo');
        $email      = $this->request->getPost('email');
        $celular    = $this->request->getPost('celular');
        $nome       = $this->request->getPost('nome');
        $password   = $this->request->getPost('password');

        $db = \Config\Database::connect();

        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        $modo_cadastro = $dadosCorp ? (int)($dadosCorp->modo_cadastro ?? 1) : 1;

        // Modo 3: entrar na home apenas por celular (clientes); admin usa celular+senha
        if ($modo_cadastro == 3) {
            if (empty($celular)) {
                return redirect()->back()->withInput()->with('atencao', 'Por favor, informe seu celular');
            }

            $telefoneNumeros = preg_replace("/[^0-9]/", "", $celular);

            // Verificar se é admin
            $admin = $db->query(
                "SELECT * FROM usuarios WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
                [$telefoneNumeros]
            )->getRow();

            if ($admin) {
                if (empty($password)) {
                    return redirect()->back()->withInput()->with('atencao', 'Por favor, informe a senha');
                }
                $autenticacao = service('autenticacao');
                if ($autenticacao->login($admin->email, $password)) {
                    $usuario = $autenticacao->pegaUsuarioLogado();
                    if (!$usuario->ativo) {
                        $autenticacao->logout();
                        return redirect()->back()->with('atencao', 'Sua conta está desativada.');
                    }
                    return redirect()->to($usuario->is_admin ? site_url('admin/home') : site_url('admin/pedidos'))
                        ->with('sucesso', "Olá {$usuario->nome}!");
                }
                return redirect()->back()->withInput()->with('atencao', 'Senha incorreta');
            }

            $cliente = $db->query(
                "SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
                [$telefoneNumeros]
            )->getRow();

            if (!$cliente) {
                return redirect()->back()->withInput()->with('atencao', 'Celular não cadastrado. <a href="' . site_url('login/cadastrar') . '">Cadastre-se aqui</a>.');
            }

            session()->set('cliente_id', $cliente->id);
            session()->set('cliente_telefone', $celular);
            session()->set('cliente_nome', $cliente->nome);
            return redirect()->to('/')->with('sucesso', "Bem-vindo(a), {$cliente->nome}!");
        }

        // Modo 2: entrar na home apenas por celular; admin usa celular+senha
        if ($modo_cadastro == 2) {
            if (empty($celular)) {
                return redirect()->back()->withInput()->with('atencao', 'Por favor, informe seu celular');
            }

            $telefoneNumeros = preg_replace("/[^0-9]/", "", $celular);

            // Verificar se é admin
            $admin = $db->query(
                "SELECT * FROM usuarios WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
                [$telefoneNumeros]
            )->getRow();

            if ($admin) {
                if (empty($password)) {
                    return redirect()->back()->withInput()->with('atencao', 'Por favor, informe a senha');
                }
                $autenticacao = service('autenticacao');
                if ($autenticacao->login($admin->email, $password)) {
                    $usuario = $autenticacao->pegaUsuarioLogado();
                    if (!$usuario->ativo) {
                        $autenticacao->logout();
                        return redirect()->back()->with('atencao', 'Sua conta está desativada.');
                    }
                    return redirect()->to($usuario->is_admin ? site_url('admin/home') : site_url('admin/pedidos'))
                        ->with('sucesso', "Olá {$usuario->nome}!");
                }
                return redirect()->back()->withInput()->with('atencao', 'Senha incorreta');
            }

            $cliente = $db->query(
                "SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
                [$telefoneNumeros]
            )->getRow();

            if ($cliente) {
                session()->set('cliente_id', $cliente->id);
                session()->set('cliente_telefone', $celular);
                session()->set('cliente_nome', $cliente->nome);
                if (!empty($cliente->email)) session()->set('cliente_email', $cliente->email);
                return redirect()->to('/')->with('sucesso', "Bem-vindo(a), {$cliente->nome}!");
            }

            return redirect()->to(site_url('registrar/semVerificacao'))->with('atencao', 'Celular não cadastrado. Por favor, crie sua conta.');
        }

        // Modo 1: login tradicional
        if ($login_tipo === 'celular') {
            log_message('info', 'Login::criar - Modo 1: login por celular');
            if (empty($celular)) {
                return redirect()->back()->withInput()->with('atencao', 'Por favor, preencha o número de celular');
            }
            $telefoneNumeros = preg_replace("/[^0-9]/", "", $celular);
            $cliente = $db->query(
                "SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
                [$telefoneNumeros]
            )->getRow();
            if ($cliente) {
                session()->set('cliente_id', $cliente->id);
                session()->set('cliente_telefone', $celular);
                session()->set('cliente_nome', $cliente->nome);
                return redirect()->to('/')->with('sucesso', "Bem-vindo(a), {$cliente->nome}!");
            }
            return redirect()->back()->withInput()->with('atencao', 'Celular não cadastrado.');
        }

        if (empty($email)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, preencha o e-mail');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, digite um e-mail válido');
        }

        $cliente = $db->query("SELECT * FROM clientes WHERE email = ?", [$email])->getRow();
        if ($cliente) {
            session()->set('cliente_id', $cliente->id);
            session()->set('cliente_email', $email);
            session()->set('cliente_nome', $cliente->nome);
            return redirect()->to('/')->with('sucesso', "Bem-vindo(a), {$cliente->nome}!");
        }

        $usuarioAdmin = $db->query("SELECT * FROM usuarios WHERE email = ?", [$email])->getRow();
        if (!$usuarioAdmin) {
            return redirect()->back()->withInput()->with('atencao', 'E-mail não cadastrado.');
        }

        if (empty($password)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, preencha a senha');
        }

        $autenticacao = service('autenticacao');
        if ($autenticacao->login($email, $password)) {
            $usuario = $autenticacao->pegaUsuarioLogado();
            if (!$usuario->ativo) {
                $autenticacao->logout();
                return redirect()->back()->with('atencao', 'Sua conta está desativada. Entre em contato com o suporte.');
            }
            if ($usuario->is_admin) {
                return redirect()->to(site_url('admin/home'))->with('sucesso', "Olá {$usuario->nome}, que bom que está de volta!");
            } else {
                return redirect()->to(site_url('admin/pedidos'))->with('sucesso', "Bem-vindo(a), {$usuario->nome}! Você foi direcionado para a área de Pedidos.");
            }
        }
        return redirect()->back()->withInput()->with('atencao', 'Senha incorreta');
    }

    public function logout() {
        $autenticacao = service('autenticacao');
        $usuario = $autenticacao->pegaUsuarioLogado();
        $nomeUsuario = $usuario ? $usuario->nome : 'Usuário';
        $autenticacao->logout();
        return redirect()->to(site_url('login'))->with('info', "Até logo, {$nomeUsuario}! Esperamos ver você novamente.");
    }

    public function verificarEmail() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }
        $json = $this->request->getJSON();
        $email = $json->email ?? '';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Email inválido']);
        }
        $db = \Config\Database::connect();
        $usuario = $db->query("SELECT email FROM usuarios WHERE email = ?", [$email])->getRow();
        if ($usuario) {
            return $this->response->setJSON(['tipo' => 'admin', 'requer_senha' => true]);
        }
        $cliente = $db->query("SELECT email FROM clientes WHERE email = ?", [$email])->getRow();
        if ($cliente) {
            return $this->response->setJSON(['tipo' => 'cliente', 'requer_senha' => false]);
        }
        return $this->response->setJSON(['tipo' => 'nao_encontrado', 'requer_senha' => false]);
    }

    public function buscar_cep() {
        $cep = $this->request->getVar('cep');
        $cep = preg_replace("/[^0-9]/", "", $cep);
        if (strlen($cep) != 8) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Formato de CEP inválido.']);
        }
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resposta = curl_exec($ch);
        curl_close($ch);
        $dados = json_decode($resposta, true);
        if (isset($dados['erro'])) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'CEP não encontrado.']);
        }
        return $this->response->setJSON($dados);
    }

    public function verificarNomeCelular() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }
        $json = $this->request->getJSON();
        $nome = trim($json->nome ?? '');
        $celular = preg_replace("/[^0-9]/", "", $json->celular ?? '');

        if (empty($nome) || strlen($celular) < 10) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Preencha nome e celular']);
        }

        $db = \Config\Database::connect();
        $usuario = $db->query(
            "SELECT id FROM usuarios WHERE nome = ? AND REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
            [$nome, $celular]
        )->getRow();

        if ($usuario) {
            return $this->response->setJSON(['encontrado' => true]);
        }
        return $this->response->setJSON(['encontrado' => false, 'msg' => 'Nome ou celular não encontrado']);
    }

    public function verificarTelefone() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }
        $json = $this->request->getJSON();
        $telefone = $json->telefone ?? '';
        $telefone = preg_replace("/[^0-9]/", "", $telefone);
        if (strlen($telefone) < 10 || strlen($telefone) > 11) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Telefone inválido']);
        }
        $db = \Config\Database::connect();
        $cliente = $db->query(
            "SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
            [$telefone]
        )->getRow();
        if ($cliente) {
            session()->set('cliente_telefone', $telefone);
            return $this->response->setJSON(['sucesso' => true, 'tipo' => 'cliente', 'msg' => 'Telefone encontrado']);
        }
        return $this->response->setJSON(['erro' => true, 'msg' => 'Telefone não cadastrado']);
    }

    public function verificarTelefoneAdmin() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }
        $json = $this->request->getJSON();
        $telefone = preg_replace("/[^0-9]/", "", $json->telefone ?? '');
        if (strlen($telefone) < 10) {
            return $this->response->setJSON(['is_admin' => false]);
        }
        $db = \Config\Database::connect();
        $admin = $db->query(
            "SELECT id FROM usuarios WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
            [$telefone]
        )->getRow();
        return $this->response->setJSON(['is_admin' => (bool)$admin]);
    }
}
