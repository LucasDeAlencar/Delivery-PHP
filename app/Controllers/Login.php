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

        // Verificar se é uma requisição POST
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->back()->with('atencao', 'Método não permitido');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validação básica
        if (empty($email) || empty($password)) {
            return redirect()->back()->with('atencao', 'Por favor, preencha todos os campos');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('atencao', 'Por favor, digite um e-mail válido');
        }

        $autenticacao = service('autenticacao');

        if ($autenticacao->login($email, $password)) {

            $usuario = $autenticacao->pegaUsuarioLogado();

            // Verificar se o usuário está ativo
            if (!$usuario->ativo) {
                $autenticacao->logout();
                return redirect()->back()->with('atencao', 'Sua conta está desativada. Entre em contato com o suporte.');
            }

            // Redirecionar baseado no tipo de usuário
            if ($usuario->is_admin) {
                // Admin vai para dashboard
                return redirect()->to(site_url('admin/home'))->with('sucesso', "Olá {$usuario->nome}, que bom que está de volta!");
            } else {
                // Operador vai direto para pedidos
                return redirect()->to(site_url('admin/pedidos'))->with('sucesso', "Bem-vindo(a), {$usuario->nome}! Você foi direcionado para a área de Pedidos.");
            }
        }

        return redirect()->back()->with('atencao', 'E-mail ou senha incorretos');
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
