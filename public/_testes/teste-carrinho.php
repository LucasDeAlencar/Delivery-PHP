<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Carrinho - localStorage</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .item { background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; display: flex; justify-content: space-between; align-items: center; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-primary { background: #007bff; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        #carrinho-itens { min-height: 100px; border: 1px solid #ddd; padding: 10px; margin: 10px 0; }
        .debug { background: #e9ecef; padding: 10px; margin: 10px 0; font-family: monospace; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 Teste do Carrinho - localStorage</h1>
        
        <div>
            <button class="btn btn-success" onclick="adicionarItem()">➕ Adicionar Item Teste</button>
            <button class="btn btn-warning" onclick="mostrarCarrinho()">🔄 Atualizar Lista</button>
            <button class="btn btn-danger" onclick="limparTudo()">🗑️ Limpar Tudo</button>
        </div>
        
        <h3>Itens no Carrinho:</h3>
        <div id="carrinho-itens"></div>
        
        <h3>Debug localStorage:</h3>
        <div id="debug" class="debug"></div>
    </div>

    <script>
        let contador = 1;

        function adicionarItem() {
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
            
            const novoItem = {
                id: contador,
                nome: `Produto Teste ${contador}`,
                preco: 10.50,
                quantidade: 1
            };
            
            carrinho.push(novoItem);
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            contador++;
            
            mostrarCarrinho();
            console.log('✅ Item adicionado:', novoItem);
        }

        function removerItem(index) {
            console.log('🗑️ Tentando remover índice:', index);
            
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
            console.log('📦 Carrinho antes:', carrinho);
            
            if (index >= 0 && index < carrinho.length) {
                // REMOÇÃO DRÁSTICA
                const itemRemovido = carrinho[index];
                console.log('🎯 Item a ser removido:', itemRemovido);
                
                // 1. Remover do array
                carrinho.splice(index, 1);
                console.log('📦 Carrinho após splice:', carrinho);
                
                // 2. Limpar localStorage completamente
                localStorage.removeItem('carrinho');
                console.log('🧹 localStorage limpo');
                
                // 3. Salvar novamente
                localStorage.setItem('carrinho', JSON.stringify(carrinho));
                console.log('💾 Carrinho salvo novamente');
                
                // 4. Verificar se salvou
                const verificacao = localStorage.getItem('carrinho');
                console.log('🔍 Verificação:', verificacao);
                
                // 5. Atualizar interface
                mostrarCarrinho();
                
                alert(`✅ Item "${itemRemovido.nome}" removido!`);
            } else {
                alert('❌ Índice inválido!');
            }
        }

        function mostrarCarrinho() {
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
            const container = document.getElementById('carrinho-itens');
            const debug = document.getElementById('debug');
            
            // Mostrar itens
            if (carrinho.length === 0) {
                container.innerHTML = '<p>🛒 Carrinho vazio</p>';
            } else {
                let html = '';
                carrinho.forEach((item, index) => {
                    html += `
                        <div class="item">
                            <div>
                                <strong>${item.nome}</strong><br>
                                <small>Preço: R$ ${item.preco.toFixed(2)} | Qtd: ${item.quantidade}</small>
                            </div>
                            <button class="btn btn-danger" onclick="removerItem(${index})">🗑️ Remover</button>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }
            
            // Debug
            debug.innerHTML = `
                <strong>localStorage raw:</strong><br>
                ${localStorage.getItem('carrinho') || 'null'}<br><br>
                <strong>Total de itens:</strong> ${carrinho.length}<br>
                <strong>Timestamp:</strong> ${new Date().toLocaleTimeString()}
            `;
        }

        function limparTudo() {
            localStorage.clear();
            mostrarCarrinho();
            console.log('🧹 localStorage completamente limpo');
            alert('✅ Carrinho limpo!');
        }

        // Inicializar
        mostrarCarrinho();
        
        // Atualizar a cada 2 segundos para monitorar mudanças
        setInterval(mostrarCarrinho, 2000);
    </script>
</body>
</html>
