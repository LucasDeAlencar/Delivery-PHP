<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController {

    public function __construct() {
        helper(['form']);
    }

    public function index() {
        $data = [
            'titulo' => "Escolha uma opção"
        ];

        return view('Login/escolha', $data);
    }

    public function novo() {
        $data = [
            'titulo' => "Realize o login "
        ];

        return view('Login/novo', $data);
    }

    public function criar() {
        log_message('info', '=== Login::criar INÍCIO ===');
        
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->back()->with('atencao', 'Método não permitido');
        }

        $login_tipo = $this->request->getPost('login_tipo');
        $email = $this->request->getPost('email');
        $celular = $this->request->getPost('celular');
        $password = $this->request->getPost('password');

        log_message('info', "Login::criar - Tipo: {$login_tipo}, Email: {$email}, Celular: {$celular}");

        $db = \Config\Database::connect();

        if ($login_tipo === 'celular') {
            log_message('info', 'Login::criar - Tentando login por celular');
            
            if (empty($celular)) {
                log_message('error', 'Login::criar - Celular vazio');
                return redirect()->back()->with('atencao', 'Por favor, preencha o número de celular');
            }

            $celularNumeros = preg_replace("/[^0-9]/", "", $celular);
            log_message('info', "Login::criar - Celular limpo: {$celularNumeros}");
            
            $cliente = $db->query(
                "SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
                [$celularNumeros]
            )->getRow();

            if ($cliente) {
                log_message('info', "Login::criar - Cliente encontrado: {$cliente->nome}");
                session()->set('cliente_id', $cliente->id);
                session()->set('cliente_telefone', $celular);
                session()->set('cliente_nome', $cliente->nome);
                session()->set('cliente_email', $cliente->email);
                log_message('info', 'Login::criar - Sessão criada, redirecionando para home');
                return redirect()->to('/')->with('sucesso', "Bem-vindo(a), {$cliente->nome}!");
            }

            log_message('error', "Login::criar - Cliente não encontrado com celular: {$celularNumeros}");
            return redirect()->back()->with('atencao', 'Celular não cadastrado. <a href="' . site_url('registrar') . '">Cadastre-se aqui</a>');
        }

        if (empty($email)) {
            return redirect()->back()->with('atencao', 'Por favor, preencha o e-mail');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('atencao', 'Por favor, digite um e-mail válido');
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
            return redirect()->back()->with('atencao', 'E-mail não cadastrado. <a href="' . site_url('registrar') . '">Cadastre-se aqui</a>');
        }

        if (empty($password)) {
            return redirect()->back()->with('atencao', 'Por favor, preencha a senha');
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

        return redirect()->back()->with('atencao', 'Senha incorreta');
    }

    /**
     * Realiza o logout do usuário
     */
    public function logout() {
        $autenticacao = service('autenticacao');
        $usuario = $autenticacao->pegaUsuarioLogado();

        $nomeUsuario = $usuario ? $usuario->nome : 'Usuário';

        $autenticacao->logout();

        return redirect()->to(site_url('login'))->with('info', "Até logo, {$nomeUsuario}! Esperamos ver você novamente.");
    }
    
    public function testarAutenticacao() {
    $email = 'admin@gmail.com';
    $senhaTeste = 'admin123';
    
    echo "<h1>🔍 DIAGNÓSTICO COMPLETO DO PROBLEMA</h1>";
    
    $db = \Config\Database::connect();
    $usuarioModel = new \App\Models\UsuarioModel();
    
    // 1. VERIFICAR CONEXÃO E TABELA
    echo "<h2>1. Verificação da Base de Dados</h2>";
    
    // Verificar se a tabela existe
    $tabelaExiste = $db->tableExists('usuarios');
    echo "<p>Tabela 'usuarios' existe: " . ($tabelaExiste ? '✅ SIM' : '❌ NÃO') . "</p>";
    
    if (!$tabelaExiste) {
        echo "<p style='color: red;'>❌ A tabela usuarios não existe!</p>";
        return;
    }
    
    // 2. VERIFICAR ESTRUTURA DA TABELA
    echo "<h2>2. Estrutura da Tabela 'usuarios'</h2>";
    $campos = $db->getFieldNames('usuarios');
    echo "<p>Campos encontrados: " . implode(', ', $campos) . "</p>";
    
    // 3. VERIFICAR USUÁRIO ESPECÍFICO
    echo "<h2>3. Dados do Usuário no Banco</h2>";
    $usuario = $db->query("SELECT * FROM usuarios WHERE email = ?", [$email])->getRow();
    
    if (!$usuario) {
        echo "<p style='color: red;'>❌ Usuário não encontrado!</p>";
        return;
    }
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Valor</th><th>Status</th></tr>";
    
    foreach ($usuario as $campo => $valor) {
        $status = '';
        if ($campo === 'password_hash') {
            $status = $valor ? '✅' : '❌ VAZIO';
        } elseif ($campo === 'ativo') {
            $status = $valor ? '✅ ATIVO' : '❌ INATIVO';
        }
        echo "<tr>";
        echo "<td><strong>$campo</strong></td>";
        echo "<td>" . (strlen($valor) > 50 ? substr($valor, 0, 50) . '...' : $valor) . "</td>";
        echo "<td>$status</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 4. TESTE DIRETO DE SENHA
    echo "<h2>4. Teste Direto de Autenticação</h2>";
    
    if (empty($usuario->senha)) {
        echo "<p style='color: red;'>❌ PROBLEMA CRÍTICO: Campo 'senha' está VAZIO no banco!</p>";
        
        // Tentar corrigir UMA ÚLTIMA VEZ
        echo "<h3>Tentativa de Correção Imediata:</h3>";
        $novoHash = password_hash($senhaTeste, PASSWORD_DEFAULT);
        echo "<p>Novo hash gerado: " . substr($novoHash, 0, 30) . "...</p>";
        
        $resultado = $db->query("UPDATE usuarios SET password_hash = ? WHERE email = ?", [$novoHash, $email]);
        
        if ($resultado) {
            echo "<p style='color: green;'>✅ UPDATE executado!</p>";
            
            // Verificar novamente
            $usuarioAtualizado = $db->query("SELECT password_hash FROM usuarios WHERE email = ?", [$email])->getRow();
            if (!empty($usuarioAtualizado->senha)) {
                echo "<p style='color: green;'>🎉 SENHA FINALMENTE SALVA! Tamanho: " . strlen($usuarioAtualizado->senha) . " caracteres</p>";
            } else {
                echo "<p style='color: red;'>💥 PROBLEMA GRAVE: O UPDATE não está funcionando!</p>";
            }
        }
    } else {
        echo "<p>Hash no banco: " . substr($usuario->senha, 0, 30) . "...</p>";
        echo "<p>Tamanho do hash: " . strlen($usuario->senha) . " caracteres</p>";
        
        // Testar verificação
        $senhaCorreta = password_verify($senhaTeste, $usuario->senha);
        echo "<p>Verificação password_verify: " . ($senhaCorreta ? '✅ CORRETA' : '❌ INCORRETA') . "</p>";
        
        if (!$senhaCorreta) {
            echo "<h3>🔧 Soluções Alternativas:</h3>";
            
            // Tentar diferentes algoritmos
            $hashes = [
                'PASSWORD_DEFAULT' => password_hash($senhaTeste, PASSWORD_DEFAULT),
                'PASSWORD_BCRYPT' => password_hash($senhaTeste, PASSWORD_BCRYPT),
            ];
            
            foreach ($hashes as $algoritmo => $hash) {
                $teste = password_verify($senhaTeste, $hash);
                echo "<p>$algoritmo: " . ($teste ? '✅' : '❌') . " | Hash: " . substr($hash, 0, 20) . "...</p>";
            }
        }
    }
    
    // 5. VERIFICAR MÉTODO verificaPassword
    echo "<h2>5. Verificação do Método verificaPassword</h2>";
    
    if (method_exists($usuarioModel, 'buscaUsuarioPorEmail')) {
        $usuarioObj = $usuarioModel->buscaUsuarioPorEmail($email);
        if ($usuarioObj && method_exists($usuarioObj, 'verificaPassword')) {
            $resultado = $usuarioObj->verificaPassword($senhaTeste);
            echo "<p>Método verificaPassword retornou: " . ($resultado ? '✅ TRUE' : '❌ FALSE') . "</p>";
        } else {
            echo "<p>❌ Método verificaPassword não disponível</p>";
        }
    }
    
    echo "<hr>";
    echo "<h2>🎯 PRÓXIMOS PASSOS:</h2>";
    echo "<ol>";
    echo "<li><a href='" . site_url('teste-auth') . "'>🔄 Testar Autenticação Novamente</a></li>";
    echo "<li><a href='javascript:location.reload()'>🔄 Atualizar Esta Página</a></li>";
    echo "</ol>";
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

    public function loginCliente() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }

        $json = $this->request->getJSON();
        $email = $json->email ?? '';
        $telefone = $json->telefone ?? '';

        if (!empty($email)) {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->response->setJSON(['erro' => true, 'msg' => 'Email inválido']);
            }

            $db = \Config\Database::connect();
            $cliente = $db->query("SELECT * FROM clientes WHERE email = ?", [$email])->getRow();

            if ($cliente) {
                session()->set('cliente_email', $email);
                return $this->response->setJSON(['sucesso' => true, 'msg' => 'Login realizado com sucesso']);
            }

            return $this->response->setJSON(['erro' => true, 'msg' => 'Cliente não encontrado']);
        }

        if (!empty($telefone)) {
            $telefone = preg_replace("/[^0-9]/", "", $telefone);

            $db = \Config\Database::connect();
            $cliente = $db->query("SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?", [$telefone])->getRow();

            if ($cliente) {
                session()->set('cliente_telefone', $telefone);
                return $this->response->setJSON(['sucesso' => true, 'msg' => 'Login realizado com sucesso']);
            }

            return $this->response->setJSON(['erro' => true, 'msg' => 'Cliente não encontrado']);
        }

        return $this->response->setJSON(['erro' => true, 'msg' => 'Informe email ou telefone']);
    }

    public function enviarCodigo() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }

        $json = $this->request->getJSON();
        $email = $json->email ?? '';

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Email inválido']);
        }

        $codigo = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        
        session()->set('codigo_verificacao', [
            'codigo' => $codigo,
            'email' => $email,
            'timestamp' => time()
        ]);

        try {
            $emailService = service('email');
            $emailService->setTo($email);
            $emailService->setSubject('Código de Verificação - Delivery MV 🍔');
            $emailService->setMessage("
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background: #0d0d0d; }
                        .container { max-width: 600px; margin: 20px auto; background: #1a1a1a; border: 3px solid #FC4400; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(252, 68, 0, 0.3); }
                        .header { background: linear-gradient(135deg, #FC4400 0%, #e03800 100%); color: #fff; padding: 35px 20px; text-align: center; position: relative; }
                        .header::before { content: '🍔'; position: absolute; top: 10px; left: 20px; font-size: 30px; }
                        .header::after { content: '🍕'; position: absolute; top: 10px; right: 20px; font-size: 30px; }
                        .header h1 { margin: 0; font-size: 32px; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
                        .header p { margin: 8px 0 0 0; font-size: 14px; opacity: 0.95; }
                        .content { padding: 40px 30px; text-align: center; background: #1a1a1a; }
                        .content h2 { color: #FC4400; margin: 0 0 15px 0; font-size: 24px; }
                        .content > p { color: #fff; font-size: 15px; margin-bottom: 25px; }
                        .code-box { background: #2d2d2d; border: 3px dashed #FC4400; border-radius: 15px; padding: 35px 20px; margin: 25px 0; position: relative; }
                        .code-box::before { content: '🍟'; position: absolute; top: -15px; left: 20px; font-size: 25px; background: #1a1a1a; padding: 0 10px; }
                        .code-box::after { content: '🌭'; position: absolute; top: -15px; right: 20px; font-size: 25px; background: #1a1a1a; padding: 0 10px; }
                        .code { font-size: 42px; font-weight: bold; color: #FC4400; letter-spacing: 10px; font-family: 'Courier New', monospace; text-shadow: 0 0 10px rgba(252, 68, 0, 0.5); }
                        .code-box p { color: #999; font-size: 13px; margin: 15px 0 0 0; }
                        .info { background: rgba(120, 213, 239, 0.1); border-left: 4px solid #78d5ef; border-radius: 10px; padding: 20px 25px; margin: 25px 0; text-align: left; }
                        .info p { color: #78d5ef; font-weight: 600; margin: 0 0 12px 0; font-size: 15px; }
                        .info ul { color: #ccc; margin: 0; padding-left: 20px; line-height: 1.8; }
                        .footer { background: #0d0d0d; color: #666; padding: 25px; text-align: center; font-size: 13px; border-top: 2px solid #333; }
                        .footer p { margin: 0; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>🍔 Delivery MV 🍕</h1>
                            <p>Verificação de Acesso</p>
                        </div>
                        <div class='content'>
                            <h2>Código de Verificação</h2>
                            <p>Para continuar com seu acesso, utilize o código abaixo:</p>
                            <div class='code-box'>
                                <div class='code'>{$codigo}</div>
                                <p>Digite este código na tela de verificação</p>
                            </div>
                            <div class='info'>
                                <p>🍟 Informações importantes:</p>
                                <ul>
                                    <li>Este código é válido por 5 minutos</li>
                                    <li>Use apenas uma vez</li>
                                    <li>Não compartilhe com terceiros</li>
                                </ul>
                            </div>
                        </div>
                        <div class='footer'>
                            <p>© 2024 Delivery MV - Sistema de Delivery 🍔</p>
                        </div>
                    </div>
                </body>
                </html>
            ");

            if ($emailService->send()) {
                return $this->response->setJSON(['sucesso' => true, 'msg' => 'Código enviado para seu email']);
            } else {
                throw new \Exception($emailService->printDebugger(['headers']));
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['sucesso' => true, 'msg' => 'Código gerado', 'codigo_dev' => $codigo]);
        }
    }

    public function verificarCodigo() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }

        $json = $this->request->getJSON();
        $codigo = $json->codigo ?? '';

        $dadosVerificacao = session()->get('codigo_verificacao');
        
        if (!$dadosVerificacao) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Código não encontrado']);
        }

        // Verificar se expirou (5 minutos = 300 segundos)
        if (time() - $dadosVerificacao['timestamp'] > 300) {
            session()->remove('codigo_verificacao');
            return $this->response->setJSON(['erro' => true, 'msg' => 'Código expirado']);
        }

        if (strtoupper($codigo) === $dadosVerificacao['codigo']) {
            session()->remove('codigo_verificacao');
            return $this->response->setJSON(['sucesso' => true, 'msg' => 'Código verificado com sucesso']);
        }

        return $this->response->setJSON(['erro' => true, 'msg' => 'Código incorreto']);
    }

public function buscar_cep() {
        // Recebe o CEP via POST ou GET
        $cep = $this->request->getVar('cep'); 

        // 1. Tratamento: Remove tudo que não for número
        $cep = preg_replace("/[^0-9]/", "", $cep);

        // 2. Validação básica: CEP deve ter 8 dígitos
        if (strlen($cep) != 8) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Formato de CEP inválido.']);
        }

        // 3. Inicializa cURL para chamar a API ViaCEP
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Opcional, ajuda em localhost
        $resposta = curl_exec($ch);
        curl_close($ch);

        $dados = json_decode($resposta, true);

        // 4. Verifica se a API retornou erro (CEP inexistente)
        if (isset($dados['erro'])) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'CEP não encontrado.']);
        }

        // 5. Retorna os dados para a View ou Javascript
        // O array conterá: logradouro (rua), bairro, localidade (cidade), uf, etc.
        return $this->response->setJSON($dados);
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
        
        $cliente = $db->query("SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?", [$telefone])->getRow();

        if ($cliente) {
            session()->set('cliente_telefone', $telefone);
            return $this->response->setJSON(['sucesso' => true, 'tipo' => 'cliente', 'msg' => 'Telefone encontrado']);
        }

        return $this->response->setJSON(['erro' => true, 'msg' => 'Telefone não cadastrado']);
    }
}
