<!DOCTYPE html>
<html>
<head>
    <title>Carrinho - Delivery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background:#1a1a1a; color:#fff; font-family:'Poppins',sans-serif; }
        .card { background:#2d2d2d; border:1px solid #333; }
        .card-header { background:#1a1a1a; border-bottom:1px solid #333; }
        .btn-success { background:linear-gradient(135deg,#28a745,#20c997); border:none; }
        .btn-outline-warning { color:#ffc107; border-color:#ffc107; }
        .btn-outline-warning:hover { background:rgba(255,193,7,0.1); }
    </style>
</head>
<body>
<?php if (($modo_cadastro ?? 1) >= 2): ?>
<script>
window.clienteLogado = {
    logado: <?= json_encode($cliente_logado ?? false) ?>,
    nome: <?= json_encode($cliente_nome ?? '') ?>,
    telefone: <?= json_encode($cliente_telefone ?? '') ?>,
    email: <?= json_encode($cliente_email ?? '') ?>
};
window.modoCadastro = <?= (int)($modo_cadastro ?? 1) ?>;
window.clienteTemEndereco = <?= json_encode($cliente_tem_endereco ?? false) ?>;
</script>
<?php endif; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h5><i class="fas fa-shopping-cart"></i> Seus Itens (<?= count($carrinho_itens) ?>)</h5></div>
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
            <div class="card mb-4">
                <div class="card-header"><h5><i class="fas fa-receipt"></i> Resumo do Pedido</h5></div>
                <div class="card-body">
                    <!-- Tipo de Entrega -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo de Entrega</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_entrega" id="entrega" value="entrega" checked>
                            <label class="form-check-label" for="entrega"><i class="fas fa-motorcycle"></i> Entrega</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_entrega" id="retirada" value="retirada">
                            <label class="form-check-label" for="retirada"><i class="fas fa-store"></i> Retirada no Local</label>
                        </div>
                    </div>

                    <?php if (($modo_cadastro ?? 1) == 3): ?>
                    <div class="mb-3">
                        <button type="button" id="btn-definir-endereco" class="btn btn-outline-warning w-100">
                            <i class="fas fa-map-marker-alt mr-2"></i> Definir Endereço de Entrega
                        </button>
                        <small id="endereco-definido" class="text-success d-block mt-1" style="display:none;font-size:0.85em;"></small>
                        <small class="form-text text-muted mt-1" style="font-size:0.8rem;">Clique para informar seu endereço completo.</small>
                    </div>
                    <?php endif; ?>

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

                    <!-- Campo Troco -->
                    <div class="mb-3" id="campo-troco" style="display: none;">
                        <label for="troco_para" class="form-label">Troco para:</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="troco_para" name="troco_para" 
                                   step="0.01" min="<?= number_format($subtotal, 2, '.', '') ?>" 
                                   placeholder="0.00">
                        </div>
                        <small class="text-muted">Informe o valor que você tem para pagar</small>
                    </div>

                    <!-- QR Code PIX (exemplo) -->
                    <div class="mb-3" id="campo-pix-qrcode" style="display: none;">
                        <label class="form-label">PIX</label>
                        <div class="text-center">
                            <img src="https://via.placeholder.com/200x200?text=PIX+QR+Code" alt="QR Code PIX" class="img-fluid mb-2" style="max-width:200px;">
                            <p class="small text-muted">Escaneie o QR Code para pagar</p>
                        </div>
                    </div>

                    <!-- Taxa de Entrega -->
                    <div class="mb-3" id="linha-entrega" style="display: none;">
                        <div class="d-flex justify-content-between">
                            <span>Taxa de Entrega:</span>
                            <span id="valor-entrega">R$ 0,00</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total:</span>
                            <span id="valor-total">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                        </div>
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

<!-- Modal Endereço Entrega (Modo 3) -->
<?php if (($modo_cadastro ?? 1) == 3): ?>
<div class="modal fade" id="modalEnderecoEntrega" tabindex="-1" aria-labelledby="modalEnderecoEntregaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1a1a1a;color:#fff;">
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="modalEnderecoEntregaLabel"><i class="fas fa-map-marker-alt mr-2"></i>Endereço de Entrega</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-endereco-entrega-modo3">
          <div class="mb-3">
            <label for="endereco_cidade" class="form-label">Cidade *</label>
            <input type="text" class="form-control bg-dark text-light" id="endereco_cidade" name="cidade" required>
          </div>
          <div class="mb-3">
            <label for="endereco_bairro" class="form-label">Bairro *</label>
            <select class="form-select bg-dark text-light" id="endereco_bairro" name="bairro_id" required>
              <option value="">Selecione...</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="endereco_logradouro" class="form-label">Rua/Av *</label>
            <input type="text" class="form-control bg-dark text-light" id="endereco_logradouro" name="endereco" required>
          </div>
          <div class="mb-3">
            <label for="endereco_numero" class="form-label">Número *</label>
            <input type="text" class="form-control bg-dark text-light" id="endereco_numero" name="numero" required>
          </div>
          <div class="mb-3">
            <label for="endereco_complemento" class="form-label">Complemento</label>
            <input type="text" class="form-control bg-dark text-light" id="endereco_complemento" name="complemento">
          </div>
        </form>
      </div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn-salvar-endereco-entrega">Salvar</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/carrinho-simples.min.js?v=' . (@filemtime(FCPATH . 'assets/js/carrinho-simples.min.js') ?: '1')) ?>"></script>
<script src="<?= base_url('assets/js/finalizar-pedido.min.js?v=' . (@filemtime(FCPATH . 'assets/js/finalizar-pedido.min.js') ?: '1')) ?>"></script>
<script>
// Alimentar window.Carrinho com os itens do banco (página /carrinho)
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Carrinho === 'undefined') return;
    window.Carrinho.itens = <?= json_encode(array_map(function($item) {
        return [
            'id'         => $item['produto_id'],
            'nome'       => $item['produto_nome'],
            'quantidade' => (int)$item['quantidade'],
            'preco'      => (float)$item['preco_unitario'],
            'total'      => (float)$item['preco_total'],
            'observacoes'=> $item['observacoes'] ?? '',
            'categoria_id'=> $item['categoria_id'] ?? null,
            'extras'     => [],
        ];
    }, $carrinho_itens)) ?>;
});
</script>
<script>
$(document).ready(function() {
    const subtotal = <?= $subtotal ?? 0 ?>;
    const modoCadastro = <?= json_encode($modo_cadastro ?? 1) ?>;
    
    // Controle de forma de pagamento
    $('input[name="forma_pagamento"]').change(function() {
        const formaSelecionada = $(this).val();
        if (formaSelecionada === 'dinheiro') {
            $('#campo-troco').show();
            $('#troco_para').attr('required', true);
        } else {
            $('#campo-troco').hide();
            $('#troco_para').attr('required', false);
        }
        if (formaSelecionada === 'pix') {
            $('#campo-pix-qrcode').show();
        } else {
            $('#campo-pix-qrcode').hide();
        }
    });

    // Controle tipo de entrega
    $('input[name="tipo_entrega"]').change(function() {
        const tipoEntrega = $(this).val();
        if (tipoEntrega === 'retirada') {
            $('#linha-entrega').hide();
            $('#valor-entrega').text('R$ 0,00');
            $('#valor-total').text('R$ ' + subtotal.toFixed(2).replace('.', ','));
        } else {
            $('#linha-entrega').show();
            $('#valor-entrega').text('R$ 0,00');
            $('#valor-total').text('R$ ' + subtotal.toFixed(2).replace('.', ','));
        }
    });

    function calcularTaxaEntrega() {
        const bairroSelect = $('#bairro_id');
        if (bairroSelect.length && bairroSelect.val()) {
            const taxa = parseFloat(bairroSelect.find(':selected').data('taxa')) || 0;
            const total = subtotal + taxa;
            $('#valor-entrega').text('R$ ' + taxa.toFixed(2).replace('.', ','));
            $('#valor-total').text('R$ ' + total.toFixed(2).replace('.', ','));
        } else {
            $('#valor-entrega').text('R$ 0,00');
            $('#valor-total').text('R$ ' + subtotal.toFixed(2).replace('.', ','));
        }
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

    // Botão finalizar
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

        // Se modo 3 e entrega, verificar se endereço foi definido (localStorage ou banco)
        if (modoCadastro === 3 && tipoEntrega === 'entrega') {
            const enderecoSalvo = localStorage.getItem('endereco_entrega_modo3');
            const temEnderecoNoBanco = window.clienteTemEndereco === true;
            if (!enderecoSalvo && !temEnderecoNoBanco) {
                // Abrir modal de endereço primeiro
                const modal = new bootstrap.Modal(document.getElementById('modalEnderecoEntrega'));
                modal.show();
                return;
            }
        }

        // Prosseguir para finalização
        if (window.FinalizarPedido && typeof window.FinalizarPedido.abrirModal === 'function') {
            window.FinalizarPedido.abrirModal();
        } else {
            alert('Sistema de finalização não carregado.');
        }
    });

    // ===========================
    // Lógica do botão Definir Endereço (Modo 3)
    // ===========================
    if (modoCadastro === 3) {
        const btnDefinir = $('#btn-definir-endereco');
        const enderecoDefinido = $('#endereco-definido');
        const modalEl = $('#modalEnderecoEntrega');
        const btnSalvar = $('#btn-salvar-endereco-entrega');

        // Restaurar estado salvo, se houver
        const saved = localStorage.getItem('endereco_entrega_modo3');
        if (saved) {
            try {
                const d = JSON.parse(saved);
                btnDefinir.html('<i class="fas fa-check"></i> Entrega em ' + (d.bairro_nome || 'bairro')).removeClass('btn-outline-warning').addClass('btn-success');
                enderecoDefinido.text('Endereço definido!').show();
            } catch(e) {}
        }

        // Carregar bairros
        fetch('<?= site_url('api/bairros') ?>')
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const select = $('#endereco_bairro');
                    data.data.forEach(b => {
                        const opt = $('<option>').val(b.id).text(b.nome + (b.valor_entrega ? ' (+R$ ' + parseFloat(b.valor_entrega).toFixed(2).replace('.', ',') + ')' : '')).attr('data-taxa', b.valor_entrega || 0);
                        select.append(opt);
                    });
                }
            });

        // Abrir modal com dados salvos
        btnDefinir.on('click', function() {
            const saved = localStorage.getItem('endereco_entrega_modo3');
            if (saved) {
                try {
                    const d = JSON.parse(saved);
                    $('#endereco_cidade').val(d.cidade || '');
                    $('#endereco_bairro').val(d.bairro_id || '');
                    $('#endereco_logradouro').val(d.endereco || '');
                    $('#endereco_numero').val(d.numero || '');
                    $('#endereco_complemento').val(d.complemento || '');
                } catch(e) {}
            }
            const modal = new bootstrap.Modal(modalEl[0]);
            modal.show();
        });

        // Salvar endereço
        btnSalvar.on('click', function() {
            const cidade = $('#endereco_cidade').val().trim();
            const bairroId = $('#endereco_bairro').val();
            const endereco = $('#endereco_logradouro').val().trim();
            const numero = $('#endereco_numero').val().trim();
            const complemento = $('#endereco_complemento').val().trim();

            if (!cidade || !bairroId || !endereco || !numero) {
                alert('Preencha todos os campos obrigatórios');
                return;
            }

            const bairroSelect = $('#endereco_bairro');
            const bairroNome = bairroSelect.find(':selected').text().split(' (')[0];
            const taxa = parseFloat(bairroSelect.find(':selected').data('taxa')) || 0;

            const dados = {
                cidade,
                bairro_id: bairroId,
                bairro_nome: bairroNome,
                endereco,
                numero,
                complemento,
                taxa_entrega: taxa
            };
            localStorage.setItem('endereco_entrega_modo3', JSON.stringify(dados));

            // Atualizar cliente no backend (modo 3)
            fetch('<?= site_url('cliente/atualizar_endereco') ?>', {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
                body: JSON.stringify(dados)
            }).then(r => r.json()).then(res => {
                if (!res.sucesso) console.warn('Erro ao atualizar endereço do cliente:', res.msg);
            });

            btnDefinir.html('<i class="fas fa-check"></i> Entrega em ' + bairroNome).removeClass('btn-outline-warning').addClass('btn-success');
            enderecoDefinido.text('Endereço definido!').show();

            bootstrap.Modal.getInstance(modalEl[0]).hide();
        });

        // Ao mudar bairro, atualizar taxa no resumo
        $('#endereco_bairro').change(function() {
            const taxa = parseFloat($(this).find(':selected').data('taxa')) || 0;
            const total = subtotal + taxa;
            $('#valor-entrega').text('R$ ' + taxa.toFixed(2).replace('.', ','));
            $('#valor-total').text('R$ ' + total.toFixed(2).replace('.', ','));
        });
    }
});

function removerItem(itemId) {
    if (confirm('Deseja remover este item do carrinho?')) {
        $.ajax({
            url: '<?= base_url('carrinho/remover') ?>/' + itemId,
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                if (response.success) {
                    $('[data-item-id="' + itemId + '"]').fadeOut(300, function() {
                        $(this).remove();
                        if ($('#itens-carrinho .row').length === 0) location.reload();
                    });
                } else {
                    alert(response.message || 'Erro ao remover item');
                }
            },
            error: function() { alert('Erro ao remover item do carrinho'); }
        });
    }
}
</script>
</body>
</html>
