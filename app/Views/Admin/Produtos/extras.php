<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> 
<?php echo $titulo; ?> 
<?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    .list-group-item {
        border: 1px solid #dee2e6;
        margin-bottom: 5px;
        border-radius: 4px;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    /* Customização do Select2 */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 45px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        margin: 0.125rem;
        font-weight: 500;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: #6c757d;
        margin-right: 0.25rem;
        font-weight: bold;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
        border-radius: 0.125rem;
    }

    /* Template customizado para mostrar preço */
    .select2-results__option .extra-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.25rem 0;
    }

    .select2-results__option .extra-price {
        font-weight: 600;
        color: #198754;
        font-size: 0.875rem;
        background-color: #d1e7dd;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        border: 1px solid #badbcc;
    }

    .select2-results__option .extra-description {
        color: #333333;
        font-size: 0.8rem;
        margin-top: 0.125rem;
        font-style: italic;
    }

    .select2-results__option--highlighted .extra-price {
        background-color: #198754;
        color: #fff;
        border-color: #198754;
    }

    .select2-results__option--highlighted .extra-description {
        color: #191919;
    }

    .select2-container {
        width: 100% !important;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
</style>
<?php echo $this->endSection(); ?>

<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title"><?= $titulo ?></h4>
                    <a href="<?= base_url("admin/produtos/show/$produto->id") ?>" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Voltar ao Produto
                    </a>
                </div>

                <!-- Exibir mensagens -->
                <?php if (session()->has('sucesso')): ?>
                    <div class="alert alert-success">
                        <?= session('sucesso') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('erro')): ?>
                    <div class="alert alert-danger">
                        <?= session('erro') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('atencao')): ?>
                    <div class="alert alert-warning">
                        <?= session('atencao') ?>
                    </div>
                <?php endif; ?>

                <!-- Informações do Produto -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informações do Produto</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Nome:</strong></td>
                                                <td><?= esc($produto->nome) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Categoria:</strong></td>
                                                <td>
                                                    <?php if ($produto->categoria): ?>
                                                        <span class="badge badge-info"><?= esc($produto->categoria) ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">Não definida</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    <?php if ($produto->ativo): ?>
                                                        <span class="badge badge-success">Ativo</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger">Inativo</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <?php if (!empty($produto->imagem)): ?>
                                            <img src="<?= base_url('uploads/produtos/' . $produto->imagem) ?>" 
                                                 alt="<?= esc($produto->nome) ?>" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 120px; max-height: 120px; object-fit: cover;">
                                             <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 120px; height: 120px; margin: 0 auto; border-radius: 8px;">
                                                <i class="mdi mdi-image text-muted" style="font-size: 2rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Seleção de Extras -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Selecionar Extras</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($extras)): ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="mdi mdi-information-outline" style="font-size: 48px;"></i>
                                        <p class="mt-2">Nenhum extra disponível no momento.</p>
                                        <a href="<?= base_url('admin/extras/criar') ?>" class="btn btn-success btn-sm">
                                            <i class="mdi mdi-plus"></i> Criar Primeiro Extra
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <form id="form-extras" action="<?= site_url("admin/produtos/salvar-extras/$produto->id") ?>" method="post">
                                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                                        <?php
                                        // Criar array dos extras já associados ao produto
                                        $extrasAssociados = [];
                                        if (!empty($produtosExtras)) {
                                            foreach ($produtosExtras as $produtoExtra) {
                                                $extrasAssociados[] = $produtoExtra->extra_id;
                                            }
                                        }
                                        ?>

                                        <div class="form-group">
                                            <label for="extras-select">Escolha os extras para este produto:</label>
                                            <select class="form-control" 
                                                    id="extras-select" 
                                                    name="extras[]" 
                                                    multiple="multiple">
                                                        <?php foreach ($extras as $extra): ?>
                                                    <option value="<?= $extra->id ?>" 
                                                    <?= in_array($extra->id, $extrasAssociados) ? 'selected' : '' ?>
                                                            data-price="<?= $extra->preco ?? 0 ?>"
                                                            data-description="<?= esc($extra->descricao ?? '') ?>">
                                                                <?= esc($extra->nome) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="text-muted mt-2 d-block">
                                                Use a busca para encontrar extras específicos. Você pode selecionar múltiplos extras.
                                            </small>
                                        </div>

                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-success">
                                                <i class="mdi mdi-content-save"></i> Salvar Extras do Produto
                                            </button>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Extras Associados ao Produto -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Extras Associados</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($produtosExtras)): ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="mdi mdi-package-variant-closed" style="font-size: 48px;"></i>
                                        <p class="mt-2">Este produto ainda não possui extras associados.</p>
                                        <p class="small">Selecione os extras desejados na lista ao lado.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($produtosExtras as $produtoExtra): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1"><?= esc($produtoExtra->extra) ?></h6>
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
                                                    <?php if ($extraCompleto && !empty($extraCompleto->descricao)): ?>
                                                        <p class="mb-1 text-muted small"><?= esc($extraCompleto->descricao) ?></p>
                                                    <?php endif; ?>
                                                    <?php if ($extraCompleto && !empty($extraCompleto->preco) && $extraCompleto->preco > 0): ?>
                                                        <span class="badge badge-success">+ R$ <?= number_format($extraCompleto->preco, 2, ',', '.') ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Gratuito</span>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="badge badge-primary badge-pill">
                                                    <i class="mdi mdi-check"></i>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="mt-3 text-center">
                                        <small class="text-muted">
                                            Total de extras: <?= count($produtosExtras) ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="text-center">
                            <a href="<?= base_url("admin/produtos/show/$produto->id") ?>" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Voltar ao Produto
                            </a>
                            <a href="<?= base_url('admin/extras') ?>" class="btn btn-info ml-2">
                                <i class="mdi mdi-view-list"></i> Gerenciar Extras
                            </a>
                            <a href="<?= base_url('admin/extras/criar') ?>" class="btn btn-success ml-2">
                                <i class="mdi mdi-plus"></i> Criar Novo Extra
                            </a>
                            <a href="<?= base_url("admin/produtos/editar/$produto->id") ?>" class="btn btn-primary ml-2">
                                <i class="mdi mdi-pencil"></i> Editar Produto
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Inicializar Select2
        $('#extras-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Busque e selecione os extras...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function () {
                    return "Nenhum extra encontrado";
                },
                searching: function () {
                    return "Buscando...";
                },
                removeAllItems: function () {
                    return "Remover todos os itens";
                }
            },
            templateResult: function (option) {
                if (!option.id) {
                    return option.text;
                }

                var $option = $(option.element);
                var price = $option.data('price');
                var description = $option.data('description');

                var $container = $('<div class="extra-info">');
                var $mainInfo = $('<div>');

                $mainInfo.append('<strong>' + option.text + '</strong>');

                if (description) {
                    $mainInfo.append('<div class="extra-description">' + description + '</div>');
                }

                $container.append($mainInfo);

                if (price && price > 0) {
                    $container.append('<span class="extra-price">+ R$ ' + parseFloat(price).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</span>');
                } else {
                    $container.append('<span class="text-muted">Gratuito</span>');
                }

                return $container;
            },
            templateSelection: function (option) {
                if (!option.id) {
                    return option.text;
                }

                var $option = $(option.element);
                var price = $option.data('price');

                if (price && price > 0) {
                    return option.text + ' (+R$ ' + parseFloat(price).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + ')';
                }

                return option.text + ' (Gratuito)';
            }
        });

        // Confirmação antes de salvar
        $('#form-extras').on('submit', function (e) {
            const selectedExtras = $('#extras-select').val();

            if (!selectedExtras || selectedExtras.length === 0) {
                if (!confirm('Você está prestes a remover todos os extras deste produto. Deseja continuar?')) {
                    e.preventDefault();
                    return false;
                }
            }

            // Mostra loading
            const $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Salvando...');
        });

        // Atualizar lista de extras associados em tempo real (opcional)
        $('#extras-select').on('change', function () {
            var selectedCount = $(this).val() ? $(this).val().length : 0;
            console.log('Extras selecionados: ' + selectedCount);
        });
    });
</script>
<?php echo $this->endSection(); ?>