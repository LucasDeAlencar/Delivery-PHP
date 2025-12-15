<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController {

    public function __construct() {
        helper(['form']);
    }

    public function novo() {
        $data = [
            'titulo' => "Realize o login "
        ];

        return view('Login/novo', $data);
    }

    public function criar() {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->back()->with('atencao', 'Método não permitido');
        }

        $acao = $this->request->getPost('acao');
        $email = $this->request->getPost('email');
        
        // Se ação é login, processar login de admin
        if ($acao === 'login') {
            $password = $this->request->getPost('password');
            
            if (empty($email) || empty($password)) {
                return redirect()->back()->with('atencao', 'Por favor, preencha todos os campos');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return redirect()->back()->with('atencao', 'Por favor, digite um e-mail válido');
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

            return redirect()->back()->with('atencao', 'E-mail ou senha incorretos');
        }
        
        // Se ação é cadastro, processar cadastro de cliente
        if ($acao === 'cadastro') {
            $nome = $this->request->getPost('nome');
            $telefone = $this->request->getPost('telefone');
            $cep = $this->request->getPost('cep');
            
            // Remover hífen e outros caracteres não numéricos do CEP
            $cep = preg_replace("/[^0-9]/", "", $cep);
            $cidade = $this->request->getPost('cidade');
            $bairro = $this->request->getPost('bairro');
            $endereco = $this->request->getPost('endereco');
            $numero = $this->request->getPost('numero');
            $complemento = $this->request->getPost('complemento');

            if (empty($email) || empty($nome) || empty($telefone) || empty($cep) || empty($cidade) || empty($bairro) || empty($endereco)) {
                return redirect()->back()->with('atencao', 'Por favor, preencha todos os campos obrigatórios');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return redirect()->back()->with('atencao', 'Por favor, digite um e-mail válido');
            }

            $db = \Config\Database::connect();
            
            // Verificar se email já existe
            $clienteExiste = $db->query("SELECT id FROM clientes WHERE email = ?", [$email])->getRow();
            if ($clienteExiste) {
                return redirect()->back()->with('atencao', 'Este e-mail já está cadastrado');
            }

            // Inserir cliente
            $dados = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'cep' => $cep,
                'Bairro' => $bairro,
                'Endereco' => $endereco,
                'Numero' => (int)$numero ?: 0,
                'Cidade' => $cidade,
                'complemento' => $complemento ?: ''
            ];

            try {
                $resultado = $db->table('clientes')->insert($dados);
                if ($resultado) {
                    return redirect()->to(site_url('/'))->with('sucesso', 'Cliente cadastrado com sucesso!');
                } else {
                    log_message('error', 'Falha ao inserir cliente: ' . print_r($dados, true));
                    return redirect()->back()->with('atencao', 'Erro ao cadastrar cliente. Tente novamente.');
                }
            } catch (\Exception $e) {
                log_message('error', 'Erro ao inserir cliente: ' . $e->getMessage());
                return redirect()->back()->with('atencao', 'Erro no banco de dados: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('atencao', 'Ação inválida');
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
        
        // Primeiro verifica se é usuário (admin/operário)
        $usuario = $db->query("SELECT email FROM usuarios WHERE email = ?", [$email])->getRow();
        if ($usuario) {
            return $this->response->setJSON(['tipo' => 'admin']);
        }

        // Depois verifica se é cliente
        $cliente = $db->query("SELECT email FROM clientes WHERE email = ?", [$email])->getRow();
        if ($cliente) {
            return $this->response->setJSON(['tipo' => 'cliente']);
        }

        // Se não encontrou nem usuário nem cliente
        return $this->response->setJSON(['tipo' => 'nao_encontrado']);
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

        // Gerar código de 6 caracteres
        $codigo = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        
        // Salvar código na sessão com timestamp
        session()->set('codigo_verificacao', [
            'codigo' => $codigo,
            'email' => $email,
            'timestamp' => time()
        ]);

        // Tentar enviar email
        try {
            $emailService = service('email');
            
            $emailService->setFrom(env('email.SMTPUser') ?: 'noreply@nokapricho.com', 'No Kapricho Pizzaria');
            $emailService->setTo($email);
            $emailService->setSubject('Código de Verificação - No Kapricho');
            
            $mensagem = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Código de Verificação</title>
                    <style>
                        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background-color: #0d0d0d; color: #fff; }
                        .container { max-width: 600px; margin: 0 auto; background: #1a1a1a; border: 2px solid #f8b531; border-radius: 15px; overflow: hidden; }
                        .header { background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%); color: #f8b531; padding: 30px 20px; text-align: center; border-bottom: 1px solid #333; }
                        .header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #f8b531; }
                        .content { padding: 40px 30px; text-align: center; background: #1a1a1a; }
                        .code-box { background: #2d2d2d; border: 2px dashed #f8b531; border-radius: 12px; padding: 30px; margin: 30px 0; }
                        .code { font-size: 36px; font-weight: bold; color: #f8b531; letter-spacing: 8px; font-family: 'Courier New', monospace; text-shadow: 0 0 10px rgba(248, 181, 49, 0.3); }
                        .info { background: rgba(248, 181, 49, 0.1); border: 1px solid #f8b531; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: left; }
                        .footer { background: #0d0d0d; color: #666; padding: 20px; text-align: center; font-size: 12px; border-top: 1px solid #333; }
                        .warning { color: #ff6b6b; font-weight: bold; }
                        .brand { color: #f8b531; font-weight: 600; }
                        .text-light { color: #ccc; }
                        .highlight { color: #f8b531; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>🍕 <span class='brand'>No Kapricho Pizzaria</span></h1>
                            <p style='margin: 10px 0 0 0; opacity: 0.9; color: #ccc;'>Verificação de Acesso</p>
                        </div>
                        
                        <div class='content'>
                            <h2 style='color: #f8b531; margin-bottom: 20px; font-weight: 600;'>Código de Verificação</h2>
                            <p class='text-light' style='font-size: 16px; line-height: 1.6; margin-bottom: 30px;'>
                                Para continuar com seu acesso, utilize o código abaixo:
                            </p>
                            
                            <div class='code-box'>
                                <div style='margin-bottom: 15px;'>
                                    <svg width='64' height='64' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                        <path d='M6 10V8C6 5.79086 7.79086 4 10 4H14C16.2091 4 18 5.79086 18 8V10' stroke='#f8b531' stroke-width='2' stroke-linecap='round'/>
                                        <rect x='4' y='10' width='16' height='10' rx='2' fill='#f8b531' fill-opacity='0.2' stroke='#f8b531' stroke-width='2'/>
                                        <circle cx='12' cy='15' r='2' fill='#f8b531'/>
                                        <path d='M12 17V18' stroke='#f8b531' stroke-width='2' stroke-linecap='round'/>
                                    </svg>
                                </div>
                                <div class='code'>{$codigo}</div>
                                <p style='margin: 15px 0 0 0; color: #999; font-size: 14px;'>Digite este código na tela de verificação</p>
                            </div>
                            
                            <div class='info'>
                                <p style='margin: 0; color: #f8b531;'><strong>⚡ Informações importantes:</strong></p>
                                <ul style='margin: 15px 0 0 0; color: #ccc; line-height: 1.8;'>
                                    <li>Este código é válido por <span class='highlight'>5 minutos</span></li>
                                    <li>Use <span class='highlight'>apenas uma vez</span></li>
                                    <li>Não compartilhe com terceiros</li>
                                </ul>
                            </div>
                            
                            <div style='margin-top: 30px; padding: 15px; background: rgba(255, 107, 107, 0.1); border: 1px solid #ff6b6b; border-radius: 8px;'>
                                <p class='warning' style='margin: 0;'>
                                    ⚠️ Se você não solicitou este código, ignore este email
                                </p>
                            </div>
                        </div>
                        
                        <div class='footer'>
                            <p style='margin: 0 0 5px 0;'>© 2024 No Kapricho Pizzaria - Sistema de Delivery</p>
                            <p style='margin: 0; opacity: 0.7;'>Este é um email automático, não responda esta mensagem</p>
                        </div>
                    </div>
                </body>
                </html>
            ";
            
            $emailService->setMessage($mensagem);

            if ($emailService->send()) {
                return $this->response->setJSON([
                    'sucesso' => true, 
                    'msg' => 'Código enviado para seu email'
                ]);
            } else {
                throw new \Exception($emailService->printDebugger(['headers']));
            }
        } catch (\Exception $e) {
            // Se falhar o envio, retornar código para desenvolvimento
            log_message('error', 'Erro no envio de email: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'sucesso' => true, 
                'msg' => 'Código gerado (email não configurado)',
                'codigo_dev' => $codigo // Para desenvolvimento
            ]);
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
}
