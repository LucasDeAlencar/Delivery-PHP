/**
 * Sistema de Finalização de Pedido
 * Versão 1.0
 */

console.log('🛍️ Sistema de Finalização de Pedido carregando...');

window.FinalizarPedido = {
    formasPagamento: [],
    bairros: [],
    tipoEntrega: null,
    taxaEntrega: 0,
    
    // Inicializa o sistema
    init: function() {
        console.log('🚀 Inicializando sistema de finalização...');
        this.carregarFormasPagamento();
        this.carregarBairros();
        this.configurarEventos();
        console.log('✅ Sistema de finalização inicializado!');
    },
    
    // Carrega formas de pagamento do servidor
    carregarFormasPagamento: async function() {
        try {
            const response = await fetch('/api/formas-pagamento');
            const data = await response.json();
            
            if (data.success) {
                this.formasPagamento = data.data;
                console.log('💳 Formas de pagamento carregadas:', this.formasPagamento.length);
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
                console.log('📍 Bairros carregados:', this.bairros.length);
            }
        } catch (error) {
            console.error('Erro ao carregar bairros:', error);
            this.bairros = [];
        }
    },
    
    // Abre modal de finalização
    abrirModal: function() {
        console.log('📂 Abrindo modal de finalização...');
        
        // Verificar se há itens no carrinho
        if (!window.Carrinho || window.Carrinho.itens.length === 0) {
            this.mostrarNotificacao('⚠️ Carrinho vazio! Adicione produtos antes de finalizar.', 'warning');
            return;
        }
        
        // Criar modal se não existir
        if (!document.getElementById('modalFinalizarPedido')) {
            this.criarModal();
        }
        
        // Resetar estado
        this.tipoEntrega = null;
        this.taxaEntrega = 0;
        
        // Atualizar resumo do pedido
        this.atualizarResumo();
        
        // Renderizar formas de pagamento
        this.renderizarFormasPagamento();
        
        // Renderizar bairros
        this.renderizarBairros();
        
        // Abrir modal
        $('#modalFinalizarPedido').modal('show');
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
                                    <div id="dinheiro-container" class="mb-3" style="display: none;">
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
        const total = this.getValorTotalComTaxa();
        
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
        
        html += `
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2" style="border-top: 2px solid #f8b531;">
                    <strong class="text-light" style="font-size: 1.2rem;">Total:</strong>
                    <strong class="text-warning" style="font-size: 1.3rem;">R$ ${total.toFixed(2).replace('.', ',')}</strong>
                </div>
            </div>
        </div>`;
        
        $('#resumo-pedido').html(html);
    },
    
    // Retorna valor total com taxa de entrega
    getValorTotalComTaxa: function() {
        return window.Carrinho.getValorTotal() + this.taxaEntrega;
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
        console.log('📝 Concluindo pedido...');
        
        // Verificar tipo de entrega
        if (!this.tipoEntrega) {
            this.mostrarNotificacao('⚠️ Selecione o tipo de entrega', 'warning');
            return;
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
        const dados = {
            tipo_entrega: this.tipoEntrega,
            nome_cliente: $('#nome_cliente').val(),
            telefone_cliente: $('#telefone_cliente').val(),
            endereco_entrega: this.tipoEntrega === 'entrega' ? $('#endereco_entrega').val() : 'Retirada na loja',
            bairro_id: this.tipoEntrega === 'entrega' ? $('#bairro_id').val() : null,
            bairro_nome: this.tipoEntrega === 'entrega' ? $('#bairro_id option:selected').text().split(' - ')[0] : null,
            complemento: $('#complemento').val(),
            forma_pagamento: formaPagamento,
            valor_dinheiro: formaPagamento === 'dinheiro' ? parseFloat($('#valor_dinheiro').val()) : null,
            observacoes: $('#observacoes').val(),
            itens: window.Carrinho.itens,
            valor_produtos: window.Carrinho.getValorTotal(),
            valor_entrega: this.taxaEntrega,
            valor_total: this.getValorTotalComTaxa()
        };
        
        console.log('📦 Dados do pedido:', dados);
        
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
                // Enviar para WhatsApp
                this.enviarWhatsApp(result.pedido, dados);
                
                // Limpar carrinho completamente
                if (typeof window.Carrinho !== 'undefined') {
                    window.Carrinho.itens = [];
                    window.Carrinho.salvar();
                    window.Carrinho.atualizarBadge();
                    console.log('🗑️ Carrinho limpo após pedido concluído');
                }
                
                // Fechar modal
                $('#modalFinalizarPedido').modal('hide');
                
                // Mostrar sucesso
                this.mostrarNotificacao('✅ Pedido realizado com sucesso!', 'success');
                
                // Limpar formulário
                form.reset();
                
                // Aguardar um pouco e recarregar página para resetar tudo
                setTimeout(() => {
                    location.reload();
                }, 2000);
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

console.log('✅ Sistema de Finalização de Pedido carregado!');
