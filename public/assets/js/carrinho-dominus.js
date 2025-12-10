/**
 * Sistema de Carrinho - Estilo Domino's Pizza
 * Ultra-simples e funcional
 */

console.log('🍕 Carrinho Dominus carregando...');

// Objeto global do carrinho
window.Carrinho = {
    itens: [],
    
    // Inicializa o carrinho
    init: function() {
        console.log('🚀 Inicializando carrinho...');
        this.carregarDoLocalStorage();
        this.criarSidebar();
        this.atualizarBadge();
        this.configurarEventos();
        console.log('✅ Carrinho inicializado!');
    },
    
    // Carrega do localStorage
    carregarDoLocalStorage: function() {
        try {
            const dados = localStorage.getItem('carrinho_dominus');
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
            localStorage.setItem('carrinho_dominus', JSON.stringify(this.itens));
            console.log('💾 Carrinho salvo!');
            this.atualizarBadge();
            this.atualizarSidebar();
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
        this.mostrarNotificacao('✅ Produto adicionado!');
    },
    
    // Remove produto
    remover: function(index) {
        console.log('🗑️ Removendo item:', index);
        this.itens.splice(index, 1);
        this.salvar();
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
    },
    
    // Limpa carrinho
    limpar: function() {
        if (confirm('Limpar todo o carrinho?')) {
            this.itens = [];
            this.salvar();
            this.mostrarNotificacao('🗑️ Carrinho limpo!');
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
            // Mostra badge apenas se houver itens
            badge.style.display = total > 0 ? 'flex' : 'none';
        }
    },
    
    // Cria sidebar
    criarSidebar: function() {
        console.log('🏭 Criando sidebar...');
        
        // Remove sidebar existente se houver
        const sidebarExistente = document.getElementById('carrinho-sidebar');
        if (sidebarExistente) {
            console.log('🗑️ Removendo sidebar existente');
            sidebarExistente.remove();
        }
        
        const overlayExistente = document.getElementById('carrinho-overlay');
        if (overlayExistente) {
            overlayExistente.remove();
        }
        
        const sidebarHTML = `
            <div id="carrinho-sidebar" class="carrinho-sidebar">
                <div class="carrinho-sidebar-header">
                    <h3>🛒 Meu Carrinho</h3>
                    <button class="btn-fechar" onclick="Carrinho.fecharSidebar()">✕</button>
                </div>
                
                <div class="carrinho-sidebar-body" id="carrinho-sidebar-body">
                    <!-- Itens aqui -->
                </div>
                
                <div class="carrinho-sidebar-footer">
                    <div class="carrinho-total">
                        <span>Total:</span>
                        <strong id="carrinho-total-valor">R$ 0,00</strong>
                    </div>
                    <button class="btn-finalizar" onclick="Carrinho.finalizar()">
                        Finalizar Pedido
                    </button>
                    <button class="btn-limpar" onclick="Carrinho.limpar()">
                        Limpar Carrinho
                    </button>
                </div>
            </div>
            
            <div id="carrinho-overlay" class="carrinho-overlay" onclick="Carrinho.fecharSidebar()"></div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', sidebarHTML);
        
        // Verificar se foi criada
        const sidebar = document.getElementById('carrinho-sidebar');
        const overlay = document.getElementById('carrinho-overlay');
        
        if (sidebar && overlay) {
            console.log('✅ Sidebar e overlay criados com sucesso!');
            console.log('📍 Sidebar:', sidebar);
            console.log('📍 Overlay:', overlay);
        } else {
            console.error('❌ Erro ao criar sidebar!');
            console.log('Sidebar:', sidebar);
            console.log('Overlay:', overlay);
        }
    },
    
    // Abre sidebar
    abrirSidebar: function() {
        console.log('📂 Abrindo sidebar...');
        
        const sidebar = document.getElementById('carrinho-sidebar');
        const overlay = document.getElementById('carrinho-overlay');
        
        if (!sidebar) {
            console.error('❌ Sidebar não encontrada! Criando...');
            this.criarSidebar();
        }
        
        this.atualizarSidebar();
        
        const sidebarElement = document.getElementById('carrinho-sidebar');
        const overlayElement = document.getElementById('carrinho-overlay');
        
        if (sidebarElement) {
            sidebarElement.classList.add('aberto');
            console.log('✅ Sidebar aberta');
        } else {
            console.error('❌ Erro: Sidebar ainda não existe!');
        }
        
        if (overlayElement) {
            overlayElement.classList.add('ativo');
        }
        
        document.body.style.overflow = 'hidden';
    },
    
    // Fecha sidebar
    fecharSidebar: function() {
        console.log('📁 Fechando sidebar...');
        document.getElementById('carrinho-sidebar').classList.remove('aberto');
        document.getElementById('carrinho-overlay').classList.remove('ativo');
        document.body.style.overflow = '';
    },
    
    // Atualiza conteúdo da sidebar
    atualizarSidebar: function() {
        const body = document.getElementById('carrinho-sidebar-body');
        const totalValor = document.getElementById('carrinho-total-valor');
        
        if (!body) return;
        
        if (this.itens.length === 0) {
            body.innerHTML = `
                <div class="carrinho-vazio">
                    <div class="icone">🛒</div>
                    <p>Seu carrinho está vazio</p>
                    <small>Adicione produtos para continuar</small>
                </div>
            `;
            if (totalValor) totalValor.textContent = 'R$ 0,00';
            return;
        }
        
        let html = '';
        this.itens.forEach((item, index) => {
            html += `
                <div class="carrinho-item">
                    <div class="item-info">
                        <h4>${item.nome}</h4>
                        ${item.observacoes ? `<small>Obs: ${item.observacoes}</small>` : ''}
                    </div>
                    <div class="item-quantidade">
                        <button onclick="Carrinho.atualizarQuantidade(${index}, ${item.quantidade - 1})">-</button>
                        <span>${item.quantidade}</span>
                        <button onclick="Carrinho.atualizarQuantidade(${index}, ${item.quantidade + 1})">+</button>
                    </div>
                    <div class="item-preco">
                        <strong>R$ ${item.total.toFixed(2).replace('.', ',')}</strong>
                    </div>
                    <button class="item-remover" onclick="Carrinho.remover(${index})">🗑️</button>
                </div>
            `;
        });
        
        body.innerHTML = html;
        
        if (totalValor) {
            totalValor.textContent = 'R$ ' + this.getValorTotal().toFixed(2).replace('.', ',');
        }
    },
    
    // Finaliza pedido
    finalizar: function() {
        if (this.itens.length === 0) {
            alert('Carrinho vazio!');
            return;
        }
        
        console.log('🎯 Finalizando pedido...');
        window.location.href = '/carrinho';
    },
    
    // Configura eventos
    configurarEventos: function() {
        console.log('⚙️ Configurando eventos...');
        
        // Clique no badge/ícone do carrinho - Múltiplas formas de capturar
        const container = document.getElementById('carrinho-badge-container');
        
        if (container) {
            console.log('✅ Container do carrinho encontrado');
            
            // Evento direto no container
            container.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('👆 Clique no carrinho detectado!');
                this.abrirSidebar();
            });
        } else {
            console.error('❌ Container do carrinho NÃO encontrado!');
        }
        
        // Evento global como backup
        document.addEventListener('click', (e) => {
            if (e.target.closest('#carrinho-badge-container')) {
                e.preventDefault();
                console.log('👆 Clique no carrinho (evento global)');
                this.abrirSidebar();
            }
        });
        
        // Tecla ESC fecha sidebar
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                console.log('⏏️ ESC pressionado, fechando sidebar');
                this.fecharSidebar();
            }
        });
        
        console.log('✅ Eventos configurados!');
    },
    
    // Mostra notificação
    mostrarNotificacao: function(mensagem) {
        const notif = document.createElement('div');
        notif.className = 'carrinho-notificacao';
        notif.textContent = mensagem;
        document.body.appendChild(notif);
        
        setTimeout(() => notif.classList.add('show'), 10);
        setTimeout(() => {
            notif.classList.remove('show');
            setTimeout(() => notif.remove(), 300);
        }, 2000);
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

console.log('✅ Carrinho Dominus carregado!');
