/**
 * Sistema de Carrinho Simples e Funcional
 */
window.Carrinho = {
    itens: [],
    
    init: function() {
        this.carregarDoLocalStorage();
        this.atualizarContador();
        this.configurarEventos();
    },
    
    configurarEventos: function() {
        // Usar delegação de eventos para garantir funcionamento
        $(document).off('click.carrinho').on('click.carrinho', '#btn-aumentar', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const campo = $('#quantidade');
            let valor = parseInt(campo.val()) || 1;
            valor = Math.min(valor + 1, 99);
            campo.val(valor).trigger('change');
            
            // Recalcular totais se a função existir
            if (typeof recalcularTotaisComExtras === 'function') {
                recalcularTotaisComExtras();
            }
            
        });
        
        $(document).off('click.carrinho-diminuir').on('click.carrinho-diminuir', '#btn-diminuir', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const campo = $('#quantidade');
            let valor = parseInt(campo.val()) || 1;
            valor = Math.max(valor - 1, 1);
            campo.val(valor).trigger('change');
            
            // Recalcular totais se a função existir
            if (typeof recalcularTotaisComExtras === 'function') {
                recalcularTotaisComExtras();
            }
            
        });
        
        // Evento para mudanças manuais no campo
        $(document).off('input.carrinho').on('input.carrinho', '#quantidade', function(e) {
            let valor = parseInt($(this).val()) || 1;
            valor = Math.min(Math.max(valor, 1), 99);
            $(this).val(valor);
            
            // Recalcular totais se a função existir
            if (typeof recalcularTotaisComExtras === 'function') {
                recalcularTotaisComExtras();
            }
            
        });
        
        // Forçar atualização quando modal abre
        $(document).off('shown.bs.modal.carrinho').on('shown.bs.modal.carrinho', '#modalCompra', function() {
            $('#quantidade').val(1);
        });
        
        // Atualizar carrinho quando modal do carrinho abre
        $(document).off('show.bs.modal.carrinho-modal').on('show.bs.modal.carrinho-modal', '#modalCarrinho', () => {
            this.atualizarModalCarrinho();
        });
    },
    
    adicionar: function(produto) {
        
        // Validação mais flexível
        if (!produto.id || !produto.nome || produto.preco === undefined || produto.preco === null || !produto.quantidade) {
            console.error('❌ Dados do produto inválidos:', produto);
                id: !!produto.id,
                nome: !!produto.nome,
                preco: produto.preco !== undefined && produto.preco !== null,
                quantidade: !!produto.quantidade
            });
            this.mostrarNotificacao('Erro: Dados do produto inválidos', 'error');
            return false;
        }
        
        // Converter valores para garantir tipos corretos
        const produtoLimpo = {
            id: String(produto.id),
            nome: String(produto.nome),
            preco: parseFloat(produto.preco) || 0,
            quantidade: parseInt(produto.quantidade) || 1,
            observacoes: String(produto.observacoes || ''),
            extras: Array.isArray(produto.extras) ? produto.extras : [],
            tamanho: produto.tamanho || null,
            tamanho_id: produto.tamanho_id || null,
            total: 0
        };
        
        // Calcular total incluindo extras
        const totalExtras = produtoLimpo.extras.reduce((sum, e) => sum + (parseFloat(e.preco) * (parseInt(e.quantidade) || 1)), 0);
        produtoLimpo.total = (produtoLimpo.preco + totalExtras) * produtoLimpo.quantidade;
        
        // Criar chave única baseada em ID + tamanho + observações para diferenciar produtos
        const chaveTamanho = produtoLimpo.tamanho ? produtoLimpo.tamanho.nome : '';
        const chaveUnica = `${produtoLimpo.id}_${chaveTamanho}_${produtoLimpo.observacoes}`;
        
        
        // Verificar se produto já existe com mesmas observações e tamanho
        const itemExistente = this.itens.find(item => {
            const itemChaveTamanho = item.tamanho ? item.tamanho.nome : '';
            return `${item.id}_${itemChaveTamanho}_${item.observacoes}` === chaveUnica;
        });
        
        if (itemExistente) {
            itemExistente.quantidade += produtoLimpo.quantidade;
            itemExistente.total = itemExistente.quantidade * itemExistente.preco;
        } else {
            this.itens.push(produtoLimpo);
        }
        
        this.salvarNoLocalStorage();
        this.atualizarContador();
        this.mostrarNotificacao('Produto adicionado!', `${produtoLimpo.nome} foi adicionado ao carrinho`);
        
        return true;
    },
    
    remover: function(produtoId) {
        if (!produtoId) {
            console.error('ID do produto não fornecido');
            return false;
        }
        
        const tamanhoAntes = this.itens.length;
        this.itens = this.itens.filter(item => item.id !== produtoId);
        
        if (this.itens.length === tamanhoAntes) {
            console.warn('Produto não encontrado no carrinho:', produtoId);
            return false;
        }
        
        this.salvarNoLocalStorage();
        this.atualizarContador();
        return true;
    },
    
    limpar: function() {
        this.itens = [];
        this.salvarNoLocalStorage();
        this.atualizarContador();
        this.mostrarNotificacao('Carrinho limpo!', 'Todos os itens foram removidos');
    },
    
    getValorTotal: function() {
        return this.itens.reduce((total, item) => total + item.total, 0);
    },
    
    getQuantidadeTotal: function() {
        return this.itens.reduce((total, item) => total + item.quantidade, 0);
    },
    
    salvarNoLocalStorage: function() {
        try {
            localStorage.setItem('carrinho', JSON.stringify(this.itens));
        } catch (error) {
            console.error('Erro ao salvar carrinho:', error);
        }
    },
    
    carregarDoLocalStorage: function() {
        try {
            const dados = localStorage.getItem('carrinho');
            this.itens = dados ? JSON.parse(dados) : [];
        } catch (error) {
            console.error('Erro ao carregar carrinho:', error);
            this.itens = [];
        }
    },
    
    atualizarModalCarrinho: function() {
        const modal = $('#modal-carrinho-body');
        const totalItens = $('#modal-carrinho-total-itens');
        
        if (this.itens.length === 0) {
            modal.html(`
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #555; opacity: 0.3;"></i>
                    <h4 class="mt-3 text-light">Carrinho Vazio</h4>
                    <p class="text-muted">Adicione alguns produtos deliciosos!</p>
                </div>
            `);
            totalItens.text('0 itens');
            $('#btn-finalizar-pedido').prop('disabled', true);
            return;
        }
        
        let html = '';
        this.itens.forEach((item, index) => {
            html += `
                <div class="carrinho-item mb-3 p-3 rounded" style="background: #2d2d2d; border: 1px solid #333;" data-item-id="${item.id}">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="text-warning mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">${item.nome}</h6>
                            <small class="text-muted">Preço unitário: R$ ${item.preco.toFixed(2).replace('.', ',')}</small>
                        </div>
                        <div class="col-md-4 text-right">
                            <strong class="text-light h6">R$ ${item.total.toFixed(2).replace('.', ',')}</strong>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="text-light small">Quantidade:</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-warning btn-diminuir-item" data-item-id="${item.id}" style="border-color: #f8b531; color: #f8b531;">-</button>
                                </div>
                                <input type="number" class="form-control text-center bg-dark text-warning quantidade-item" 
                                       value="${item.quantidade}" min="1" max="99" data-item-id="${item.id}"
                                       style="border-color: #f8b531; color: #f8b531 !important;">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-warning btn-aumentar-item" data-item-id="${item.id}" style="border-color: #f8b531; color: #f8b531;">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-light small">Observações:</label>
                            <textarea class="form-control form-control-sm bg-dark text-light observacoes-item" 
                                      rows="2" placeholder="Observações..." data-item-id="${item.id}"
                                      style="border-color: #333; resize: none;">${item.observacoes || ''}</textarea>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="text-light small d-block">Ações:</label>
                            <button class="btn btn-outline-danger btn-sm btn-remover-item" data-item-id="${item.id}" 
                                    style="border-color: #dc3545; color: #dc3545;" title="Remover item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        modal.html(html);
        totalItens.text(`${this.getQuantidadeTotal()} ${this.getQuantidadeTotal() === 1 ? 'item' : 'itens'}`);
        $('#btn-finalizar-pedido').prop('disabled', false);
        
        this.configurarEventosModal();
    },
    
    configurarEventosModal: function() {
        // Aumentar quantidade
        $(document).off('click.modal-carrinho').on('click.modal-carrinho', '.btn-aumentar-item', (e) => {
            const itemId = $(e.target).data('item-id');
            const item = this.itens.find(i => i.id === itemId);
            if (item && item.quantidade < 99) {
                item.quantidade++;
                item.total = item.quantidade * item.preco;
                this.salvarNoLocalStorage();
                this.atualizarContador();
                this.atualizarModalCarrinho();
            }
        });
        
        // Diminuir quantidade
        $(document).off('click.modal-carrinho-dim').on('click.modal-carrinho-dim', '.btn-diminuir-item', (e) => {
            const itemId = $(e.target).data('item-id');
            const item = this.itens.find(i => i.id === itemId);
            if (item && item.quantidade > 1) {
                item.quantidade--;
                item.total = item.quantidade * item.preco;
                this.salvarNoLocalStorage();
                this.atualizarContador();
                this.atualizarModalCarrinho();
            }
        });
        
        // Alterar quantidade manualmente
        $(document).off('change.modal-carrinho').on('change.modal-carrinho', '.quantidade-item', (e) => {
            const itemId = $(e.target).data('item-id');
            const novaQtd = Math.min(Math.max(parseInt($(e.target).val()) || 1, 1), 99);
            const item = this.itens.find(i => i.id === itemId);
            if (item) {
                item.quantidade = novaQtd;
                item.total = item.quantidade * item.preco;
                $(e.target).val(novaQtd);
                this.salvarNoLocalStorage();
                this.atualizarContador();
                this.atualizarModalCarrinho();
            }
        });
        
        // Alterar observações
        $(document).off('blur.modal-carrinho').on('blur.modal-carrinho', '.observacoes-item', (e) => {
            const itemId = $(e.target).data('item-id');
            const novasObs = $(e.target).val().trim();
            const item = this.itens.find(i => i.id === itemId);
            if (item) {
                item.observacoes = novasObs;
                this.salvarNoLocalStorage();
            }
        });
        
        // Remover item
        $(document).off('click.modal-carrinho-rem').on('click.modal-carrinho-rem', '.btn-remover-item', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const $btn = $(e.currentTarget);
            const itemId = $btn.data('item-id');
            
            if (!itemId) {
                console.error('ID do item não encontrado');
                return;
            }
            
            const item = this.itens.find(i => i.id === itemId);
            if (!item) {
                console.error('Item não encontrado no carrinho');
                this.atualizarModalCarrinho();
                return;
            }
            
            if (confirm(`Remover "${item.nome}" do carrinho?`)) {
                $btn.prop('disabled', true);
                
                if (this.remover(itemId)) {
                    this.atualizarModalCarrinho();
                    this.mostrarNotificacao('Item removido!', `${item.nome} foi removido do carrinho`, 'info');
                } else {
                    this.mostrarNotificacao('Erro!', 'Não foi possível remover o item', 'error');
                    $btn.prop('disabled', false);
                }
            }
        });
        
        // Eventos dos botões principais
        $('#btn-limpar-carrinho').off('click.carrinho').on('click.carrinho', () => {
            if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
                this.limpar();
                this.atualizarModalCarrinho();
            }
        });
        
        $('#btn-finalizar-pedido').off('click.carrinho').on('click.carrinho', () => {
            this.finalizar();
        });
    },
    
    atualizarContador: function() {
        const quantidade = this.getQuantidadeTotal();
        const contador = $('.carrinho-contador, .badge-total-itens, #modal-carrinho-total-itens');
        
        if (contador.length > 0) {
            contador.text(quantidade > 0 ? quantidade : '0');
            if (quantidade > 0) {
                contador.show();
            } else {
                contador.hide();
            }
        }
        
        // Atualizar total no modal
        const total = $('#modal-carrinho-total');
        if (total.length > 0) {
            total.text('R$ ' + this.getValorTotal().toFixed(2).replace('.', ','));
        }
        
    },
    
    gerarHTMLPopup: function() {
        if (this.itens.length === 0) {
            return `
                <div class="carrinho-vazio">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #555; margin-bottom: 15px;"></i>
                    <p>Seu carrinho está vazio</p>
                </div>
            `;
        }
        
        let html = '';
        this.itens.forEach(item => {
            html += `
                <div class="carrinho-item" data-item-id="${item.id}">
                    <div class="item-header">
                        <div class="item-info">
                            <h5>${item.nome}</h5>
                        </div>
                        <div class="item-preco">R$ ${item.total.toFixed(2).replace('.', ',')}</div>
                    </div>
                    
                    <div class="item-controls">
                        <div class="quantidade-control">
                            <label>Qtd:</label>
                            <button class="btn-qty btn-diminuir-popup" data-item-id="${item.id}">-</button>
                            <input type="number" class="qty-input" value="${item.quantidade}" min="1" max="99" data-item-id="${item.id}">
                            <button class="btn-qty btn-aumentar-popup" data-item-id="${item.id}">+</button>
                        </div>
                        
                        <div class="observacoes-control">
                            <label>Observações:</label>
                            <textarea class="observacoes-input" rows="2" placeholder="Observações..." data-item-id="${item.id}">${item.observacoes || ''}</textarea>
                        </div>
                        
                        <button class="btn-remover" data-item-id="${item.id}" title="Remover item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        return html;
    },
    
    atualizarPopup: function() {
        const lista = document.getElementById('carrinho-lista');
        const subtotal = document.getElementById('subtotal');
        const totalFinal = document.getElementById('total-final');
        
        if (lista) {
            lista.innerHTML = this.gerarHTMLPopup();
            this.configurarEventosPopup();
        }
        
        const valorTotal = this.getValorTotal();
        if (subtotal) subtotal.textContent = `R$ ${valorTotal.toFixed(2).replace('.', ',')}`;
        if (totalFinal) totalFinal.textContent = `R$ ${valorTotal.toFixed(2).replace('.', ',')}`;
    },
    
    configurarEventosPopup: function() {
        // Aumentar quantidade
        document.querySelectorAll('.btn-aumentar-popup').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const itemId = e.target.dataset.itemId;
                const item = this.itens.find(i => i.id === itemId);
                if (item && item.quantidade < 99) {
                    item.quantidade++;
                    item.total = item.quantidade * item.preco;
                    this.salvarNoLocalStorage();
                    this.atualizarContador();
                    this.atualizarPopup();
                }
            });
        });
        
        // Diminuir quantidade
        document.querySelectorAll('.btn-diminuir-popup').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const itemId = e.target.dataset.itemId;
                const item = this.itens.find(i => i.id === itemId);
                if (item && item.quantidade > 1) {
                    item.quantidade--;
                    item.total = item.quantidade * item.preco;
                    this.salvarNoLocalStorage();
                    this.atualizarContador();
                    this.atualizarPopup();
                }
            });
        });
        
        // Alterar quantidade manualmente
        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const itemId = e.target.dataset.itemId;
                const novaQtd = Math.min(Math.max(parseInt(e.target.value) || 1, 1), 99);
                const item = this.itens.find(i => i.id === itemId);
                if (item) {
                    item.quantidade = novaQtd;
                    item.total = item.quantidade * item.preco;
                    e.target.value = novaQtd;
                    this.salvarNoLocalStorage();
                    this.atualizarContador();
                    this.atualizarPopup();
                }
            });
        });
        
        // Alterar observações
        document.querySelectorAll('.observacoes-input').forEach(textarea => {
            textarea.addEventListener('blur', (e) => {
                const itemId = e.target.dataset.itemId;
                const novasObs = e.target.value.trim();
                const item = this.itens.find(i => i.id === itemId);
                if (item) {
                    item.observacoes = novasObs;
                    this.salvarNoLocalStorage();
                }
            });
        });
        
        // Remover item
        document.querySelectorAll('.btn-remover').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const itemId = e.target.dataset.itemId;
                const item = this.itens.find(i => i.id === itemId);
                if (item && confirm(`Remover "${item.nome}" do carrinho?`)) {
                    this.remover(itemId);
                    this.atualizarPopup();
                    this.mostrarNotificacao('Item removido!', `${item.nome} foi removido do carrinho`, 'info');
                }
            });
        });
    },
    
    mostrarNotificacao: function(titulo, mensagem, tipo = 'success') {
        // Remove notificações anteriores
        $('.carrinho-notificacao').remove();
        
        const icones = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };
        
        const cores = {
            success: 'alert-success',
            error: 'alert-danger', 
            info: 'alert-info'
        };
        
        const notificacao = $(`
            <div class="carrinho-notificacao alert ${cores[tipo] || cores.success} position-fixed" 
                 style="top: 80px; right: 20px; z-index: 9999; min-width: 300px; 
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px;">
                <div class="d-flex align-items-center">
                    <i class="fas ${icones[tipo] || icones.success} mr-3"></i>
                    <div>
                        <strong>${titulo}</strong>
                        <div style="margin-top: 5px;">${mensagem}</div>
                    </div>
                    <button type="button" class="close ml-auto">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
        `);
        
        $('body').append(notificacao);
        
        // Fechar ao clicar no X
        notificacao.find('.close').click(function() {
            notificacao.fadeOut(300, function() {
                $(this).remove();
            });
        });
        
        // Auto-remover após 4 segundos
        setTimeout(function() {
            if (notificacao.is(':visible')) {
                notificacao.fadeOut(500, function() {
                    $(this).remove();
                });
            }
        }, 4000);
    },
    
    finalizar: async function() {
        if (this.itens.length === 0) {
            this.mostrarNotificacao('Carrinho vazio!', 'Adicione produtos antes de finalizar', 'error');
            return;
        }
        
        // Verificar preço mínimo
        try {
            const response = await fetch('/api/configuracao/preco-minimo');
            const data = await response.json();
            
            if (data.success && data.preco_minimo > 0) {
                const totalCarrinho = this.calcularTotal();
                
                if (totalCarrinho < data.preco_minimo) {
                    const valorFaltante = data.preco_minimo - totalCarrinho;
                    this.mostrarNotificacao(
                        'Valor mínimo não atingido!', 
                        `Valor mínimo para pedido: R$ ${data.preco_minimo.toFixed(2).replace('.', ',')}. ` +
                        `Adicione mais R$ ${valorFaltante.toFixed(2).replace('.', ',')} em produtos.`, 
                        'warning'
                    );
                    return;
                }
            }
        } catch (error) {
            console.error('Erro ao verificar preço mínimo:', error);
        }
        
        // Abrir modal de finalização
        if (typeof window.FinalizarPedido !== 'undefined') {
            window.FinalizarPedido.abrirModal();
        } else {
            this.mostrarNotificacao('Sistema indisponível', 'Tente novamente em alguns instantes', 'error');
        }
    }
};

// Inicializar quando o documento estiver pronto
$(document).ready(function() {
    window.Carrinho.init();
});
