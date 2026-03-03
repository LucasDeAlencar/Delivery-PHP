<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste Direto - Carrinho</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #218838;
        }
        #resultado {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f8f9fa;
        }
        pre {
            background: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            overflow-x: auto;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>🧪 Teste Direto do Carrinho</h1>
    
    <p>Este teste envia uma requisição AJAX diretamente para o endpoint do carrinho.</p>
    
    <button class="btn" onclick="testarAdicionar()">🛒 Testar Adicionar ao Carrinho</button>
    
    <div id="resultado">
        <h3>Resultado:</h3>
        <div id="output">Clique no botão para testar...</div>
    </div>

    <script>
        function testarAdicionar() {
            console.log('Iniciando teste...');
            
            $('#output').html('<p>⏳ Enviando requisição...</p>');
            
            $.ajax({
                url: '/carrinho/adicionar',
                method: 'POST',
                dataType: 'json',
                data: {
                    produto_id: 1,
                    quantidade: 2,
                    observacoes: 'Teste direto via PHP'
                },
                beforeSend: function() {
                    console.log('Enviando requisição...');
                },
                success: function(response) {
                    console.log('✅ Sucesso:', response);
                    
                    let html = '<p class="success">✅ SUCESSO!</p>';
                    html += '<pre>' + JSON.stringify(response, null, 2) + '</pre>';
                    
                    if (response.carrinho) {
                        html += '<h4>Resumo do Carrinho:</h4>';
                        html += '<ul>';
                        html += '<li>Total de Itens: ' + response.carrinho.total_itens + '</li>';
                        html += '<li>Total de Produtos: ' + response.carrinho.total_produtos + '</li>';
                        html += '<li>Valor Total: R$ ' + response.carrinho.total_valor + '</li>';
                        html += '</ul>';
                    }
                    
                    $('#output').html(html);
                },
                error: function(xhr, status, error) {
                    console.error('❌ Erro:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                    
                    let html = '<p class="error">❌ ERRO!</p>';
                    html += '<p><strong>Status:</strong> ' + xhr.status + ' ' + xhr.statusText + '</p>';
                    html += '<p><strong>Erro:</strong> ' + error + '</p>';
                    html += '<h4>Resposta do Servidor:</h4>';
                    html += '<pre>' + xhr.responseText + '</pre>';
                    
                    $('#output').html(html);
                }
            });
        }
        
        // Teste automático ao carregar
        $(document).ready(function() {
            console.log('Página carregada. Pronto para testar!');
            console.log('jQuery versão:', $.fn.jquery);
        });
    </script>
</body>
</html>
