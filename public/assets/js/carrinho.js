/**
 * Gerenciador de Carrinho - Delivery PHP
 * Gerencia o carrinho de compras usando localStorage com popup modal
 */

class CarrinhoManager {
    constructor() {
        this.carrinho = this.carregarCarrinho();
        console.log('CarrinhoManager inicializado. Itens no carrinho:', this.carrinho.length);
        this.inicializar();
    }

    /**
     * Inicializa o gerenciador
     */
    inicializar() {
        this.atualizarBadge();
        this.atualizarVisibilidadeMenu();
        this.criarModalCarrinho();
        this.configurarEventos();
    }

    /**
     * Carrega o carrinho do localStorage
     */
    carregarCarrinho() {
        try {
            const carrinhoSalvo = localStorage.getItem('carrinho');
            return carrinhoSalvo ? JSON.parse(carrinhoSalvo) : [];
        } catch (error) {
            console.error('Erro ao carregar carrinho:', error);
            return [];
        }
    }

    /**
     * Salva o carrinho no localStorage
     */
    salvarCarrinho() {
        try {
            localStorage.setItem('carrinho', JSON.stringify(this.carrinho));
            console.log('Carrinho salvo. Total de itens:', this.carrinho.length);
            this.atualizarBadge();
            this.atualizarVisibilidadeMenu();
            this.dispararEvento('carrinhoAtualizado');
        } catch (error) {
            console.error('Erro ao salvar carrinho:', error);
        }
    }

    /**
     * Adiciona um produto ao carrinho
     */
    adicionarProduto(pedido) {
        console.log('🎯 MÉTODO adicionarProduto CHAMADO!');
        console.log('📦 Produto recebido:', pedido);
        console.log('🛒 Carrinho antes:', this.carrinho.length, 'itens');
        
        // Verifica se o produto já existe no carrinho
        const indiceExistente = this.carrinho.findIndex(
            item => item.produto.id === pedido.produto.id && 
                    item.observacoes === pedido.observacoes
        );

        if (indiceExistente !== -1) {
            // Atualiza a quantidade se já existe
            this.carrinho[indiceExistente].quantidade += pedido.quantidade;
            this.carrinho[indiceExistente].total = 
                this.carrinho[indiceExistente].quantidade * 
                this.carrinho[indiceExistente].precoUnitario;
            console.log('Produto já existia. Quantidade atualizada.');
        } else {
            // Adiciona novo item
            this.carrinho.push(pedido);
            console.log('Novo produto adicionado.');
        }

        this.salvarCarrinho();
        console.log('💾 Carrinho salvo! Total agora:', this.carrinho.length, 'itens');
        console.log('📊 Total de itens (quantidade):', this.getTotalItens());
        this.mostrarNotificacao('✅ Produto Adicionado!', `${pedido.produto.nome} foi adicionado ao carrinho`, 'success');
        return true;
    }

    /**
     * Remove um produto do carrinho
     */
    removerProduto(produtoId) {
        const tamanhoAntes = this.carrinho.length;
        this.carrinho = this.carrinho.filter(item => item.produto.id !== produtoId);
        
        if (this.carrinho.length < tamanhoAntes) {
            this.salvarCarrinho();
            this.mostrarNotificacao('🗑️ Item Removido', 'Produto removido do carrinho', 'info');
            this.atualizarModalCarrinho();
        }
    }

    /**
     * Atualiza a quantidade de um produto
     */
    atualizarQuantidade(produtoId, novaQuantidade) {
        const produto = this.carrinho.find(item => item.produto.id === produtoId);
        if (produto && novaQuantidade > 0) {
            produto.quantidade = novaQuantidade;
            produto.total = produto.quantidade * produto.precoUnitario;
            this.salvarCarrinho();
            this.atualizarModalCarrinho();
        }
    }

    /**
     * Limpa todo o carrinho
     */
    limparCarrinho() {
        this.carrinho = [];
        this.salvarCarrinho();
        this.mostrarNotificacao('🧹 Carrinho Limpo', 'Todos os itens foram removidos', 'info');
    }

    /**
     * Retorna o número total de itens no carrinho
     */
    getTotalItens() {
        return this.carrinho.reduce((total, item) => total + item.quantidade, 0);
    }

    /**
     * Retorna o valor total do carrinho
     */
    getValorTotal() {
        return this.carrinho.reduce((total, item) => total + item.total, 0);
    }

    /**
     * Atualiza o badge do carrinho no menu
     */
    atualizarBadge() {
        const totalItens = this.getTotalItens();
        const badge = document.getElementById('carrinho-badge');

        console.log('Atualizando badge. Total de itens:', totalItens);

        if (badge) {
            badge.textContent = totalItens;
            if (totalItens > 0) {
                badge.classList.add('show');
                badge.style.display = 'flex';
            } else {
                badge.classList.remove('show');
                badge.style.display = 'none';
            }
        } else {
            console.warn('Badge do carrinho não encontrado no DOM');
        }
    }

    /**
     * Atualiza a visibilidade do menu do carrinho
     */
    atualizarVisibilidadeMenu() {
        const totalItens = this.getTotalItens();
        const linkCarrinho = document.getElementById('link-carrinho');
        
        console.log('🔄 ATUALIZANDO VISIBILIDADE DO MENU');
        console.log('📊 Total de itens:', totalItens);
        console.log('🔍 Elemento link-carrinho:', linkCarrinho);
        
        if (linkCarrinho) {
            if (totalItens > 0) {
                linkCarrinho.classList.add('show');
                linkCarrinho.style.display = 'block';
                console.log('✅ Menu do carrinho EXIBIDO');
                console.log('📍 Display:', linkCarrinho.style.display);
                console.log('📍 Classes:', linkCarrinho.className);
            } else {
                linkCarrinho.classList.remove('show');
                linkCarrinho.style.display = 'none';
                console.log('❌ Menu do carrinho OCULTO');
            }
        } else {
            console.error('❌❌❌ ERRO: Link do carrinho NÃO encontrado no DOM!');
            console.log('🔍 Tentando encontrar por querySelector...');
            const linkAlt = document.querySelector('#link-carrinho');
            console.log('🔍 Resultado querySelector:', linkAlt);
        }
    }

    /**
     * Cria o modal do carrinho
     */
    criarModalCarrinho() {
        // Remove modal existente se houver
        $('#modalCarrinhoPopup').remove();

        const modalHTML = `
            <div class="modal fade" id="modalCarrinhoPopup" tabindex="-1" role="dialog" aria-labelledby="modalCarrinhoLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                        <div class="modal-header" style="background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%); border-bottom: 1px solid #333;">
                            <h5 class="modal-title text-warning" id="modalCarrinhoLabel">
                                <i class="fas fa-shopping-cart mr-2"></i>Meu Carrinho
                            </h5>
                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="carrinho-conteudo-popup" style="background: #1a1a1a; max-height: 60vh; overflow-y: auto;">
                            <!-- Conteúdo será preenchido dinamicamente -->
                        </div>
                        <div class="modal-footer" style="background: #1a1a1a; border-top: 1px solid #333;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-2"></i>Fechar
                            </button>
                            <button type="button" class="btn btn-danger" id="btn-limpar-carrinho-popup">
                                <i class="fas fa-trash mr-2"></i>Limpar Carrinho
                            </button>
                            <button type="button" class="btn btn-success" id="btn-finalizar-pedido-popup">
                                <i class="fas fa-check-circle mr-2"></i>Finalizar Pedido
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('body').append(modalHTML);
        console.log('Modal do carrinho criado');
        
        this.configurarEventosModal();
    }

    /**
     * Configura eventos do modal
     */
    configurarEventosModal() {
        // Atualizar conteúdo quando modal for aberto
        $('#modalCarrinhoPopup').on('show.bs.modal', () => {
            console.log('Modal do carrinho aberto');
            this.atualizarModalCarrinho();
        });

        // Limpar carrinho
        $('#btn-limpar-carrinho-popup').off('click').on('click', () => {
            if (confirm('Tem certeza que deseja limpar o carrinho?')) {
                this.limparCarrinho();
                this.atualizarModalCarrinho();
            }
        });

        // Finalizar pedido
        $('#btn-finalizar-pedido-popup').off('click').on('click', () => {
            this.finalizarPedido();
        });
    }

    /**
     * Atualiza o conteúdo do modal do carrinho
     */
    atualizarModalCarrinho() {
        const conteudo = $('#carrinho-conteudo-popup');
        
        if (this.carrinho.length === 0) {
            conteudo.html(`
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #333;"></i>
                    <h4 class="mt-3 text-light">Carrinho Vazio</h4>
                    <p class="text-muted">Adicione alguns produtos deliciosos!</p>
                </div>
            `);
            $('#btn-finalizar-pedido-popup').prop('disabled', true);
            return;
        }

        let html = '<div class="carrinho-itens-lista">';
        
        this.carrinho.forEach((item) => {
            html += `
                <div class="carrinho-item-row border-bottom py-3" data-produto-id="${item.produto.id}" style="border-color: #333 !important;">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            ${item.produto.imagem ? 
                                `<img src="${item.produto.imagem}" class="img-fluid rounded" style="max-height: 60px; border: 1px solid #333;">` :
                                `<div class="bg-dark rounded d-flex align-items-center justify-content-center" style="height: 60px; width: 60px; border: 1px solid #333;">
                                    <i class="flaticon-pizza-1 text-warning"></i>
                                </div>`
                            }
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1 text-light">${item.produto.nome}</h6>
                            <small class="text-muted">${item.produto.categoria}</small>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-warning btn-diminuir-carrinho-popup" data-produto-id="${item.produto.id}">-</button>
                                </div>
                                <input type="number" class="form-control text-center bg-dark text-light" value="${item.quantidade}" min="1" readonly style="border-color: #333;">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-warning btn-aumentar-carrinho-popup" data-produto-id="${item.produto.id}">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <strong class="text-warning">R$ ${item.total.toFixed(2).replace('.', ',')}</strong>
                        </div>
                        <div class="col-md-1 text-center">
                            <button class="btn btn-sm btn-outline-danger btn-remover-carrinho-popup" data-produto-id="${item.produto.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    ${item.observacoes ? `<div class="row mt-2"><div class="col-12"><small class="text-muted"><strong>Obs:</strong> ${item.observacoes}</small></div></div>` : ''}
                </div>
            `;
        });

        html += `
            </div>
            <div class="carrinho-total mt-4 p-3 rounded" style="background: #2d2d2d; border: 1px solid #333;">
                <div class="row">
                    <div class="col-md-6">
                        <strong class="text-light">Total de Itens: ${this.getTotalItens()}</strong>
                    </div>
                    <div class="col-md-6 text-right">
                        <h4 class="text-warning mb-0">Total: R$ ${this.getValorTotal().toFixed(2).replace('.', ',')}</h4>
                    </div>
                </div>
            </div>
        `;

        conteudo.html(html);
        $('#btn-finalizar-pedido-popup').prop('disabled', false);
        
        this.configurarBotoesCarrinho();
    }

    /**
     * Configura eventos dos botões do carrinho
     */
    configurarBotoesCarrinho() {
        // Aumentar quantidade
        $('.btn-aumentar-carrinho-popup').off('click').on('click', (e) => {
            const produtoId = parseInt($(e.currentTarget).data('produto-id'));
            const produto = this.carrinho.find(item => item.produto.id === produtoId);
            if (produto) {
                this.atualizarQuantidade(produtoId, produto.quantidade + 1);
            }
        });

        // Diminuir quantidade
        $('.btn-diminuir-carrinho-popup').off('click').on('click', (e) => {
            const produtoId = parseInt($(e.currentTarget).data('produto-id'));
            const produto = this.carrinho.find(item => item.produto.id === produtoId);
            if (produto && produto.quantidade > 1) {
                this.atualizarQuantidade(produtoId, produto.quantidade - 1);
            }
        });

        // Remover produto
        $('.btn-remover-carrinho-popup').off('click').on('click', (e) => {
            const produtoId = parseInt($(e.currentTarget).data('produto-id'));
            if (confirm('Remover este item do carrinho?')) {
                this.removerProduto(produtoId);
            }
        });
    }

    /**
     * Finalizar pedido - redireciona para página de checkout
     */
    finalizarPedido() {
        if (this.carrinho.length === 0) {
            this.mostrarNotificacao('⚠️ Carrinho Vazio', 'Adicione produtos antes de finalizar', 'warning');
            return;
        }

        // Redireciona para a página de checkout
        window.location.href = '/carrinho';
    }

    /**
     * Configura eventos gerais
     */
    configurarEventos() {
        // Clique no link do carrinho - abre o modal
        $(document).on('click', '#link-carrinho a', (e) => {
            e.preventDefault();
            console.log('Link do carrinho clicado');
            $('#modalCarrinhoPopup').modal('show');
        });

        // Sincroniza entre abas
        window.addEventListener('storage', (e) => {
            if (e.key === 'carrinho') {
                console.log('Carrinho atualizado em outra aba');
                this.carrinho = this.carregarCarrinho();
                this.atualizarBadge();
                this.atualizarVisibilidadeMenu();
            }
        });
    }

    /**
     * Mostra notificação
     */
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
                background: #2d2d2d;
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                border-left: 4px solid ${cor};
                box-shadow: 0 5px 20px rgba(0,0,0,0.3);
                z-index: 9999;
                max-width: 300px;
                transform: translateX(400px);
                transition: all 0.3s ease;
                font-family: 'Poppins', sans-serif;
            ">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 20px;">${icone}</span>
                    <div>
                        <div style="font-weight: bold; margin-bottom: 5px;">${titulo}</div>
                        <div style="font-size: 14px;">${mensagem}</div>
                    </div>
                </div>
            </div>
        `);

        $('body').append(notificacao);

        setTimeout(() => {
            notificacao.css('transform', 'translateX(0)');
        }, 100);

        setTimeout(() => {
            notificacao.css('transform', 'translateX(400px)');
            setTimeout(() => notificacao.remove(), 300);
        }, 3000);
    }

    /**
     * Dispara evento customizado
     */
    dispararEvento(nomeEvento) {
        const evento = new CustomEvent(nomeEvento, {
            detail: {
                carrinho: this.carrinho,
                totalItens: this.getTotalItens(),
                valorTotal: this.getValorTotal()
            }
        });
        window.dispatchEvent(evento);
    }
}

// Inicializa o gerenciador de carrinho quando o documento estiver pronto
$(document).ready(function() {
    console.log('🛒 Documento pronto. Inicializando CarrinhoManager...');
    window.carrinhoManager = new CarrinhoManager();
    console.log('✅ CarrinhoManager inicializado com sucesso');
    console.log('📊 Carrinho atual:', window.carrinhoManager.carrinho);
    console.log('📦 Total de itens:', window.carrinhoManager.getTotalItens());
});

// Também inicializa quando a janela carregar completamente
window.addEventListener('load', function() {
    console.log('🌐 Janela carregada completamente');
    if (!window.carrinhoManager) {
        console.warn('⚠️ CarrinhoManager não foi inicializado no document.ready, inicializando agora...');
        window.carrinhoManager = new CarrinhoManager();
    }
    // Força atualização
    window.carrinhoManager.atualizarBadge();
    window.carrinhoManager.atualizarVisibilidadeMenu();
});
