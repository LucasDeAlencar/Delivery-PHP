<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>

<style>
    .info-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 1.1rem;
        margin-bottom: 15px;
    }

    .status-badge {
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .item-produto {
        border-left: 3px solid #f8b531;
        padding-left: 15px;
        margin-bottom: 15px;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #f8b531;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #f8b531;
    }
</style>

<?php echo $this->endSection(); ?>

<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-12 mb-3">
        <a href="<?= site_url('admin/pedidos') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
        <a href="<?= site_url("admin/pedidos/imprimir/{$pedido->id}") ?>" 
           class="btn btn-primary" 
           target="_blank">
            <i class="fas fa-print"></i> Imprimir
        </a>
    </div>
</div>

<div class="row">
    <!-- Informações do Pedido -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-bag"></i> Pedido <?= esc($pedido->codigo) ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Cliente</div>
                        <div class="info-value">
                            <i class="fas fa-user"></i> <?= esc($pedido->nome_cliente) ?>
                        </div>

                        <div class="info-label">Telefone</div>
                        <div class="info-value">
                            <i class="fas fa-phone"></i> <?= esc($pedido->telefone_cliente) ?>
                        </div>

                        <div class="info-label">Endereço de Entrega</div>
                        <div class="info-value">
                            <i class="fas fa-map-marker-alt"></i> <?= esc($pedido->endereco_entrega) ?>
                            <?php if (!empty($pedido->complemento)): ?>
                                <br><small class="text-muted">Complemento: <?= esc($pedido->complemento) ?></small>
                            <?php endif; ?>
                            <?php if (!empty($pedido->bairro_nome)): ?>
                                <br><small class="text-muted">Bairro: <?= esc($pedido->bairro_nome) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-label">Forma de Pagamento</div>
                        <div class="info-value">
                            <i class="fas fa-credit-card"></i> <?= esc($pedido->forma_pagamento) ?>
                            <?php if ($pedido->forma_pagamento === 'Dinheiro' && $pedido->troco_para > 0): ?>
                                <br><small class="text-muted">
                                    Troco para: R$ <?= number_format($pedido->troco_para, 2, ',', '.') ?>
                                </small>
                            <?php endif; ?>
                        </div>

                        <div class="info-label">Data/Hora do Pedido</div>
                        <div class="info-value">
                            <i class="fas fa-clock"></i> <?= date('d/m/Y H:i:s', strtotime($pedido->criado_em)) ?>
                        </div>

                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <?php if ($pedido->status === 'inativo'): ?>
                                <span class="badge bg-secondary" style="font-size: 1rem; padding: 8px 15px;">
                                    <i class="fas fa-ban me-1"></i> INATIVO
                                </span>
                                <br><small class="text-muted">Este pedido não pode mais ser alterado</small>
                            <?php elseif ($pedido->status === 'entregue'): ?>
                                <span class="badge bg-success" style="font-size: 1rem; padding: 8px 15px;">
                                    <i class="fas fa-check-double me-1"></i> ENTREGUE
                                </span>
                                <br><small class="text-muted">Pedido finalizado</small>
                            <?php elseif (isset($podeAlterar) && !$podeAlterar): ?>
                                <span class="badge bg-secondary" style="font-size: 1rem; padding: 8px 15px;">
                                    <?= ucfirst($pedido->status) ?>
                                </span>
                                <br><small class="text-muted">Você não pode alterar este pedido</small>
                            <?php elseif ($pedido->status === 'pendente'): ?>
                                <select class="form-control status-select" 
                                        data-pedido-id="<?= $pedido->id ?>"
                                        style="width: auto; display: inline-block;">
                                    <option value="pendente" selected>⏳ Pendente</option>
                                    <option value="confirmado">✅ Confirmado</option>
                                    <option value="cancelado">❌ Cancelado</option>
                                </select>
                                <br><small class="text-muted">Pendente → Confirmado ou Cancelado</small>
                            <?php elseif ($pedido->status === 'confirmado'): ?>
                                <select class="form-control status-select" 
                                        data-pedido-id="<?= $pedido->id ?>"
                                        style="width: auto; display: inline-block;">
                                    <option value="confirmado" selected>✅ Confirmado</option>
                                    <option value="entregue">✔️ Entregue</option>
                                    <option value="cancelado">❌ Cancelado</option>
                                </select>
                                <br><small class="text-muted">Confirmado → Entregue ou Cancelado</small>
                            <?php else: ?>
                                <span class="badge bg-secondary" style="font-size: 1rem; padding: 8px 15px;">
                                    <?= ucfirst($pedido->status) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($pedido->observacoes)): ?>
                    <hr>
                    <div class="info-label">Observações</div>
                    <div class="info-value">
                        <i class="fas fa-comment"></i> <?= nl2br(esc($pedido->observacoes)) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Itens do Pedido -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list"></i> Itens do Pedido
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($itens)): ?>
                    <?php foreach ($itens as $item): ?>
                        <div class="item-produto">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="mb-1"><?= esc($item->produto_nome) ?></h6>
                                    <?php if (!empty($item->observacoes)): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-comment"></i> <?= esc($item->observacoes) ?>
                                        </small>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($item->extras)): ?>
                                        <div class="extras-lista mt-2" style="padding-left: 10px; border-left: 2px solid #f8b531;">
                                            <?php foreach ($item->extras as $extra): ?>
                                                <small class="d-block text-warning">
                                                    <i class="fas fa-plus-circle mr-1"></i>
                                                    <?= esc($extra->extra_nome) ?>
                                                    <?= $extra->quantidade > 1 ? ' x' . $extra->quantidade : '' ?>
                                                    <?php if ($extra->extra_preco > 0): ?>
                                                        (+R$ <?= number_format($extra->extra_preco * $extra->quantidade, 2, ',', '.') ?>)
                                                    <?php endif; ?>
                                                </small>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-2 text-center">
                                    <strong>Qtd: <?= $item->quantidade ?></strong>
                                </div>
                                <div class="col-md-2 text-center">
                                    R$ <?= number_format($item->preco_unitario, 2, ',', '.') ?>
                                </div>
                                <div class="col-md-2 text-end">
                                    <strong class="text-success">
                                        R$ <?= number_format($item->preco_total, 2, ',', '.') ?>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">Nenhum item encontrado</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Resumo e Ações -->
    <div class="col-md-4">
        <!-- Resumo Financeiro -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-dollar-sign"></i> Resumo Financeiro
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Valor dos Produtos:</span>
                    <strong>R$ <?= number_format($pedido->valor_produtos, 2, ',', '.') ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Taxa de Entrega:</span>
                    <strong>R$ <?= number_format($pedido->valor_entrega, 2, ',', '.') ?></strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>TOTAL:</strong>
                    <h4 class="text-success mb-0">
                        R$ <?= number_format($pedido->valor_total, 2, ',', '.') ?>
                    </h4>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-bolt"></i> Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if (isset($podeAlterar) && $podeAlterar && $pedido->status !== 'entregue' && $pedido->status !== 'inativo'): ?>
                        <a href="<?= site_url("admin/pedidos/cancelar/{$pedido->id}") ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">
                            <i class="fas fa-times"></i> Cancelar Pedido
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($pedido->status !== 'inativo'): ?>
                        <a href="<?= site_url("admin/pedidos/imprimir/{$pedido->id}") ?>" 
                           class="btn btn-secondary"
                           target="_blank">
                            <i class="fas fa-print"></i> Imprimir Pedido
                        </a>

                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $pedido->telefone_cliente) ?>" 
                           class="btn btn-success"
                           target="_blank">
                            <i class="fab fa-whatsapp"></i> Contatar Cliente
                        </a>
                    <?php else: ?>
                        <div class="alert alert-secondary mb-0 text-center">
                            <i class="fas fa-lock me-2"></i>
                            Pedido inativo - ações bloqueadas
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>

<script>
    $(document).ready(function () {
        // Atualizar status do pedido
        $('.status-select').on('change', function () {
            const $select = $(this);
            const pedidoId = $select.data('pedido-id');
            const novoStatus = $select.val();
            const statusAnterior = $select.data('status-anterior') || $select.val();

            if (confirm('Tem certeza que deseja alterar o status deste pedido?')) {
                $.ajax({
                    url: '<?= site_url('admin/pedidos/atualizar-status') ?>',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        pedido_id: pedidoId,
                        status: novoStatus
                    },
                    success: function (response) {
                        if (response.success) {
                            $select.data('status-anterior', novoStatus);
                            alert('Status atualizado com sucesso!');
                            location.reload();
                        } else {
                            alert(response.message || 'Erro ao atualizar status');
                            $select.val(statusAnterior);
                        }
                    },
                    error: function () {
                        alert('Erro ao atualizar status');
                        $select.val(statusAnterior);
                    }
                });
            } else {
                $select.val(statusAnterior);
            }
        });
    });
</script>

<?php echo $this->endSection(); ?>
