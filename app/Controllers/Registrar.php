<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class Registrar extends BaseController {

    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        helper(['form']);
    }

    public function index() {
        log_message('info', 'Registrar::index - Acessando página de registro');
        $data = [
            'titulo' => 'Criar Nova Conta'
        ];

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
            'complemento' => $complemento ?? ''
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
        
        if (!$this->request->isAJAX()) {
            log_message('error', 'Registrar::enviarCodigo - Requisição não é AJAX');
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
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
            $emailService->setSubject('Código de Verificação - Delicias MV');
            $emailService->setMessage("
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background: #0d0d0d; }
                        .container { max-width: 600px; margin: 20px auto; background: #1a1a1a; border: 3px solid #E83F00; border-radius: 20px; overflow: hidden; }
                        .header { background: linear-gradient(135deg, #E83F00 0%, #ff6a00 100%); color: #fff; padding: 35px 20px; text-align: center; }
                        .header h1 { margin: 0; font-size: 32px; font-weight: 700; }
                        .content { padding: 40px 30px; text-align: center; background: #1a1a1a; }
                        .content h2 { color: #E83F00; margin: 0 0 15px 0; }
                        .content p { color: #cccccc; font-size: 16px; line-height: 1.6; }
                        .code-box { background: #2d2d2d; border: 3px dashed #E83F00; border-radius: 15px; padding: 25px; margin: 20px 0; }
                        .code { font-size: 42px; font-weight: bold; color: #E83F00; letter-spacing: 10px; font-family: 'Courier New', monospace; }
                        .footer { background: #0d0d0d; color: #888888; padding: 20px; text-align: center; font-size: 13px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Delicias MV</h1>
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
                            <p>© " . date('Y') . " Delicias MV - Sistema de Delivery</p>
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
        
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
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
}
