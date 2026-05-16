<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Login extends BaseController
{
    public function __construct()
    {
        helper(['form']);
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        $modo_cadastro = $dadosCorp ? (int)($dadosCorp->modo_cadastro ?? 1) : 1;
        return view('Login/escolha', ['titulo' => 'Escolha uma opção', 'modo_cadastro' => $modo_cadastro]);
    }

    public function novo()
    {
        $db = \Config\Database::connect();
        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        $modo_cadastro = $dadosCorp ? (int)($dadosCorp->modo_cadastro ?? 1) : 1;
        return view('Login/novo', ['titulo' => 'Realize o login', 'modo_cadastro' => $modo_cadastro]);
    }

    public function cadastrar()
    {
        $db = \Config\Database::connect();
        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        $modo_cadastro = $dadosCorp ? (int)($dadosCorp->modo_cadastro ?? 1) : 1;

        if ($this->request->getMethod() === 'GET') {
            return view('Login/cadastrar', ['titulo' => 'Criar conta', 'modo_cadastro' => $modo_cadastro]);
        }

        $nome    = trim($this->request->getPost('nome') ?? '');
        $celular = trim($this->request->getPost('celular') ?? '');

        if (empty($nome)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, informe seu nome');
        }
        if (empty($celular)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, informe seu celular');
        }

        $telefoneNumeros = preg_replace('/[^0-9]/', '', $celular);

        $clienteExistente = $db->query(
            "SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone,'(',''),')',''),'-',''),' ','') = ?",
            [$telefoneNumeros]
        )->getRow();

        if ($clienteExistente) {
            return $this->_logarCliente($clienteExistente, "Bem-vindo(a) de volta, {$clienteExistente->nome}!");
        }

        try {
            $db->table('clientes')->insert([
                'nome'         => $nome,
                'email'        => null,
                'telefone'     => $celular,
                'modo_cadastro'=> 3,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
            $novoCliente = (object)[
                'id'       => $db->insertID(),
                'nome'     => $nome,
                'telefone' => $celular,
                'email'    => '',
            ];
            return $this->_logarCliente($novoCliente, "Bem-vindo(a), {$nome}! Conta criada com sucesso.");
        } catch (\Exception $e) {
            log_message('error', 'Login::cadastrar - ' . $e->getMessage());
            return redirect()->back()->withInput()->with('atencao', 'Erro ao cadastrar. Tente novamente.');
        }
    }

    public function criar()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(site_url('login'));
        }

        $db            = \Config\Database::connect();
        $dadosCorp     = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        $modo_cadastro = $dadosCorp ? (int)($dadosCorp->modo_cadastro ?? 1) : 1;

        $celular  = trim($this->request->getPost('celular') ?? '');
        $email    = trim($this->request->getPost('email') ?? '');
        $password = $this->request->getPost('password') ?? '';

        // ── Modos 2 e 3: login por celular ──────────────────────────────────
        if ($modo_cadastro >= 2) {
            if (empty($celular)) {
                return redirect()->back()->withInput()->with('atencao', 'Por favor, informe seu celular');
            }

            $tel = preg_replace('/[^0-9]/', '', $celular);

            // Admin?
            $admin = $db->query(
                "SELECT * FROM usuarios WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone,'(',''),')',''),'-',''),' ','') = ?",
                [$tel]
            )->getRow();

            if ($admin) {
                if (empty($password)) {
                    return redirect()->back()->withInput()->with('atencao', 'Por favor, informe a senha');
                }
                return $this->_logarAdmin($admin, $password);
            }

            // Cliente
            $cliente = $db->query(
                "SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone,'(',''),')',''),'-',''),' ','') = ?",
                [$tel]
            )->getRow();

            if ($cliente) {
                return $this->_logarCliente($cliente, "Bem-vindo(a), {$cliente->nome}!");
            }

            if ($modo_cadastro == 3) {
                return redirect()->back()->withInput()->with('atencao', 'Celular não cadastrado. <a href="' . site_url('login/cadastrar') . '">Cadastre-se aqui</a>.');
            }

            // Modo 2: redireciona para cadastro
            return redirect()->to(site_url('registrar/semVerificacao'))->with('atencao', 'Celular não cadastrado. Por favor, crie sua conta.');
        }

        // ── Modo 1: login por e-mail ou celular ─────────────────────────────
        $login_tipo = $this->request->getPost('login_tipo');

        if ($login_tipo === 'celular') {
            if (empty($celular)) {
                return redirect()->back()->withInput()->with('atencao', 'Por favor, preencha o número de celular');
            }
            $tel     = preg_replace('/[^0-9]/', '', $celular);
            $cliente = $db->query(
                "SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone,'(',''),')',''),'-',''),' ','') = ?",
                [$tel]
            )->getRow();
            if ($cliente) {
                return $this->_logarCliente($cliente, "Bem-vindo(a), {$cliente->nome}!");
            }
            return redirect()->back()->withInput()->with('atencao', 'Celular não cadastrado.');
        }

        // E-mail
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, preencha um e-mail válido');
        }

        $cliente = $db->query("SELECT * FROM clientes WHERE email = ?", [$email])->getRow();
        if ($cliente) {
            return $this->_logarCliente($cliente, "Bem-vindo(a), {$cliente->nome}!");
        }

        $usuarioAdmin = $db->query("SELECT * FROM usuarios WHERE email = ?", [$email])->getRow();
        if (!$usuarioAdmin) {
            return redirect()->back()->withInput()->with('atencao', 'E-mail não cadastrado.');
        }

        if (empty($password)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, preencha a senha');
        }

        return $this->_logarAdmin($usuarioAdmin, $password);
    }

    public function logout()
    {
        $session = session();

        // Remove as chaves manualmente primeiro
        $session->remove(['usuario_id', 'usuario_nome', 'usuario_is_admin',
                          'cliente_id', 'cliente_nome', 'cliente_telefone', 'cliente_email']);

        // Regenera o ID (apaga o registro antigo do banco) e depois destrói
        $session->regenerate(true);
        $session->destroy();

        // Força gravação antes do redirect
        session_write_close();

        return redirect()->to(site_url('login'))->with('info', 'Até logo!');
    }

    // ── Helpers privados ────────────────────────────────────────────────────

    /**
     * Seta a sessão do cliente e redireciona para a home.
     * Usa session()->save() explícito para garantir persistência antes do redirect.
     */
    private function _logarCliente(object $cliente, string $mensagem)
    {
        $session = session();
        $session->set('cliente_id',       (int)$cliente->id);
        $session->set('cliente_nome',     $cliente->nome);
        $session->set('cliente_telefone', $cliente->telefone ?? '');
        if (!empty($cliente->email)) {
            $session->set('cliente_email', $cliente->email);
        }

        // Força gravação imediata no banco antes do redirect
        session_write_close();

        return redirect()->to(site_url('/'))->with('sucesso', $mensagem);
    }

    /**
     * Autentica admin via serviço e seta chaves de sessão extras.
     */
    private function _logarAdmin(object $usuario, string $password)
    {
        $autenticacao = service('autenticacao');

        if (!$autenticacao->login($usuario->email, $password)) {
            return redirect()->back()->withInput()->with('atencao', 'Senha incorreta');
        }

        $u = $autenticacao->pegaUsuarioLogado();

        if (!$u->ativo) {
            $autenticacao->logout();
            return redirect()->back()->with('atencao', 'Sua conta está desativada.');
        }

        // Seta chaves extras para o VisitanteFilter funcionar sem service()
        session()->set('usuario_id',       (int)$u->id);
        session()->set('usuario_nome',     $u->nome);
        session()->set('usuario_is_admin', (bool)$u->is_admin);

        session_write_close();

        $destino = $u->is_admin ? site_url('admin/home') : site_url('admin/pedidos');
        return redirect()->to($destino)->with('sucesso', "Olá {$u->nome}!");
    }

    // ── Endpoints AJAX (mantidos) ────────────────────────────────────────────

    public function verificarEmail()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }
        $json  = $this->request->getJSON();
        $email = $json->email ?? '';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Email inválido']);
        }
        $db = \Config\Database::connect();
        if ($db->query("SELECT id FROM usuarios WHERE email = ?", [$email])->getRow()) {
            return $this->response->setJSON(['tipo' => 'admin', 'requer_senha' => true]);
        }
        if ($db->query("SELECT id FROM clientes WHERE email = ?", [$email])->getRow()) {
            return $this->response->setJSON(['tipo' => 'cliente', 'requer_senha' => false]);
        }
        return $this->response->setJSON(['tipo' => 'nao_encontrado', 'requer_senha' => false]);
    }

    public function buscar_cep()
    {
        $cep = preg_replace('/[^0-9]/', '', $this->request->getVar('cep') ?? '');
        if (strlen($cep) !== 8) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Formato de CEP inválido.']);
        }
        $ch = curl_init("https://viacep.com.br/ws/{$cep}/json/");
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false]);
        $dados = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if (isset($dados['erro'])) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'CEP não encontrado.']);
        }
        return $this->response->setJSON($dados);
    }

    public function verificarNomeCelular()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }
        $json   = $this->request->getJSON();
        $nome   = trim($json->nome ?? '');
        $celular = preg_replace('/[^0-9]/', '', $json->celular ?? '');
        if (empty($nome) || strlen($celular) < 10) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Preencha nome e celular']);
        }
        $db = \Config\Database::connect();
        $u  = $db->query(
            "SELECT id FROM usuarios WHERE nome = ? AND REPLACE(REPLACE(REPLACE(REPLACE(telefone,'(',''),')',''),'-',''),' ','') = ?",
            [$nome, $celular]
        )->getRow();
        return $this->response->setJSON($u ? ['encontrado' => true] : ['encontrado' => false, 'msg' => 'Nome ou celular não encontrado']);
    }

    public function verificarTelefone()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }
        $json    = $this->request->getJSON();
        $telefone = preg_replace('/[^0-9]/', '', $json->telefone ?? '');
        if (strlen($telefone) < 10 || strlen($telefone) > 11) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Telefone inválido']);
        }
        $db      = \Config\Database::connect();
        $cliente = $db->query(
            "SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone,'(',''),')',''),'-',''),' ','') = ?",
            [$telefone]
        )->getRow();
        if ($cliente) {
            session()->set('cliente_telefone', $telefone);
            return $this->response->setJSON(['sucesso' => true, 'tipo' => 'cliente', 'msg' => 'Telefone encontrado']);
        }
        return $this->response->setJSON(['erro' => true, 'msg' => 'Telefone não cadastrado']);
    }

    public function verificarTelefoneAdmin()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }
        $json    = $this->request->getJSON();
        $telefone = preg_replace('/[^0-9]/', '', $json->telefone ?? '');
        if (strlen($telefone) < 10) {
            return $this->response->setJSON(['is_admin' => false]);
        }
        $db    = \Config\Database::connect();
        $admin = $db->query(
            "SELECT id FROM usuarios WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone,'(',''),')',''),'-',''),' ','') = ?",
            [$telefone]
        )->getRow();
        return $this->response->setJSON(['is_admin' => (bool)$admin]);
    }
}
