<!DOCTYPE html>
<html>
<head>
    <title>Carrinho - <?= $titulo ?? 'Delivery' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Itens do Carrinho -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-shopping-cart"></i> Seus Itens (<?= count($carrinho_itens) ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($carrinho_itens)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Seu carrinho está vazio</p>
                                <a href="<?= base_url() ?>" class="btn btn-primary">Ver Produtos</a>
                            </div>
                        <?php else: ?>
                            <div id="itens-carrinho">
                                <?php 
                                $subtotal = 0;
                                foreach ($carrinho_itens as $item): 
                                    $subtotal += $item['preco_total'];
                                ?>
                                <div class="row align-items-center border-bottom py-3" data-item-id="<?= $item['id'] ?>">
                                    <div class="col-2">
                                        <?php if (!empty($item['produto_imagem'])): ?>
                                            <img src="<?= $item['produto_imagem'] ?>" class="img-fluid rounded" alt="<?= $item['produto_nome'] ?>">
                                        <?php else: ?>
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="mb-1"><?= esc($item['produto_nome']) ?></h6>
                                        <?php if (!empty($item['tamanho_nome'])): ?>
                                            <small class="text-info"><i class="fas fa-ruler"></i> <?= esc($item['tamanho_nome']) ?></small><br>
                                        <?php endif; ?>
                                        <?php if (!empty($item['observacoes'])): ?>
                                            <small class="text-muted"><?= esc($item['observacoes']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-2 text-center">
                                        <span class="fw-bold"><?= $item['quantidade'] ?>x</span>
                                    </div>
                                    <div class="col-2 text-end">
                                        <div class="fw-bold">R$ <?= number_format($item['preco_total'], 2, ',', '.') ?></div>
                                        <button class="btn btn-sm btn-outline-danger mt-1" onclick="removerItem(<?= $item['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <?php if (!empty($carrinho_itens)): ?>
                <!-- Resumo do Pedido -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-receipt"></i> Resumo do Pedido</h5>
                    </div>
                    <div class="card-body">
                        <!-- Tipo de Entrega -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de Entrega</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_entrega" id="entrega" value="entrega" checked>
                                <label class="form-check-label" for="entrega">
                                    <i class="fas fa-motorcycle"></i> Entrega
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_entrega" id="retirada" value="retirada">
                                <label class="form-check-label" for="retirada">
                                    <i class="fas fa-store"></i> Retirada no Local
                                </label>
                            </div>
                        </div>

                        <!-- Forma de Pagamento -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Forma de Pagamento</label>
                            <?php foreach ($formas_pagamento as $forma): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="forma_pagamento" 
                                       id="pagamento_<?= $forma['id'] ?>" value="<?= $forma['slug'] ?>" 
                                       data-nome="<?= $forma['nome'] ?>">
                                <label class="form-check-label" for="pagamento_<?= $forma['id'] ?>">
                                    <i class="<?= $forma['icone'] ?>"></i> <?= $forma['nome'] ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Campo Troco (só aparece se for dinheiro) -->
                        <div class="mb-3" id="campo-troco" style="display: none;">
                            <label for="troco_para" class="form-label">Troco para:</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" class="form-control" id="troco_para" name="troco_para" 
                                       step="0.01" min="<?= number_format($subtotal, 2, '.', '') ?>" 
                                       placeholder="<?= number_format($subtotal, 2, ',', '.') ?>">
                            </div>
                            <small class="text-muted">Valor mínimo: R$ <?= number_format($subtotal, 2, ',', '.') ?></small>
                        </div>

                        <!-- QR Code PIX (só aparece se for pix) -->
                        <?php 
                        $formaPix = null;
                        foreach ($formas_pagamento as $fp) {
                            if ($fp['slug'] === 'pix') {
                                $formaPix = $fp;
                                break;
                            }
                        }
                        ?>
                        <?php if ($formaPix && !empty($formaPix['qrcode_image'])): ?>
                        <div class="mb-3" id="campo-pix-qrcode" style="display: none;">
                            <label class="form-label">QR Code PIX:</label>
                            <div class="text-center">
                                <img src="<?= base_url('uploads/qrcode_pix/' . $formaPix['qrcode_image']) ?>" 
                                     alt="QR Code PIX" 
                                     class="img-fluid" 
                                     style="max-width: 200px; border: 2px solid #28a745; border-radius: 8px;">
                                <?php if (!empty($formaPix['codigo'])): ?>
                                <p class="mt-2 mb-0 text-muted" style="font-size: 0.85rem;">
                                    <strong>Chave PIX:</strong> <?= esc($formaPix['codigo']) ?>
                                </p>
                                <?php endif; ?>
                                <small class="text-success d-block mt-1">
                                    <i class="fas fa-check-circle"></i> Escaneie o QR Code para pagar
                                </small>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Valores -->
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between" id="linha-entrega">
                            <span>Taxa de Entrega:</span>
                            <span id="valor-entrega">R$ 0,00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total:</span>
                            <span id="valor-total">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                        </div>

                        <button class="btn btn-success w-100 mt-3" id="btn-finalizar">
                            <i class="fas fa-check"></i> Finalizar Pedido
                        </button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        const subtotal = <?= $subtotal ?? 0 ?>;
        
        // Controlar exibição do campo troco
        $('input[name="forma_pagamento"]').change(function() {
            const formaSelecionada = $(this).val();
            if (formaSelecionada === 'dinheiro') {
                $('#campo-troco').show();
                $('#troco_para').attr('required', true);
            } else {
                $('#campo-troco').hide();
                $('#troco_para').attr('required', false);
            }
            
            // Controlar exibição do QR Code PIX
            if (formaSelecionada === 'pix') {
                $('#campo-pix-qrcode').show();
            } else {
                $('#campo-pix-qrcode').hide();
            }
        });

        // Controlar exibição da taxa de entrega
        $('input[name="tipo_entrega"]').change(function() {
            const tipoEntrega = $(this).val();
            if (tipoEntrega === 'retirada') {
                $('#linha-entrega').hide();
                $('#valor-entrega').text('R$ 0,00');
                $('#valor-total').text('R$ ' + subtotal.toFixed(2).replace('.', ','));
            } else {
                $('#linha-entrega').show();
                // Aqui você pode implementar o cálculo da taxa de entrega
                calcularTaxaEntrega();
            }
        });

        function calcularTaxaEntrega() {
            // Implementar lógica de cálculo da taxa de entrega
            // Por enquanto, valor fixo de exemplo
            const taxaEntrega = 5.00;
            const total = subtotal + taxaEntrega;
            
            $('#valor-entrega').text('R$ ' + taxaEntrega.toFixed(2).replace('.', ','));
            $('#valor-total').text('R$ ' + total.toFixed(2).replace('.', ','));
        }

        // Validar troco
        $('#troco_para').on('input', function() {
            const valorTroco = parseFloat($(this).val()) || 0;
            if (valorTroco < subtotal) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Finalizar pedido
        $('#btn-finalizar').click(function() {
            const formaPagamento = $('input[name="forma_pagamento"]:checked').val();
            const tipoEntrega = $('input[name="tipo_entrega"]:checked').val();
            
            if (!formaPagamento) {
                alert('Selecione uma forma de pagamento');
                return;
            }

            if (formaPagamento === 'dinheiro') {
                const trocoValue = parseFloat($('#troco_para').val()) || 0;
                if (trocoValue < subtotal) {
                    alert('O valor do troco deve ser maior ou igual ao total do pedido');
                    return;
                }
            }

            // Aqui você implementaria a lógica de finalização do pedido
            console.log('Dados do pedido:', {
                tipo_entrega: tipoEntrega,
                forma_pagamento: formaPagamento,
                troco_para: formaPagamento === 'dinheiro' ? $('#troco_para').val() : null
            });
            
            alert('Funcionalidade de finalização será implementada');
        });
    });

    function removerItem(itemId) {
        if (confirm('Deseja remover este item do carrinho?')) {
            $.ajax({
                url: '<?= base_url('carrinho/remover') ?>/' + itemId,
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.success) {
                        $('[data-item-id="' + itemId + '"]').fadeOut(300, function() {
                            $(this).remove();
                            // Recarregar página se carrinho ficar vazio
                            if ($('#itens-carrinho .row').length === 0) {
                                location.reload();
                            }
                        });
                    } else {
                        alert(response.message || 'Erro ao remover item');
                    }
                },
                error: function() {
                    alert('Erro ao remover item do carrinho');
                }
            });
        }
    }
    </script>
</body>
</html>
