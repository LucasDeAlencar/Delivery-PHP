<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class Registrar extends BaseController {

    private $usuarioModel;
    private $modo_cadastro;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        helper(['form']);

        // Buscar modo_cadastro atual dos dados corporativos
        $db = \Config\Database::connect();
        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        $this->modo_cadastro = $dadosCorp ? (int)($dadosCorp->modo_cadastro ?? 1) : 1;
    }

    public function index()
    {
        log_message('info', 'Registrar::index - Acessando página de registro. Modo: ' . $this->modo_cadastro);

        $data = [
            'titulo' => 'Criar Nova Conta',
            'modo_cadastro' => $this->modo_cadastro
        ];

        // Modo 3: exibe view de cadastro simplificado (nome + celular)
        if ($this->modo_cadastro == 3) {
            return view('Login/cadastrar', $data);
        }

        // Modo 2: passa bairros da área de cobertura
        if ($this->modo_cadastro == 2) {
            $db = \Config\Database::connect();
            $data['bairros'] = $db->table('bairros')->where('ativo', 1)->orderBy('cidade', 'ASC')->orderBy('nome', 'ASC')->get()->getResult();
            return view('Registrar/sem_verificacao', $data);
        }

        // Modo 2: exibe view de cadastro simplificado sem verificação
        if ($this->modo_cadastro == 2) {
            log_message('info', 'Registrar::index - Modo 2, exibindo view sem verificação');
            return view('Registrar/sem_verificacao', $data);
        }

        // Modo 1 (padrão): exibe view completa com verificação
        return view('Registrar/novo', $data);
    }

    public function criar() {
        log_message('info', '=== Registrar::criar INÍCIO ===');
        
        $email = $this->request->getPost('email');
        $nome = $this->request->getPost('nome');
        $telefone = $this->request->getPost('telefone');
        $cep = $this->request->getPost('cep');
        $cidade = $this->request->getPost('cidade');
        $bairro = $this->request->getPost('bairro');
        $endereco = $this->request->getPost('endereco');
        $numero = $this->request->getPost('numero');
        $complemento = $this->request->getPost('complemento');

        log_message('info', "Registrar::criar - Dados recebidos - Email: $email, Nome: $nome, Telefone: $telefone");

        if (empty($email)) {
            log_message('error', 'Registrar::criar - Email vazio');
            return redirect()->back()->withInput()->with('atencao', 'Por favor, preencha o e-mail');
        }
        
        if (empty($nome)) {
            log_message('error', 'Registrar::criar - Nome vazio');
            return redirect()->back()->withInput()->with('atencao', 'Por favor, preencha o nome');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'Registrar::criar - Email inválido: ' . $email);
            return redirect()->back()->withInput()->with('atencao', 'Por favor, digite um e-mail válido');
        }

        $db = \Config\Database::connect();
        
        $clienteExiste = $db->query("SELECT id FROM clientes WHERE email = ?", [$email])->getRow();
        if ($clienteExiste) {
            log_message('error', 'Registrar::criar - Email já cadastrado: ' . $email);
            return redirect()->back()->withInput()->with('atencao', 'Este e-mail já está cadastrado');
        }

        $telefoneNumeros = preg_replace("/[^0-9]/", "", $telefone ?? '');
        if (!empty($telefoneNumeros)) {
            $telefoneExistente = $db->query(
                "SELECT id FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
                [$telefoneNumeros]
            )->getRow();
            if ($telefoneExistente) {
                log_message('error', 'Registrar::criar - Telefone já cadastrado: ' . $telefone);
                return redirect()->back()->withInput()->with('atencao', 'Este telefone já está cadastrado');
            }
        }

        $cepNumeros = preg_replace("/[^0-9]/", "", $cep ?? '');

        $dados = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone ?? '',
            'cep' => $cepNumeros,
            'Bairro' => $bairro ?? '',
            'Endereco' => $endereco ?? '',
            'Numero' => (int)($numero ?? 0) ?: 0,
            'Cidade' => $cidade ?? '',
            'complemento' => $complemento ?? '',
            'modo_cadastro' => 1
        ];

        log_message('info', 'Registrar::criar - Tentando inserir cliente: ' . json_encode($dados));

        try {
            $resultado = $db->table('clientes')->insert($dados);
            if ($resultado) {
                $cliente = $db->query("SELECT * FROM clientes WHERE email = ?", [$email])->getRow();
                
                session()->set('cliente_id', $cliente->id);
                session()->set('cliente_email', $email);
                session()->set('cliente_nome', $cliente->nome);
                
                log_message('info', 'Registrar::criar - Cliente cadastrado com sucesso: ' . $email);
                return redirect()->to(site_url('/'))->with('sucesso', "Bem-vindo(a), {$cliente->nome}! Seu cadastro foi realizado com sucesso.");
            } else {
                log_message('error', 'Registrar::criar - Falha ao inserir cliente');
                return redirect()->back()->withInput()->with('atencao', 'Erro ao cadastrar cliente. Tente novamente.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Registrar::criar - Exceção: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('atencao', 'Erro no banco de dados: ' . $e->getMessage());
        }
    }

    public function enviarCodigo() {
        log_message('info', '=== Registrar::enviarCodigo INÍCIO ===');

        log_message('info', 'Registrar::enviarCodigo - Headers: ' . json_encode($this->request->getHeaders()));
        log_message('info', 'Registrar::enviarCodigo - Body: ' . $this->request->getBody());
        
        if (!$this->request->isAJAX()) {
            log_message('error', 'Registrar::enviarCodigo - Requisição não é AJAX');
            // Permite requisições POST mesmo que não tenham header AJAX em alguns ambientes
            if ($this->request->getMethod() !== 'POST') {
                return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
            }
        }

        $json = $this->request->getJSON();
        $email = $json->email ?? '';

        log_message('info', 'Registrar::enviarCodigo - Email: ' . $email);

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Email inválido']);
        }

        $db = \Config\Database::connect();
        $clienteExistente = $db->query("SELECT id FROM clientes WHERE email = ?", [$email])->getRow();
        if ($clienteExistente) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Este e-mail já está cadastrado']);
        }

        $codigo = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        
        session()->set('codigo_verificacao_registro', [
            'codigo' => $codigo,
            'email' => $email,
            'timestamp' => time()
        ]);

        log_message('info', 'Registrar::enviarCodigo - Código gerado: ' . $codigo);

        try {
            $emailService = service('email');
            $emailService->setTo($email);
            $emailService->setSubject('Código de Verificação - Space Burger Dog Do Paulista');
            $emailService->setMessage("
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background: #0d0d0d; }
                        .container { max-width: 600px; margin: 20px auto; background: #1a1a1a; border: 3px solid #0055ff; border-radius: 20px; overflow: hidden; }
                        .header { background: linear-gradient(135deg, #0055ff 0%, #0055ff 100%); color: #fff; padding: 35px 20px; text-align: center; }
                        .header h1 { margin: 0; font-size: 32px; font-weight: 700; }
                        .content { padding: 40px 30px; text-align: center; background: #1a1a1a; }
                        .content h2 { color: #0055ff; margin: 0 0 15px 0; }
                        .content p { color: #cccccc; font-size: 16px; line-height: 1.6; }
                        .code-box { background: #2d2d2d; border: 3px dashed #0055ff; border-radius: 15px; padding: 25px; margin: 20px 0; }
                        .code { font-size: 42px; font-weight: bold; color: #0055ff; letter-spacing: 10px; font-family: 'Courier New', monospace; }
                        .footer { background: #0d0d0d; color: #888888; padding: 20px; text-align: center; font-size: 13px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Space Burger Dog Do Paulista</h1>
                            <p>Verificação de Cadastro</p>
                        </div>
                        <div class='content'>
                            <h2>Código de Verificação</h2>
                            <p>Para criar sua conta, utilize o código abaixo:</p>
                            <div class='code-box'>
                                <div class='code'>{$codigo}</div>
                            </div>
                            <p style='color: #888888;'>Este código é válido por 5 minutos</p>
                        </div>
                        <div class='footer'>
                            <p>© " . date('Y') . " Space Burger Dog Do Paulista - Sistema de Delivery</p>
                        </div>
                    </div>
                </body>
                </html>
            ");

            if ($emailService->send()) {
                log_message('info', 'Registrar::enviarCodigo - Email enviado com sucesso');
                return $this->response->setJSON(['sucesso' => true, 'msg' => 'Código enviado para seu email']);
            } else {
                throw new \Exception($emailService->printDebugger(['headers']));
            }
        } catch (\Exception $e) {
            log_message('info', 'Registrar::enviarCodigo - Erro no email, retornando código para teste: ' . $e->getMessage());
            return $this->response->setJSON(['sucesso' => true, 'msg' => 'Código gerado para teste', 'codigo_dev' => $codigo]);
        }
    }

    public function verificarCodigo() {
        log_message('info', '=== Registrar::verificarCodigo INÍCIO ===');

        log_message('info', 'Registrar::verificarCodigo - Headers: ' . json_encode($this->request->getHeaders()));
        log_message('info', 'Registrar::verificarCodigo - Body: ' . $this->request->getBody());
        if (!$this->request->isAJAX()) {
            // Permite requisições POST mesmo que não tenham header AJAX em alguns ambientes
            if ($this->request->getMethod() !== 'POST') {
                return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
            }
        }

        $json = $this->request->getJSON();
        $codigo = $json->codigo ?? '';

        $dadosVerificacao = session()->get('codigo_verificacao_registro');

        if (!$dadosVerificacao) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Código não encontrado. Solicite um novo código.']);
        }

        if (time() - $dadosVerificacao['timestamp'] > 300) {
            session()->remove('codigo_verificacao_registro');
            return $this->response->setJSON(['erro' => true, 'msg' => 'Código expirado. Solicite um novo código.']);
        }

        if (strtoupper($codigo) === $dadosVerificacao['codigo']) {
            session()->remove('codigo_verificacao_registro');
            return $this->response->setJSON(['sucesso' => true, 'msg' => 'Código verificado com sucesso']);
        }

        return $this->response->setJSON(['erro' => true, 'msg' => 'Código incorreto']);
    }

    public function verificarSessao() {
        log_message('info', '=== Registrar::verificarSessao INÍCIO ===');

        if (!$this->request->isAJAX()) {
            // Permite requisições POST mesmo que não tenham header AJAX em alguns ambientes
            if ($this->request->getMethod() !== 'POST') {
                return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
            }
        }

        $dadosVerificacao = session()->get('codigo_verificacao_registro');

        if (!$dadosVerificacao) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Nenhuma sessão ativa']);
        }

        if (time() - $dadosVerificacao['timestamp'] > 300) {
            session()->remove('codigo_verificacao_registro');
            return $this->response->setJSON(['erro' => true, 'msg' => 'Código expirado']);
        }

        return $this->response->setJSON([
            'sucesso' => true,
            'email' => $dadosVerificacao['email'],
            'timestamp' => $dadosVerificacao['timestamp']
        ]);
    }

    public function buscar_cep() {
        log_message('info', '=== Registrar::buscar_cep INÍCIO ===');
        
        $json = $this->request->getJSON();
        $cep = $json->cep ?? '';
        $cep = preg_replace("/[^0-9]/", "", $cep);

        log_message('info', 'Registrar::buscar_cep - CEP: ' . $cep);

        if (strlen($cep) != 8) {
            log_message('error', 'Registrar::buscar_cep - CEP inválido: ' . $cep);
            return $this->response->setJSON(['erro' => true, 'msg' => 'Formato de CEP inválido.']);
        }

        $url = "https://viacep.com.br/ws/{$cep}/json/";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resposta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        log_message('info', 'Registrar::buscar_cep - HTTP Code: ' . $httpCode);
        log_message('info', 'Registrar::buscar_cep - Resposta: ' . $resposta);

        if (empty($resposta)) {
            log_message('error', 'Registrar::buscar_cep - Resposta vazia do ViaCEP');
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao buscar CEP. Tente novamente.']);
        }

        $dados = json_decode($resposta, true);

        if (empty($dados)) {
            log_message('error', 'Registrar::buscar_cep - Falha ao decodificar JSON');
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao processar dados do CEP.']);
        }

        if (isset($dados['erro'])) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'CEP não encontrado.']);
        }

        return $this->response->setJSON($dados);
    }

    public function criarSemVerificacao()
    {
        log_message('info', '=== Registrar::criarSemVerificacao INÍCIO ===');

        $nome = $this->request->getPost('nome');
        $telefone = $this->request->getPost('telefone');
        $cidade = $this->request->getPost('cidade');
        $bairro = $this->request->getPost('bairro');
        $endereco = $this->request->getPost('endereco');
        $numero = $this->request->getPost('numero');
        $complemento = $this->request->getPost('complemento') ?? '';

        log_message('info', "Registrar::criarSemVerificacao - Dados: nome=$nome, telefone=$telefone");

        if (empty($nome)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, preencha o nome');
        }

        if (empty($telefone)) {
            return redirect()->back()->withInput()->with('atencao', 'Por favor, preencha o telefone');
        }

        $db = \Config\Database::connect();

        // Verificar se telefone já existe
        $telefoneNumeros = preg_replace("/[^0-9]/", "", $telefone);
        if (!empty($telefoneNumeros)) {
            $telefoneExistente = $db->query(
                "SELECT id FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
                [$telefoneNumeros]
            )->getRow();
            if ($telefoneExistente) {
                return redirect()->back()->withInput()->with('atencao', 'Este telefone já está cadastrado');
            }
        }

        $dados = [
            'nome' => $nome,
            'telefone' => $telefone,
            'Cidade' => $cidade ?? null,
            'Bairro' => $bairro ?? null,
            'Endereco' => $endereco ?? null,
            'Numero' => $numero ?? null,
            'complemento' => $complemento ?? null,
            'modo_cadastro' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $resultado = $db->table('clientes')->insert($dados);
            if ($resultado) {
                $clienteId = $db->insertID();
                $cliente = $db->table('clientes')->where('id', $clienteId)->get()->getRow();

                session()->set('cliente_id', $cliente->id);
                session()->set('cliente_telefone', $telefone);
                session()->set('cliente_nome', $cliente->nome);

                log_message('info', 'Registrar::criarSemVerificacao - Cliente cadastrado: ' . $cliente->id);
                return redirect()->to(site_url('/'))->with('sucesso', "Bem-vindo(a), {$cliente->nome}! Seu cadastro foi realizado com sucesso.");
            }

            return redirect()->back()->withInput()->with('atencao', 'Erro ao cadastrar cliente. Tente novamente.');
        } catch (\Exception $e) {
            log_message('error', 'Registrar::criarSemVerificacao - Exceção: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('atencao', 'Erro no banco de dados: ' . $e->getMessage());
        }
    }

    public function cadastroRapido()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }

        $json = $this->request->getJSON();
        $nome = $json->nome ?? '';
        $telefone = $json->telefone ?? '';

        if (empty($nome) || empty($telefone)) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Nome e telefone são obrigatórios']);
        }

        $db = \Config\Database::connect();

        // Verificar se telefone já existe
        $telefoneNumeros = preg_replace("/[^0-9]/", "", $telefone);
        $telefoneExistente = null;
        if (!empty($telefoneNumeros)) {
            $telefoneExistente = $db->query(
                "SELECT id FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
                [$telefoneNumeros]
            )->getRow();
        }

        // Se telefone já existe, faz login automático
        if ($telefoneExistente) {
            $cliente = $db->table('clientes')->where('id', $telefoneExistente->id)->get()->getRow();
            session()->set('cliente_id', $cliente->id);
            session()->set('cliente_telefone', $telefone);
            session()->set('cliente_nome', $cliente->nome);
            return $this->response->setJSON(['sucesso' => true, 'msg' => 'Bem-vindo de volta!', 'login' => true]);
        }

        // Cadastrar novo cliente (modo 3)
        $dados = [
            'nome' => $nome,
            'telefone' => $telefone,
            'modo_cadastro' => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $resultado = $db->table('clientes')->insert($dados);
            if ($resultado) {
                $clienteId = $db->insertID();
                $cliente = $db->table('clientes')->where('id', $clienteId)->get()->getRow();

                session()->set('cliente_id', $cliente->id);
                session()->set('cliente_telefone', $telefone);
                session()->set('cliente_nome', $cliente->nome);

                return $this->response->setJSON(['sucesso' => true, 'msg' => 'Cadastro realizado com sucesso!']);
            }

            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao cadastrar']);
        } catch (\Exception $e) {
            log_message('error', 'Registrar::cadastroRapido - ' . $e->getMessage());
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro no banco de dados']);
        }
    }

    public function bairros_cidade() {
        $cidade = trim($this->request->getGet('cidade') ?? '');
        if (!$cidade) return $this->response->setJSON(['bairros' => []]);

        $db = \Config\Database::connect();
        $rows = $db->query(
            "SELECT DISTINCT nome FROM bairros WHERE deletado_em IS NULL AND ativo = 1 AND cidade = ? ORDER BY nome",
            [$cidade]
        )->getResultArray();

        return $this->response->setJSON(['bairros' => array_column($rows, 'nome')]);
    }
}
