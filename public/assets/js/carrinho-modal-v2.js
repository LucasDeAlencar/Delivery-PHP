/**
 * Sistema de Carrinho - Modal Popup com Integração API
 * Versão 2.0 - Com persistência no banco de dados
 */

console.log('🍕 Carrinho Modal V2 carregando...');

// Objeto global do carrinho
window.Carrinho = {
    itens: [],
    apiUrl: '/api/carrinho',
    usarApi: false, // DESABILITADO: Usar apenas localStorage (temporário por navegador)
    sessionKey: null, // Chave única da sessão do navegador
    
    // Inicializa o carrinho
    init: function() {
        console.log('🚀 Inicializando carrinho modal V2...');
        this.gerarSessionKey();
        this.carregarCarrinho();
        this.atualizarBadge();
        this.configurarEventos();
        console.log('✅ Carrinho modal V2 inicializado!');
        console.log('🔑 Session Key:', this.sessionKey);
    },
    
    // Gera ou recupera chave única da sessão do navegador
    gerarSessionKey: function() {
        let key = localStorage.getItem('carrinho_session_key');
        if (!key) {
            // Gera chave única: timestamp + random
            key = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('carrinho_session_key', key);
            console.log('🆕 Nova sessão criada:', key);
        } else {
            console.log('♻️ Sessão existente:', key);
        }
        this.sessionKey = key;
    },
    
    // Carrega carrinho do localStorage (método principal)
    carregarCarrinho: function() {
        this.carregarDoLocalStorage();
    },
    
    // Carrega do localStorage (único por navegador)
    carregarDoLocalStorage: function() {
        try {
            const chave = 'carrinho_' + this.sessionKey;
            const dados = localStorage.getItem(chave);
            this.itens = dados ? JSON.parse(dados) : [];
            console.log('📦 Carrinho carregado do localStorage:', this.itens.length, 'itens');
            console.log('📦 Itens:', this.itens);
        } catch (e) {
            console.error('Erro ao carregar carrinho:', e);
            this.itens = [];
        }
    },
    
    // Converte itens da API para formato do frontend
    converterItensApi: function(itensApi) {
        return itensApi.map(item => ({
            id: item.produto_id,
            itemId: item.id, // ID do item no carrinho_temporario
            nome: item.produto_nome,
            preco: parseFloat(item.preco_unitario),
            quantidade: parseInt(item.quantidade),
            total: parseFloat(item.preco_total),
            observacoes: item.observacoes || '',
            imagem: item.produto_imagem || ''
        }));
    },
    
    // Sincroniza com localStorage (único por navegador)
    sincronizarLocalStorage: function() {
        try {
            const chave = 'carrinho_' + this.sessionKey;
            localStorage.setItem(chave, JSON.stringify(this.itens));
            console.log('💾 Carrinho salvo no localStorage');
        } catch (e) {
            console.error('Erro ao sincronizar localStorage:', e);
        }
    },
    
    // Salva no localStorage
    salvar: function() {
        this.sincronizarLocalStorage();
        this.atualizarBadge();
    },
    
    // Adiciona produto (apenas localStorage - temporário por navegador)
    adicionar: function(produto) {
        console.log('➕ Adicionando:', produto);
        this.adicionarLocal(produto);
    },
    
    // Adiciona produto localmente (método principal)
    adicionarLocal: function(produto) {
        console.log('📝 Produto recebido:', produto);
        
        // Calcular preço total incluindo extras
        let precoBase = parseFloat(produto.preco) || 0;
        let precoExtras = 0;
        let extras = [];
        
        // Processar extras se existirem
        if (produto.extras && Array.isArray(produto.extras) && produto.extras.length > 0) {
            extras = produto.extras.map(extra => ({
                id: extra.id,
                nome: extra.nome,
                preco: parseFloat(extra.preco) || 0,
                quantidade: parseInt(extra.quantidade) || 1
            }));
            
            extras.forEach(extra => {
                precoExtras += (extra.preco * extra.quantidade);
            });
            
            console.log(`💰 Preço base: R$ ${precoBase.toFixed(2)}`);
            console.log(`💰 Preço extras: R$ ${precoExtras.toFixed(2)}`);
            console.log(`💰 Preço unitário: R$ ${(precoBase + precoExtras).toFixed(2)}`);
        }
        
        const precoUnitario = precoBase + precoExtras;
        const quantidade = parseInt(produto.quantidade) || 1;
        const total = precoUnitario * quantidade;
        
        // Criar chave única para este item (produto + observações + extras)
        const extrasKey = JSON.stringify(extras.map(e => ({id: e.id, qtd: e.quantidade})));
        const itemKey = `${produto.id}_${(produto.observacoes || '')}`;
        
        // Verificar se item idêntico já existe
        const existe = this.itens.find(item => 
            item.id == produto.id && 
            (item.observacoes || '') === (produto.observacoes || '') &&
            JSON.stringify(item.extras.map(e => ({id: e.id, qtd: e.quantidade}))) === extrasKey
        );
        
        if (existe) {
            console.log('📦 Item idêntico já existe, aumentando quantidade...');
            existe.quantidade += quantidade;
            existe.total = existe.quantidade * precoUnitario;
        } else {
            console.log('🆕 Novo item, adicionando ao carrinho...');
            this.itens.push({
                id: produto.id,
                nome: produto.nome,
                preco: precoBase,
                precoUnitario: precoUnitario,
                quantidade: quantidade,
                total: total,
                observacoes: produto.observacoes || '',
                imagem: produto.imagem || '',
                extras: extras
            });
        }
        
        console.log('📦 Carrinho atualizado:', this.itens);
        this.salvar();
        this.atualizarBadge();
        this.mostrarNotificacao('✅ Produto adicionado ao carrinho!', 'success');
    },
    
    // Remove produto (apenas localStorage)
    remover: function(index) {
        console.log('🗑️ Removendo item:', index);
        this.removerLocal(index);
    },
    
    // Remove produto localmente (fallback)
    removerLocal: function(index) {
        const item = this.itens[index];
        this.itens.splice(index, 1);
        this.salvar();
        this.atualizarModal();
        this.mostrarNotificacao(`🗑️ ${item.nome} removido`, 'info');
    },
    
    // Atualiza quantidade (apenas localStorage)
    atualizarQuantidade: function(index, quantidade) {
        if (quantidade < 1) {
            this.remover(index);
            return;
        }
        this.atualizarQuantidadeLocal(index, quantidade);
    },
    
    // Atualiza quantidade localmente (fallback)
    atualizarQuantidadeLocal: function(index, quantidade) {
        this.itens[index].quantidade = quantidade;
        
        // Recalcular total incluindo extras
        const precoUnitario = this.itens[index].precoUnitario || this.itens[index].preco;
        this.itens[index].total = precoUnitario * quantidade;
        
        this.salvar();
        this.atualizarModal();
    },
    
    // Edita observações (apenas localStorage)
    editarObservacoes: function(index) {
        const item = this.itens[index];
        const novaObs = prompt('Editar observações:', item.observacoes || '');
        
        if (novaObs === null) return;
        
        this.editarObservacoesLocal(index, novaObs);
    },
    
    // Edita extras de um item do carrinho
    editarExtras: function(index) {
        const item = this.itens[index];
        console.log('✏️ Editando extras do item:', item);
        
        // Fechar modal do carrinho
        $('#modalCarrinho').modal('hide');
        
        // Aguardar fechar e abrir modal do produto
        setTimeout(() => {
            // Simular clique no produto para abrir modal
            const $produto = $(`.produto-item[data-produto-id="${item.id}"]`).first();
            
            if ($produto.length) {
                // Trigger click no produto
                $produto.trigger('click');
                
                // Aguardar modal abrir e carregar extras
                setTimeout(() => {
                    // Pré-preencher quantidade
                    $('#quantidade').val(item.quantidade);
                    
                    // Pré-preencher observações
                    if (item.observacoes) {
                        $('#observacoes').val(item.observacoes);
                    }
                    
                    // Aguardar extras carregarem
                    setTimeout(() => {
                        // Pré-selecionar extras
                        if (item.extras && item.extras.length > 0 && typeof ProdutoExtras !== 'undefined') {
                            item.extras.forEach(extra => {
                                if (extra.multitude) {
                                    // Extra com quantidade
                                    const input = $(`#qty-input-${extra.id}`);
                                    if (input.length) {
                                        input.val(extra.quantidade || 1).trigger('input');
                                    }
                                } else {
                                    // Extra checkbox
                                    const checkbox = $(`#extra-${extra.id}`);
                                    if (checkbox.length) {
                                        checkbox.prop('checked', true).trigger('change');
                                    }
                                }
                            });
                        }
                        
                        // Remover item antigo do carrinho
                        this.itens.splice(index, 1);
                        this.salvar();
                        
                        // Mostrar notificação
                        this.mostrarNotificacao('✏️ Editando produto. Confirme as alterações.', 'info');
                    }, 500);
                }, 300);
            } else {
                this.mostrarNotificacao('❌ Produto não encontrado no menu', 'error');
            }
        }, 300);
    },
    
    // Edita observações localmente (fallback)
    editarObservacoesLocal: function(index, novaObs) {
        this.itens[index].observacoes = novaObs.trim();
        this.salvar();
        this.atualizarModal();
        this.mostrarNotificacao('✅ Observações atualizadas!', 'success');
    },
    
    // Limpa carrinho (apenas localStorage)
    limpar: function() {
        if (this.itens.length === 0) {
            this.mostrarNotificacao('⚠️ Carrinho já está vazio', 'warning');
            return;
        }
        
        if (!confirm('🗑️ Deseja realmente limpar todo o carrinho?')) {
            return;
        }
        
        this.limparLocal();
    },
    
    // Limpa carrinho localmente (fallback)
    limparLocal: function() {
        this.itens = [];
        this.salvar();
        this.atualizarModal();
        this.mostrarNotificacao('🗑️ Carrinho limpo!', 'success');
    },
    
    // Retorna total de itens
    getTotalItens: function() {
        return this.itens.reduce((total, item) => total + item.quantidade, 0);
    },
    
    // Retorna valor total
    getValorTotal: function() {
        const total = this.itens.reduce((total, item) => {
            // Usar precoUnitario se existir, senão calcular
            const precoUnitario = item.precoUnitario || item.preco;
            return total + (precoUnitario * item.quantidade);
        }, 0);
        console.log('💰 Valor total do carrinho:', total.toFixed(2));
        return total;
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
                            
                            <!-- Extras do Produto -->
                            ${item.extras && item.extras.length > 0 ? `
                                <div class="extras-lista mt-2" style="padding-left: 10px; border-left: 2px solid #f8b531;">
                                    ${item.extras.map(extra => {
                                        const qtdTexto = extra.quantidade > 1 ? ` x${extra.quantidade}` : '';
                                        const precoExtra = parseFloat(extra.preco) || 0;
                                        const precoTexto = precoExtra > 0 ? ` (+R$ ${(precoExtra * extra.quantidade).toFixed(2).replace('.', ',')})` : '';
                                        return `
                                            <small class="d-block text-warning" style="font-size: 0.8rem;">
                                                <i class="fas fa-plus-circle mr-1" style="font-size: 0.7rem;"></i>
                                                ${extra.nome}${qtdTexto}${precoTexto}
                                            </small>
                                        `;
                                    }).join('')}
                                </div>
                            ` : ''}
                            
                            <div class="text-warning mt-1" style="font-size: 0.9rem;">
                                R$ ${(item.precoUnitario || item.preco).toFixed(2).replace('.', ',')} <span class="text-muted">x ${item.quantidade}</span>
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
        
        console.log('🎯 Abrindo modal de finalização...');
        $('#modalCarrinho').modal('hide');
        
        // Aguardar fechar modal do carrinho e abrir modal de finalização
        setTimeout(() => {
            if (typeof window.FinalizarPedido !== 'undefined') {
                window.FinalizarPedido.abrirModal();
            } else {
                console.error('❌ Sistema de finalização não carregado!');
                this.mostrarNotificacao('Erro ao abrir finalização', 'error');
            }
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
    
    // Mostra notificação estilo dark
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
        
        // Posicionar abaixo da seção do menu
        const menuSection = $('#menu');
        let topPosition = 120; // Fallback
        
        if (menuSection.length) {
            const menuOffset = menuSection.offset();
            const menuHeight = menuSection.outerHeight();
            topPosition = menuOffset.top + menuHeight + 20;
        }
        
        // Remover notificações anteriores
        $('.notificacao-carrinho').remove();
        
        const notificacao = $(`
            <div class="notificacao-carrinho" style="
                position: absolute;
                top: ${topPosition}px;
                left: 50%;
                transform: translateX(-50%) translateY(-20px);
                background: #1a1a1a;
                color: white;
                padding: 15px 25px;
                border-radius: 8px;
                border: 2px solid ${cor};
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
                z-index: 10000;
                min-width: 280px;
                opacity: 0;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                font-family: 'Poppins', sans-serif;
            ">
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <span style="font-size: 18px; color: ${cor};">${icone}</span>
                    <div style="font-size: 14px; font-weight: 500;">${mensagem}</div>
                </div>
            </div>
        `);
        
        $('body').append(notificacao);
        
        // Animar entrada
        requestAnimationFrame(() => {
            notificacao.css({
                'opacity': '1',
                'transform': 'translateX(-50%) translateY(0)'
            });
        });
        
        // Auto remover após 3 segundos
        setTimeout(() => {
            notificacao.css({
                'opacity': '0',
                'transform': 'translateX(-50%) translateY(-20px)'
            });
            setTimeout(() => notificacao.remove(), 400);
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

console.log('✅ Carrinho Modal V2 carregado!');
