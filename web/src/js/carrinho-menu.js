/**
 * Sistema de Carrinho - Versão Dark Sidebar (Full Height)
 */

window.CarrinhoMenu = {
    itens: [],
    taxaEntrega: 0,
    distanciaKm: 0,
    
    init() {
        console.log('Inicializando CarrinhoMenu...');
        this.carregarCarrinho();
        console.log('Carrinho carregado:', this.itens);
        this.criarElementoCarrinho();
        console.log('Elemento carrinho criado');
        this.calcularTaxaEntrega();
        
        // Sincronizar com sistema Carrinho a cada 2 segundos
        setInterval(() => {
            this.carregarCarrinho();
        }, 2000);
        
        console.log('CarrinhoMenu inicializado com sucesso');
    },
    
    carregarCarrinho() {
        try {
            // Primeiro tentar carregar do sistema Carrinho existente
            if (typeof window.Carrinho !== 'undefined' && window.Carrinho.itens) {
                this.itens = window.Carrinho.itens;
                console.log('Carregando do sistema Carrinho:', this.itens);
                return;
            }
            
            // Se não houver, buscar do banco de dados via API
            const email = localStorage.getItem('cliente_email');
            if (email) {
                this.carregarCarrinhoAPI(email);
            } else {
                // Fallback para localStorage
                this.itens = JSON.parse(localStorage.getItem('carrinho') || '[]');
                console.log('Carregando do localStorage:', this.itens);
            }
        } catch (error) {
            console.error('Erro ao carregar carrinho:', error);
            this.itens = [];
        }
    },

    async carregarCarrinhoAPI(email) {
        try {
            const response = await fetch('/api/carrinho-cliente', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email })
            });

            const data = await response.json();
            
            if (data.sucesso) {
                this.itens = data.itens || [];
                console.log('Carrinho carregado da API:', this.itens);
            } else {
                this.itens = [];
            }
        } catch (error) {
            console.error('Erro ao carregar carrinho da API:', error);
            this.itens = [];
        }
    },
    
    salvarCarrinho() {
        localStorage.setItem('carrinho', JSON.stringify(this.itens));
        this.atualizarConteudo();
        if (this.itens.length === 0) {
            this.esconder();
        }
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
        this.mostrarNotificacao('✅ Produto adicionado ao carrinho!', 'success');
        this.calcularTaxaEntrega();
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
        const subtotal = this.itens.reduce((total, item) => {
            return total + (item.totalCalculado || (item.preco * item.quantidade));
        }, 0);
        return subtotal + this.taxaEntrega;
    },
    
    obterSubtotal() {
        return this.itens.reduce((total, item) => {
            return total + (item.totalCalculado || (item.preco * item.quantidade));
        }, 0);
    },
    
    async calcularTaxaEntrega() {
        const email = localStorage.getItem('cliente_email') || localStorage.getItem('userEmail');
        
        console.log('Iniciando cálculo de taxa. Email:', email);
        
        if (!email) {
            console.log('Nenhum email encontrado, taxa = 0');
            this.taxaEntrega = 0;
            this.atualizarConteudo();
            return;
        }

        try {
            console.log('Fazendo requisição para /api/configuracao-entrega');
            
            // Buscar configuração do sistema e dados do cliente
            const response = await fetch('/api/configuracao-entrega', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email })
            });

            console.log('Resposta recebida:', response.status);
            const data = await response.json();
            console.log('Dados da configuração:', data);
            
            if (data.sucesso) {
                console.log('Cliente encontrado! Bairro:', data.cliente.bairro);
                
                // Sempre calcular por bairro
                this.calcularPorBairro(data.cliente.bairro, data.bairros);
            } else {
                console.error('Erro ao buscar configuração:', data.msg);
                this.taxaEntrega = 0;
            }
        } catch (error) {
            console.error('Erro na requisição:', error);
            this.taxaEntrega = 0;
        }
        
        console.log('Taxa calculada final:', this.taxaEntrega);
        this.atualizarConteudo();
    },

    calcularPorBairro(bairroCliente, bairros) {
        console.log('=== CALCULANDO POR BAIRRO ===');
        console.log('Bairro do cliente:', bairroCliente);
        console.log('Bairros disponíveis:', bairros);
        
        const bairro = bairros.find(b => 
            b.nome.toLowerCase().trim() === bairroCliente.toLowerCase().trim()
        );
        
        console.log('Bairro encontrado:', bairro);
        
        if (bairro && bairro.ativo == 1) {
            this.taxaEntrega = parseFloat(bairro.valor_entrega);
            console.log('Taxa definida:', this.taxaEntrega);
        } else {
            this.taxaEntrega = 0;
            console.log('Bairro não encontrado ou inativo, taxa = 0');
        }
        
        console.log('=== FIM CÁLCULO BAIRRO ===');
    },
    
    criarElementoCarrinho() {
        if ($('#carrinho-container').length > 0) return;
        
        // Estrutura alterada para incluir um Overlay (fundo escuro) e a Sidebar
        const carrinhoHtml = `
            <div id="carrinho-container" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
                <div class="carrinho-overlay"></div>
                <div class="carrinho-sidebar">
                    <div class="carrinho-header">
                        <h5><i class="fas fa-shopping-cart"></i> Meu Pedido</h5>
                        <button class="btn-fechar-carrinho">&times;</button>
                    </div>
                    <div class="carrinho-body">
                        <div id="carrinho-itens"></div>
                        <div class="opcoes-entrega" style="padding: 15px; border-top: 1px solid #333; margin-top: 10px;">
                            <h6 style="color: #f8b531; margin-bottom: 15px; font-size: 14px;">
                                <i class="fas fa-shipping-fast mr-2"></i>Escolha a forma de recebimento:
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-outline-warning btn-sm w-100 btn-retirada" style="border-color: #f8b531; color: #f8b531;">
                                        <i class="fas fa-store mr-2"></i>Retirar na Loja
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-warning btn-sm w-100 btn-entrega" style="background: #f8b531; border-color: #f8b531; color: #000;">
                                        <i class="fas fa-motorcycle mr-2"></i>Entrega
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carrinho-footer-wrapper">
                        <div class="carrinho-subtotal">
                            <span>Subtotal:</span>
                            <span>R$ <span id="carrinho-subtotal-valor">0,00</span></span>
                        </div>
                        <div class="carrinho-taxa" id="carrinho-taxa-container" style="display: none;">
                            <span>Taxa de Entrega:</span>
                            <span>R$ <span id="carrinho-taxa-valor">0,00</span></span>
                        </div>
                        <div class="carrinho-total">
                            <span>Total do Pedido: <small class="text-muted" id="carrinho-km-info"></small></span>
                            <strong>R$ <span id="carrinho-total-valor">0,00</span></strong>
                        </div>
                        <div class="carrinho-acoes">
                            <button class="btn btn-outline-light btn-sm" id="btn-limpar-carrinho">Limpar</button>
                            <button class="btn btn-primary btn-block" id="btn-finalizar-carrinho">Finalizar Compra</button>
                        </div>
                    </div>
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
                /* Container Geral */
                #carrinho-container {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 9999;
                }

                /* Fundo escuro transparente (Overlay) */
                .carrinho-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 9998;
                }

                /* A Barra Lateral Dark */
                .carrinho-sidebar {
                    position: fixed;
                    top: 0;
                    right: 0;
                    width: 400px;
                    max-width: 85%;
                    height: 100%;
                    background: #1a1a1a; /* Fundo Dark */
                    box-shadow: -5px 0 30px rgba(0,0,0,0.5);
                    display: flex;
                    flex-direction: column;
                    color: #fff;
                    transform: translateX(100%);
                    animation: slideInRight 0.4s forwards;
                    border-left: 1px solid #333;
                    z-index: 9999;
                }
                
                @keyframes slideInRight {
                    from { transform: translateX(100%); }
                    to { transform: translateX(0); }
                }
                
                /* Header */
                .carrinho-header {
                    background: #252525;
                    padding: 20px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 1px solid #333;
                }
                
                .carrinho-header h5 {
                    margin: 0;
                    font-weight: 600;
                    color: #f8b531; /* Cor de destaque mantida */
                    font-size: 1.2rem;
                }
                
                .btn-fechar-carrinho {
                    background: none;
                    border: none;
                    color: #fff;
                    font-size: 28px;
                    cursor: pointer;
                    line-height: 1;
                    opacity: 0.7;
                    transition: 0.3s;
                }
                
                .btn-fechar-carrinho:hover {
                    opacity: 1;
                    color: #f8b531;
                }
                
                /* Corpo com Scroll */
                .carrinho-body {
                    flex: 1;
                    overflow-y: auto;
                    padding: 20px;
                }
                
                /* Custom Scrollbar Dark */
                .carrinho-body::-webkit-scrollbar {
                    width: 6px;
                }
                .carrinho-body::-webkit-scrollbar-track {
                    background: #1a1a1a;
                }
                .carrinho-body::-webkit-scrollbar-thumb {
                    background: #444;
                    border-radius: 3px;
                }

                /* Itens do Carrinho */
                .carrinho-item {
                    background: #252525;
                    border: 1px solid #333;
                    border-radius: 8px;
                    padding: 15px;
                    margin-bottom: 15px;
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    transition: transform 0.2s;
                }
                
                .carrinho-item:hover {
                    border-color: #f8b531;
                }

                .item-info { flex: 1; }
                
                .item-nome {
                    font-weight: 600;
                    font-size: 15px;
                    margin-bottom: 5px;
                    color: #fff;
                }
                
                .item-detalhes {
                    font-size: 13px;
                    color: #aaa; /* Cinza claro para texto secundário */
                    margin-bottom: 8px;
                }
                
                .item-preco {
                    font-weight: 700;
                    color: #f8b531;
                    font-size: 15px;
                }
                
                .btn-remover-item {
                    background: rgba(220, 53, 69, 0.1);
                    border: none;
                    color: #dc3545;
                    width: 30px;
                    height: 30px;
                    border-radius: 6px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    margin-left: 10px;
                    transition: 0.2s;
                }
                
                .btn-remover-item:hover {
                    background: #dc3545;
                    color: #fff;
                }
                
                /* Footer / Totais */
                .carrinho-footer-wrapper {
                    background: #252525;
                    padding: 20px;
                    border-top: 1px solid #333;
                }

                .carrinho-subtotal, .carrinho-taxa {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 10px;
                    font-size: 14px;
                    color: #aaa;
                }

                .carrinho-total {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 20px;
                    font-size: 18px;
                    color: #fff;
                    padding-top: 10px;
                    border-top: 1px solid #333;
                }

                .carrinho-total strong {
                    color: #f8b531;
                    font-size: 22px;
                }
                
                .carrinho-acoes {
                    display: flex;
                    gap: 10px;
                }
                
                #btn-finalizar-carrinho {
                    background: #f8b531;
                    border: none;
                    color: #000;
                    font-weight: 700;
                    padding: 12px;
                    border-radius: 8px;
                    text-transform: uppercase;
                    flex: 2;
                    transition: 0.3s;
                }
                
                #btn-finalizar-carrinho:hover {
                    background: #e6a429;
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(248, 181, 49, 0.2);
                }

                #btn-limpar-carrinho {
                    flex: 1;
                    border-color: #444;
                    color: #aaa;
                    border-radius: 8px;
                }
                
                #btn-limpar-carrinho:hover {
                    background: #333;
                    color: #fff;
                    border-color: #fff;
                }

                /* Botões de opções de entrega */
                .opcoes-entrega .btn {
                    transition: all 0.3s ease;
                    font-weight: 600;
                    font-size: 12px;
                    padding: 12px 15px;
                    border-width: 2px;
                    position: relative;
                }
                
                .opcoes-entrega .btn:not(.selecionado) {
                    background: #2d2d2d;
                    color: #f8b531;
                    border-color: #f8b531;
                }
                
                .opcoes-entrega .btn.selecionado {
                    background: #f8b531;
                    color: #1a1a1a;
                    border-color: #f8b531;
                    box-shadow: 0 0 15px rgba(248, 181, 49, 0.4);
                    font-weight: 700;
                }
                
                .opcoes-entrega .btn.selecionado::before {
                    content: '✓';
                    position: absolute;
                    top: 2px;
                    right: 8px;
                    font-size: 14px;
                    font-weight: bold;
                    color: #1a1a1a;
                }
                
                .opcoes-entrega .btn:hover:not(.selecionado) {
                    background: rgba(248, 181, 49, 0.1);
                    transform: translateY(-1px);
                }
                
                .opcoes-entrega h6 {
                    font-family: 'Poppins', sans-serif;
                }

                /* Estado Vazio */
                .carrinho-vazio {
                    text-align: center;
                    padding: 60px 20px;
                    color: #666;
                }
                .carrinho-vazio i {
                    font-size: 60px;
                    margin-bottom: 20px;
                    color: #333;
                }

                /* Animação do botão Voltar ao Cardápio */
                .btn-voltar-cardapio {
                    transition: all 0.5s ease;
                    border-color: #444 !important;
                    color: #aaa !important;
                }

                .btn-voltar-cardapio:hover {
                    color: #f8b531 !important;
                    border-color: #f8b531 !important;
                    background: rgba(248, 181, 49, 0.1) !important;
                }

                /* Mobile */
                @media (max-width: 480px) {
                    .carrinho-sidebar { width: 100%; max-width: 100%; }
                }
            </style>
        `;
        
        $('head').append(estilos);
    },
    
    configurarEventos() {
        // Fechar carrinho (Botão X ou Clicar no Overlay)
        $(document).on('click', '.btn-fechar-carrinho, .carrinho-overlay', () => {
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
            // Usa currentTarget para garantir que pegamos o botão, mesmo clicando no ícone
            const btn = $(e.currentTarget); 
            const index = btn.data('index');
            this.remover(index);
        });
        
        // Opções de entrega
        $(document).on('click', '.btn-retirada', () => {
            this.selecionarTipoEntrega('retirada');
        });
        
        $(document).on('click', '.btn-entrega', () => {
            this.selecionarTipoEntrega('entrega');
        });
    },
    
    mostrar() {
        console.log('Mostrando carrinho. Itens atuais:', this.itens);
        
        // Recarregar dados do sistema Carrinho antes de mostrar
        this.carregarCarrinho();
        
        $('#carrinho-container').fadeIn(200);
        this.atualizarConteudo();
        
        // Carregar tipo de entrega salvo
        setTimeout(() => {
            this.carregarTipoEntrega();
        }, 100);
        
        // Bloqueia rolagem do body quando carrinho está aberto
        $('body').css('overflow', 'hidden');
    },
    
    esconder() {
        $('#carrinho-container').fadeOut(200);
        // Libera rolagem do body
        $('body').css('overflow', '');
    },
    
    atualizarVisibilidade() {
        // Mantido para compatibilidade, mas a lógica agora é controlada pelo .mostrar()
    },
    
    atualizarConteudo() {
        console.log('Atualizando conteúdo do carrinho. Itens:', this.itens);
        
        const container = $('#carrinho-itens');
        const totalElement = $('#carrinho-total-valor');
        
        if (container.length === 0) {
            console.error('Elemento #carrinho-itens não encontrado');
            return;
        }
        
        if (this.itens.length === 0) {
            container.html(`
                <div class="carrinho-vazio">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Seu carrinho está vazio</p>
                    <button class="btn btn-sm btn-outline-secondary mt-3 btn-fechar-carrinho btn-voltar-cardapio">
                        <span class="btn-text">Voltar ao Cardápio</span>
                        <i class="fas fa-arrow-left btn-icon" style="display: none; margin-right: 8px;"></i>
                    </button>
                </div>
            `);
            totalElement.text('0,00');
            // Desabilita botão finalizar
            $('#btn-finalizar-carrinho').prop('disabled', true).css('opacity', '0.5');
            return;
        }
        
        // Habilita botão finalizar
        $('#btn-finalizar-carrinho').prop('disabled', false).css('opacity', '1');
        
        let html = '';
        this.itens.forEach((item, index) => {
            const extrasTexto = item.extras && item.extras.length > 0 
                ? `<div style="color: #888; font-size: 11px; margin-top:2px;">+ ${item.extras.length} opcionais</div>` 
                : '';
            
            html += `
                <div class="carrinho-item">
                    <div class="item-info">
                        <div class="item-nome">${item.nome}</div>
                        <div class="item-detalhes">
                            Qtd: ${item.quantidade} un.
                            ${extrasTexto}
                            ${item.observacoes ? `<div style="color: #f8b531; font-size: 11px; margin-top:2px;"><i class="fas fa-comment-dots"></i> ${item.observacoes}</div>` : ''}
                        </div>
                        <div class="item-preco">R$ ${(item.totalCalculado || (item.preco * item.quantidade)).toFixed(2).replace('.', ',')}</div>
                    </div>
                    <button class="btn-remover-item" data-index="${index}" title="Remover item">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `;
        });
        
        container.html(html);
        
        const subtotal = this.obterSubtotal();
        $('#carrinho-subtotal-valor').text(subtotal.toFixed(2).replace('.', ','));
        
        console.log('Taxa de entrega atual:', this.taxaEntrega);
        
        if (this.taxaEntrega > 0) {
            $('#carrinho-taxa-container').show();
            $('#carrinho-taxa-valor').text(this.taxaEntrega.toFixed(2).replace('.', ','));
            console.log('Mostrando taxa de entrega:', this.taxaEntrega);
        } else {
            $('#carrinho-taxa-container').hide();
            console.log('Ocultando taxa de entrega');
        }
        
        $('#carrinho-total-valor').text(this.obterTotal().toFixed(2).replace('.', ','));
    },
    
    finalizarPedido() {
        if (this.itens.length === 0) {
            this.mostrarNotificacao('Carrinho vazio!', 'error');
            return;
        }
        window.location.href = '/carrinho';
    },
    
    mostrarNotificacao(mensagem, tipo = 'success') {
        const icone = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const cor = tipo === 'success' ? '#f8b531' : '#dc3545'; 
        
        // Posicionar abaixo da seção do menu
        const menuSection = $('#menu');
        let topPosition = 120; // Fallback
        
        if (menuSection.length) {
            const menuOffset = menuSection.offset();
            const menuHeight = menuSection.outerHeight();
            topPosition = menuOffset.top + menuHeight + 20;
        }
        
        // Remova notificações anteriores
        $('.notificacao-carrinho').remove();

        const notificacao = $(`
            <div class="notificacao-carrinho" style="
                position: absolute;
                top: ${topPosition}px;
                left: 50%;
                transform: translateX(-50%) translateY(-20px);
                background: #1a1a1a;
                color: #fff;
                border: 2px solid ${cor};
                padding: 15px 25px;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.5);
                z-index: 10000;
                opacity: 0;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                display: flex;
                align-items: center;
                font-weight: 500;
                font-size: 14px;
                min-width: 280px;
                justify-content: center;
            ">
                <i class="fas ${icone} mr-2" style="color: ${cor}; font-size: 1.2em;"></i>
                ${mensagem}
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

        setTimeout(() => {
            notificacao.css('transform', 'translateX(120%)');
            setTimeout(() => notificacao.remove(), 400);
        }, 3000);
    },
    
    async selecionarTipoEntrega(tipo) {
        console.log('=== FUNÇÃO CHAMADA ===');
        console.log('Tipo de entrega selecionado:', tipo);
        
        // Remover seleção anterior e resetar classes
        $('.btn-retirada, .btn-entrega').removeClass('selecionado');
        $('.btn-retirada, .btn-entrega').removeAttr('style');
        
        // Adicionar seleção atual
        if (tipo === 'retirada') {
            $('.btn-retirada').addClass('selecionado');
            $('.btn-retirada').attr('style', 'background: #f8b531 !important; color: #1a1a1a !important; border-color: #f8b531 !important;');
            this.taxaEntrega = 0;
            this.mostrarNotificacao('🏪 Retirada na loja selecionada! Taxa: R$ 0,00');
        } else {
            console.log('=== ENTREGA SELECIONADA ===');
            $('.btn-entrega').addClass('selecionado');
            $('.btn-entrega').attr('style', 'background: #f8b531 !important; color: #1a1a1a !important; border-color: #f8b531 !important;');
            
            await this.calcularTaxaEntrega();
            this.mostrarNotificacao('🏍️ Entrega selecionada! Calculando taxa...');
        }
        
        // Salvar no localStorage
        localStorage.setItem('tipoEntrega', tipo);
        
        // Atualizar conteúdo
        this.atualizarConteudo();
        console.log('=== FIM DA FUNÇÃO ===');
    },
    
    carregarTipoEntrega() {
        const tipoSalvo = localStorage.getItem('tipoEntrega');
        
        // Resetar estilos
        $('.btn-retirada, .btn-entrega').removeClass('selecionado');
        $('.btn-retirada, .btn-entrega').removeAttr('style');
        
        if (tipoSalvo) {
            // Aplicar seleção salva
            if (tipoSalvo === 'retirada') {
                $('.btn-retirada').addClass('selecionado');
                $('.btn-retirada').attr('style', 'background: #f8b531 !important; color: #1a1a1a !important; border-color: #f8b531 !important;');
                this.taxaEntrega = 0;
            } else {
                $('.btn-entrega').addClass('selecionado');
                $('.btn-entrega').attr('style', 'background: #f8b531 !important; color: #1a1a1a !important; border-color: #f8b531 !important;');
                this.calcularTaxaEntrega();
            }
        } else {
            // Padrão: retirada na loja selecionada
            $('.btn-retirada').addClass('selecionado');
            $('.btn-retirada').attr('style', 'background: #f8b531 !important; color: #1a1a1a !important; border-color: #f8b531 !important;');
            this.taxaEntrega = 0;
            localStorage.setItem('tipoEntrega', 'retirada');
        }
    }
};

$(document).ready(() => {
    window.CarrinhoMenu.init();
});