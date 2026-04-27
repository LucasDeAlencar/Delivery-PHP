/**
 * Sistema de Popup do Carrinho
 */

let carrinhoPopupAberto = false;

// Função global para abrir o popup do carrinho
window.abrirCarrinhoPopup = function() {
    
    if (carrinhoPopupAberto) {
        return;
    }
    
    // Verificar se há pedido ativo
    const codigoPedido = localStorage.getItem('codigo_pedido_ativo');
    
    if (codigoPedido) {
        mostrarStatusPedido(codigoPedido);
        return;
    }
    
    criarPopupCarrinho();
    carregarItensCarrinho();
    carrinhoPopupAberto = true;
};

function criarPopupCarrinho() {
    const popupHTML = `
        <div id="carrinho-popup" class="carrinho-popup-overlay">
            <div class="carrinho-popup-container">
                <div class="carrinho-header">
                    <h3>Meu Carrinho</h3>
                    <button onclick="fecharCarrinhoEVoltarMenu()" class="btn-fechar" title="Voltar ao Menu"><i class="fas fa-arrow-left"></i> Voltar ao Menu</button>
                </div>
                
                <div class="carrinho-content">
                    <div id="carrinho-lista" class="carrinho-lista"></div>
                    
                    <div class="entrega-opcoes">
                        <h4>Forma de Recebimento:</h4>
                        <div class="opcoes-radio">
                            <label>
                                <input type="radio" name="tipoEntrega" value="retirada" onchange="calcularTotal(); verificarMesas()">
                                <span>Retirada na Loja</span>
                            </label>
                            <label>
                                <input type="radio" name="tipoEntrega" value="entrega" onchange="calcularTotal()">
                                <span>Entrega</span>
                            </label>
                        </div>
                        <div id="opcoes-retirada" class="mt-3" style="display: none;">
                            <h5>Escolha o local de retirada:</h5>
                            <div id="lista-locais-retirada" class="opcoes-radio"></div>
                        </div>
                    </div>
                    
                    <div class="carrinho-total">
                        <div class="total-linha">
                            <span>Subtotal:</span>
                            <span id="subtotal">R$ 0,00</span>
                        </div>
                        <div class="total-linha" id="taxa-entrega-linha" style="display: none;">
                            <span>Taxa de Entrega:</span>
                            <span id="taxa-entrega">R$ 0,00</span>
                        </div>
                        <div class="total-linha total-final">
                            <span>Total:</span>
                            <span id="total-final">R$ 0,00</span>
                        </div>
                    </div>
                </div>
                
                <div class="carrinho-footer">
                    <button onclick="fecharCarrinhoEVoltarMenu()" class="btn btn-cancelar">Cancelar</button>
                    <button onclick="finalizarCompra()" class="btn btn-finalizar">Finalizar Compra</button>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(popupHTML);
}

function carregarItensCarrinho() {
    // Limpar e recarregar do localStorage - sempre buscar dados frescos
    let carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
    
    // Validação rigorosa: garantir que cada item tem ID único e dados válidos
    carrinho = carrinho.filter(item => item && item.id && item.nome && typeof item.preco === 'number' && typeof item.quantidade === 'number');
    
    // Re-salvar carrinho limpo
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    
    const lista = $('#carrinho-lista');
    
    if (carrinho.length === 0) {
        lista.html('<p class="carrinho-vazio">Seu carrinho está vazio</p>');
        if (typeof window.Carrinho !== 'undefined') {
            window.Carrinho.carregarDoLocalStorage();
            window.Carrinho.atualizarContador();
        }
        return;
    }
    
    let html = '';
    carrinho.forEach((item) => {
        html += `
            <div class="carrinho-item" data-item-id="${item.id}">
                <div class="item-header">
                    <div class="item-info">
                        <h5>${item.nome}</h5>
                        ${item.observacoes ? `<small class="text-muted">Obs: ${item.observacoes}</small>` : ''}
                    </div>
                    <div class="item-preco">R$ ${(item.preco * item.quantidade).toFixed(2).replace('.', ',')}</div>
                </div>
                
                <div class="item-controls">
                    <div class="quantidade-control">
                        <label>Qtd:</label>
                        <input type="number" class="qty-input" value="${item.quantidade}" min="1" max="99" data-item-id="${item.id}">
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
    
    lista.html(html);
    configurarEventosEdicao();
    calcularTotal();
    
    if (typeof window.Carrinho !== 'undefined') {
        window.Carrinho.carregarDoLocalStorage();
        window.Carrinho.atualizarContador();
    }
}

function configurarEventosEdicao() {
    // Alterar quantidade manualmente
    $('.qty-input').off('change').on('change', function() {
        const itemId = $(this).data('item-id');
        const novaQtd = Math.min(Math.max(parseInt($(this).val()) || 1, 1), 99);
        $(this).val(novaQtd);
        definirQuantidade(itemId, novaQtd);
    });
    
    // Alterar observações
    $('.observacoes-input').off('blur').on('blur', function() {
        const itemId = $(this).data('item-id');
        const novasObs = $(this).val().trim();
        alterarObservacoes(itemId, novasObs);
    });
    
    // Remover item
    $('.btn-remover').off('click').on('click', function() {
        const itemId = $(this).data('item-id');
        removerItem(itemId);
    });
}

function alterarQuantidade(itemId, delta) {
    let carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
    
    const index = carrinho.findIndex(item => item.id === itemId);
    if (index !== -1 && carrinho[index]) {
        const novaQtd = Math.min(Math.max(carrinho[index].quantidade + delta, 1), 99);
        carrinho[index].quantidade = novaQtd;
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        carregarItensCarrinho();
    }
}

function definirQuantidade(itemId, quantidade) {
    let carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
    
    const index = carrinho.findIndex(item => item.id === itemId);
    if (index !== -1 && carrinho[index]) {
        carrinho[index].quantidade = quantidade;
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        carregarItensCarrinho();
    }
}

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
                // Se pedido não encontrado, limpar localStorage e mostrar carrinho normal
                localStorage.removeItem('codigo_pedido_ativo');
                abrirCarrinhoPopup();
            }
        },
        error: function() {
            // Em caso de erro, mostrar carrinho normal
            localStorage.removeItem('codigo_pedido_ativo');
            abrirCarrinhoPopup();
        }
    });
}

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
    
    const popupHTML = `
        <div id="carrinho-popup" class="carrinho-popup-overlay">
            <div class="carrinho-popup-container">
                <div class="carrinho-header">
                    <h3>Status do Pedido</h3>
                    <button onclick="fecharCarrinhoEVoltarMenu()" class="btn-fechar" title="Voltar ao Menu"><i class="fas fa-arrow-left"></i> Voltar ao Menu</button>
                </div>
                
                <div class="carrinho-content" style="text-align: center; padding: 30px;">
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
                        '<button onclick="finalizarAcompanhamento()" class="btn btn-warning" style="margin-top: 20px;">Fazer Novo Pedido</button>' : 
                        '<p style="color: #ccc; margin-top: 20px;">Acompanhe o status do seu pedido aqui</p>'
                    }
                </div>
                
                <div class="carrinho-footer">
                    <button onclick="fecharCarrinhoEVoltarMenu()" class="btn btn-cancelar">Fechar</button>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(popupHTML);
    carrinhoPopupAberto = true;
}

function finalizarAcompanhamento() {
    localStorage.removeItem('codigo_pedido_ativo');
    fecharCarrinhoPopup();
    // Recarregar para mostrar carrinho normal
    window.location.reload();
}
function alterarObservacoes(itemId, observacoes) {
    let carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
    
    const index = carrinho.findIndex(item => item.id === itemId);
    if (index !== -1 && carrinho[index]) {
        carrinho[index].observacoes = observacoes;
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
    }
}

function removerItem(itemId) {
    let carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
    
    const index = carrinho.findIndex(item => item.id === itemId);
    if (index === -1 || !carrinho[index]) {
        return;
    }
    
    const nomeItem = carrinho[index].nome;
    
    if (confirm(`Remover "${nomeItem}" do carrinho?`)) {
        // Remover o item pelo índice encontrado
        carrinho.splice(index, 1);
        
        // Validar e re-salvar carrinho limpo
        carrinho = carrinho.filter(item => item && item.id && item.nome && typeof item.preco === 'number' && typeof item.quantidade === 'number');
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        
        // Recarregar toda a interface
        carregarItensCarrinho();
    }
}

function calcularTotal() {
    const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
    const tipoEntrega = $('input[name="tipoEntrega"]:checked').val();
    
    let subtotal = 0;
    carrinho.forEach(item => {
        subtotal += item.preco * item.quantidade;
    });
    
    $('#subtotal').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
    
    if (tipoEntrega === 'entrega') {
        verificarTaxaEntrega().then(resultado => {
            if (resultado.sucesso) {
                $('#taxa-entrega-linha').show();
                $('#taxa-entrega').text(`R$ ${resultado.taxa.toFixed(2).replace('.', ',')}`);
                
                const total = subtotal + resultado.taxa;
                $('#total-final').text(`R$ ${total.toFixed(2).replace('.', ',')}`);
                
                // Habilitar botão finalizar
                $('.btn-finalizar').prop('disabled', false).text('Finalizar Compra');
                $('#aviso-entrega').hide();
            } else {
                $('#taxa-entrega-linha').hide();
                $('#total-final').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
                
                // Desabilitar botão e mostrar aviso
                $('.btn-finalizar').prop('disabled', true).text('Entrega Indisponível');
                mostrarAvisoEntrega(resultado.mensagem);
            }
        });
    } else {
        $('#taxa-entrega-linha').hide();
        $('#total-final').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
        $('.btn-finalizar').prop('disabled', false).text('Finalizar Compra');
        $('#aviso-entrega').hide();
    }
}

function verificarTaxaEntrega() {
    
    const email = localStorage.getItem('cliente_email');
    if (!email) {
        return Promise.resolve({
            sucesso: false,
            mensagem: 'Faça login para verificar entrega'
        });
    }
    
    return new Promise((resolve) => {
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
                    resolve({
                        sucesso: true,
                        taxa: parseFloat(response.valor_entrega) || 0
                    });
                } else {
                    resolve({
                        sucesso: false,
                        mensagem: response.mensagem
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Erro na requisição:', { xhr, status, error });
                resolve({
                    sucesso: false,
                    mensagem: 'Erro ao verificar disponibilidade de entrega'
                });
            }
        });
    });
}

let locaisRetiradaCarregados = false;
let locaisRetirada = null;

function verificarMesas() {
    const tipoEntrega = $('input[name="tipoEntrega"]:checked').val();
    const opcoesDiv = $('#opcoes-retirada');
    
    if (tipoEntrega !== 'retirada') {
        opcoesDiv.hide();
        localStorage.removeItem('local_retirada');
        return;
    }
    
    if (locaisRetiradaCarregados) {
        opcoesDiv.show();
        return;
    }
    
    $.ajax({
        url: '/api/mesas',
        method: 'GET',
        dataType: 'json',
        xhrFields: { withCredentials: true },
        success: function(response) {
            if (response.sucesso) {
                locaisRetirada = response;
                locaisRetiradaCarregados = true;
                exibirLocaisRetirada(response);
                opcoesDiv.show();
            }
        },
        error: function() {
            opcoesDiv.hide();
        }
    });
}

function exibirLocaisRetirada(data) {
    const container = $('#lista-locais-retirada');
    let html = '';
    
    // Balcão
    html += `
        <label class="local-retirada-item">
            <input type="radio" name="localRetirada" value="balcao" checked>
            <div class="local-info">
                <i class="fas fa-store"></i>
                <span>${data.balcao.nome}</span>
                <span class="badge bg-success">Disponível</span>
            </div>
        </label>
    `;
    
    // Mesas
    if (data.mesas && data.mesas.length > 0) {
        data.mesas.forEach(mesa => {
            const ocupado = mesa.ocupado ? 'occupied' : 'available';
            const badge = mesa.ocupado ? '<span class="badge bg-warning">Ocupada</span>' : '<span class="badge bg-success">Livre</span>';
            const disabled = mesa.ocupado ? 'disabled' : '';
            
            html += `
                <label class="local-retirada-item ${disabled}">
                    <input type="radio" name="localRetirada" value="mesa_${mesa.id}" ${disabled}>
                    <div class="local-info">
                        <i class="fas fa-chair"></i>
                        <span>Mesa ${mesa.numero}</span>
                        ${badge}
                    </div>
                </label>
            `;
        });
    }
    
    container.html(html);
}

function mostrarAvisoEntrega(mensagem) {
    let aviso = $('#aviso-entrega');
    if (aviso.length === 0) {
        aviso = $(`
            <div id="aviso-entrega" class="alert alert-warning mt-2" style="background: rgba(255, 193, 7, 0.1); border: 1px solid #ffc107; color: #856404;">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span id="aviso-texto"></span>
            </div>
        `);
        $('.entrega-opcoes').after(aviso);
    }
    
    $('#aviso-texto').text(mensagem);
    aviso.show();
}

function obterTaxaEntrega() {
    const endereco = JSON.parse(localStorage.getItem('endereco_usuario') || '{}');
    
    if (!endereco.bairro || !endereco.cidade) {
        return Promise.resolve(0);
    }
    
    return new Promise((resolve) => {
        $.ajax({
            url: '/api/bairro/taxa',
            method: 'POST',
            data: {
                bairro: endereco.bairro,
                cidade: endereco.cidade
            },
            xhrFields: {
                withCredentials: true
            },
            success: function(response) {
                resolve(response.taxa || 0);
            },
            error: function() {
                resolve(0);
            }
        });
    });
}

function fecharCarrinhoPopup() {
    $('#carrinho-popup').remove();
    carrinhoPopupAberto = false;
}

function fecharCarrinhoEVoltarMenu() {
    $('#carrinho-popup').remove();
    carrinhoPopupAberto = false;
    
    // Remover hash temporariamente se já estiver em #menu
    if (window.location.hash === '#menu') {
        window.location.hash = '';
        setTimeout(() => {
            window.location.hash = '#menu';
        }, 10);
    } else {
        window.location.hash = '#menu';
    }
    
    // Garantir scroll para o menu
    setTimeout(() => {
        const menuSection = document.getElementById('menu');
        if (menuSection) {
            menuSection.scrollIntoView({ behavior: 'smooth' });
        }
    }, 100);
}

function finalizarCompra() {
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
    
    // Calcular totais
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
    
    const localRetirada = $('input[name="localRetirada"]:checked')?.val() || 'balcao';
    
    const pedido = {
        email: email,
        itens: carrinho,
        tipo_entrega: tipoEntrega,
        local_retirada: localRetirada,
        subtotal: subtotal,
        taxa_entrega: taxaEntrega,
        total: total
    };
    
    // Enviar pedido para o servidor
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
                // Salvar código do pedido no localStorage
                localStorage.setItem('codigo_pedido_ativo', response.codigo_pedido);
                
                // Limpar carrinho
                localStorage.removeItem('carrinho');
                
                // Redirecionar para WhatsApp
                if (response.whatsapp_url) {
                    window.open(response.whatsapp_url, '_blank');
                }
                
                alert('Pedido enviado com sucesso! Código: ' + response.codigo_pedido);
                fecharCarrinhoPopup();
                
                // Recarregar página para atualizar contador
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

// Fim do arquivo
