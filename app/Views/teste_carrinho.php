<!DOCTYPE html>
<html>
<head>
    <title>Teste Carrinho - CodeIgniter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .produto-inativo { background-color: #ffebee; }
        .log-container { background: #f8f9fa; border: 1px solid #dee2e6; max-height: 300px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Teste do Sistema de Carrinho</h1>
        
        <!-- Carrinho -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Carrinho (<span id="total-itens">0</span> itens)</h5>
            </div>
            <div class="card-body">
                <div id="itens-carrinho"></div>
                <div class="mt-3">
                    <button class="btn btn-danger" onclick="TesteCarrinho.limpar()">Limpar</button>
                    <button class="btn btn-warning" onclick="TesteCarrinho.validar()">Validar</button>
                    <button class="btn btn-info" onclick="TesteCarrinho.simularRemocao()">Simular Remoção</button>
                </div>
            </div>
        </div>
        
        <!-- Produtos -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Produtos Disponíveis</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($produtos as $produto): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card <?= !$produto['ativo'] ? 'produto-inativo' : '' ?>">
                            <div class="card-body">
                                <h6><?= $produto['nome'] ?></h6>
                                <p>R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                <?= !$produto['ativo'] ? ' <span class="badge bg-danger">INATIVO</span>' : '' ?></p>
                                <button class="btn btn-primary btn-sm" 
                                        onclick="TesteCarrinho.adicionar(<?= $produto['id'] ?>, '<?= $produto['nome'] ?>', <?= $produto['preco'] ?>)">
                                    Adicionar
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Log -->
        <div class="card">
            <div class="card-header">
                <h5>Log de Atividades</h5>
            </div>
            <div class="card-body">
                <div id="log" class="log-container p-3" style="font-family: monospace; font-size: 0.9em;"></div>
            </div>
        </div>
    </div>

    <script>
        const TesteCarrinho = {
            sessionKey: 'teste_carrinho_ci4',
            itens: [],
            
            init() {
                this.carregarLocalStorage();
                this.atualizarInterface();
                this.log('✅ Sistema iniciado');
            },
            
            carregarLocalStorage() {
                try {
                    const dados = localStorage.getItem(this.sessionKey);
                    this.itens = dados ? JSON.parse(dados) : [];
                    this.log(`📦 Carregados ${this.itens.length} itens do localStorage`);
                } catch (e) {
                    this.itens = [];
                    this.log('⚠️ Erro ao carregar localStorage, iniciando vazio');
                }
            },
            
            salvarLocalStorage() {
                try {
                    localStorage.setItem(this.sessionKey, JSON.stringify(this.itens));
                    this.log('💾 Carrinho salvo no localStorage');
                } catch (e) {
                    this.log('❌ Erro ao salvar no localStorage');
                }
            },
            
            adicionar(id, nome, preco) {
                const existe = this.itens.find(item => item.id === id);
                if (existe) {
                    existe.quantidade++;
                    existe.total = existe.quantidade * existe.preco;
                    this.log(`➕ Aumentada quantidade: ${nome} (${existe.quantidade})`);
                } else {
                    this.itens.push({
                        id: id,
                        nome: nome,
                        preco: preco,
                        quantidade: 1,
                        total: preco
                    });
                    this.log(`🆕 Adicionado: ${nome}`);
                }
                this.salvarLocalStorage();
                this.atualizarInterface();
            },
            
            remover(id) {
                const index = this.itens.findIndex(item => item.id === id);
                if (index > -1) {
                    const item = this.itens[index];
                    this.itens.splice(index, 1);
                    this.salvarLocalStorage();
                    this.atualizarInterface();
                    this.log(`🗑️ Removido: ${item.nome}`);
                }
            },
            
            limpar() {
                if (this.itens.length === 0) {
                    this.log('⚠️ Carrinho já está vazio');
                    return;
                }
                this.itens = [];
                this.salvarLocalStorage();
                this.atualizarInterface();
                this.log('🧹 Carrinho limpo');
            },
            
            validar() {
                if (this.itens.length === 0) {
                    this.log('⚠️ Carrinho vazio - nada para validar');
                    return;
                }
                
                const produtoIds = this.itens.map(item => item.id);
                this.log(`🔍 Validando produtos: [${produtoIds.join(', ')}]`);
                
                fetch('<?= site_url('teste-carrinho/validar') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ produto_ids: produtoIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.processarValidacao(data.produtos_invalidos);
                    } else {
                        this.log('❌ Erro na validação');
                    }
                })
                .catch(error => {
                    this.log(`❌ Erro na requisição: ${error.message}`);
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
                    this.salvarLocalStorage();
                    this.atualizarInterface();
                    this.log(`⚠️ Removidos produtos inválidos: ${itensRemovidos.join(', ')}`);
                }
            },
            
            simularRemocao() {
                // Simula a remoção do produto ID 999 (como se fosse removido pelo admin)
                this.log('🎭 Simulando remoção do produto ID 999 pelo admin...');
                setTimeout(() => {
                    this.validar();
                }, 1000);
            },
            
            atualizarInterface() {
                const container = document.getElementById('itens-carrinho');
                const totalItens = document.getElementById('total-itens');
                
                const total = this.itens.reduce((sum, item) => sum + item.quantidade, 0);
                totalItens.textContent = total;
                
                if (this.itens.length === 0) {
                    container.innerHTML = '<div class="alert alert-info">Carrinho vazio</div>';
                    return;
                }
                
                container.innerHTML = this.itens.map(item => `
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <strong>${item.nome}</strong><br>
                            <small>Qtd: ${item.quantidade} × R$ ${item.preco.toFixed(2).replace('.', ',')} = 
                            R$ ${item.total.toFixed(2).replace('.', ',')}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="TesteCarrinho.remover(${item.id})">
                            Remover
                        </button>
                    </div>
                `).join('');
            },
            
            log(mensagem) {
                const log = document.getElementById('log');
                const timestamp = new Date().toLocaleTimeString();
                const div = document.createElement('div');
                div.innerHTML = `<span class="text-muted">[${timestamp}]</span> ${mensagem}`;
                log.appendChild(div);
                log.scrollTop = log.scrollHeight;
                console.log(mensagem);
            }
        };
        
        // Inicializar
        document.addEventListener('DOMContentLoaded', () => {
            TesteCarrinho.init();
        });
    </script>
</body>
</html>
