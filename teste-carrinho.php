<?php
// Simulação de dados de produtos para teste
$produtos = [
    ['id' => 1, 'nome' => 'Pizza Margherita', 'preco' => 25.90, 'ativo' => true],
    ['id' => 2, 'nome' => 'Pizza Calabresa', 'preco' => 28.90, 'ativo' => true],
    ['id' => 3, 'nome' => 'Pizza Portuguesa', 'preco' => 32.90, 'ativo' => false], // Inativo
    ['id' => 999, 'nome' => 'Produto Removido', 'preco' => 15.90, 'ativo' => true] // Será removido
];

// API endpoint para validação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'validar') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    $produtoIds = $input['produto_ids'] ?? [];
    
    $produtosValidos = [];
    $produtosInvalidos = [];
    
    foreach ($produtoIds as $id) {
        $produto = array_filter($produtos, fn($p) => $p['id'] == $id && $p['ativo']);
        if (empty($produto)) {
            $produtosInvalidos[] = (int)$id;
        } else {
            $produtosValidos[] = (int)$id;
        }
    }
    
    echo json_encode([
        'success' => true,
        'produtos_validos' => $produtosValidos,
        'produtos_invalidos' => $produtosInvalidos
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste Carrinho - LocalStorage</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .produto { border: 1px solid #ddd; padding: 10px; margin: 10px 0; }
        .carrinho { background: #f5f5f5; padding: 15px; margin: 20px 0; }
        button { padding: 8px 15px; margin: 5px; cursor: pointer; }
        .invalido { background: #ffebee; border-color: #f44336; }
        .log { background: #f0f0f0; padding: 10px; margin: 10px 0; font-family: monospace; }
    </style>
</head>
<body>
    <h1>Teste do Sistema de Carrinho</h1>
    
    <div class="carrinho">
        <h3>Carrinho (<span id="total-itens">0</span> itens)</h3>
        <div id="itens-carrinho"></div>
        <button onclick="limparCarrinho()">Limpar Carrinho</button>
        <button onclick="validarCarrinho()">Validar Carrinho</button>
    </div>
    
    <h3>Produtos Disponíveis</h3>
    <?php foreach ($produtos as $produto): ?>
    <div class="produto <?= !$produto['ativo'] ? 'invalido' : '' ?>">
        <strong><?= $produto['nome'] ?></strong> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
        <?= !$produto['ativo'] ? ' (INATIVO)' : '' ?>
        <button onclick="adicionarAoCarrinho(<?= $produto['id'] ?>, '<?= $produto['nome'] ?>', <?= $produto['preco'] ?>)">
            Adicionar
        </button>
    </div>
    <?php endforeach; ?>
    
    <div class="log" id="log"></div>

    <script>
        // Sistema de Carrinho com LocalStorage
        const Carrinho = {
            sessionKey: 'teste_carrinho',
            itens: [],
            
            init() {
                this.carregarDoLocalStorage();
                this.atualizarInterface();
                this.log('Sistema iniciado');
            },
            
            carregarDoLocalStorage() {
                const dados = localStorage.getItem(this.sessionKey);
                this.itens = dados ? JSON.parse(dados) : [];
                this.log(`Carregados ${this.itens.length} itens do localStorage`);
            },
            
            salvarNoLocalStorage() {
                localStorage.setItem(this.sessionKey, JSON.stringify(this.itens));
                this.log('Carrinho salvo no localStorage');
            },
            
            adicionar(id, nome, preco) {
                const existe = this.itens.find(item => item.id === id);
                if (existe) {
                    existe.quantidade++;
                    existe.total = existe.quantidade * existe.preco;
                } else {
                    this.itens.push({
                        id: id,
                        nome: nome,
                        preco: preco,
                        quantidade: 1,
                        total: preco
                    });
                }
                this.salvarNoLocalStorage();
                this.atualizarInterface();
                this.log(`Adicionado: ${nome}`);
            },
            
            remover(id) {
                const index = this.itens.findIndex(item => item.id === id);
                if (index > -1) {
                    const item = this.itens[index];
                    this.itens.splice(index, 1);
                    this.salvarNoLocalStorage();
                    this.atualizarInterface();
                    this.log(`Removido: ${item.nome}`);
                }
            },
            
            limpar() {
                this.itens = [];
                this.salvarNoLocalStorage();
                this.atualizarInterface();
                this.log('Carrinho limpo');
            },
            
            validar() {
                if (this.itens.length === 0) {
                    this.log('Carrinho vazio - nada para validar');
                    return;
                }
                
                const produtoIds = this.itens.map(item => item.id);
                this.log(`Validando produtos: ${produtoIds.join(', ')}`);
                
                fetch('?action=validar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ produto_ids: produtoIds })
                })
                .then(response => response.json())
                .then(data => {
                    this.processarValidacao(data.produtos_invalidos);
                })
                .catch(error => {
                    this.log(`Erro na validação: ${error.message}`);
                });
            },
            
            processarValidacao(produtosInvalidos) {
                if (produtosInvalidos.length === 0) {
                    this.log('✅ Todos os produtos são válidos');
                    return;
                }
                
                const itensRemovidos = [];
                this.itens = this.itens.filter(item => {
                    const isInvalido = produtosInvalidos.includes(item.id);
                    if (isInvalido) {
                        itensRemovidos.push(item.nome);
                    }
                    return !isInvalido;
                });
                
                if (itensRemovidos.length > 0) {
                    this.salvarNoLocalStorage();
                    this.atualizarInterface();
                    this.log(`⚠️ Removidos produtos inválidos: ${itensRemovidos.join(', ')}`);
                }
            },
            
            atualizarInterface() {
                const container = document.getElementById('itens-carrinho');
                const totalItens = document.getElementById('total-itens');
                
                totalItens.textContent = this.itens.reduce((total, item) => total + item.quantidade, 0);
                
                if (this.itens.length === 0) {
                    container.innerHTML = '<p>Carrinho vazio</p>';
                    return;
                }
                
                container.innerHTML = this.itens.map(item => `
                    <div style="border-bottom: 1px solid #ddd; padding: 5px 0;">
                        <strong>${item.nome}</strong> - 
                        Qtd: ${item.quantidade} - 
                        R$ ${item.total.toFixed(2).replace('.', ',')}
                        <button onclick="Carrinho.remover(${item.id})" style="margin-left: 10px;">Remover</button>
                    </div>
                `).join('');
            },
            
            log(mensagem) {
                const log = document.getElementById('log');
                const timestamp = new Date().toLocaleTimeString();
                log.innerHTML += `<div>[${timestamp}] ${mensagem}</div>`;
                log.scrollTop = log.scrollHeight;
                console.log(mensagem);
            }
        };
        
        // Funções globais
        function adicionarAoCarrinho(id, nome, preco) {
            Carrinho.adicionar(id, nome, preco);
        }
        
        function limparCarrinho() {
            Carrinho.limpar();
        }
        
        function validarCarrinho() {
            Carrinho.validar();
        }
        
        // Inicializar quando a página carregar
        document.addEventListener('DOMContentLoaded', () => {
            Carrinho.init();
            
            // Simular produtos inválidos após 3 segundos
            setTimeout(() => {
                Carrinho.log('🔄 Simulando remoção do produto ID 999...');
                // Na vida real, isso seria feito pelo admin
            }, 3000);
        });
    </script>
</body>
</html>
