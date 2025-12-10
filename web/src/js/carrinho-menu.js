/**
 * Sistema de Carrinho que aparece após o menu
 */

window.Carrinho = {
    itens: [],
    
    init() {
        this.carregarCarrinho();
        this.criarElementoCarrinho();
        this.atualizarVisibilidade();
    },
    
    carregarCarrinho() {
        try {
            this.itens = JSON.parse(localStorage.getItem('carrinho') || '[]');
        } catch (error) {
            console.error('Erro ao carregar carrinho:', error);
            this.itens = [];
        }
    },
    
    salvarCarrinho() {
        localStorage.setItem('carrinho', JSON.stringify(this.itens));
        this.atualizarVisibilidade();
        this.atualizarConteudo();
    },
    
    adicionar(produto) {
        const itemExistente = this.itens.find(item => 
            item.id === produto.id && 
            JSON.stringify(item.extras) === JSON.stringify(produto.extras)
        );
        
        if (itemExistente) {
            itemExistente.quantidade += produto.quantidade;
        } else {
            this.itens.push({
                id: produto.id,
                nome: produto.nome,
                preco: produto.preco,
                quantidade: produto.quantidade,
                observacoes: produto.observacoes || '',
                extras: produto.extras || [],
                totalCalculado: produto.totalCalculado || (produto.preco * produto.quantidade)
            });
        }
        
        this.salvarCarrinho();
        this.mostrarNotificacao('Produto adicionado ao carrinho!');
    },
    
    remover(index) {
        this.itens.splice(index, 1);
        this.salvarCarrinho();
    },
    
    limpar() {
        this.itens = [];
        this.salvarCarrinho();
    },
    
    obterTotal() {
        return this.itens.reduce((total, item) => {
            return total + (item.totalCalculado || (item.preco * item.quantidade));
        }, 0);
    },
    
    obterQuantidadeTotal() {
        return this.itens.reduce((total, item) => total + item.quantidade, 0);
    },
    
    criarElementoCarrinho() {
        if ($('#carrinho-flutuante').length > 0) return;
        
        const carrinhoHtml = `
            <div id="carrinho-flutuante" class="carrinho-flutuante" style="display: none;">
                <div class="carrinho-header">
                    <h5><i class="fas fa-shopping-cart"></i> Meu Carrinho</h5>
                    <button class="btn-fechar-carrinho">&times;</button>
                </div>
                <div class="carrinho-body">
                    <div id="carrinho-itens"></div>
                    <div class="carrinho-total">
                        <strong>Total: R$ <span id="carrinho-total-valor">0,00</span></strong>
                    </div>
                </div>
                <div class="carrinho-footer">
                    <button class="btn btn-danger btn-sm" id="btn-limpar-carrinho">Limpar</button>
                    <button class="btn btn-success btn-sm" id="btn-finalizar-carrinho">Finalizar</button>
                </div>
            </div>
        `;
        
        $('body').append(carrinhoHtml);
        this.adicionarEstilos();
        this.configurarEventos();
    },
    
    adicionarEstilos() {
        if ($('#carrinho-estilos').length > 0) return;
        
        const estilos = `
            <style id="carrinho-estilos">
                .carrinho-flutuante {
                    position: fixed;
                    top: 50%;
                    right: 20px;
                    transform: translateY(-50%);
                    width: 350px;
                    max-height: 80vh;
                    background: #fff;
                    border: 2px solid #f8b531;
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                    z-index: 1000;
                    overflow: hidden;
                    animation: slideInRight 0.3s ease-out;
                }
                
                @keyframes slideInRight {
                    from { transform: translateY(-50%) translateX(100%); opacity: 0; }
                    to { transform: translateY(-50%) translateX(0); opacity: 1; }
                }
                
                .carrinho-header {
                    background: linear-gradient(135deg, #f8b531 0%, #e6a429 100%);
                    color: white;
                    padding: 15px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                
                .carrinho-header h5 {
                    margin: 0;
                    font-weight: 600;
                }
                
                .btn-fechar-carrinho {
                    background: none;
                    border: none;
                    color: white;
                    font-size: 24px;
                    cursor: pointer;
                    padding: 0;
                    width: 30px;
                    height: 30px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    transition: background 0.2s;
                }
                
                .btn-fechar-carrinho:hover {
                    background: rgba(255,255,255,0.2);
                }
                
                .carrinho-body {
                    max-height: 400px;
                    overflow-y: auto;
                    padding: 15px;
                }
                
                .carrinho-item {
                    border-bottom: 1px solid #eee;
                    padding: 10px 0;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                
                .carrinho-item:last-child {
                    border-bottom: none;
                }
                
                .item-info {
                    flex: 1;
                }
                
                .item-nome {
                    font-weight: 600;
                    font-size: 14px;
                    margin-bottom: 5px;
                }
                
                .item-detalhes {
                    font-size: 12px;
                    color: #666;
                }
                
                .item-preco {
                    font-weight: 600;
                    color: #f8b531;
                }
                
                .btn-remover-item {
                    background: none;
                    border: none;
                    color: #dc3545;
                    cursor: pointer;
                    padding: 5px;
                    margin-left: 10px;
                }
                
                .carrinho-total {
                    border-top: 2px solid #f8b531;
                    padding-top: 15px;
                    margin-top: 15px;
                    text-align: center;
                    font-size: 18px;
                    color: #333;
                }
                
                .carrinho-footer {
                    padding: 15px;
                    background: #f8f9fa;
                    display: flex;
                    gap: 10px;
                }
                
                .carrinho-footer button {
                    flex: 1;
                    padding: 10px;
                    border: none;
                    border-radius: 8px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s;
                }
                
                .carrinho-vazio {
                    text-align: center;
                    padding: 40px 20px;
                    color: #666;
                }
                
                .carrinho-vazio i {
                    font-size: 48px;
                    margin-bottom: 15px;
                    color: #ddd;
                }
                
                @media (max-width: 768px) {
                    .carrinho-flutuante {
                        width: 90%;
                        right: 5%;
                        left: 5%;
                    }
                }
            </style>
        `;
        
        $('head').append(estilos);
    },
    
    configurarEventos() {
        // Fechar carrinho
        $(document).on('click', '.btn-fechar-carrinho', () => {
            this.esconder();
        });
        
        // Limpar carrinho
        $(document).on('click', '#btn-limpar-carrinho', () => {
            if (confirm('Deseja limpar o carrinho?')) {
                this.limpar();
            }
        });
        
        // Finalizar pedido
        $(document).on('click', '#btn-finalizar-carrinho', () => {
            this.finalizarPedido();
        });
        
        // Remover item
        $(document).on('click', '.btn-remover-item', (e) => {
            const index = $(e.target).data('index');
            this.remover(index);
        });
        
        // Fechar ao clicar fora
        $(document).on('click', (e) => {
            if (!$(e.target).closest('#carrinho-flutuante').length && 
                !$(e.target).closest('#btn-adicionar-carrinho').length) {
                // Não fechar automaticamente - deixar o usuário controlar
            }
        });
    },
    
    mostrar() {
        $('#carrinho-flutuante').fadeIn(300);
        this.atualizarConteudo();
    },
    
    esconder() {
        $('#carrinho-flutuante').fadeOut(300);
    },
    
    atualizarVisibilidade() {
        if (this.itens.length > 0) {
            // Mostrar automaticamente quando há itens
            setTimeout(() => this.mostrar(), 1000);
        } else {
            this.esconder();
        }
    },
    
    atualizarConteudo() {
        const container = $('#carrinho-itens');
        const totalElement = $('#carrinho-total-valor');
        
        if (this.itens.length === 0) {
            container.html(`
                <div class="carrinho-vazio">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Carrinho vazio</p>
                </div>
            `);
            totalElement.text('0,00');
            return;
        }
        
        let html = '';
        this.itens.forEach((item, index) => {
            const extrasTexto = item.extras && item.extras.length > 0 
                ? `+ ${item.extras.length} extra${item.extras.length > 1 ? 's' : ''}` 
                : '';
            
            html += `
                <div class="carrinho-item">
                    <div class="item-info">
                        <div class="item-nome">${item.nome}</div>
                        <div class="item-detalhes">
                            Qtd: ${item.quantidade} ${extrasTexto}
                            ${item.observacoes ? `<br>Obs: ${item.observacoes}` : ''}
                        </div>
                        <div class="item-preco">R$ ${(item.totalCalculado || (item.preco * item.quantidade)).toFixed(2).replace('.', ',')}</div>
                    </div>
                    <button class="btn-remover-item" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        });
        
        container.html(html);
        totalElement.text(this.obterTotal().toFixed(2).replace('.', ','));
    },
    
    finalizarPedido() {
        if (this.itens.length === 0) {
            this.mostrarNotificacao('Carrinho vazio!', 'error');
            return;
        }
        
        // Redirecionar para página de checkout ou abrir modal
        window.location.href = '/carrinho';
    },
    
    mostrarNotificacao(mensagem, tipo = 'success') {
        const icone = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const cor = tipo === 'success' ? '#28a745' : '#dc3545';
        
        // Calcular posição abaixo da navbar
        const navbar = $('#ftco-navbar');
        const navbarHeight = navbar.length ? navbar.outerHeight() : 70;
        const topPosition = navbarHeight + 10;
        
        const notificacao = $(`
            <div class="notificacao-carrinho" style="
                position: fixed;
                top: ${topPosition}px;
                right: 20px;
                background: ${cor};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                z-index: 9999;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            ">
                <i class="fas ${icone} mr-2"></i>${mensagem}
            </div>
        `);
        
        $('body').append(notificacao);
        
        setTimeout(() => notificacao.css('transform', 'translateX(0)'), 100);
        setTimeout(() => {
            notificacao.css('transform', 'translateX(100%)');
            setTimeout(() => notificacao.remove(), 300);
        }, 3000);
    }
};

// Inicializar quando documento estiver pronto
$(document).ready(() => {
    window.Carrinho.init();
});
