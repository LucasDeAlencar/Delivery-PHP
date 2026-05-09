/**
 * Sistema de Finalização de Pedido
 * Versão 1.0
 */


window.FinalizarPedido = {
    formasPagamento: [],
    bairros: [],
    sachesDisponiveis: [],
    tipoEntrega: null,
    taxaEntrega: 0,
    
    // Inicializa o sistema
    init: function() {
        this.carregarFormasPagamento();
        this.carregarBairros();
        this.configurarEventos();
    },
    
    // Carrega formas de pagamento do servidor
    carregarFormasPagamento: async function() {
        try {
            const response = await fetch('/api/formas-pagamento');
            const data = await response.json();
            
            if (data.success) {
                this.formasPagamento = data.data;
            }
        } catch (error) {
            console.error('Erro ao carregar formas de pagamento:', error);
            // Fallback com formas padrão
            this.formasPagamento = [
                { id: 1, nome: 'Dinheiro', slug: 'dinheiro', icone: 'fas fa-money-bill-wave' },
                { id: 2, nome: 'Cartão de Débito', slug: 'debito', icone: 'fas fa-credit-card' },
                { id: 3, nome: 'Cartão de Crédito', slug: 'credito', icone: 'fas fa-credit-card' },
                { id: 4, nome: 'PIX', slug: 'pix', icone: 'fas fa-qrcode' }
            ];
        }
    },
    
    // Carrega bairros do servidor
    carregarBairros: async function() {
        try {
            const response = await fetch('/api/bairros');
            const data = await response.json();
            
            if (data.success) {
                this.bairros = data.data;
            }
        } catch (error) {
            console.error('Erro ao carregar bairros:', error);
            this.bairros = [];
        }
    },

    // Carrega sachês disponíveis para as categorias dos itens do carrinho
    carregarSaches: async function() {
        try {
            const categoriaIds = [...new Set(
                (window.Carrinho?.itens || [])
                    .map(i => i.categoria_id)
                    .filter(Boolean)
            )];

            if (categoriaIds.length === 0) {
                this.sachesDisponiveis = [];
                this.renderizarSaches();
                return;
            }

            const response = await fetch('/api/saches/disponiveis', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ categoria_ids: categoriaIds })
            });
            const data = await response.json();
            this.sachesDisponiveis = data.saches || [];
        } catch (e) {
            this.sachesDisponiveis = [];
        }
        this.renderizarSaches();
    },

    // Calcula limite máximo de um sachê com base no valor do carrinho
    calcularLimiteSache: function(sache) {
        if (sache.limite_tipo === 'fixo') {
            return parseInt(sache.limite_fixo) || 1;
        }
        // personalizado: minimo + floor(total / por_valor)
        const total = window.Carrinho?.getValorTotal() || 0;
        const min = parseInt(sache.limite_minimo) || 0;
        const porValor = parseFloat(sache.limite_por_valor) || 1;
        return min + Math.floor(total / porValor);
    },

    // Renderiza seção de sachês no modal
    renderizarSaches: function() {
        const container = $('#secao-saches');
        if (!container.length) return;

        if (!this.sachesDisponiveis || this.sachesDisponiveis.length === 0) {
            container.hide();
            return;
        }

        let html = '';
        this.sachesDisponiveis.forEach(s => {
            const limite = this.calcularLimiteSache(s);
            const preco = parseFloat(s.preco);
            const precoTexto = preco > 0
                ? `<small class="text-warning"> +R$ ${preco.toFixed(2).replace('.', ',')} cada</small>`
                : '<small class="text-muted"> Grátis até o limite</small>';
            html += `
                <div class="d-flex justify-content-between align-items-center mb-2 p-2" style="background:#2d2d2d;border-radius:6px;border:1px solid #333;">
                    <div>
                        <span class="text-light">${s.nome}</span>${precoTexto}
                        <br><small class="text-muted">Grátis até: <strong class="text-light">${limite}</strong></small>
                        <span id="sache-preco-extra-${s.id}" class="text-warning" style="display:none;font-size:0.8em;margin-left:6px;"></span>
                    </div>
                    <div class="d-flex align-items-center" style="gap:6px;">
                        <button type="button" class="btn btn-sm btn-secondary sache-dec" data-id="${s.id}" style="padding:2px 8px;">−</button>
                        <span id="sache-qtd-${s.id}" class="text-light" style="min-width:20px;text-align:center;">0</span>
                        <button type="button" class="btn btn-sm btn-secondary sache-inc" data-id="${s.id}" data-limite="${limite}" data-preco="${preco}" style="padding:2px 8px;">+</button>
                    </div>
                </div>`;
        });

        container.find('#saches-lista').html(html);
        container.show();
    },

    // Retorna sachês selecionados [{id, nome, preco, quantidade, preco_cobrado}]
    getSachesSelecionados: function() {
        const result = [];
        (this.sachesDisponiveis || []).forEach(s => {
            const qtd = parseInt($(`#sache-qtd-${s.id}`).text()) || 0;
            if (qtd > 0) {
                const limite = this.calcularLimiteSache(s);
                const preco = parseFloat(s.preco);
                // Apenas a quantidade excedente ao limite gratuito é cobrada
                const qtdPaga = Math.max(0, qtd - limite);
                const precoCobrado = qtdPaga * preco;
                result.push({
                    id: s.id,
                    nome: s.nome,
                    preco: preco,
                    quantidade: qtd,
                    quantidade_paga: qtdPaga,
                    preco_cobrado: precoCobrado
                });
            }
        });
        return result;
    },

    // Carrega preço mínimo de compra
    carregarPrecoMinimo: async function() {
        try {
            const response = await fetch('/api/configuracao/preco-minimo');
            const data = await response.json();
            
            if (data.success) {
                return data.preco_minimo;
            }
        } catch (error) {
            console.error('Erro ao carregar preço mínimo:', error);
        }
        return 0;
    },

    // Verifica se o valor do carrinho atende ao preço mínimo
    verificarPrecoMinimo: async function() {
        const precoMinimo = await this.carregarPrecoMinimo();
        
        if (precoMinimo > 0 && window.Carrinho) {
            const totalCarrinho = window.Carrinho.calcularTotal();
            
            if (totalCarrinho < precoMinimo) {
                const valorFaltante = precoMinimo - totalCarrinho;
                this.mostrarNotificacao(
                    `⚠️ Valor mínimo para pedido: R$ ${precoMinimo.toFixed(2).replace('.', ',')}. ` +
                    `Adicione mais R$ ${valorFaltante.toFixed(2).replace('.', ',')} em produtos.`, 
                    'warning'
                );
                return false;
            }
        }
        return true;
    },
    
    // Abre modal de finalização
    abrirModal: async function() {
        
        // Verificar se há itens no carrinho
        if (!window.Carrinho || window.Carrinho.itens.length === 0) {
            this.mostrarNotificacao('⚠️ Carrinho vazio! Adicione produtos antes de finalizar.', 'warning');
            return;
        }

        // Verificar preço mínimo
        const precoMinimoOk = await this.verificarPrecoMinimo();
        if (!precoMinimoOk) {
            return;
        }
        
        // Criar modal se não existir
        if (!document.getElementById('modalFinalizarPedido')) {
            this.criarModal();
        }

        // Preencher dados do cliente logado, se houver
        if (window.clienteLogado && window.clienteLogado.logado) {
            $('#nome_cliente').val(window.clienteLogado.nome || '');
            $('#telefone_cliente').val(window.clienteLogado.telefone || '');
        }

        // Resetar estado
        this.tipoEntrega = null;
        this.taxaEntrega = 0;
        
        // Atualizar resumo do pedido
        this.atualizarResumo();
        
        // Renderizar formas de pagamento
        this.renderizarFormasPagamento();
        
        // Aguardar carregamento de bairros e depois renderizar
        await this.carregarBairros();
        this.renderizarBairros();

        // Modo 3: preencher endereço salvo no localStorage (APÓS bairros carregados)
        if (window.modoCadastro && window.modoCadastro == 3) {
            const enderecoSalvo = localStorage.getItem('endereco_entrega_modo3');
            if (enderecoSalvo) {
                try {
                    const d = JSON.parse(enderecoSalvo);
                    let enderecoCompleto = (d.endereco || '') + ', ' + (d.numero || '');
                    if (d.complemento) enderecoCompleto += ' - ' + d.complemento;
                    $('#endereco_entrega').val(enderecoCompleto);
                    $('#bairro_id').val(d.bairro_id);
                    $('#bairro_id').trigger('change');
                } catch(e) {
                    console.error('Erro ao carregar endereco salvo', e);
                }
            } else if (window.clienteTemEndereco) {
                // Buscar endereço do banco via API
                fetch('/cliente/endereco_atual', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.json())
                    .then(res => {
                        if (res.sucesso && res.endereco) {
                            const e = res.endereco;
                            let endStr = (e.Endereco || '') + (e.Numero ? ', ' + e.Numero : '');
                            if (e.complemento) endStr += ' - ' + e.complemento;
                            $('#endereco_entrega').val(endStr);
                            // Tentar selecionar bairro pelo nome
                            $('#bairro_id option').filter(function() {
                                return $(this).text().toLowerCase().startsWith((e.Bairro || '').toLowerCase());
                            }).first().prop('selected', true).trigger('change');
                        }
                    }).catch(() => {});
            }
        }
        
        // Recarregar sachês com as categorias atuais do carrinho e renderizar
        await this.carregarSaches();
        
        // Abrir modal sem scroll
        const scrollTop = $(window).scrollTop();
        $('#modalFinalizarPedido').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        $(window).scrollTop(scrollTop);
        
        // Evitar scroll para o topo
        $('#modalFinalizarPedido').on('show.bs.modal', function() {
            $('body').css('overflow', 'hidden');
        });
        
        $('#modalFinalizarPedido').on('hidden.bs.modal', function() {
            $('body').css('overflow', 'auto');
        });
    },
    
    // Cria o modal HTML
    criarModal: function() {
        const modalHTML = `
            <div class="modal fade" id="modalFinalizarPedido" tabindex="-1" role="dialog" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                        <!-- Header -->
                        <div class="modal-header" style="background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%); border-bottom: 1px solid #333; border-radius: 15px 15px 0 0;">
                            <h5 class="modal-title text-warning" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <i class="fas fa-shopping-bag mr-2"></i>Finalizar Pedido
                            </h5>
                            <button type="button" class="close text-light" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        
                        <!-- Body -->
                        <div class="modal-body" style="background: #1a1a1a; padding: 25px; max-height: 70vh; overflow-y: auto;">
                            
                            <!-- Resumo do Pedido -->
                            <div class="mb-4">
                                <h6 class="text-warning mb-3">
                                    <i class="fas fa-receipt mr-2"></i>Resumo do Pedido
                                </h6>
                                <div id="resumo-pedido" class="p-3" style="background: #2d2d2d; border-radius: 8px; border: 1px solid #333;">
                                    <!-- Será preenchido via JS -->
                                </div>
                            </div>
                            
                            <!-- Tipo de Entrega -->
                            <div class="mb-4">
                                <h6 class="text-warning mb-3">
                                    <i class="fas fa-truck mr-2"></i>Tipo de Entrega *
                                </h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="tipo-entrega-card" data-tipo="entrega" 
                                             style="background: #2d2d2d; border: 2px solid #333; border-radius: 10px; 
                                                    padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s;">
                                            <i class="fas fa-motorcycle text-warning" style="font-size: 2.5rem;"></i>
                                            <div class="text-light mt-2" style="font-weight: 600;">Entrega</div>
                                            <small class="text-muted">Receba em casa</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="tipo-entrega-card" data-tipo="retirada" 
                                             style="background: #2d2d2d; border: 2px solid #333; border-radius: 10px; 
                                                    padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s;">
                                            <i class="fas fa-store text-warning" style="font-size: 2.5rem;"></i>
                                            <div class="text-light mt-2" style="font-weight: 600;">Retirada</div>
                                            <small class="text-muted">Buscar na loja</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sachês -->
                            <div class="mb-4" id="secao-saches" style="display:none;">
                                <h6 class="text-warning mb-3">
                                    <i class="fas fa-pepper-hot mr-2"></i>Sachês
                                </h6>
                                <div id="saches-lista"></div>
                            </div>
                            
                            <!-- Dados do Cliente -->
                            <div class="mb-4">
                                <h6 class="text-warning mb-3">
                                    <i class="fas fa-user mr-2"></i>Seus Dados
                                </h6>
                                <form id="form-finalizar-pedido">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="text-light">Nome Completo *</label>
                                            <input type="text" class="form-control bg-dark text-light" 
                                                   id="nome_cliente" name="nome_cliente" required
                                                   style="border-color: #333;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-light">Telefone/WhatsApp *</label>
                                            <input type="tel" class="form-control bg-dark text-light" 
                                                   id="telefone_cliente" name="telefone_cliente" required
                                                   placeholder="(00) 00000-0000"
                                                   style="border-color: #333;">
                                        </div>
                                    </div>
                                    
                                    <!-- Campos de Endereço (aparecem apenas se entrega) -->
                                    <div id="campos-endereco" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-8 mb-3">
                                                <label class="text-light">Endereço Completo *</label>
                                                <input type="text" class="form-control bg-dark text-light" 
                                                       id="endereco_entrega" name="endereco_entrega"
                                                       placeholder="Rua, número"
                                                       style="border-color: #333;">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="text-light">Bairro *</label>
                                                <select class="form-control bg-dark text-light" 
                                                        id="bairro_id" name="bairro_id"
                                                        style="border-color: #333;">
                                                    <option value="">Selecione...</option>
                                                    <!-- Será preenchido via JS -->
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="text-light">Complemento</label>
                                            <input type="text" class="form-control bg-dark text-light" 
                                                   id="complemento" name="complemento"
                                                   placeholder="Apartamento, bloco, ponto de referência..."
                                                   style="border-color: #333;">
                                        </div>
                                        
                                        <!-- Taxa de Entrega -->
                                        <div id="taxa-entrega-info" class="alert" style="background: rgba(248, 181, 49, 0.1); border: 1px solid #f8b531; display: none;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-light">
                                                    <i class="fas fa-motorcycle mr-2"></i>Taxa de Entrega:
                                                </span>
                                                <strong class="text-warning" id="valor-taxa-entrega">R$ 0,00</strong>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Forma de Pagamento -->
                                    <div class="mb-3">
                                        <label class="text-light">Forma de Pagamento *</label>
                                        <div id="formas-pagamento-container" class="row">
                                            <!-- Será preenchido via JS -->
                                        </div>
                                    </div>
                                    
                                    <!-- Dinheiro (aparece apenas se dinheiro) -->
                                    <div id="dinheiro-container" class="mb-33" style="display: none;">
                                        <label class="text-light">Valor em Dinheiro *</label>
                                        <input type="number" class="form-control bg-dark text-light" 
                                               id="valor_dinheiro" name="valor_dinheiro"
                                               placeholder="Ex: 100.00" step="0.01"
                                               style="border-color: #333;">
                                        <small class="text-muted">Informe o valor que você tem para pagar</small>
                                        <div id="troco-info" class="mt-2" style="display: none;">
                                            <div class="alert alert-success">
                                                <i class="fas fa-money-bill-wave mr-2"></i>
                                                <strong>Troco: <span id="valor-troco">R$ 0,00</span></strong>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Observações -->
                                    <div class="mb-3">
                                        <label class="text-light">Observações do Pedido</label>
                                        <textarea class="form-control bg-dark text-light" 
                                                  id="observacoes" name="observacoes" rows="3"
                                                  placeholder="Alguma observação adicional?"
                                                  style="border-color: #333;"></textarea>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                        
                        <!-- Footer -->
                        <div class="modal-footer" style="background: #1a1a1a; border-top: 1px solid #333; border-radius: 0 0 15px 15px;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </button>
                            <button type="button" class="btn btn-success" id="btn-concluir-pedido">
                                <i class="fab fa-whatsapp mr-2"></i>Concluir Pedido
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modalHTML);
    },
    
    // Atualiza resumo do pedido
    atualizarResumo: function() {
        const itens = window.Carrinho.itens;
        const subtotal = window.Carrinho.getValorTotal();

        // Calcular custo dos sachês excedentes
        const saches = this.getSachesSelecionados();
        const totalSaches = saches.reduce((sum, s) => sum + s.preco_cobrado, 0);

        const total = this.getValorTotalComTaxa() + totalSaches;
        
        let html = '<div class="resumo-itens">';
        
        itens.forEach(item => {
            html += `
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2" style="border-bottom: 1px solid #333;">
                    <div>
                        <strong class="text-light">${item.quantidade}x ${item.nome}</strong>
                        ${item.observacoes ? `<br><small class="text-muted"><i class="fas fa-comment-dots mr-1"></i>${item.observacoes}</small>` : ''}
                    </div>
                    <span class="text-warning">R$ ${item.total.toFixed(2).replace('.', ',')}</span>
                </div>
            `;
        });

        // Mostrar sachês com custo no resumo
        saches.forEach(s => {
            if (s.preco_cobrado > 0) {
                html += `
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2" style="border-bottom: 1px solid #333;">
                        <div>
                            <strong class="text-light">${s.quantidade}x ${s.nome}</strong>
                            <br><small class="text-muted">${s.quantidade - s.quantidade_paga} grátis + ${s.quantidade_paga} pagos</small>
                        </div>
                        <span class="text-warning">R$ ${s.preco_cobrado.toFixed(2).replace('.', ',')}</span>
                    </div>
                `;
            }
        });
        
        html += `
            <div class="mt-3 pt-3" style="border-top: 1px solid #333;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-light">Subtotal:</span>
                    <span class="text-light">R$ ${subtotal.toFixed(2).replace('.', ',')}</span>
                </div>
        `;
        
        if (this.taxaEntrega > 0) {
            html += `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-light">Taxa de Entrega:</span>
                    <span class="text-light">R$ ${this.taxaEntrega.toFixed(2).replace('.', ',')}</span>
                </div>
            `;
        }

        if (totalSaches > 0) {
            html += `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-light">Sachês (excedente):</span>
                    <span class="text-light">R$ ${totalSaches.toFixed(2).replace('.', ',')}</span>
                </div>
            `;
        }
        
        html += `
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2" style="border-top: 2px solid #f8b531;">
                    <strong class="text-light" style="font-size: 1.2rem;">Total:</strong>
                    <strong class="text-warning" style="font-size: 1.3rem;">R$ ${total.toFixed(2).replace('.', ',')}</strong>
                </div>
            </div>
        `;
        
        $('#resumo-pedido').html(html);
    },
    
    // Retorna valor total com taxa de entrega e sachês pagos
    getValorTotalComTaxa: function() {
        const totalSaches = (this.sachesDisponiveis || []).reduce((sum, s) => {
            const qtd = parseInt($(`#sache-qtd-${s.id}`).text()) || 0;
            const limite = this.calcularLimiteSache(s);
            const preco = parseFloat(s.preco);
            const qtdPaga = Math.max(0, qtd - limite);
            return sum + (qtdPaga * preco);
        }, 0);
        return window.Carrinho.getValorTotal() + this.taxaEntrega + totalSaches;
    },
    
    // Renderiza formas de pagamento
    renderizarFormasPagamento: function() {
        let html = '';
        
        this.formasPagamento.forEach(forma => {
            html += `
                <div class="col-6 col-md-3 mb-3">
                    <div class="forma-pagamento-card" data-slug="${forma.slug}" 
                         style="background: #2d2d2d; border: 2px solid #333; border-radius: 10px; 
                                padding: 15px; text-align: center; cursor: pointer; transition: all 0.3s;">
                        <i class="${forma.icone} text-warning" style="font-size: 2rem;"></i>
                        <div class="text-light mt-2" style="font-size: 0.9rem;">${forma.nome}</div>
                    </div>
                </div>
            `;
        });
        
        $('#formas-pagamento-container').html(html);
    },
    
    // Renderiza bairros
    renderizarBairros: function() {
        let html = '<option value="">Selecione...</option>';
        
        this.bairros.forEach(bairro => {
            html += `<option value="${bairro.id}" data-taxa="${bairro.valor_entrega}">${bairro.nome} - R$ ${parseFloat(bairro.valor_entrega).toFixed(2).replace('.', ',')}</option>`;
        });
        
        $('#bairro_id').html(html);
    },
    
    // Configura eventos
    configurarEventos: function() {
        // Seleção de tipo de entrega
        $(document).on('click', '.tipo-entrega-card', function() {
            $('.tipo-entrega-card').css({
                'border-color': '#333',
                'background': '#2d2d2d'
            });
            
            $(this).css({
                'border-color': '#f8b531',
                'background': 'rgba(248, 181, 49, 0.1)'
            });
            
            const tipo = $(this).data('tipo');
            FinalizarPedido.tipoEntrega = tipo;
            
            if (tipo === 'entrega') {
                $('#campos-endereco').slideDown();
                $('#endereco_entrega').prop('required', true);
                $('#bairro_id').prop('required', true);
            } else {
                $('#campos-endereco').slideUp();
                $('#endereco_entrega').prop('required', false);
                $('#bairro_id').prop('required', false);
                $('#taxa-entrega-info').hide();
                FinalizarPedido.taxaEntrega = 0;
                FinalizarPedido.atualizarResumo();
            }
        });
        
        // Seleção de bairro
        $(document).on('change', '#bairro_id', function() {
            const taxa = parseFloat($(this).find(':selected').data('taxa')) || 0;
            FinalizarPedido.taxaEntrega = taxa;
            
            if (taxa > 0) {
                $('#valor-taxa-entrega').text('R$ ' + taxa.toFixed(2).replace('.', ','));
                $('#taxa-entrega-info').slideDown();
            } else {
                $('#taxa-entrega-info').slideUp();
            }
            
            FinalizarPedido.atualizarResumo();
        });
        
        // Seleção de forma de pagamento
        $(document).on('click', '.forma-pagamento-card', function() {
            $('.forma-pagamento-card').css({
                'border-color': '#333',
                'background': '#2d2d2d'
            });
            
            $(this).css({
                'border-color': '#f8b531',
                'background': 'rgba(248, 181, 49, 0.1)'
            });
            
            const slug = $(this).data('slug');
            $('#forma_pagamento_selecionada').remove();
            $('<input>').attr({
                type: 'hidden',
                id: 'forma_pagamento_selecionada',
                value: slug
            }).appendTo('#form-finalizar-pedido');
            
            // Mostrar campo de dinheiro se for dinheiro
            if (slug === 'dinheiro') {
                $('#dinheiro-container').slideDown();
                $('#valor_dinheiro').prop('required', true);
            } else {
                $('#dinheiro-container').slideUp();
                $('#valor_dinheiro').prop('required', false);
                $('#troco-info').hide();
            }
        });
        
        // Cálculo de troco
        $(document).on('input', '#valor_dinheiro', function() {
            const valorDinheiro = parseFloat($(this).val()) || 0;
            const valorTotal = FinalizarPedido.getValorTotalComTaxa();
            
            if (valorDinheiro > 0) {
                if (valorDinheiro < valorTotal) {
                    $(this).css('border-color', '#dc3545');
                    $('#troco-info').hide();
                    FinalizarPedido.mostrarNotificacao('⚠️ Valor insuficiente! Total: R$ ' + valorTotal.toFixed(2).replace('.', ','), 'warning');
                } else {
                    $(this).css('border-color', '#28a745');
                    const troco = valorDinheiro - valorTotal;
                    $('#valor-troco').text('R$ ' + troco.toFixed(2).replace('.', ','));
                    $('#troco-info').slideDown();
                }
            } else {
                $(this).css('border-color', '#333');
                $('#troco-info').hide();
            }
        });
        
        // Botões + / - dos sachês
        $(document).on('click', '.sache-inc', function() {
            const id = $(this).data('id');
            const limite = parseInt($(this).data('limite')) || 0;
            const preco = parseFloat($(this).data('preco')) || 0;
            const span = $(`#sache-qtd-${id}`);
            const atual = parseInt(span.text()) || 0;
            const novaQtd = atual + 1;
            span.text(novaQtd);

            // Atualizar indicador de custo extra
            const extraSpan = $(`#sache-preco-extra-${id}`);
            if (novaQtd > limite && preco > 0) {
                const qtdPaga = novaQtd - limite;
                const totalExtra = (qtdPaga * preco).toFixed(2).replace('.', ',');
                extraSpan.text(`(+R$ ${totalExtra} pelo excedente)`).show();
            } else {
                extraSpan.hide();
            }

            FinalizarPedido.atualizarResumo();
        });
        $(document).on('click', '.sache-dec', function() {
            const id = $(this).data('id');
            const span = $(`#sache-qtd-${id}`);
            const atual = parseInt(span.text()) || 0;
            if (atual > 0) {
                span.text(atual - 1);

                // Atualizar indicador de custo extra
                const btn = $(`.sache-inc[data-id="${id}"]`);
                const limite = parseInt(btn.data('limite')) || 0;
                const preco = parseFloat(btn.data('preco')) || 0;
                const novaQtd = atual - 1;
                const extraSpan = $(`#sache-preco-extra-${id}`);
                if (novaQtd > limite && preco > 0) {
                    const qtdPaga = novaQtd - limite;
                    const totalExtra = (qtdPaga * preco).toFixed(2).replace('.', ',');
                    extraSpan.text(`(+R$ ${totalExtra} pelo excedente)`).show();
                } else {
                    extraSpan.hide();
                }

                FinalizarPedido.atualizarResumo();
            }
        });

        // Máscara de telefone
        $(document).on('input', '#telefone_cliente', function() {
            let valor = $(this).val().replace(/\D/g, '');
            if (valor.length <= 11) {
                valor = valor.replace(/^(\d{2})(\d)/g, '($1) $2');
                valor = valor.replace(/(\d)(\d{4})$/, '$1-$2');
            }
            $(this).val(valor);
        });
        
        // Botão concluir pedido
        $(document).on('click', '#btn-concluir-pedido', () => {
            this.concluirPedido();
        });
    },
    
    // Conclui o pedido
    concluirPedido: async function() {
        
        // Verificar tipo de entrega
        if (!this.tipoEntrega) {
            this.mostrarNotificacao('⚠️ Selecione o tipo de entrega', 'warning');
            return;
        }

        // Se entrega e endereço/bairro vazios, abrir popup de endereço
        if (this.tipoEntrega === 'entrega') {
            const enderecoVal = $('#endereco_entrega').val().trim();
            if (!enderecoVal) {
                this.abrirPopupEndereco();
                return;
            }
        }
        
        // Validar formulário
        const form = document.getElementById('form-finalizar-pedido');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Verificar forma de pagamento
        const formaPagamento = $('#forma_pagamento_selecionada').val();
        if (!formaPagamento) {
            this.mostrarNotificacao('⚠️ Selecione uma forma de pagamento', 'warning');
            return;
        }
        
        // Validar dinheiro
        if (formaPagamento === 'dinheiro') {
            const valorDinheiro = parseFloat($('#valor_dinheiro').val()) || 0;
            const valorTotal = this.getValorTotalComTaxa();
            
            if (valorDinheiro < valorTotal) {
                this.mostrarNotificacao('⚠️ Valor em dinheiro insuficiente!', 'warning');
                return;
            }
        }
        
        // Coletar dados
        const sachesSelecionados = this.getSachesSelecionados();
        const totalSaches = sachesSelecionados.reduce((sum, s) => sum + s.preco_cobrado, 0);
        const dados = {
            email: (window.clienteLogado && window.clienteLogado.email) ? window.clienteLogado.email : null,
            tipo_entrega: this.tipoEntrega,
            nome_cliente: $('#nome_cliente').val(),
            telefone_cliente: $('#telefone_cliente').val(),
            endereco_entrega: this.tipoEntrega === 'entrega' ? $('#endereco_entrega').val() : 'Retirada na loja',
            bairro_id: this.tipoEntrega === 'entrega' ? $('#bairro_id').val() : null,
            bairro_nome: this.tipoEntrega === 'entrega' ? $('#bairro_id option:selected').text().split(' - ')[0] : null,
            cidade: this.tipoEntrega === 'entrega' ? (() => { try { return JSON.parse(localStorage.getItem('endereco_entrega_modo3') || '{}').cidade || null; } catch(e) { return null; } })() : null,
            complemento: $('#complemento').val(),
            forma_pagamento: formaPagamento,
            valor_dinheiro: formaPagamento === 'dinheiro' ? parseFloat($('#valor_dinheiro').val()) : null,
            observacoes: $('#observacoes').val(),
            itens: window.Carrinho.itens,
            saches: sachesSelecionados,
            valor_produtos: window.Carrinho.getValorTotal(),
            valor_entrega: this.taxaEntrega,
            valor_saches: totalSaches,
            valor_total: this.getValorTotalComTaxa()
        };
        
        
        // Desabilitar botão
        const $btn = $('#btn-concluir-pedido');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processando...');
        
        try {
            // Enviar para o servidor
            const response = await fetch('/pedidos/criar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(dados)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Remover redirecionamento WhatsApp - apenas mostrar popup do pedido
                
                // Limpar carrinho completamente
                if (typeof window.Carrinho !== 'undefined') {
                    window.Carrinho.itens = [];
                    window.Carrinho.salvar();
                    window.Carrinho.atualizarBadge();
                }
                
                // Fechar modal
                $('#modalFinalizarPedido').modal('hide');
                
                // Salvar código do pedido em andamento
                localStorage.setItem('pedido_em_andamento', result.pedido.codigo);
                
                // Abrir popup do pedido automaticamente
                if (typeof CarrinhoSimples !== 'undefined') {
                    CarrinhoSimples.exibirPopupAcompanhamento(result.pedido, result.itens || [], result.saches || [], result.chave_pix || null, result.qrcode_image || null, result.tempo_entrega || 0);
                } else {
                    this.mostrarNotificacao('✅ Pedido realizado com sucesso! Código: ' + result.pedido.codigo, 'success');
                }
                
                // Limpar formulário
                form.reset();
            } else {
                this.mostrarNotificacao('❌ Erro ao criar pedido: ' + result.message, 'error');
                $btn.prop('disabled', false).html(originalText);
            }
        } catch (error) {
            console.error('Erro ao concluir pedido:', error);
            this.mostrarNotificacao('❌ Erro ao processar pedido', 'error');
            $btn.prop('disabled', false).html(originalText);
        }
    },
    
    // Envia pedido para WhatsApp
    enviarWhatsApp: function(pedido, dados) {
        const telefone = '5531982473800';
        
        let mensagem = `📦 *Novo Pedido Recebido!*\n\n`;
        mensagem += `🧾 ${pedido.codigo}\n`;
        mensagem += `👤 ${dados.nome_cliente}\n`;
        mensagem += `📞 ${dados.telefone_cliente}\n`;
        
        if (dados.tipo_entrega === 'entrega') {
            mensagem += `📍 ${dados.bairro_nome} – ${dados.endereco_entrega}\n`;
            if (dados.complemento) {
                mensagem += `🏢 ${dados.complemento}\n`;
            }
        } else {
            mensagem += `🏪 *RETIRADA NA LOJA*\n`;
        }
        
        mensagem += `\n🛒 *Itens do Pedido:*\n`;
        
        dados.itens.forEach(item => {
            const precoUnitario = item.precoUnitario || item.preco || 0;
            mensagem += `🍕 ${item.quantidade}x ${item.nome} — R$ ${precoUnitario.toFixed(2).replace('.', ',')} cada\n`;
            
            // Adicionar extras se existirem
            if (item.extras && Array.isArray(item.extras) && item.extras.length > 0) {
                item.extras.forEach(extra => {
                    const precoExtra = parseFloat(extra.preco) || 0;
                    const qtdExtra = parseInt(extra.quantidade) || 1;
                    const precoTexto = precoExtra > 0 ? ` (+R$ ${(precoExtra * qtdExtra).toFixed(2).replace('.', ',')})` : '';
                    const qtdTexto = qtdExtra > 1 ? ` x${qtdExtra}` : '';
                    mensagem += `   ➕ ${extra.nome}${qtdTexto}${precoTexto}\n`;
                });
            }
            
            if (item.observacoes) {
                mensagem += `   📝 ${item.observacoes}\n`;
            }
        });
        
        mensagem += `\n💲 *Subtotal:* R$ ${dados.valor_produtos.toFixed(2).replace('.', ',')}\n`;
        
        if (dados.valor_entrega > 0) {
            mensagem += `🏍️ *Taxa de Entrega:* R$ ${dados.valor_entrega.toFixed(2).replace('.', ',')}\n`;
        }
        
        mensagem += `💰 *Total:* R$ ${dados.valor_total.toFixed(2).replace('.', ',')}\n`;
        mensagem += `💳 *Pagamento:* ${this.getNomeFormaPagamento(dados.forma_pagamento)}\n`;
        
        if (dados.forma_pagamento === 'dinheiro' && dados.valor_dinheiro) {
            const troco = dados.valor_dinheiro - dados.valor_total;
            mensagem += `💵 *Troco para:* R$ ${dados.valor_dinheiro.toFixed(2).replace('.', ',')}\n`;
            if (troco > 0) {
                mensagem += `💸 *Troco:* R$ ${troco.toFixed(2).replace('.', ',')}\n`;
            }
        }
        
        if (dados.observacoes) {
            mensagem += `\n📋 *Observações:* ${dados.observacoes}\n`;
        }
        
        mensagem += `\n👍 *Já estamos preparando seu pedido!*`;
        
        const url = `https://wa.me/${telefone}?text=${encodeURIComponent(mensagem)}`;
        window.open(url, '_blank');
    },
    
    // Retorna nome da forma de pagamento
    getNomeFormaPagamento: function(slug) {
        const forma = this.formasPagamento.find(f => f.slug === slug);
        return forma ? forma.nome : slug;
    },
    
    // Mostra notificação
    // Abre popup para definir endereço de entrega (modo 3)
    abrirPopupEndereco: function() {
        const self = this;
        // Remover popup anterior se existir
        document.getElementById('popup-endereco-fp')?.remove();

        const popup = document.createElement('div');
        popup.id = 'popup-endereco-fp';
        popup.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:9999;display:flex;align-items:center;justify-content:center;padding:0 12px;box-sizing:border-box;';
        popup.innerHTML = `
            <div style="background:#1a1a1a;width:100%;max-width:480px;border-radius:12px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.6);">
                <div style="background:#2d2d2d;padding:14px 18px;border-bottom:1px solid #333;display:flex;justify-content:space-between;align-items:center;">
                    <h6 style="color:#f8b531;margin:0;font-family:'Poppins',sans-serif;"><i class="fas fa-map-marker-alt" style="margin-right:8px;"></i>Endereço de Entrega</h6>
                    <button onclick="document.getElementById('popup-endereco-fp').remove()" style="background:none;border:none;color:#fff;font-size:1.3rem;cursor:pointer;line-height:1;">&times;</button>
                </div>
                <div style="padding:18px;max-height:70vh;overflow-y:auto;">
                    <div style="background:#2a3a2a;border:1px solid #3a5a3a;border-radius:8px;padding:10px 12px;margin-bottom:14px;font-size:.82rem;color:#90ee90;line-height:1.4;">
                        <i class="fas fa-info-circle" style="margin-right:6px;color:#f8b531;"></i>
                        Caso sua cidade ou bairro não apareça na lista, sua região não está na área de entrega. Mas você pode realizar a <strong>retirada no local</strong>!
                    </div>
                    <div style="margin-bottom:12px;position:relative;">
                        <label style="color:#ccc;font-size:.85rem;display:block;margin-bottom:4px;">Cidade *</label>
                        <input type="text" id="fp-cidade" placeholder="Digite sua cidade..." autocomplete="off"
                            style="width:100%;background:#2d2d2d;border:1px solid #444;color:#fff;padding:8px;border-radius:6px;font-size:16px;">
                        <div id="fp-cidade-sugestoes" style="display:none;position:absolute;top:100%;left:0;right:0;background:#2d2d2d;border:1px solid #555;border-radius:0 0 6px 6px;z-index:10;max-height:160px;overflow-y:auto;"></div>
                    </div>
                    <div style="margin-bottom:12px;position:relative;">
                        <label style="color:#ccc;font-size:.85rem;display:block;margin-bottom:4px;">Bairro *</label>
                        <input type="text" id="fp-bairro" placeholder="Digite seu bairro..." autocomplete="off"
                            style="width:100%;background:#2d2d2d;border:1px solid #444;color:#fff;padding:8px;border-radius:6px;font-size:16px;">
                        <div id="fp-bairro-sugestoes" style="display:none;position:absolute;top:100%;left:0;right:0;background:#2d2d2d;border:1px solid #555;border-radius:0 0 6px 6px;z-index:10;max-height:160px;overflow-y:auto;"></div>
                        <input type="hidden" id="fp-bairro-id" value="">
                        <input type="hidden" id="fp-bairro-taxa" value="0">
                    </div>
                    <div style="margin-bottom:12px;">
                        <label style="color:#ccc;font-size:.85rem;display:block;margin-bottom:4px;">Rua/Av *</label>
                        <input type="text" id="fp-endereco" placeholder="Ex: Rua das Flores" style="width:100%;background:#2d2d2d;border:1px solid #444;color:#fff;padding:8px;border-radius:6px;font-size:16px;">
                    </div>
                    <div style="display:flex;gap:10px;margin-bottom:12px;">
                        <div style="flex:1;">
                            <label style="color:#ccc;font-size:.85rem;display:block;margin-bottom:4px;">Número *</label>
                            <input type="text" id="fp-numero" placeholder="123" style="width:100%;background:#2d2d2d;border:1px solid #444;color:#fff;padding:8px;border-radius:6px;font-size:16px;">
                        </div>
                        <div style="flex:2;">
                            <label style="color:#ccc;font-size:.85rem;display:block;margin-bottom:4px;">Complemento</label>
                            <input type="text" id="fp-complemento" placeholder="Apto, bloco..." style="width:100%;background:#2d2d2d;border:1px solid #444;color:#fff;padding:8px;border-radius:6px;font-size:16px;">
                        </div>
                    </div>
                    <button id="fp-btn-salvar" style="width:100%;padding:12px;background:linear-gradient(135deg,#28a745,#20c997);border:none;border-radius:8px;color:#fff;font-weight:600;font-size:1rem;cursor:pointer;">
                        <i class="fas fa-check" style="margin-right:6px;"></i>Confirmar Endereço
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(popup);

        const cidadeInput  = document.getElementById('fp-cidade');
        const cidadeSugest = document.getElementById('fp-cidade-sugestoes');
        const bairroInput  = document.getElementById('fp-bairro');
        const bairroSugest = document.getElementById('fp-bairro-sugestoes');
        const bairroIdHid  = document.getElementById('fp-bairro-id');
        const bairroTaxaHid= document.getElementById('fp-bairro-taxa');

        const cidades = [...new Set((self.bairros || []).map(b => b.cidade).filter(Boolean))].sort();

        function itemSugestao(texto, onClick) {
            const d = document.createElement('div');
            d.textContent = texto;
            d.style.cssText = 'padding:8px 12px;cursor:pointer;color:#fff;font-size:.9rem;border-bottom:1px solid #3a3a3a;';
            d.addEventListener('mousedown', onClick);
            d.addEventListener('mouseover', () => d.style.background = '#3a3a3a');
            d.addEventListener('mouseout',  () => d.style.background = '');
            return d;
        }

        // Autocomplete cidade
        cidadeInput.addEventListener('input', function() {
            const q = this.value.trim().toLowerCase();
            cidadeSugest.innerHTML = '';
            bairroInput.value = ''; bairroIdHid.value = ''; bairroTaxaHid.value = '0';
            if (!q) { cidadeSugest.style.display = 'none'; return; }
            const matches = cidades.filter(c => c.toLowerCase().includes(q));
            if (!matches.length) { cidadeSugest.style.display = 'none'; return; }
            matches.forEach(c => cidadeSugest.appendChild(itemSugestao(c, () => {
                cidadeInput.value = c;
                cidadeSugest.style.display = 'none';
                bairroInput.focus();
            })));
            cidadeSugest.style.display = 'block';
        });
        cidadeInput.addEventListener('blur', () => setTimeout(() => cidadeSugest.style.display = 'none', 150));

        // Autocomplete bairro (filtra pela cidade digitada, ou todos se cidade não bater)
        bairroInput.addEventListener('input', function() {
            const q = this.value.trim().toLowerCase();
            bairroIdHid.value = ''; bairroTaxaHid.value = '0';
            bairroSugest.innerHTML = '';
            if (!q) { bairroSugest.style.display = 'none'; return; }
            const cidadeDigitada = cidadeInput.value.trim();
            const pool = (self.bairros || []).filter(b =>
                (!cidadeDigitada || b.cidade.toLowerCase() === cidadeDigitada.toLowerCase()) &&
                b.nome.toLowerCase().includes(q)
            );
            if (!pool.length) { bairroSugest.style.display = 'none'; return; }
            pool.forEach(b => {
                const taxa = parseFloat(b.valor_entrega) || 0;
                const label = b.nome + (cidadeDigitada ? '' : ' — ' + b.cidade) + (taxa > 0 ? ' (+R$ ' + taxa.toFixed(2).replace('.', ',') + ')' : '');
                bairroSugest.appendChild(itemSugestao(label, () => {
                    bairroInput.value = b.nome;
                    if (!cidadeInput.value.trim()) cidadeInput.value = b.cidade;
                    bairroIdHid.value  = b.id;
                    bairroTaxaHid.value = b.valor_entrega || 0;
                    bairroSugest.style.display = 'none';
                }));
            });
            bairroSugest.style.display = 'block';
        });
        bairroInput.addEventListener('blur', () => setTimeout(() => bairroSugest.style.display = 'none', 150));

        // Pré-preencher com localStorage se houver
        const saved = localStorage.getItem('endereco_entrega_modo3');
        if (saved) {
            try {
                const d = JSON.parse(saved);
                cidadeInput.value = d.cidade || '';
                bairroInput.value = d.bairro_nome || '';
                bairroIdHid.value  = d.bairro_id || '';
                bairroTaxaHid.value = d.taxa_entrega || 0;
                document.getElementById('fp-endereco').value = d.endereco || '';
                document.getElementById('fp-numero').value   = d.numero || '';
                document.getElementById('fp-complemento').value = d.complemento || '';
            } catch(e) {}
        }

        // Salvar e preencher campos do modal principal
        document.getElementById('fp-btn-salvar').addEventListener('click', function() {
            const cidade    = cidadeInput.value.trim();
            const bairroNome= bairroInput.value.trim();
            const bairroId  = bairroIdHid.value;
            const taxa      = parseFloat(bairroTaxaHid.value) || 0;
            const endereco  = document.getElementById('fp-endereco').value.trim();
            const numero    = document.getElementById('fp-numero').value.trim();
            const complemento = document.getElementById('fp-complemento').value.trim();

            if (!cidade || !bairroNome || !endereco || !numero) {
                alert('Preencha cidade, bairro, rua e número');
                return;
            }

            localStorage.setItem('endereco_entrega_modo3', JSON.stringify({
                cidade, bairro_id: bairroId, bairro_nome: bairroNome,
                endereco, numero, complemento, taxa_entrega: taxa
            }));

            fetch('/cliente/atualizar_endereco', {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
                body: JSON.stringify({ cidade, bairro_nome: bairroNome, endereco, numero, complemento })
            }).catch(() => {});

            // Preencher campos do modal de finalização (se existir)
            const endStr = endereco + ', ' + numero + (complemento ? ' - ' + complemento : '');
            $('#endereco_entrega').val(endStr);
            $('#bairro_id').val(bairroId).trigger('change');

            popup.remove();

            // Recalcular taxa no carrinho (mostrará aviso se fora da cobertura)
            if (window.CarrinhoSimples && typeof window.CarrinhoSimples.calcularTaxaEntrega === 'function') {
                const subtotal = JSON.parse(localStorage.getItem('carrinho') || '[]')
                    .reduce((s, i) => s + i.total, 0);
                window.CarrinhoSimples.calcularTaxaEntrega(subtotal);
            } else if (window.location.pathname.includes('/carrinho')) {
                // Na página de carrinho sem CarrinhoSimples, retomar finalização
                self.concluirPedido();
            }
        });
    },

    mostrarNotificacao: function(mensagem, tipo = 'info') {
        if (typeof window.Carrinho !== 'undefined' && window.Carrinho.mostrarNotificacao) {
            window.Carrinho.mostrarNotificacao(mensagem, tipo);
        } else {
            alert(mensagem);
        }
    }
};

// Inicializa quando DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        FinalizarPedido.init();
    });
} else {
    FinalizarPedido.init();
}

