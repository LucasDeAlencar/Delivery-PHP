/**
 * Gerenciador de Carrinho - Versão Simplificada
 * Execução imediata para garantir funcionamento
 */

console.log('🚀 CARRINHO.JS CARREGANDO...');

(function() {
    'use strict';
    
    console.log('📦 Iniciando CarrinhoManager...');
    
    /**
     * Classe CarrinhoManager
     */
    class CarrinhoManager {
        constructor() {
            console.log('🛒 Construtor do CarrinhoManager chamado');
            this.carrinho = this.carregarCarrinho();
            this.inicializar();
        }

        /**
         * Inicializa o gerenciador
         */
        inicializar() {
            console.log('⚙️ Inicializando componentes...');
            console.log('📊 Carrinho carregado:', this.carrinho.length, 'itens');
            
            // Aguarda DOM estar pronto
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    this.inicializarDOM();
                });
            } else {
                this.inicializarDOM();
            }
        }

        /**
         * Inicializa elementos do DOM
         */
        inicializarDOM() {
            console.log('🌐 DOM pronto, inicializando elementos...');
            this.atualizarBadge();
            this.atualizarVisibilidadeMenu();
            this.criarModalCarrinho();
            this.configurarEventos();
            console.log('✅ Inicialização completa!');
        }

        /**
         * Carrega o carrinho do localStorage
         */
        carregarCarrinho() {
            try {
                const carrinhoSalvo = localStorage.getItem('carrinho');
                const carrinho = carrinhoSalvo ? JSON.parse(carrinhoSalvo) : [];
                console.log('💾 Carrinho carregado do localStorage:', carrinho.length, 'itens');
                return carrinho;
            } catch (error) {
                console.error('❌ Erro ao carregar carrinho:', error);
                return [];
            }
        }

        /**
         * Salva o carrinho no localStorage
         */
        salvarCarrinho() {
            try {
                localStorage.setItem('carrinho', JSON.stringify(this.carrinho));
                console.log('💾 Carrinho salvo:', this.carrinho.length, 'itens');
                this.atualizarBadge();
                this.atualizarVisibilidadeMenu();
                this.dispararEvento('carrinhoAtualizado');
            } catch (error) {
                console.error('❌ Erro ao salvar carrinho:', error);
            }
        }

        /**
         * Adiciona um produto ao carrinho
         */
        adicionarProduto(pedido) {
            console.log('🎯 ADICIONANDO PRODUTO AO CARRINHO');
            console.log('📦 Produto:', pedido);
            
            // Verifica se o produto já existe
            const indiceExistente = this.carrinho.findIndex(
                item => item.produto.id === pedido.produto.id && 
                        item.observacoes === pedido.observacoes
            );

            if (indiceExistente !== -1) {
                // Atualiza quantidade
                this.carrinho[indiceExistente].quantidade += pedido.quantidade;
                this.carrinho[indiceExistente].total = 
                    this.carrinho[indiceExistente].quantidade * 
                    this.carrinho[indiceExistente].precoUnitario;
                console.log('✏️ Produto já existia, quantidade atualizada');
            } else {
                // Adiciona novo
                this.carrinho.push(pedido);
                console.log('➕ Novo produto adicionado');
            }

            this.salvarCarrinho();
            console.log('📊 Total de itens agora:', this.getTotalItens());
            
            this.mostrarNotificacao('✅ Produto Adicionado!', `${pedido.produto.nome} foi adicionado ao carrinho`, 'success');
            
            // Força atualização imediata
            setTimeout(() => {
                this.atualizarBadge();
                this.atualizarVisibilidadeMenu();
            }, 100);
            
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
         * Atualiza quantidade
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
         * Limpa o carrinho
         */
        limparCarrinho() {
            this.carrinho = [];
            this.salvarCarrinho();
            this.mostrarNotificacao('🧹 Carrinho Limpo', 'Todos os itens foram removidos', 'info');
        }

        /**
         * Retorna total de itens
         */
        getTotalItens() {
            return this.carrinho.reduce((total, item) => total + item.quantidade, 0);
        }

        /**
         * Retorna valor total
         */
        getValorTotal() {
            return this.carrinho.reduce((total, item) => total + item.total, 0);
        }

        /**
         * Atualiza o badge
         */
        atualizarBadge() {
            const totalItens = this.getTotalItens();
            const badge = document.getElementById('carrinho-badge');

            console.log('🔄 Atualizando badge. Total:', totalItens);

            if (badge) {
                badge.textContent = totalItens;
                if (totalItens > 0) {
                    badge.classList.add('show');
                    badge.style.display = 'flex';
                    console.log('✅ Badge atualizado e visível');
                } else {
                    badge.classList.remove('show');
                    badge.style.display = 'none';
                    console.log('❌ Badge oculto (carrinho vazio)');
                }
            } else {
                console.warn('⚠️ Badge não encontrado no DOM');
            }
        }

        /**
         * Atualiza visibilidade do menu
         */
        atualizarVisibilidadeMenu() {
            const totalItens = this.getTotalItens();
            const linkCarrinho = document.getElementById('link-carrinho');
            
            console.log('🔄 ATUALIZANDO VISIBILIDADE DO MENU');
            console.log('📊 Total de itens:', totalItens);
            console.log('🔍 Elemento encontrado:', !!linkCarrinho);
            
            if (linkCarrinho) {
                if (totalItens > 0) {
                    linkCarrinho.style.display = 'block';
                    linkCarrinho.classList.add('show');
                    console.log('✅ MENU DO CARRINHO EXIBIDO!');
                    console.log('📍 Display:', linkCarrinho.style.display);
                } else {
                    linkCarrinho.style.display = 'none';
                    linkCarrinho.classList.remove('show');
                    console.log('❌ Menu do carrinho oculto');
                }
            } else {
                console.error('❌❌❌ ELEMENTO #link-carrinho NÃO ENCONTRADO!');
                console.log('🔍 Tentando buscar de outra forma...');
                const linkAlt = document.querySelector('#link-carrinho');
                console.log('🔍 querySelector resultado:', linkAlt);
                
                if (!linkAlt) {
                    console.error('❌ Elemento realmente não existe no DOM!');
                    console.log('📋 Elementos com "carrinho" no ID:');
                    document.querySelectorAll('[id*="carrinho"]').forEach(el => {
                        console.log('  -', el.id, el.tagName);
                    });
                }
            }
        }

        /**
         * Cria modal do carrinho
         */
        criarModalCarrinho() {
            // Remove modal existente
            const modalExistente = document.getElementById('modalCarrinhoPopup');
            if (modalExistente) {
                modalExistente.remove();
            }

            const modalHTML = `
                <div class="modal fade" id="modalCarrinhoPopup" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333;">
                            <div class="modal-header" style="background: #2d2d2d; border-bottom: 1px solid #333;">
                                <h5 class="modal-title text-warning">
                                    <i class="fas fa-shopping-cart mr-2"></i>Meu Carrinho
                                </h5>
                                <button type="button" class="close text-light" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="carrinho-conteudo-popup" style="background: #1a1a1a;">
                                <!-- Conteúdo dinâmico -->
                            </div>
                            <div class="modal-footer" style="background: #1a1a1a; border-top: 1px solid #333;">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-danger" id="btn-limpar-carrinho-popup">Limpar</button>
                                <button type="button" class="btn btn-success" id="btn-finalizar-pedido-popup">Finalizar</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
            this.configurarEventosModal();
            console.log('✅ Modal do carrinho criado');
        }

        /**
         * Configura eventos do modal
         */
        configurarEventosModal() {
            const modal = document.getElementById('modalCarrinhoPopup');
            if (!modal) return;

            // Atualizar ao abrir
            if (typeof $ !== 'undefined') {
                $('#modalCarrinhoPopup').on('show.bs.modal', () => {
                    this.atualizarModalCarrinho();
                });

                // Limpar carrinho
                $('#btn-limpar-carrinho-popup').off('click').on('click', () => {
                    if (confirm('Limpar carrinho?')) {
                        this.limparCarrinho();
                        this.atualizarModalCarrinho();
                    }
                });

                // Finalizar
                $('#btn-finalizar-pedido-popup').off('click').on('click', () => {
                    window.location.href = '/carrinho';
                });
            }
        }

        /**
         * Atualiza conteúdo do modal
         */
        atualizarModalCarrinho() {
            const conteudo = document.getElementById('carrinho-conteudo-popup');
            if (!conteudo) return;

            if (this.carrinho.length === 0) {
                conteudo.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #333;"></i>
                        <h4 class="mt-3 text-light">Carrinho Vazio</h4>
                    </div>
                `;
                return;
            }

            let html = '<div class="carrinho-itens-lista">';
            this.carrinho.forEach(item => {
                html += `
                    <div class="border-bottom py-3" style="border-color: #333 !important;">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="text-light">${item.produto.nome}</h6>
                                <small class="text-muted">${item.produto.categoria}</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <span class="text-light">Qtd: ${item.quantidade}</span>
                            </div>
                            <div class="col-md-3 text-center">
                                <strong class="text-warning">R$ ${item.total.toFixed(2)}</strong>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `
                </div>
                <div class="mt-4 p-3 rounded" style="background: #2d2d2d;">
                    <div class="row">
                        <div class="col-6">
                            <strong class="text-light">Total de Itens: ${this.getTotalItens()}</strong>
                        </div>
                        <div class="col-6 text-right">
                            <h4 class="text-warning mb-0">R$ ${this.getValorTotal().toFixed(2)}</h4>
                        </div>
                    </div>
                </div>
            `;

            conteudo.innerHTML = html;
        }

        /**
         * Configura eventos
         */
        configurarEventos() {
            // Clique no link do carrinho
            if (typeof $ !== 'undefined') {
                $(document).on('click', '#link-carrinho a, #btn-abrir-carrinho', (e) => {
                    e.preventDefault();
                    console.log('🖱️ Link do carrinho clicado');
                    $('#modalCarrinhoPopup').modal('show');
                });
            }

            // Sincronização entre abas
            window.addEventListener('storage', (e) => {
                if (e.key === 'carrinho') {
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
            const cores = {
                success: '#28a745',
                error: '#dc3545',
                info: '#17a2b8'
            };

            const notificacao = document.createElement('div');
            notificacao.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #2d2d2d;
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                border-left: 4px solid ${cores[tipo] || cores.success};
                box-shadow: 0 5px 20px rgba(0,0,0,0.3);
                z-index: 9999;
                transform: translateX(400px);
                transition: all 0.3s ease;
            `;

            notificacao.innerHTML = `
                <div style="font-weight: bold; margin-bottom: 5px;">${titulo}</div>
                <div style="font-size: 14px;">${mensagem}</div>
            `;

            document.body.appendChild(notificacao);

            setTimeout(() => {
                notificacao.style.transform = 'translateX(0)';
            }, 100);

            setTimeout(() => {
                notificacao.style.transform = 'translateX(400px)';
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

    // Inicializa imediatamente
    console.log('🚀 Criando instância do CarrinhoManager...');
    window.carrinhoManager = new CarrinhoManager();
    console.log('✅ window.carrinhoManager criado!');
    console.log('📊 Carrinho:', window.carrinhoManager.carrinho);
    
})();

console.log('✅ CARRINHO.JS CARREGADO COMPLETAMENTE!');
