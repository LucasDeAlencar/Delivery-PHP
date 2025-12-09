<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de BaseURL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        .info {
            color: #17a2b8;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #666;
            border-bottom: 2px solid #f8b531;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8b531;
            color: white;
        }
        .test-result {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .test-pass {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .test-fail {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>🔧 Teste de Configuração de BaseURL</h1>
    
    <div class="card">
        <h2>📊 Informações do Servidor</h2>
        <table>
            <tr>
                <th>Variável</th>
                <th>Valor</th>
            </tr>
            <tr>
                <td>HTTP_HOST</td>
                <td class="info"><?= $_SERVER['HTTP_HOST'] ?? 'Não definido' ?></td>
            </tr>
            <tr>
                <td>SERVER_NAME</td>
                <td class="info"><?= $_SERVER['SERVER_NAME'] ?? 'Não definido' ?></td>
            </tr>
            <tr>
                <td>SERVER_PORT</td>
                <td class="info"><?= $_SERVER['SERVER_PORT'] ?? 'Não definido' ?></td>
            </tr>
            <tr>
                <td>HTTPS</td>
                <td class="info"><?= $_SERVER['HTTPS'] ?? 'Não definido' ?></td>
            </tr>
            <tr>
                <td>REQUEST_URI</td>
                <td class="info"><?= $_SERVER['REQUEST_URI'] ?? 'Não definido' ?></td>
            </tr>
        </table>
    </div>
    
    <div class="card">
        <h2>🌐 Detecção de Ambiente</h2>
        <?php
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
        
        echo "<p><strong>Protocolo detectado:</strong> <span class='info'>$protocol</span></p>";
        echo "<p><strong>Host detectado:</strong> <span class='info'>$host</span></p>";
        
        if (strpos($host, 'nookapricho.wuaze.com') !== false) {
            echo "<p class='success'>✅ Ambiente: PRODUÇÃO (nookapricho.wuaze.com)</p>";
            $expectedBaseURL = 'https://nookapricho.wuaze.com/';
        } else if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
            echo "<p class='success'>✅ Ambiente: DESENVOLVIMENTO (localhost)</p>";
            $expectedBaseURL = $protocol . $host . '/';
        } else {
            echo "<p class='info'>ℹ️ Ambiente: OUTRO ($host)</p>";
            $expectedBaseURL = $protocol . $host . '/';
        }
        
        echo "<p><strong>BaseURL esperado:</strong> <span class='info'>$expectedBaseURL</span></p>";
        ?>
    </div>
    
    <div class="card">
        <h2>🧪 Testes de Assets</h2>
        
        <?php
        $assets = [
            'CSS Principal' => 'web/src/css/style.css',
            'JS jQuery' => 'web/src/js/jquery.min.js',
            'Imagem BG' => 'web/src/images/bg_1.jpg',
            'Carrinho JS' => 'assets/js/carrinho-modal.js',
            'Carrinho CSS' => 'assets/css/carrinho-modal.css',
        ];
        
        foreach ($assets as $nome => $caminho) {
            $url = $expectedBaseURL . $caminho;
            $arquivo = __DIR__ . '/' . $caminho;
            
            echo "<div class='test-result ";
            
            if (file_exists($arquivo)) {
                echo "test-pass'>";
                echo "✅ <strong>$nome:</strong> Arquivo existe<br>";
                echo "<small>$url</small>";
            } else {
                echo "test-fail'>";
                echo "❌ <strong>$nome:</strong> Arquivo NÃO encontrado<br>";
                echo "<small>Esperado em: $arquivo</small>";
            }
            
            echo "</div>";
        }
        ?>
    </div>
    
    <div class="card">
        <h2>🔗 Links de Teste</h2>
        <p>Clique nos links abaixo para testar se os assets carregam:</p>
        <ul>
            <?php foreach ($assets as $nome => $caminho): ?>
                <li>
                    <a href="<?= $expectedBaseURL . $caminho ?>" target="_blank">
                        <?= $nome ?> (<?= $caminho ?>)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div class="card">
        <h2>📝 Instruções</h2>
        <ol>
            <li>Verifique se todos os testes acima passaram (✅)</li>
            <li>Clique nos links de teste para confirmar que os assets carregam</li>
            <li>Se algum teste falhar (❌), verifique:
                <ul>
                    <li>Se o link simbólico <code>public/web</code> existe</li>
                    <li>Se os arquivos estão nos locais corretos</li>
                    <li>Se as permissões estão corretas</li>
                </ul>
            </li>
            <li>Após confirmar que tudo funciona, <strong>DELETE ESTE ARQUIVO</strong> por segurança</li>
        </ol>
    </div>
    
    <div class="card">
        <h2>⚠️ Segurança</h2>
        <p class="error">
            <strong>IMPORTANTE:</strong> Este arquivo é apenas para testes. 
            Delete-o após confirmar que tudo funciona corretamente!
        </p>
        <p>
            Para deletar:
            <code>rm public/test-baseurl.php</code>
        </p>
    </div>
    
    <div class="card">
        <h2>🚀 Próximos Passos</h2>
        <ol>
            <li>✅ Confirme que todos os testes passaram</li>
            <li>✅ Teste o site principal: <a href="<?= $expectedBaseURL ?>" target="_blank"><?= $expectedBaseURL ?></a></li>
            <li>✅ Teste o carrinho</li>
            <li>✅ Verifique se as imagens aparecem</li>
            <li>✅ Delete este arquivo de teste</li>
        </ol>
    </div>
</body>
</html>
