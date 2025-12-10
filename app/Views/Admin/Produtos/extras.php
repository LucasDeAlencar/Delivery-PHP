<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> 
<?php echo $titulo; ?> 
<?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>
<style>
    .extra-card {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 12px;
        background: rgba(248, 181, 49, 0.05);
        transition: all 0.3s ease;
        position: relative;
    }

    .extra-card:hover {
        background: rgba(248, 181, 49, 0.1);
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(248, 181, 49, 0.2);
    }

    .extra-card .extra-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .extra-card .extra-details h6 {
        margin: 0 0 5px 0;
        color: var(--primary-color);
        font-weight: 600;
    }

    .extra-card .extra-details p {
        margin: 0;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .extra-card .extra-price {
        font-size: 1.1rem;
        font-weight: 600;
        color: #28a745;
    }

    .btn-remove-extra {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #dc3545;
        border: none;
        color: #fff;
        font-size: 16px;
        transition: all 0.2s ease;
    }

    .btn-remove-extra:hover {
        background: #c82333;
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
    }

    .extras-disponiveis {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .extras-disponiveis::-webkit-scrollbar {
        width: 8px;
    }

    .extras-disponiveis::-webkit-scrollbar-track {
        background: var(--darker-bg);
        border-radius: 4px;
    }

    .extras-disponiveis::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 4px;
    }

    .extra-disponivel-item {
        border: 1px solid var(--border-color);
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 10px;
        background: var(--darker-bg);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .extra-disponivel-item:hover {
        border-color: var(--primary-color);
        background: rgba(248, 181, 49, 0.1);
        transform: translateX(3px);
    }

    .extra-disponivel-item.associado {
        opacity: 0.5;
        cursor: not-allowed;
        background: rgba(108, 117, 125, 0.1);
    }

    .extra-disponivel-item.associado:hover {
        transform: none;
        border-color: var(--border-color);
    }

    .badge-associado {
        background: #6c757d;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }
</style>
<?php echo $this->endSection(); ?>

<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title"><?= $titulo ?></h4>
                    <a href="<?= base_url("admin/produtos/show/$produto->id") ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-left"></i> Voltar ao Produto
                    </a>
                </div>

                <!-- Informações do Produto -->
                <div class="card mb-4" style="background: rgba(248, 181, 49, 0.05);">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <?php if (!empty($produto->imagem)): ?>
                                    <img src="<?= base_url('uploads/produtos/' . $produto->imagem) ?>" 
                                         alt="<?= esc($produto->nome) ?>" 
                                         class="img-thumbnail" 
                                         style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                         style="width: 100px; height: 100px; margin: 0 auto; border-radius: 8px;">
                                        <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-10">
                                <h5 class="mb-2" style="color: var(--primary-color);"><?= esc($produto->nome) ?></h5>
                                <p class="mb-1"><strong>Categoria:</strong> 
                                    <?php if ($produto->categoria): ?>
                                        <span class="badge bg-info"><?= esc($produto->categoria) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Não definida</span>
                                    <?php endif; ?>
                                </p>
                                <p class="mb-1"><strong>Extras Obrigatórios:</strong> 
                                    <?php if (isset($produto->obrigatorio_extras) && $produto->obrigatorio_extras > 0): ?>
                                        <span class="badge bg-warning text-dark"><?= $produto->obrigatorio_extras ?> extra(s) obrigatório(s)</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nenhum extra obrigatório</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Extras Associados -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-check-circle"></i> Extras Associados</h5>
                                <span class="badge bg-primary"><?= count($produtosExtras) ?></span>
                            </div>
                            <div class="card-body">
                                <?php if (empty($produtosExtras)): ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p class="mt-2">Nenhum extra associado ainda.</p>
                                        <p class="small">Clique nos extras disponíveis ao lado para associá-los.</p>
                                    </div>
                                <?php else: ?>
                                    <div id="extras-associados-lista">
                                        <?php foreach ($produtosExtras as $produtoExtra): ?>
                                            <?php
                                            // Buscar informações completas do extra
                                            $extraCompleto = null;
                                            foreach ($extras as $extra) {
                                                if ($extra->id == $produtoExtra->extra_id) {
                                                    $extraCompleto = $extra;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <div class="extra-card" data-extra-id="<?= $produtoExtra->extra_id ?>">
                                                <button type="button" 
                                                        class="btn-remove-extra" 
                                                        onclick="removerExtra(<?= $produtoExtra->extra_id ?>)"
                                                        title="Remover extra">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <div class="extra-info">
                                                    <div class="extra-details">
                                                        <h6><?= esc($produtoExtra->extra) ?></h6>
                                                        <?php if ($extraCompleto && !empty($extraCompleto->descricao)): ?>
                                                            <p><?= esc($extraCompleto->descricao) ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="extra-price">
                                                        <?php if ($extraCompleto && !empty($extraCompleto->preco) && $extraCompleto->preco > 0): ?>
                                                            + R$ <?= number_format($extraCompleto->preco, 2, ',', '.') ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">Gratuito</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Extras Disponíveis -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Extras Disponíveis</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($extras)): ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p class="mt-2">Nenhum extra disponível no momento.</p>
                                        <a href="<?= base_url('admin/extras/criar') ?>" class="btn btn-success btn-sm mt-2">
                                            <i class="fas fa-plus"></i> Criar Primeiro Extra
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3">
                                        <input type="text" 
                                               id="busca-extra" 
                                               class="form-control" 
                                               placeholder="🔍 Buscar extra...">
                                    </div>
                                    <div class="extras-disponiveis" id="extras-disponiveis-lista">
                                        <?php
                                        // Criar array dos extras já associados
                                        $extrasAssociadosIds = [];
                                        if (!empty($produtosExtras)) {
                                            foreach ($produtosExtras as $produtoExtra) {
                                                $extrasAssociadosIds[] = $produtoExtra->extra_id;
                                            }
                                        }
                                        ?>
                                        <?php foreach ($extras as $extra): ?>
                                            <?php $jaAssociado = in_array($extra->id, $extrasAssociadosIds); ?>
                                            <div class="extra-disponivel-item <?= $jaAssociado ? 'associado' : '' ?>" 
                                                 data-extra-id="<?= $extra->id ?>"
                                                 data-extra-nome="<?= esc($extra->nome) ?>"
                                                 onclick="<?= !$jaAssociado ? 'adicionarExtra(' . $extra->id . ')' : '' ?>">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?= esc($extra->nome) ?></strong>
                                                        <?php if (!empty($extra->descricao)): ?>
                                                            <p class="mb-0 small text-muted"><?= esc($extra->descricao) ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-end">
                                                        <?php if (!empty($extra->preco) && $extra->preco > 0): ?>
                                                            <div class="text-success fw-bold">+ R$ <?= number_format($extra->preco, 2, ',', '.') ?></div>
                                                        <?php else: ?>
                                                            <div class="text-muted">Gratuito</div>
                                                        <?php endif; ?>
                                                        <?php if ($jaAssociado): ?>
                                                            <span class="badge-associado mt-1">✓ Associado</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="<?= base_url("admin/produtos/show/$produto->id") ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar ao Produto
                        </a>
                        <a href="<?= base_url('admin/extras') ?>" class="btn btn-info">
                            <i class="fas fa-list"></i> Gerenciar Extras
                        </a>
                        <a href="<?= base_url('admin/extras/criar') ?>" class="btn btn-success">
                            <i class="fas fa-plus"></i> Criar Novo Extra
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Busca de extras
    const buscaExtra = document.getElementById('busca-extra');
    if (buscaExtra) {
        buscaExtra.addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            const items = document.querySelectorAll('.extra-disponivel-item');
            items.forEach(function(item) {
                const nome = item.getAttribute('data-extra-nome').toLowerCase();
                if (nome.includes(termo)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});

function adicionarExtra(extraId) {
    if (confirm('Deseja associar este extra ao produto?')) {
        fetch('<?= site_url("admin/produtos/adicionar-extra/$produto->id") ?>/' + extraId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao adicionar extra: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            alert('Erro ao adicionar extra. Tente novamente.');
            console.error('Erro:', error);
        });
    }
}

function removerExtra(extraId) {
    if (confirm('Deseja remover este extra do produto?')) {
        fetch('<?= site_url("admin/produtos/remover-extra/$produto->id") ?>/' + extraId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao remover extra: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            alert('Erro ao remover extra. Tente novamente.');
            console.error('Erro:', error);
        });
    }
}
</script>
<?php echo $this->endSection(); ?>
