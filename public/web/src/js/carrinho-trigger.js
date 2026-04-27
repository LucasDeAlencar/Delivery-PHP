$(document).ready(function() {
    // Evento para abrir carrinho ao clicar no ícone da navbar
    $(document).on('click', '.carrinho-navbar a, .fa-shopping-cart, .fa-receipt', function(e) {
        e.preventDefault();
        
        // Verificar se há pedido ativo para este usuário
        verificarPedidoAtivo().then(codigoPedido => {
            if (codigoPedido) {
                mostrarStatusPedido(codigoPedido);
            } else {
                abrirCarrinhoNormal();
            }
        });
    });
    
    // Verificar pedido ativo do usuário atual
    function verificarPedidoAtivo() {
        return new Promise((resolve) => {
            // Buscar email do usuário logado
            $.ajax({
                url: '/api/usuario-atual',
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(response) {
                    if (response.sucesso && response.email) {
                        const chaveUsuario = `pedido_ativo_${response.email}`;
                        const codigoPedido = localStorage.getItem(chaveUsuario);
                        resolve(codigoPedido);
                    } else {
                        resolve(null);
                    }
                },
                error: function() {
                    resolve(null);
                }
            });
        });
    }
    
    // Mostrar status do pedido
    function mostrarStatusPedido(codigo) {
        $.ajax({
            url: '/api/status-pedido',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({ codigo: codigo }),
            xhrFields: {
                withCredentials: true
            },
            success: function(response) {
                if (response.sucesso) {
                    criarPopupStatusPedido(response.pedido);
                } else {
                    // Pedido não encontrado, limpar localStorage e mostrar carrinho
                    limparPedidoAtivo();
                    abrirCarrinhoNormal();
                }
            },
            error: function() {
                limparPedidoAtivo();
                abrirCarrinhoNormal();
            }
        });
    }
    
    // Criar popup de status do pedido
    function criarPopupStatusPedido(pedido) {
        const statusTexto = {
            'pendente': 'Aguardando Confirmação',
            'confirmado': 'Pedido Confirmado',
            'preparando': 'Em Preparação',
            'saiu_entrega': 'Saiu para Entrega',
            'finalizado': 'Finalizado',
            'cancelado': 'Cancelado'
        };
        
        const statusCor = {
            'pendente': '#ffc107',
            'confirmado': '#17a2b8',
            'preparando': '#fd7e14',
            'saiu_entrega': '#6f42c1',
            'finalizado': '#28a745',
            'cancelado': '#dc3545'
        };
        
        const popup = `
            <div id="carrinho-popup" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                <div style="background: #1a1a1a; width: 90%; max-width: 500px; border-radius: 15px; padding: 20px; color: white; text-align: center;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #f8b531; margin: 0;">Status do Pedido</h3>
                        <button onclick="$('#carrinho-popup').remove()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer;">&times;</button>
                    </div>
                    
                    <div style="font-size: 3rem; margin-bottom: 20px;">📋</div>
                    
                    <h4 style="color: #f8b531; margin-bottom: 10px;">Pedido ${pedido.codigo}</h4>
                    
                    <div style="background: ${statusCor[pedido.status]}; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; font-weight: bold;">
                        ${statusTexto[pedido.status] || pedido.status}
                    </div>
                    
                    <div style="text-align: left; background: #2d2d2d; padding: 20px; border-radius: 8px; margin: 20px 0;">
                        <p><strong>Cliente:</strong> ${pedido.nome_cliente}</p>
                        <p><strong>Total:</strong> R$ ${parseFloat(pedido.valor_total).toFixed(2).replace('.', ',')}</p>
                        <p><strong>Data:</strong> ${new Date(pedido.criado_em).toLocaleString('pt-BR')}</p>
                    </div>
                    
                    ${pedido.status === 'finalizado' || pedido.status === 'cancelado' ? 
                        '<button onclick="finalizarAcompanhamento()" style="padding: 12px 20px; background: #f8b531; color: black; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 20px;">Fazer Novo Pedido</button>' : 
                        '<p style="color: #ccc; margin-top: 20px;">Acompanhe o status do seu pedido aqui</p>'
                    }
                    
                    <div style="margin-top: 20px;">
                        <button onclick="$('#carrinho-popup').remove()" style="padding: 12px 20px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer;">Fechar</button>
                    </div>
                </div>
            </div>
        `;
        
        $('#carrinho-popup').remove();
        $('body').append(popup);
    }
    
    // Limpar pedido ativo do usuário atual
    function limparPedidoAtivo() {
        $.ajax({
            url: '/api/usuario-atual',
            method: 'GET',
            xhrFields: {
                withCredentials: true
            },
            success: function(response) {
                if (response.sucesso && response.email) {
                    const chaveUsuario = `pedido_ativo_${response.email}`;
                    localStorage.removeItem(chaveUsuario);
                    $('.fa-receipt').removeClass('fa-receipt').addClass('fa-shopping-cart');
                }
            }
        });
    }
    
    // Abrir carrinho normal
    function abrirCarrinhoNormal() {
        
        // Remover popup existente
        $('#carrinho-popup').remove();
        
        // Criar popup simples
        const popup = `
            <div id="carrinho-popup" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                <div style="background: #1a1a1a; width: 90%; max-width: 500px; border-radius: 15px; padding: 20px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #f8b531; margin: 0;">Meu Carrinho</h3>
                        <button onclick="$('#carrinho-popup').remove()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer;">&times;</button>
                    </div>
                    
                    <div id="carrinho-lista">
                        <p>Carregando...</p>
                    </div>
                    
                    <div style="margin: 20px 0;">
                        <h4 style="color: #f8b531;">Forma de Recebimento:</h4>
                        <label style="display: block; margin: 10px 0; cursor: pointer;">
                            <input type="radio" name="tipoEntrega" value="retirada" style="margin-right: 10px;">
                            Retirada na Loja
                        </label>
                        <label style="display: block; margin: 10px 0; cursor: pointer;">
                            <input type="radio" name="tipoEntrega" value="entrega" style="margin-right: 10px;">
                            Entrega
                        </label>
                    </div>
                    
                    <div style="border-top: 2px solid #f8b531; padding-top: 15px; margin-top: 20px;">
                        <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                            <span>Subtotal:</span>
                            <span id="subtotal">R$ 0,00</span>
                        </div>
                        <div id="taxa-entrega-linha" style="display: none; display: flex; justify-content: space-between; margin: 5px 0;">
                            <span>Taxa de Entrega:</span>
                            <span id="taxa-entrega">R$ 0,00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin: 10px 0; font-weight: bold; font-size: 18px; color: #f8b531; border-top: 1px solid #333; padding-top: 10px;">
                            <span>Total:</span>
                            <span id="total-final">R$ 0,00</span>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button onclick="$('#carrinho-popup').remove()" style="flex: 1; padding: 12px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer;">Cancelar</button>
                        <button id="btn-finalizar" style="flex: 1; padding: 12px; background: #f8b531; color: black; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Finalizar Compra</button>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(popup);
        
        // Carregar itens
        setTimeout(function() {
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
            let html = '';
            let subtotal = 0;
            
            if (carrinho.length === 0) {
                html = '<p style="text-align: center; color: #666; padding: 20px;">Seu carrinho está vazio</p>';
            } else {
                carrinho.forEach((item, index) => {
                    subtotal += item.preco * item.quantidade;
                    html += `
                        <div style="border-bottom: 1px solid #333; padding: 15px 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <div>
                                    <h6 style="margin: 0; color: #f8b531;">${item.nome}</h6>
                                    ${item.observacoes ? `<small style="color: #ccc;">Obs: ${item.observacoes}</small>` : ''}
                                </div>
                                <strong style="color: white;">R$ ${(item.preco * item.quantidade).toFixed(2).replace('.', ',')}</strong>
                            </div>
                            
                            <div style="display: flex; gap: 15px; align-items: flex-start; margin-top: 10px;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 5px; min-width: 80px;">
                                    <label style="color: #ccc; font-size: 12px;">Qtd:</label>
                                    <input type="number" value="${item.quantidade}" min="1" max="99" data-index="${index}" 
                                           style="width: 50px; text-align: center; background: #333; border: 1px solid #555; color: #f8b531; padding: 3px; border-radius: 3px;"
                                           onchange="alterarQuantidadeItem(${index}, this.value)">
                                </div>
                                
                                <div style="flex: 1; display: flex; flex-direction: column; gap: 5px;">
                                    <label style="color: #ccc; font-size: 12px;">Observações:</label>
                                    <textarea rows="2" placeholder="Observações..." data-index="${index}"
                                              style="width: 100%; background: #333; border: 1px solid #555; color: #fff; padding: 5px; border-radius: 3px; resize: none; font-size: 12px;"
                                              onblur="alterarObservacoesItem(${index}, this.value)">${item.observacoes || ''}</textarea>
                                </div>
                                
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 5px; min-width: 60px;">
                                    <label style="color: #ccc; font-size: 12px;">Ações:</label>
                                    <button onclick="removerItem(${index})" title="Remover item"
                                            style="background: none; border: 1px solid #dc3545; color: #dc3545; padding: 5px 8px; border-radius: 3px; cursor: pointer; font-size: 12px;">
                                        ✕
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            $('#carrinho-lista').html(html);
            $('#subtotal').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
            $('#total-final').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
            
            // Configurar eventos
            configurarEventosCarrinho();
        }, 100);
    }
    
    // Verificar pedido ativo ao carregar página
    $(document).ready(function() {
        verificarPedidoAtivo().then(codigoPedido => {
            if (codigoPedido) {
                $('.fa-shopping-cart').removeClass('fa-shopping-cart').addClass('fa-receipt').attr('title', 'Ver Status do Pedido');
            }
        });
    });
    
    // Configurar eventos do carrinho
    function configurarEventosCarrinho() {
        // Evento para mudança de tipo de entrega
        $('input[name="tipoEntrega"]').on('change', function() {
            calcularTotalCarrinho();
        });
        
        // Evento para finalizar compra
        $('#btn-finalizar').off('click').on('click', function() {
            finalizarCompraCarrinho();
        });
    }
    
    // Calcular total com taxa de entrega
    function calcularTotalCarrinho() {
        const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
        const tipoEntrega = $('input[name="tipoEntrega"]:checked').val();
        
        let subtotal = 0;
        carrinho.forEach(item => {
            subtotal += item.preco * item.quantidade;
        });
        
        $('#subtotal').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
        
        if (tipoEntrega === 'entrega') {
            const email = localStorage.getItem('cliente_email');
            
            if (!email) {
                $('#taxa-entrega-linha').hide();
                $('#total-final').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
                $('#btn-finalizar').prop('disabled', true).text('Faça login para entrega');
                return;
            }

            
            $.ajax({
                url: '/api/verificar-entrega',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ email: email }),
                xhrFields: {
                    withCredentials: true
                },
                success: function(response) {
                    if (response.disponivel) {
                        const taxa = parseFloat(response.valor_entrega) || 0;
                        $('#taxa-entrega-linha').show();
                        $('#taxa-entrega').text(`R$ ${taxa.toFixed(2).replace('.', ',')}`);
                        
                        const total = subtotal + taxa;
                        $('#total-final').text(`R$ ${total.toFixed(2).replace('.', ',')}`);
                        $('#btn-finalizar').prop('disabled', false).text('Finalizar Compra');
                    } else {
                        $('#taxa-entrega-linha').hide();
                        $('#total-final').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
                        $('#btn-finalizar').prop('disabled', true).text('Entrega Indisponível');
                        alert(response.mensagem);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na verificação de entrega:', xhr.responseText);
                    $('#taxa-entrega-linha').hide();
                    $('#total-final').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
                    $('#btn-finalizar').prop('disabled', true).text('Erro na Verificação');
                }
            });
        } else {
            $('#taxa-entrega-linha').hide();
            $('#total-final').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
            $('#btn-finalizar').prop('disabled', false).text('Finalizar Compra');
        }
    }
    
    // Finalizar compra
    function finalizarCompraCarrinho() {
        const tipoEntrega = $('input[name="tipoEntrega"]:checked').val();
        
        if (!tipoEntrega) {
            alert('Por favor, selecione uma forma de recebimento.');
            return;
        }
        
        const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
        
        if (carrinho.length === 0) {
            alert('Seu carrinho está vazio.');
            return;
        }
        
        let subtotal = 0;
        carrinho.forEach(item => {
            subtotal += item.preco * item.quantidade;
        });
        
        const taxaEntrega = tipoEntrega === 'entrega' ? parseFloat($('#taxa-entrega').text().replace('R$ ', '').replace(',', '.')) || 0 : 0;
        const total = subtotal + taxaEntrega;
        
        const email = localStorage.getItem('cliente_email');
        if (!email) {
            alert('Faça login para finalizar o pedido.');
            return;
        }
        
        const pedido = {
            email: email,
            itens: carrinho,
            tipo_entrega: tipoEntrega,
            subtotal: subtotal,
            taxa_entrega: taxaEntrega,
            total: total
        };
        
        $.ajax({
            url: '/api/finalizar-pedido',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(pedido),
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function() {
                $('#btn-finalizar').prop('disabled', true).text('Processando...');
            },
            success: function(response) {
                if (response.sucesso) {
                    // Salvar código do pedido com chave única do usuário
                    const chaveUsuario = `pedido_ativo_${response.usuario_email || 'user'}`;
                    localStorage.setItem(chaveUsuario, response.codigo_pedido);
                    
                    localStorage.removeItem('carrinho');
                    
                    if (response.whatsapp_url) {
                        window.open(response.whatsapp_url, '_blank');
                    }
                    
                    alert('Pedido enviado com sucesso! Código: ' + response.codigo_pedido);
                    $('#carrinho-popup').remove();
                    window.location.reload();
                } else {
                    alert('Erro: ' + response.mensagem);
                    $('#btn-finalizar').prop('disabled', false).text('Finalizar Compra');
                }
            },
            error: function() {
                alert('Erro ao processar pedido. Tente novamente.');
                $('#btn-finalizar').prop('disabled', false).text('Finalizar Compra');
            }
        });
    }
});

// Funções globais para edição
window.alterarQuantidadeItem = function(index, novaQtd) {
    const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
    const qtd = Math.min(Math.max(parseInt(novaQtd) || 1, 1), 99);
    
    if (carrinho[index]) {
        carrinho[index].quantidade = qtd;
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        
        // Atualizar sistema principal
        if (typeof window.Carrinho !== 'undefined') {
            window.Carrinho.carregarDoLocalStorage();
            window.Carrinho.atualizarContador();
        }
        
        // Recarregar popup
        setTimeout(() => {
            $('.fa-shopping-cart').click();
        }, 100);
    }
};

window.alterarObservacoesItem = function(index, novasObs) {
    const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
    
    if (carrinho[index]) {
        carrinho[index].observacoes = novasObs.trim();
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        
        if (typeof window.Carrinho !== 'undefined') {
            window.Carrinho.carregarDoLocalStorage();
        }
    }
};

window.removerItem = function(index) {
    const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
    
    if (carrinho[index] && confirm(`Remover "${carrinho[index].nome}" do carrinho?`)) {
        carrinho.splice(index, 1);
        localStorage.removeItem('carrinho');
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        
        if (typeof window.Carrinho !== 'undefined') {
            window.Carrinho.carregarDoLocalStorage();
            window.Carrinho.atualizarContador();
        }
        
        // Recarregar popup
        setTimeout(() => {
            $('.fa-shopping-cart').click();
        }, 100);
    }
};

// Função global para finalizar acompanhamento
window.finalizarAcompanhamento = function() {
    $.ajax({
        url: '/api/usuario-atual',
        method: 'GET',
        success: function(response) {
            if (response.sucesso && response.email) {
                const chaveUsuario = `pedido_ativo_${response.email}`;
                localStorage.removeItem(chaveUsuario);
                $('.fa-receipt').removeClass('fa-receipt').addClass('fa-shopping-cart');
            }
        }
    });
    $('#carrinho-popup').remove();
    window.location.reload();
};
