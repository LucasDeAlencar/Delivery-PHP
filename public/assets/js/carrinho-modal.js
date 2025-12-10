/**
 * Sistema de Carrinho - Modal Popup (Estilo Menu Produtos)
 * Versão com Modal Bootstrap
 */

console.log('🍕 Carrinho Modal carregando...');

// Objeto global do carrinho
window.Carrinho = {
    itens: [],
    
    // Inicializa o carrinho
    init: function() {
        console.log('🚀 Inicializando carrinho modal...');
        this.carregarDoLocalStorage();
        this.atualizarBadge();
        this.configurarEventos();
        console.log('✅ Carrinho modal inicializado!');
    },
    
    // Carrega do localStorage
    carregarDoLocalStorage: function() {
        try {
            const dados = localStorage.getItem('carrinho_modal');
            this.itens = dados ? JSON.parse(dados) : [];
            console.log('📦 Carrinho carregado:', this.itens.length, 'itens');
        } catch (e) {
            console.error('Erro ao carregar carrinho:', e);
            this.itens = [];
        }
    },
    
    // Salva no localStorage
    salvar: function() {
        try {
            localStorage.setItem('carrinho_modal', JSON.stringify(this.itens));
            console.log('💾 Carrinho salvo!');
            this.atualizarBadge();
        } catch (e) {
            console.error('Erro ao salvar:', e);
        }
    },
    
    // Adiciona produto
    adicionar: function(produto) {
        console.log('➕ Adicionando:', produto.nome);
        
        // Verifica se já existe
        const existe = this.itens.find(item => 
            item.id === produto.id && item.observacoes === produto.observacoes
        );
        
        if (existe) {
            existe.quantidade += produto.quantidade;
            existe.total = existe.quantidade * existe.preco;
        } else {
            this.itens.push({
                id: produto.id,
                nome: produto.nome,
                preco: produto.preco,
                quantidade: produto.quantidade,
                total: produto.preco * produto.quantidade,
                observacoes: produto.observacoes || '',
                imagem: produto.imagem || ''
            });
        }
        
        this.salvar();
        this.mostrarNotificacao('✅ Produto adicionado ao carrinho!', 'success');
    },
    
    // Remove produto
    remover: function(index) {
        console.log('🗑️ Removendo item:', index);
        const item = this.itens[index];
        this.itens.splice(index, 1);
        this.salvar();
        this.atualizarModal();
        this.mostrarNotificacao(`🗑️ ${item.nome} removido`, 'info');
    },
    
    // Atualiza quantidade
    atualizarQuantidade: function(index, quantidade) {
        if (quantidade < 1) {
            this.remover(index);
            return;
        }
        
        this.itens[index].quantidade = quantidade;
        this.itens[index].total = this.itens[index].preco * quantidade;
        this.salvar();
        this.atualizarModal();
    },
    
    // Edita observações do item
    editarObservacoes: function(index) {
        const item = this.itens[index];
        const novaObs = prompt('Editar observações:', item.observacoes || '');
        
        if (novaObs !== null) {
            this.itens[index].observacoes = novaObs.trim();
            this.salvar();
            this.atualizarModal();
            this.mostrarNotificacao('✅ Observações atualizadas!', 'success');
        }
    },
    
    // Limpa carrinho
    limpar: function() {
        if (this.itens.length === 0) {
            this.mostrarNotificacao('⚠️ Carrinho já está vazio', 'warning');
            return;
        }
        
        if (confirm('🗑️ Deseja realmente limpar todo o carrinho?')) {
            this.itens = [];
            this.salvar();
            this.atualizarModal();
            this.mostrarNotificacao('🗑️ Carrinho limpo!', 'success');
        }
    },
    
    // Retorna total de itens
    getTotalItens: function() {
        return this.itens.reduce((total, item) => total + item.quantidade, 0);
    },
    
    // Retorna valor total
    getValorTotal: function() {
        return this.itens.reduce((total, item) => total + item.total, 0);
    },
    
    // Atualiza badge
    atualizarBadge: function() {
        const total = this.getTotalItens();
        const badge = document.getElementById('carrinho-badge');
        
        console.log('🔄 Atualizando badge:', total, 'itens');
        
        if (badge) {
            badge.textContent = total;
            badge.style.display = total > 0 ? 'flex' : 'none';
        }
    },
    
    // Abre modal
    abrirModal: function() {
        console.log('📂 Abrindo modal do carrinho...');
        this.atualizarModal();
        $('#modalCarrinho').modal('show');
    },
    
    // Atualiza conteúdo do modal
    atualizarModal: function() {
        const body = document.getElementById('modal-carrinho-body');
        const totalValor = document.getElementById('modal-carrinho-total');
        const totalItens = document.getElementById('modal-carrinho-total-itens');
        
        if (!body) return;
        
        // Atualiza total de itens no header
        if (totalItens) {
            const qtd = this.getTotalItens();
            totalItens.textContent = qtd === 1 ? '1 item' : `${qtd} itens`;
        }
        
        // Se carrinho vazio
        if (this.itens.length === 0) {
            body.innerHTML = `
                <div class="carrinho-vazio text-center py-5">
                    <div class="icone mb-4" style="font-size: 5rem; opacity: 0.3;">🛒</div>
                    <h4 class="text-light mb-3">Seu carrinho está vazio</h4>
                    <p class="text-muted">Adicione produtos para continuar</p>
                </div>
            `;
            if (totalValor) totalValor.textContent = 'R$ 0,00';
            
            // Desabilita botões
            document.getElementById('btn-finalizar-pedido')?.setAttribute('disabled', 'true');
            document.getElementById('btn-limpar-carrinho')?.setAttribute('disabled', 'true');
            return;
        }
        
        // Habilita botões
        document.getElementById('btn-finalizar-pedido')?.removeAttribute('disabled');
        document.getElementById('btn-limpar-carrinho')?.removeAttribute('disabled');
        
        // Renderiza itens
        let html = '';
        this.itens.forEach((item, index) => {
            html += `
                <div class="carrinho-item-modal mb-3" style="
                    background: #2d2d2d;
                    border-radius: 10px;
                    padding: 15px;
                    border: 1px solid #333;
                    transition: all 0.3s ease;
                ">
                    <div class="row align-items-center">
                        <!-- Imagem (se houver) -->
                        ${item.imagem ? `
                            <div class="col-md-2 text-center mb-3 mb-md-0">
                                <img src="${item.imagem}" alt="${item.nome}" 
                                     class="img-fluid rounded" 
                                     style="max-height: 60px; border: 1px solid #333;">
                            </div>
                        ` : ''}
                        
                        <!-- Info do Produto -->
                        <div class="col-md-${item.imagem ? '4' : '6'}">
                            <h5 class="text-light mb-1" style="font-size: 1rem; font-weight: 600;">
                                ${item.nome}
                            </h5>
                            ${item.observacoes ? `
                                <small class="text-muted d-block" style="font-size: 0.85rem; cursor: pointer;" 
                                       onclick="Carrinho.editarObservacoes(${index})" 
                                       title="Clique para editar">
                                    <i class="fas fa-comment-dots mr-1"></i>${item.observacoes}
                                    <i class="fas fa-edit ml-1" style="font-size: 0.75rem; opacity: 0.6;"></i>
                                </small>
                            ` : `
                                <small class="text-muted d-block" style="font-size: 0.85rem; cursor: pointer;" 
                                       onclick="Carrinho.editarObservacoes(${index})" 
                                       title="Clique para adicionar observações">
                                    <i class="fas fa-plus-circle mr-1"></i>Adicionar observações
                                </small>
                            `}
                            <div class="text-warning mt-1" style="font-size: 0.9rem;">
                                R$ ${item.preco.toFixed(2).replace('.', ',')} <span class="text-muted">x ${item.quantidade}</span>
                            </div>
                        </div>
                        
                        <!-- Controles de Quantidade -->
                        <div class="col-md-3 text-center mb-2 mb-md-0">
                            <div class="input-group" style="max-width: 130px; margin: 0 auto;">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-warning btn-sm" 
                                            onclick="Carrinho.atualizarQuantidade(${index}, ${item.quantidade - 1})"
                                            style="border-color: #f8b531; color: #f8b531;">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center bg-dark text-light" 
                                       value="${item.quantidade}" readonly
                                       style="border-color: #f8b531; max-width: 50px;">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-warning btn-sm" 
                                            onclick="Carrinho.atualizarQuantidade(${index}, ${item.quantidade + 1})"
                                            style="border-color: #f8b531; color: #f8b531;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total e Remover -->
                        <div class="col-md-3 text-center text-md-right">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-end">
                                <strong class="text-warning mr-3" style="font-size: 1.1rem;">
                                    R$ ${item.total.toFixed(2).replace('.', ',')}
                                </strong>
                                <button class="btn btn-sm btn-danger" 
                                        onclick="Carrinho.remover(${index})"
                                        style="width: 35px; height: 35px; padding: 0;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        body.innerHTML = html;
        
        // Atualiza total
        if (totalValor) {
            totalValor.textContent = 'R$ ' + this.getValorTotal().toFixed(2).replace('.', ',');
        }
    },
    
    // Finaliza pedido
    finalizar: function() {
        if (this.itens.length === 0) {
            this.mostrarNotificacao('⚠️ Carrinho vazio!', 'warning');
            return;
        }
        
        console.log('🎯 Finalizando pedido...');
        $('#modalCarrinho').modal('hide');
        
        // Redireciona para página de checkout
        setTimeout(() => {
            window.location.href = '/carrinho';
        }, 300);
    },
    
    // Configura eventos
    configurarEventos: function() {
        console.log('⚙️ Configurando eventos...');
        
        // Clique no ícone do carrinho
        const container = document.getElementById('carrinho-badge-container');
        
        if (container) {
            console.log('✅ Container do carrinho encontrado');
            
            container.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('👆 Clique no carrinho detectado!');
                this.abrirModal();
            });
        } else {
            console.error('❌ Container do carrinho NÃO encontrado!');
        }
        
        // Evento global como backup
        document.addEventListener('click', (e) => {
            if (e.target.closest('#carrinho-badge-container')) {
                e.preventDefault();
                console.log('👆 Clique no carrinho (evento global)');
                this.abrirModal();
            }
        });
        
        console.log('✅ Eventos configurados!');
    },
    
    // Mostra notificação estilo dark (igual ao menu de produtos)
    mostrarNotificacao: function(mensagem, tipo = 'success') {
        const icones = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };
        
        const cores = {
            success: '#f8b531',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8'
        };
        
        const icone = icones[tipo] || icones.success;
        const cor = cores[tipo] || cores.success;
        
        const notificacao = $(`
            <div class="notificacao-carrinho" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: #2d2d2d;
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                border-left: 4px solid ${cor};
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
                z-index: 10000;
                max-width: 300px;
                transform: translateX(400px);
                transition: all 0.3s ease;
                font-family: 'Poppins', sans-serif;
            ">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 20px;">${icone}</span>
                    <div style="font-size: 14px; font-weight: 500;">${mensagem}</div>
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
};

// Inicializa quando DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        Carrinho.init();
    });
} else {
    Carrinho.init();
}

console.log('✅ Carrinho Modal carregado!');
