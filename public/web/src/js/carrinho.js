/**
 * Sistema de Carrinho de Compras
 * Funcionalidades para gerenciar o carrinho de produtos
 */

class CarrinhoManager {
    constructor() {
        this.carrinho = this.carregarCarrinho();
        this.inicializar();
    }
    
    inicializar() {
        this.atualizarContadorCarrinho();
        this.criarIconeCarrinho();
    }
    
    // Carregar carrinho do localStorage
    carregarCarrinho() {
        try {
            return JSON.parse(localStorage.getItem('carrinho') || '[]');
        } catch (error) {
            console.error('Erro ao carregar carrinho:', error);
            return [];
        }
    }
    
    // Salvar carrinho no localStorage
    salvarCarrinho() {
        try {
            localStorage.removeItem('carrinho');
            localStorage.setItem('carrinho', JSON.stringify(this.carrinho));
            this.atualizarContadorCarrinho();
        } catch (error) {
            console.error('Erro ao salvar carrinho:', error);
        }
    }
    
    // Adicionar produto ao carrinho
    adicionarProduto(produto) {
        // Verificar se o produto já existe no carrinho
        const produtoExistente = this.carrinho.find(item => 
            item.produto.id === produto.produto.id
        );
        
        if (produtoExistente) {
            // Se existe, aumentar a quantidade
            produtoExistente.quantidade += produto.quantidade;
            produtoExistente.total = produtoExistente.quantidade * produtoExistente.precoUnitario;
        } else {
            // Se não existe, adicionar novo
            this.carrinho.push(produto);
        }
        
        this.salvarCarrinho();
        return true;
    }
    
    // Remover produto do carrinho
    removerProduto(produtoId) {
        if (!produtoId) {
            console.error('ID do produto não fornecido');
            return false;
        }
        
        const tamanhoAntes = this.carrinho.length;
        this.carrinho = this.carrinho.filter(item => item.produto.id !== produtoId);
        
        if (this.carrinho.length === tamanhoAntes) {
            console.warn('Produto não encontrado no carrinho:', produtoId);
            return false;
        }
        
        this.salvarCarrinho();
        return true;
    }
    
    // Atualizar quantidade de um produto
    atualizarQuantidade(produtoId, novaQuantidade) {
        const produto = this.carrinho.find(item => item.produto.id === produtoId);
        if (produto && novaQuantidade > 0) {
            produto.quantidade = novaQuantidade;
            produto.total = produto.quantidade * produto.precoUnitario;
            this.salvarCarrinho();
        }
    }
    
    // Limpar carrinho
    limparCarrinho() {
        this.carrinho = [];
        localStorage.removeItem('carrinho');
        this.salvarCarrinho();
    }
    
    // Obter total do carrinho
    obterTotal() {
        return this.carrinho.reduce((total, item) => total + item.total, 0);
    }
    
    // Obter quantidade total de itens
    obterQuantidadeTotal() {
        return this.carrinho.reduce((total, item) => total + item.quantidade, 0);
    }
    
    // Atualizar contador visual do carrinho
    atualizarContadorCarrinho() {
        const quantidade = this.obterQuantidadeTotal();
        const contador = $('.carrinho-contador');
        
        if (contador.length > 0) {
            contador.text(quantidade);
            if (quantidade > 0) {
                contador.show().addClass('animate__animated animate__pulse');
                setTimeout(() => contador.removeClass('animate__animated animate__pulse'), 1000);
            } else {
                contador.hide();
            }
        }
    }
    
    // Criar ícone do carrinho na navbar
    criarIconeCarrinho() {
        const navbar = $('.navbar-nav');
        if (navbar.length > 0 && $('.carrinho-item').length === 0) {
            const iconeCarrinho = $(`
                <li class="nav-item carrinho-item">
                    <a href="#" class="nav-link carrinho-link" data-toggle="modal" data-target="#modalCarrinho">
                        <i class="flaticon-pizza-1"></i>
                        Carrinho
                        <span class="carrinho-contador badge badge-warning ml-1" style="display: none;">0</span>
                    </a>
                </li>
            `);
            
            navbar.append(iconeCarrinho);
            this.criarModalCarrinho();
        }
    }
    
    // Criar modal do carrinho
    criarModalCarrinho() {
        if ($('#modalCarrinho').length === 0) {
            const modalCarrinho = $(`
                <div class="modal fade" id="modalCarrinho" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title">
                                    <i class="flaticon-pizza-1 mr-2"></i>Meu Carrinho
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="carrinho-conteudo">
                                <!-- Conteúdo será preenchido dinamicamente -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-danger" id="btn-limpar-carrinho">Limpar Carrinho</button>
                                <button type="button" class="btn btn-success" id="btn-finalizar-pedido">Finalizar Pedido</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            
            $('body').append(modalCarrinho);
            this.configurarEventosCarrinho();
        }
    }
    
    // Configurar eventos do modal do carrinho
    configurarEventosCarrinho() {
        // Atualizar conteúdo quando modal for aberto
        $('#modalCarrinho').on('show.bs.modal', () => {
            this.atualizarModalCarrinho();
        });
        
        // Limpar carrinho
        $('#btn-limpar-carrinho').click(() => {
            if (confirm('Tem certeza que deseja limpar o carrinho?')) {
                this.limparCarrinho();
                this.atualizarModalCarrinho();
                this.mostrarNotificacao('Carrinho Limpo!', 'Todos os itens foram removidos.', 'info');
            }
        });
        
        // Finalizar pedido
        $('#btn-finalizar-pedido').click(() => {
            this.finalizarPedido();
        });
    }
    
    // Atualizar conteúdo do modal do carrinho
    atualizarModalCarrinho() {
        const conteudo = $('#carrinho-conteudo');
        
        if (this.carrinho.length === 0) {
            conteudo.html(`
                <div class="text-center py-5">
                    <i class="flaticon-pizza-1" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3 text-muted">Carrinho Vazio</h4>
                    <p class="text-muted">Adicione alguns produtos deliciosos!</p>
                </div>
            `);
            $('#btn-finalizar-pedido').prop('disabled', true);
            return;
        }
        
        let html = '<div class="carrinho-itens">';
        
        this.carrinho.forEach((item, index) => {
            html += `
                <div class="carrinho-item-row border-bottom py-3" data-produto-id="${item.produto.id}">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            ${item.produto.imagem ? 
                                `<img src="${item.produto.imagem}" class="img-fluid rounded" style="max-height: 60px;">` :
                                `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px; width: 60px;"><i class="flaticon-pizza-1 text-muted"></i></div>`
                            }
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1">${item.produto.nome}</h6>
                            <small class="text-muted">${item.produto.categoria}</small>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary btn-diminuir-carrinho" data-produto-id="${item.produto.id}">-</button>
                                </div>
                                <input type="number" class="form-control text-center" value="${item.quantidade}" min="1" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-aumentar-carrinho" data-produto-id="${item.produto.id}">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <strong>R$ ${item.total.toFixed(2).replace('.', ',')}</strong>
                        </div>
                        <div class="col-md-2 text-center">
                            <button class="btn btn-sm btn-outline-danger btn-remover-carrinho" data-produto-id="${item.produto.id}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    ${item.observacoes ? `<div class="row mt-2"><div class="col-12"><small class="text-muted"><strong>Obs:</strong> ${item.observacoes}</small></div></div>` : ''}
                </div>
            `;
        });
        
        html += `
            </div>
            <div class="carrinho-total mt-4 p-3 bg-light rounded">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Total de Itens: ${this.obterQuantidadeTotal()}</strong>
                    </div>
                    <div class="col-md-6 text-right">
                        <h4 class="text-success mb-0">Total: R$ ${this.obterTotal().toFixed(2).replace('.', ',')}</h4>
                    </div>
                </div>
            </div>
        `;
        
        conteudo.html(html);
        $('#btn-finalizar-pedido').prop('disabled', false);
        
        // Configurar eventos dos botões
        this.configurarBotoesCarrinho();
    }
    
    // Configurar eventos dos botões do carrinho
    configurarBotoesCarrinho() {
        // Aumentar quantidade
        $('.btn-aumentar-carrinho').click((e) => {
            const produtoId = $(e.target).data('produto-id');
            const produto = this.carrinho.find(item => item.produto.id === produtoId);
            if (produto) {
                this.atualizarQuantidade(produtoId, produto.quantidade + 1);
                this.atualizarModalCarrinho();
            }
        });
        
        // Diminuir quantidade
        $('.btn-diminuir-carrinho').click((e) => {
            const produtoId = $(e.target).data('produto-id');
            const produto = this.carrinho.find(item => item.produto.id === produtoId);
            if (produto && produto.quantidade > 1) {
                this.atualizarQuantidade(produtoId, produto.quantidade - 1);
                this.atualizarModalCarrinho();
            }
        });
        
        // Remover produto
        $('.btn-remover-carrinho').click((e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const $btn = $(e.currentTarget);
            const produtoId = $btn.data('produto-id');
            
            if (!produtoId) {
                console.error('ID do produto não encontrado');
                return;
            }
            
            const produto = this.carrinho.find(item => item.produto.id === produtoId);
            if (!produto) {
                console.error('Produto não encontrado no carrinho');
                this.atualizarModalCarrinho();
                return;
            }
            
            if (confirm('Remover este item do carrinho?')) {
                $btn.prop('disabled', true);
                
                if (this.removerProduto(produtoId)) {
                    this.atualizarModalCarrinho();
                    this.mostrarNotificacao('Item Removido!', 'Produto removido do carrinho.', 'info');
                } else {
                    this.mostrarNotificacao('Erro!', 'Não foi possível remover o produto.', 'error');
                    $btn.prop('disabled', false);
                }
            }
        });
    }
    
    // Finalizar pedido
    finalizarPedido() {
        if (this.carrinho.length === 0) {
            this.mostrarNotificacao('Carrinho Vazio!', 'Adicione produtos antes de finalizar.', 'error');
            return;
        }
        
        // Aqui você pode implementar a lógica de finalização
        // Por exemplo: redirecionar para página de checkout, enviar para servidor, etc.
        
        const pedidoFinal = {
            itens: this.carrinho,
            total: this.obterTotal(),
            quantidade: this.obterQuantidadeTotal(),
            timestamp: new Date().toISOString()
        };
        
        
        // Simular processamento
        $('#btn-finalizar-pedido').text('Processando...').prop('disabled', true);
        
        setTimeout(() => {
            this.mostrarNotificacao(
                'Pedido Enviado!', 
                `Seu pedido de R$ ${this.obterTotal().toFixed(2).replace('.', ',')} foi enviado com sucesso!`
            );
            
            // Limpar carrinho após finalizar
            this.limparCarrinho();
            $('#modalCarrinho').modal('hide');
            $('#btn-finalizar-pedido').text('Finalizar Pedido').prop('disabled', false);
        }, 2000);
    }
    
    // Mostrar notificação
    mostrarNotificacao(titulo, mensagem, tipo = 'success') {
        const icones = {
            success: '✅',
            error: '❌',
            info: 'ℹ️',
            warning: '⚠️'
        };
        
        const cores = {
            success: '#28a745',
            error: '#dc3545',
            info: '#17a2b8',
            warning: '#ffc107'
        };
        
        const icone = icones[tipo] || icones.success;
        const cor = cores[tipo] || cores.success;
        
        const notificacao = $(`
            <div class="notificacao-popup" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${cor};
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                z-index: 9999;
                max-width: 300px;
                transform: translateX(100%);
                transition: all 0.3s ease;
            ">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 20px;">${icone}</span>
                    <div>
                        <div style="font-weight: bold; margin-bottom: 5px;">${titulo}</div>
                        <div style="font-size: 14px; opacity: 0.9;">${mensagem}</div>
                    </div>
                </div>
            </div>
        `);
        
        $('body').append(notificacao);
        
        setTimeout(() => notificacao.css('transform', 'translateX(0)'), 100);
        setTimeout(() => {
            notificacao.css('transform', 'translateX(100%)');
            setTimeout(() => notificacao.remove(), 300);
        }, 4000);
    }
}

// Inicializar o gerenciador do carrinho quando o documento estiver pronto
$(document).ready(function() {
    window.carrinhoManager = new CarrinhoManager();
});
