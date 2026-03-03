/**
 * Sistema de Carrinho Simples
 */

window.CarrinhoSimples = {
    // Verificar se há pedido em andamento e se pertence ao cliente logado
    verificarPedidoEmAndamento(callback) {
        const codigoPedido = localStorage.getItem('pedido_em_andamento');
        if (!codigoPedido) {
            if (callback) callback(false);
            return false;
        }

        const emailCliente = localStorage.getItem('cliente_email');

        // Verificar status do pedido via API
        $.ajax({
            url: `/acompanhar-pedido/${codigoPedido}`,
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    const status = response.pedido.status;
                    
                    // Se pedido foi entregue ou cancelado, remover do localStorage
                    if (status === 'entregue' || status === 'cancelado') {
                        localStorage.removeItem('pedido_em_andamento');
                        if (callback) callback(false);
                        return;
                    }
                    
                    // Verificar se o pedido pertence ao cliente logado
                    let pedidoPertence = false;
                    
                    if (emailCliente && response.pedido.email_cliente) {
                        // Ambos têm email - comparar
                        pedidoPertence = (response.pedido.email_cliente.toLowerCase() === emailCliente.toLowerCase());
                    } else if (!emailCliente && !response.pedido.email_cliente) {
                        // Nenhum tem email (pedido antigo sem cliente logado) - permitir por compatibilidade
                        pedidoPertence = true;
                    } else if (emailCliente && !response.pedido.email_cliente) {
                        // Cliente logado mas pedido não tem email - não permitir (evita mostrar pedido de outro usuário)
                        pedidoPertence = false;
                    } else if (!emailCliente && response.pedido.email_cliente) {
                        // Cliente não logado mas pedido tem email - não permitir
                        pedidoPertence = false;
                    }
                    
                    // Se não pertence ao cliente, limpar e retornar false
                    if (!pedidoPertence) {
                        localStorage.removeItem('pedido_em_andamento');
                        localStorage.removeItem('codigo_pedido_ativo');
                        if (callback) callback(false);
                        return;
                    }
                    
                    if (callback) callback(true);
                } else {
                    // Pedido não encontrado, remover do localStorage
                    localStorage.removeItem('pedido_em_andamento');
                    if (callback) callback(false);
                }
            },
            error: () => {
                if (callback) callback(false);
            }
        });
    },

    // Mostrar acompanhamento do pedido
    mostrarAcompanhamento() {
        const codigoPedido = localStorage.getItem('pedido_em_andamento');
        if (!codigoPedido) return;

        $.ajax({
            url: `/acompanhar-pedido/${codigoPedido}`,
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    this.exibirPopupAcompanhamento(response.pedido, response.itens, response.chave_pix, response.qrcode_image);
                } else {
                    alert('Erro ao carregar pedido');
                }
            },
            error: () => {
                alert('Erro ao carregar pedido');
            }
        });
    },

    // Exibir popup de acompanhamento
    exibirPopupAcompanhamento(pedido, itens, chavePix, qrcodeImage) {
        console.log('Exibindo popup com:', { pedido, chavePix, qrcodeImage });
        
        const statusTexto = {
            'pendente': 'Aguardando Confirmação',
            'confirmado': 'Pedido Confirmado',
            'preparando': 'Preparando seu Pedido',
            'saiu_entrega': 'Saiu para Entrega',
            'entregue': 'Entregue',
            'cancelado': 'Cancelado'
        };

        const statusIcon = {
            'pendente': '⏳',
            'confirmado': '✅',
            'preparando': '👨‍🍳',
            'saiu_entrega': '🚚',
            'entregue': '✅',
            'cancelado': '❌'
        };

        let html = `
            <div id="acompanhamento-popup" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                <div style="background: #1a1a1a; width: 90%; max-width: 500px; border-radius: 15px; padding: 20px; color: white; max-height: 80vh; overflow-y: auto;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #f8b531; margin: 0;">Seu Pedido</h3>
                        <button onclick="$('#acompanhamento-popup').remove()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer;">×</button>
                    </div>
                    
                    <div style="text-align: center; margin-bottom: 20px; padding: 15px; background: #2a2a2a; border-radius: 10px;">
                        <div style="font-size: 24px; margin-bottom: 10px;">${statusIcon[pedido.status] || '📋'}</div>
                        <h4 style="color: #f8b531; margin: 0;">${statusTexto[pedido.status] || pedido.status}</h4>
                        <p style="margin: 5px 0; color: #ccc;">Código: ${pedido.codigo}</p>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <p><strong>Total:</strong> R$ ${parseFloat(pedido.valor_total).toFixed(2).replace('.', ',')}</p>
                        <p><strong>Pagamento:</strong> ${pedido.forma_pagamento}</p>
                        ${chavePix && pedido.forma_pagamento && pedido.forma_pagamento.toLowerCase().includes('pix') ? 
                            `<div style="background: #2a2a2a; padding: 15px; border-radius: 8px; margin: 10px 0; border-left: 4px solid #f8b531;">
                                ${qrcodeImage ? 
                                    `<div style="text-align: center; margin-bottom: 10px;">
                                        <img src="/uploads/qrcode_pix/${qrcodeImage}" 
                                             alt="QR Code PIX" 
                                             style="max-width: 150px; width: 100%; height: auto; border: 2px solid #28a745; border-radius: 8px; display: block; margin: 0 auto;"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div style="display: none; color: #ffc107; font-size: 0.8rem; padding: 10px;">
                                            <i class="fas fa-exclamation-triangle"></i> QR Code indisponível. Use a chave PIX abaixo.
                                        </div>
                                    </div>` : ''
                                }
                                <p style="margin: 0; color: #f8b531; font-weight: bold; font-size: 0.9rem;"><i class="fas fa-qrcode"></i> Chave PIX para Pagamento:</p>
                                <div style="background: #1a1a1a; padding: 8px; border-radius: 5px; margin-top: 8px; border: 1px dashed #555; display: flex; align-items: center; gap: 8px;">
                                    <span style="flex: 1; font-family: monospace; font-size: 0.85rem; word-break: break-all; color: #fff;">${chavePix}</span>
                                    <button onclick="CarrinhoSimples.copiarChavePix('${chavePix}')" style="background: #f8b531; color: #000; border: none; padding: 6px 10px; border-radius: 3px; font-size: 0.7rem; cursor: pointer; white-space: nowrap;">
                                        <i class="fas fa-copy"></i> Copiar
                                    </button>
                                </div>
                                <small style="color: #ccc; font-size: 0.75rem;">${qrcodeImage ? 'Escaneie o QR Code ou copie a chave acima para pagar' : 'Copie a chave acima para fazer o pagamento via PIX'}</small>
                            </div>` : ''
                        }
                        <p><strong>Pedido em:</strong> ${new Date(pedido.criado_em).toLocaleString('pt-BR')}</p>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap;">
                        <button onclick="window.open('/recibo/${pedido.codigo}', '_blank')" style="flex: 1; padding: 12px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; min-width: 150px;">
                            <i class="fas fa-file-download"></i> Baixar Recibo
                        </button>
                        <button onclick="CarrinhoSimples.abrirSuporte('${pedido.codigo}', '${pedido.id}')" style="flex: 1; padding: 12px; background: #ff9800; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; min-width: 150px;">
                            <i class="fas fa-headset"></i> Suporte
                        </button>
                        ${pedido.status === 'pendente' ? 
                            `<button onclick="CarrinhoSimples.cancelarPedido('${pedido.codigo}')" style="flex: 1; padding: 12px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; min-width: 150px;">Cancelar Pedido</button>` :
                            pedido.status === 'entregue' || pedido.status === 'cancelado' ? 
                                `<button onclick="localStorage.removeItem('pedido_em_andamento'); $('#acompanhamento-popup').remove(); location.reload();" style="flex: 1; padding: 12px; background: #f8b531; color: black; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; min-width: 150px;">Fazer Novo Pedido</button>` :
                                `<button style="flex: 1; padding: 12px; background: #666; color: white; border: none; border-radius: 5px; cursor: not-allowed;" disabled>Aguarde a Entrega</button>`
                        }
                        <button onclick="$('#acompanhamento-popup').remove()" style="flex: 1; padding: 12px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer; min-width: 100px;">Fechar</button>
                    </div>
                </div>
            </div>
        `;

        $('body').append(html);
    },

    // Copiar chave PIX
    copiarChavePix(chave) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(chave).then(() => {
                // Feedback visual
                const btn = event.target.closest('button');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                btn.style.background = '#28a745';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '#f8b531';
                }, 2000);
            }).catch(() => {
                // Fallback para navegadores antigos
                this.copiarTextoFallback(chave);
            });
        } else {
            // Fallback para navegadores antigos
            this.copiarTextoFallback(chave);
        }
    },

    // Fallback para copiar texto
    copiarTextoFallback(texto) {
        const textArea = document.createElement('textarea');
        textArea.value = texto;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            this.mostrarModal('Sucesso!', 'Chave PIX copiada!', 'success');
        } catch (err) {
            this.mostrarModal('Chave PIX', 'Não foi possível copiar automaticamente.\n\nChave PIX: ' + texto, 'info');
        }
        document.body.removeChild(textArea);
    },

    // Modal customizado para substituir alert
    mostrarModal(titulo, mensagem, tipo = 'info') {
        const cores = {
            success: '#28a745',
            error: '#dc3545', 
            warning: '#ffc107',
            info: '#17a2b8'
        };

        const icones = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle', 
            info: 'fa-info-circle'
        };

        const html = `
            <div id="modal-custom" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; display: flex; align-items: center; justify-content: center;">
                <div style="background: #1a1a1a; width: 90%; max-width: 400px; border-radius: 15px; padding: 20px; color: white; text-align: center; border-top: 4px solid ${cores[tipo]};">
                    <div style="margin-bottom: 15px;">
                        <i class="fas ${icones[tipo]}" style="font-size: 3rem; color: ${cores[tipo]};"></i>
                    </div>
                    <h4 style="color: ${cores[tipo]}; margin-bottom: 15px;">${titulo}</h4>
                    <p style="margin-bottom: 20px; line-height: 1.5;">${mensagem.replace(/\n/g, '<br>')}</p>
                    <button onclick="$('#modal-custom').remove()" style="background: ${cores[tipo]}; color: ${tipo === 'warning' ? '#000' : '#fff'}; border: none; padding: 12px 30px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        OK
                    </button>
                </div>
            </div>
        `;

        $('body').append(html);
    },

    // Modal de confirmação para substituir confirm
    mostrarConfirmacao(titulo, mensagem, callback) {
        const html = `
            <div id="modal-confirmacao" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; display: flex; align-items: center; justify-content: center;">
                <div style="background: #1a1a1a; width: 90%; max-width: 400px; border-radius: 15px; padding: 20px; color: white; text-align: center;">
                    <div style="margin-bottom: 15px;">
                        <i class="fas fa-question-circle" style="font-size: 3rem; color: #ffc107;"></i>
                    </div>
                    <h4 style="color: #ffc107; margin-bottom: 15px;">${titulo}</h4>
                    <p style="margin-bottom: 20px; line-height: 1.5;">${mensagem}</p>
                    <div style="display: flex; gap: 10px;">
                        <button onclick="$('#modal-confirmacao').remove()" style="flex: 1; background: #6c757d; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">
                            Cancelar
                        </button>
                        <button onclick="$('#modal-confirmacao').remove(); CarrinhoSimples.executarCallback(${callback})" style="flex: 1; background: #dc3545; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        `;

        $('body').append(html);
        
        // Salvar callback para execução
        this._callbackConfirmacao = callback;
    },

    // Executar callback da confirmação
    executarCallback() {
        if (this._callbackConfirmacao) {
            this._callbackConfirmacao();
            this._callbackConfirmacao = null;
        }
    },

    // Cancelar pedido
    cancelarPedido(codigo) {
        this.mostrarConfirmacao('Cancelar Pedido', 'Tem certeza que deseja cancelar seu pedido?', () => {
            $.ajax({
                url: `/cancelar-pedido/${codigo}`,
                method: 'POST',
                success: (response) => {
                    if (response.success) {
                        this.mostrarModal('Sucesso!', 'Pedido cancelado com sucesso!', 'success');
                        localStorage.removeItem('pedido_em_andamento');
                        $('#acompanhamento-popup').remove();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        this.mostrarModal('Erro', 'Erro ao cancelar pedido: ' + response.message, 'error');
                    }
                },
                error: () => {
                    this.mostrarModal('Erro', 'Erro ao cancelar pedido. Tente novamente.', 'error');
                }
            });
        });
    },

    // Mostrar carrinho
    mostrar() {
        // Verificar se há pedido em andamento e se pertence ao cliente
        this.verificarPedidoEmAndamento((temPedido) => {
            if (temPedido) {
                this.mostrarAcompanhamento();
                return;
            }

            // Continuar com a lógica do carrinho
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
            
            if (carrinho.length === 0) {
                alert('Carrinho vazio!');
                return;
            }

            this.exibirCarrinho();
        });
    },

    exibirCarrinho() {
        const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
        
        if (carrinho.length === 0) {
            alert('Carrinho vazio!');
            return;
        }

        let html = `
            <div id="carrinho-popup" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                <div style="background: #1a1a1a; width: 90%; max-width: 500px; border-radius: 15px; padding: 20px; color: white; max-height: 80vh; overflow-y: auto;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #f8b531; margin: 0;">Meu Carrinho</h3>
                        <button onclick="CarrinhoSimples.fechar(); if(window.location.hash === '#menu') { window.location.hash = ''; setTimeout(() => window.location.hash = '#menu', 10); } else { window.location.hash = '#menu'; } setTimeout(() => document.getElementById('menu')?.scrollIntoView({behavior: 'smooth'}), 100);" style="background: #333; border: none; color: white; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 14px;">Voltar ao Menu</button>
                    </div>
                    
                    <div id="carrinho-lista">
        `;

        let total = 0;
        carrinho.forEach((item, index) => {
            const extrasTexto = item.extras.length > 0 ? 
                `<div style="color: #4CAF50; font-size: 12px; margin-top: 3px;">
                    <i class="fas fa-plus-circle"></i> ${item.extras.map(e => `${e.nome} (${e.quantidade}x)`).join(', ')}
                    (+R$ ${item.extras.reduce((t, e) => t + (e.preco * e.quantidade), 0).toFixed(2).replace('.', ',')})
                </div>` : '';

            html += `
                <div style="border-bottom: 1px solid #333; padding: 15px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <div>
                            <h6 style="margin: 0; color: #f8b531;">${item.nome}</h6>
                            ${extrasTexto}
                            ${item.observacoes ? `<div style="color: #888; font-size: 12px; margin-top: 3px;"><i class="fas fa-comment"></i> ${item.observacoes}</div>` : ''}
                        </div>
                        <strong style="color: white;">R$ ${item.total.toFixed(2).replace('.', ',')}</strong>
                    </div>
                    
                    <div style="display: flex; gap: 15px; align-items: center; margin-top: 10px;">
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <label style="color: #ccc; font-size: 12px;">Qtd:</label>
                            <input type="number" value="${item.quantidade}" min="1" max="99" 
                                   style="width: 50px; text-align: center; background: #333; border: 1px solid #555; color: #f8b531; padding: 3px; border-radius: 3px;"
                                   onchange="CarrinhoSimples.alterarQuantidade(${index}, this.value)">
                        </div>
                        
                        <button onclick="CarrinhoSimples.remover(${index})" 
                                style="background: none; border: 1px solid #dc3545; color: #dc3545; padding: 5px 8px; border-radius: 3px; cursor: pointer; font-size: 12px;">
                            ✕ Remover
                        </button>
                    </div>
                </div>
            `;
            
            total += item.total;
        });

        html += `
                    </div>
                    
                    <!-- Tipo de Entrega -->
                    <div style="border-top: 1px solid #333; padding-top: 15px; margin-top: 15px;">
                        <h6 style="color: #f8b531; margin-bottom: 10px;">Tipo de Entrega</h6>
                        <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                            <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; flex: 1; padding: 8px; border: 1px solid #555; border-radius: 5px; background: #2a2a2a;">
                                <input type="radio" name="tipo_entrega" value="entrega" checked style="margin: 0;">
                                <span style="font-size: 12px;"><i class="fas fa-motorcycle"></i> Entrega</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; flex: 1; padding: 8px; border: 1px solid #555; border-radius: 5px; background: #2a2a2a;">
                                <input type="radio" name="tipo_entrega" value="retirada" style="margin: 0;">
                                <span style="font-size: 12px;"><i class="fas fa-store"></i> Retirada</span>
                            </label>
                        </div>
                    </div>

                    <!-- Forma de Pagamento -->
                    <div style="margin-bottom: 15px;">
                        <h6 style="color: #f8b531; margin-bottom: 10px;">Forma de Pagamento</h6>
                        <div id="formas-pagamento" style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <!-- Será carregado via AJAX -->
                        </div>
                        
                        <!-- Campo Troco -->
                        <div id="campo-troco" style="display: none; margin-top: 10px;">
                            <label style="color: #ccc; font-size: 12px; display: block; margin-bottom: 5px;">Troco para:</label>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span style="color: #f8b531;">R$</span>
                                <input type="number" id="troco_para" step="0.01" min="${total.toFixed(2)}" 
                                       style="flex: 1; background: #333; border: 1px solid #555; color: white; padding: 5px; border-radius: 3px;"
                                       placeholder="${total.toFixed(2).replace('.', ',')}">
                            </div>
                            <small style="color: #888; font-size: 11px;">Mínimo: R$ ${total.toFixed(2).replace('.', ',')}</small>
                        </div>
                    </div>
                    
                    <div style="border-top: 2px solid #f8b531; padding-top: 15px; margin-top: 20px;">
                        <div id="linha-entrega" style="display: flex; justify-content: space-between; margin: 5px 0; color: #ccc; font-size: 14px;">
                            <span>Taxa de Entrega:</span>
                            <span id="valor-entrega">Calculando...</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin: 10px 0; font-weight: bold; font-size: 18px; color: #f8b531;">
                            <span>Total:</span>
                            <span id="valor-total">R$ ${total.toFixed(2).replace('.', ',')}</span>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button onclick="CarrinhoSimples.fechar()" style="flex: 1; padding: 12px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer;">Continuar Comprando</button>
                        <button id="btn-finalizar" onclick="CarrinhoSimples.finalizar()" style="flex: 1; padding: 12px; background: #f8b531; color: black; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Finalizar Pedido</button>
                    </div>
                </div>
            </div>
        `;

        $('body').append(html);
        
        // Carregar formas de pagamento
        this.carregarFormasPagamento();
        
        // Configurar eventos
        this.configurarEventos(total);
    },

    // Carregar formas de pagamento
    carregarFormasPagamento() {
        $.ajax({
            url: '/api/formas-pagamento',
            method: 'GET',
            success: (response) => {
                if (response.success && response.data) {
                    let html = '';
                    response.data.forEach(forma => {
                        html += `
                            <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; padding: 8px; border: 1px solid #555; border-radius: 5px; background: #2a2a2a;">
                                <input type="radio" name="forma_pagamento" value="${forma.slug}" data-nome="${forma.nome}" style="margin: 0;">
                                <span style="font-size: 12px;"><i class="${forma.icone}"></i> ${forma.nome}</span>
                            </label>
                        `;
                    });
                    $('#formas-pagamento').html(html);
                }
            },
            error: () => {
                // Fallback com formas básicas
                $('#formas-pagamento').html(`
                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; padding: 8px; border: 1px solid #555; border-radius: 5px; background: #2a2a2a;">
                        <input type="radio" name="forma_pagamento" value="dinheiro" data-nome="Dinheiro" style="margin: 0;">
                        <span style="font-size: 12px;"><i class="fas fa-money-bill"></i> Dinheiro</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; padding: 8px; border: 1px solid #555; border-radius: 5px; background: #2a2a2a;">
                        <input type="radio" name="forma_pagamento" value="pix" data-nome="PIX" style="margin: 0;">
                        <span style="font-size: 12px;"><i class="fas fa-qrcode"></i> PIX</span>
                    </label>
                `);
            }
        });
    },

    // Configurar eventos
    configurarEventos(subtotal) {
        // Calcular taxa de entrega ao carregar
        this.calcularTaxaEntrega(subtotal);

        // Controlar campo troco
        $(document).on('change', 'input[name="forma_pagamento"]', function() {
            if ($(this).val() === 'dinheiro') {
                $('#campo-troco').show();
                $('#troco_para').attr('required', true);
            } else {
                $('#campo-troco').hide();
                $('#troco_para').attr('required', false);
            }
        });

        // Controlar taxa de entrega
        $(document).on('change', 'input[name="tipo_entrega"]', function() {
            const tipoEntrega = $(this).val();
            $('.aviso-entrega').remove(); // Remover avisos anteriores
            
            if (tipoEntrega === 'retirada') {
                $('#linha-entrega').hide();
                $('#valor-total').text('R$ ' + subtotal.toFixed(2).replace('.', ','));
                $('#btn-finalizar').prop('disabled', false).text('Finalizar Pedido');
            } else {
                $('#linha-entrega').show();
                CarrinhoSimples.calcularTaxaEntrega(subtotal);
            }
        });

        // Validar troco
        $(document).on('input', '#troco_para', function() {
            const valorTroco = parseFloat($(this).val()) || 0;
            if (valorTroco < subtotal) {
                $(this).css('border-color', '#dc3545');
            } else {
                $(this).css('border-color', '#555');
            }
        });
    },

    // Calcular taxa de entrega baseada no cliente
    calcularTaxaEntrega(subtotal) {
        const email = localStorage.getItem('cliente_email') || localStorage.getItem('userEmail');
        
        console.log('Email encontrado:', email); // Debug
        
        if (!email) {
            // Usuário não logado - taxa padrão
            const taxaPadrao = 5.00;
            $('#valor-entrega').text('R$ ' + taxaPadrao.toFixed(2).replace('.', ','));
            $('#valor-total').text('R$ ' + (subtotal + taxaPadrao).toFixed(2).replace('.', ','));
            $('#btn-finalizar').prop('disabled', false).text('Finalizar Pedido');
            console.log('Usuário não logado - taxa padrão:', taxaPadrao);
            return;
        }

        // Mostrar loading
        $('#valor-entrega').text('Calculando...');
        $('#btn-finalizar').prop('disabled', true).text('Calculando...');

        console.log('Consultando API para email:', email); // Debug

        // Buscar taxa do cliente
        $.ajax({
            url: '/taxa-entrega-email',
            method: 'POST',
            data: { email: email },
            success: (response) => {
                console.log('Resposta da API:', response); // Debug
                
                if (response.success && response.pode_entregar) {
                    const taxa = parseFloat(response.taxa_entrega);
                    const total = subtotal + taxa;
                    
                    $('#valor-entrega').text('R$ ' + taxa.toFixed(2).replace('.', ','));
                    $('#valor-total').text('R$ ' + total.toFixed(2).replace('.', ','));
                    $('#btn-finalizar').prop('disabled', false).text('Finalizar Pedido');
                    
                    console.log('Taxa aplicada:', taxa);
                } else {
                    // Não entrega no bairro
                    $('#valor-entrega').text('Não disponível');
                    $('#valor-total').text('R$ ' + subtotal.toFixed(2).replace('.', ','));
                    $('#btn-finalizar').prop('disabled', true).text('Não entregamos neste local');
                    
                    // Remover avisos anteriores
                    $('.aviso-entrega').remove();
                    
                    // Mostrar aviso
                    if (!response.pode_entregar) {
                        const cliente = response.cliente || {};
                        const aviso = `
                            <div class="aviso-entrega" style="background: #dc3545; color: white; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 12px;">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Não entregamos em ${cliente.bairro || 'seu bairro'}, ${cliente.cidade || 'sua cidade'}.
                                Selecione "Retirada" ou atualize seu endereço.
                            </div>
                        `;
                        $('#linha-entrega').after(aviso);
                    }
                    
                    console.log('Entrega bloqueada:', response.message);
                }
            },
            error: (xhr, status, error) => {
                console.error('Erro na API:', xhr.responseText, status, error); // Debug
                
                // Erro na API - usar taxa padrão
                const taxaPadrao = 5.00;
                $('#valor-entrega').text('R$ ' + taxaPadrao.toFixed(2).replace('.', ','));
                $('#valor-total').text('R$ ' + (subtotal + taxaPadrao).toFixed(2).replace('.', ','));
                $('#btn-finalizar').prop('disabled', false).text('Finalizar Pedido');
            }
        });
    },

    // Fechar carrinho
    fechar() {
        $('#carrinho-popup').remove();
    },

    // Alterar quantidade
    alterarQuantidade(index, novaQtd) {
        const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
        const item = carrinho[index];
        
        if (item && novaQtd > 0) {
            item.quantidade = parseInt(novaQtd);
            const totalExtras = item.extras.reduce((total, e) => total + (e.preco * e.quantidade), 0);
            item.total = (item.preco + totalExtras) * item.quantidade;
            
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            
            this.fechar();
            setTimeout(() => this.mostrar(), 50);
        }
    },

    // Remover item
    remover(index) {
        let carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
        
        if (index < 0 || index >= carrinho.length) {
            console.error('Índice inválido:', index);
            return;
        }
        
        // Remover item do array
        carrinho.splice(index, 1);
        
        // Salvar novo estado ou limpar completamente se vazio
        if (carrinho.length === 0) {
            localStorage.removeItem('carrinho');
        } else {
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
        }
        
        // Sincronizar com CarrinhoMenu se existir
        if (window.CarrinhoMenu) {
            window.CarrinhoMenu.itens = [...carrinho];
            window.CarrinhoMenu.salvarCarrinho();
        }
        
        // Fechar popup
        this.fechar();
        
        // Reabrir se ainda houver itens
        if (carrinho.length > 0) {
            setTimeout(() => this.mostrar(), 100);
        } else {
            // Forçar limpeza completa
            localStorage.removeItem('carrinho');
            if (window.CarrinhoMenu) {
                window.CarrinhoMenu.itens = [];
                window.CarrinhoMenu.salvarCarrinho();
            }
        }
    },

    // Finalizar pedido
    async finalizar() {
        const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
        if (carrinho.length === 0) return;

        const subtotal = carrinho.reduce((sum, item) => sum + item.total, 0);

        // Validar valor mínimo dinâmico
        try {
            const response = await fetch('/api/configuracao/preco-minimo');
            const data = await response.json();
            
            if (data.success && data.preco_minimo > 0) {
                if (subtotal < data.preco_minimo) {
                    const valorFaltante = data.preco_minimo - subtotal;
                    this.mostrarModal(
                        'Valor Mínimo', 
                        `Valor mínimo para pedido é R$ ${data.preco_minimo.toFixed(2).replace('.', ',')}.<br>` +
                        `Adicione mais R$ ${valorFaltante.toFixed(2).replace('.', ',')} em produtos.`, 
                        'warning'
                    );
                    return;
                }
            }
        } catch (error) {
            console.error('Erro ao verificar preço mínimo:', error);
        }

        // Validar se botão não está desabilitado (não entrega no local)
        if ($('#btn-finalizar').prop('disabled')) {
            this.mostrarModal('Entrega Indisponível', 'Não é possível finalizar: não entregamos neste local. Selecione "Retirada".', 'warning');
            return;
        }

        // Validar forma de pagamento
        const formaPagamento = $('input[name="forma_pagamento"]:checked').val();
        if (!formaPagamento) {
            this.mostrarModal('Forma de Pagamento', 'Selecione uma forma de pagamento', 'warning');
            return;
        }

        // Validar troco se for dinheiro
        if (formaPagamento === 'dinheiro') {
            const trocoValue = parseFloat($('#troco_para').val()) || 0;
            if (trocoValue < subtotal) {
                this.mostrarModal('Troco Inválido', 'O valor do troco deve ser maior ou igual ao total do pedido', 'warning');
                return;
            }
        }

        // Coletar dados do pedido
        const tipoEntrega = $('input[name="tipo_entrega"]:checked').val();
        const taxaEntrega = tipoEntrega === 'entrega' ? 
            parseFloat($('#valor-entrega').text().replace('R$ ', '').replace(',', '.')) || 0 : 0;

        const dadosPedido = {
            email: localStorage.getItem('cliente_email'),
            itens: carrinho,
            tipo_entrega: tipoEntrega,
            forma_pagamento: formaPagamento,
            troco_para: formaPagamento === 'dinheiro' ? parseFloat($('#troco_para').val()) : null,
            subtotal: subtotal,
            taxa_entrega: taxaEntrega
        };

        // Desabilitar botão durante processamento
        $('#btn-finalizar').prop('disabled', true).text('Processando...');

        // Enviar pedido
        $.ajax({
            url: '/finalizar-pedido',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(dadosPedido),
            success: (response) => {
                if (response.success) {
                    // Limpar carrinho completamente
                    localStorage.setItem('carrinho', JSON.stringify([]));
                    
                    // Sincronizar com CarrinhoMenu
                    if (window.CarrinhoMenu) {
                        window.CarrinhoMenu.itens = [];
                    }
                    
                    // Salvar pedido em andamento
                    localStorage.setItem('pedido_em_andamento', response.pedido_codigo);
                    
                    // Fechar popup do carrinho primeiro
                    $('#carrinho-popup').remove();
                    
                    // Mostrar popup do pedido automaticamente
                    if (response.pedido) {
                        CarrinhoSimples.exibirPopupAcompanhamento(
                            response.pedido, 
                            response.itens || [], 
                            response.chave_pix, 
                            response.qrcode_image
                        );
                    } else {
                        // Fallback: buscar dados do pedido
                        $.ajax({
                            url: `/acompanhar-pedido/${response.pedido_codigo}`,
                            method: 'GET',
                            success: (pedidoResponse) => {
                                if (pedidoResponse.success) {
                                    CarrinhoSimples.exibirPopupAcompanhamento(
                                        pedidoResponse.pedido, 
                                        pedidoResponse.itens, 
                                        pedidoResponse.chave_pix, 
                                        pedidoResponse.qrcode_image
                                    );
                                }
                            }
                        });
                    }
                } else {
                    this.mostrarModal('Erro', 'Erro ao processar pedido: ' + response.message, 'error');
                    $('#btn-finalizar').prop('disabled', false).text('Finalizar Pedido');
                }
            },
            error: (xhr) => {
                console.error('Erro na requisição:', xhr);
                this.mostrarModal('Erro', 'Erro ao processar pedido. Tente novamente.', 'error');
                $('#btn-finalizar').prop('disabled', false).text('Finalizar Pedido');
            }
        });
    },

    async abrirSuporte(codigoPedido, pedidoId) {
        let telefoneAtual = '';
        let nomeAtual = '';
        
        const emailCliente = localStorage.getItem('cliente_email');
        
        if (emailCliente) {
            try {
                const response = await fetch('/cliente/dados', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: emailCliente })
                });
                const data = await response.json();
                if (data.sucesso && data.cliente) {
                    telefoneAtual = data.cliente.telefone || '';
                    nomeAtual = data.cliente.nome || '';
                }
            } catch (e) {
                // Silencioso
            }
        }

        if (!telefoneAtual) {
            telefoneAtual = localStorage.getItem('cliente_telefone') || '';
        }
        if (!nomeAtual) {
            nomeAtual = localStorage.getItem('cliente_nome') || '';
        }

        const html = `
            <div id="suporte-popup" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 10000; display: flex; align-items: center; justify-content: center;">
                <div style="background: #1a1a1a; width: 90%; max-width: 450px; border-radius: 15px; padding: 25px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #ff9800; margin: 0;"><i class="fas fa-headset"></i> Suporte</h3>
                        <button onclick="$('#suporte-popup').remove()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer;">×</button>
                    </div>
                    
                    <p style="color: #ccc; margin-bottom: 15px;">Pedido: <strong style="color: #f8b531;">${codigoPedido}</strong></p>
                    
                    <label style="display: block; margin-bottom: 8px; color: #f8b531;">Seu WhatsApp (para contato):</label>
                    <input type="tel" id="telefone-suporte" value="${telefoneAtual}" placeholder="(00) 00000-0000" 
                           style="width: 100%; padding: 12px; background: #2a2a2a; color: white; border: 1px solid #444; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
                    
                    <label style="display: block; margin-bottom: 8px; color: #f8b531;">Qual a razão do contato:</label>
                    <textarea id="mensagem-suporte" rows="5" placeholder="Descreva o motivo do seu contato..."
                              style="width: 100%; padding: 12px; background: #2a2a2a; color: white; border: 1px solid #444; border-radius: 5px; margin-bottom: 20px; font-size: 14px; resize: vertical;"></textarea>
                    
                    <div style="display: flex; gap: 10px;">
                        <button onclick="$('#suporte-popup').remove()" style="flex: 1; padding: 12px; background: #666; color: white; border: none; border-radius: 5px; cursor: pointer;">Cancelar</button>
                        <button onclick="CarrinhoSimples.enviarSuporte('${codigoPedido}', ${pedidoId})" style="flex: 1; padding: 12px; background: #ff9800; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;"><i class="fas fa-paper-plane"></i> Enviar</button>
                    </div>
                </div>
            </div>
        `;

        $('body').append(html);
    },

    async enviarSuporte(codigoPedido, pedidoId) {
        const telefone = $('#telefone-suporte').val().trim();
        const mensagem = $('#mensagem-suporte').val().trim();
        
        if (!telefone) {
            alert('Por favor, informe seu WhatsApp para contato.');
            return;
        }

        if (!mensagem) {
            alert('Por favor, descreva o motivo do contato.');
            return;
        }

        let clienteNome = '';
        const emailCliente = localStorage.getItem('cliente_email');
        
        if (emailCliente) {
            try {
                const response = await fetch('/cliente/dados', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: emailCliente })
                });
                const data = await response.json();
                if (data.sucesso && data.cliente) {
                    clienteNome = data.cliente.nome || '';
                }
            } catch (e) {
                // Silencioso
            }
        }

        if (!clienteNome) {
            clienteNome = localStorage.getItem('cliente_nome') || 'Cliente';
        }
        
        localStorage.setItem('cliente_telefone', telefone);
        localStorage.setItem('cliente_nome', clienteNome);

        $.ajax({
            url: '/suporte/criar',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                pedido_id: pedidoId,
                codigo_pedido: codigoPedido,
                cliente_nome: clienteNome,
                cliente_telefone: telefone,
                razao: mensagem
            }),
            success: (response) => {
                if (response.success) {
                    $('#suporte-popup').remove();
                    this.mostrarModal('Sucesso', 'Sua solicitação de suporte foi enviada! Em breve entraremos em contato.', 'success');
                } else {
                    alert('Erro ao enviar suporte: ' + response.message);
                }
            },
            error: () => {
                alert('Erro ao enviar suporte. Tente novamente.');
            }
        });
    }
};

// Adicionar botão do carrinho se não existir
$(document).ready(() => {
    // Remover botão flutuante - comentado
    /*
    if ($('#btn-carrinho').length === 0) {
        $('body').append(`
            <div id="btn-carrinho" style="position: fixed; bottom: 20px; right: 20px; background: #f8b531; color: black; padding: 15px; border-radius: 50px; cursor: pointer; box-shadow: 0 4px 8px rgba(0,0,0,0.3); z-index: 1000;" onclick="CarrinhoSimples.mostrar()">
                <i class="fas fa-shopping-cart"></i>
                <span id="carrinho-contador" style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; position: absolute; top: -5px; right: -5px;">0</span>
            </div>
        `);
    }

    // Atualizar contador
    function atualizarContador() {
        const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
        const total = carrinho.reduce((sum, item) => sum + item.quantidade, 0);
        $('#carrinho-contador').text(total);
    }

    // Atualizar contador a cada segundo
    setInterval(atualizarContador, 1000);
    atualizarContador();
    */
});

// Função global para compatibilidade com código antigo
window.abrirCarrinhoPopup = function() {
    CarrinhoSimples.mostrar();
};

console.log('✅ Carrinho Simples carregado');
