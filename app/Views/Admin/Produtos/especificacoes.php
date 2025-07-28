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

    /* Template customizado para mostrar descrição */
    .select2-results__option .medida-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.25rem 0;
    }

    .select2-results__option .medida-description {
        color: #6c757d;
        font-size: 0.8rem;
        margin-top: 0.125rem;
        font-style: italic;
    }

    .select2-results__option--highlighted .medida-description {
        color: #f8f9fa;
    }

    .select2-container {
        width: 100% !important;
    }

    /* Fix para Select2 em containers que mudam de tamanho */
    .select2-container .select2-selection--single,
    .select2-container .select2-selection--multiple {
        width: 100% !important;
        box-sizing: border-box;
        min-width: 0;
        max-width: 100%;
    }

    .select2-dropdown {
        z-index: 9999 !important;
        min-width: 200px;
    }

    /* Corrigir largura do Select2 em colunas Bootstrap */
    .col-md-5 .select2-container,
    .col-md-4 .select2-container,
    .col-md-3 .select2-container {
        width: 100% !important;
        display: block;
    }

    .select2-selection__rendered {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    /* Forçar recalculo de largura */
    .especificacao-item .form-group {
        width: 100%;
        overflow: hidden;
    }

    /* Garantir que as colunas se comportem corretamente */
    .especificacao-item .row {
        margin-left: -5px;
        margin-right: -5px;
    }

    .especificacao-item .row > [class*="col-"] {
        padding-left: 5px;
        padding-right: 5px;
    }

    /* Fix para Select2 em layouts que mudam */
    .select2-container--bootstrap-5 {
        display: block !important;
        width: 100% !important;
    }

    .select2-container--bootstrap-5 .select2-selection {
        display: block;
        width: 100%;
        box-sizing: border-box;
    }

    /* Responsividade melhorada */
    @media (max-width: 992px) {
        .especificacao-item {
            padding-top: 50px;
        }
        
        .btn-remove-especificacao {
            top: 8px;
            right: 8px;
            width: 36px;
            height: 36px;
            font-size: 18px;
        }
    }
    
    @media (max-width: 768px) {
        .especificacao-item {
            padding-top: 15px;
            padding-bottom: 20px;
        }
        
        .especificacao-item .row .col-md-5,
        .especificacao-item .row .col-md-4,
        .especificacao-item .row .col-md-3 {
            margin-bottom: 1rem;
        }
        
        .btn-remove-especificacao {
            position: static;
            width: 100%;
            height: auto;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
            transform: none !important;
        }
        
        .btn-remove-especificacao .d-sm-inline {
            display: none !important;
        }
        
        .btn-remove-especificacao .d-inline {
            display: inline !important;
        }
        
        .btn-remove-especificacao:hover {
            transform: none !important;
        }
        
        .especificacao-container {
            margin-bottom: 20px;
        }
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    /* Estilos para o formulário dinâmico */
    .especificacao-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        padding-top: 45px;
        margin-bottom: 15px;
        background-color: #f8f9fa;
        position: relative;
    }

    .especificacao-item.nova {
        border-color: #28a745;
        background-color: #d4edda;
    }

    .btn-remove-especificacao {
        position: absolute;
        top: 8px;
        right: 8px;
        z-index: 10;
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 16px;
        line-height: 1;
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }

    .btn-remove-especificacao:hover {
        background-color: #c82333 !important;
        border-color: #bd2130 !important;
        color: #fff !important;
        transform: scale(1.05);
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    }

    .btn-remove-especificacao:focus {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #fff !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .especificacao-container {
        position: relative;
    }

    .customizavel-switch {
        transform: scale(0.8);
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
                    <!-- Gerenciar Especificações -->
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Gerenciar Especificações</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($medidas)): ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="mdi mdi-information-outline" style="font-size: 48px;"></i>
                                        <p class="mt-2">Nenhuma medida disponível no momento.</p>
                                        <a href="<?= base_url('admin/medidas/criar') ?>" class="btn btn-success btn-sm">
                                            <i class="mdi mdi-plus"></i> Criar Primeira Medida
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <form id="form-especificacoes" action="<?= site_url("admin/produtos/salvar-especificacoes/$produto->id") ?>" method="post">
                                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                        
                                        <div id="especificacoes-container">
                                            <?php if (!empty($produtoEspecificacoes)): ?>
                                                <?php foreach ($produtoEspecificacoes as $index => $especificacao): ?>
                                                    <div class="especificacao-container">
                                                        <div class="especificacao-item">
                                                            <button type="button" class="btn btn-danger btn-sm btn-remove-especificacao">
                                                                <i class="mdi mdi-close d-sm-inline"></i>
                                                            </button>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label>Medida</label>
                                                                        <select class="form-control medida-select" 
                                                                                name="especificacoes[<?= $index ?>][medida_id]" 
                                                                                required>
                                                                            <option value="">Selecione uma medida...</option>
                                                                            <?php foreach ($medidas as $medida): ?>
                                                                                <option value="<?= $medida->id ?>" 
                                                                                        <?= ($medida->id == $especificacao->medida_id ? 'selected' : '') ?>
                                                                                        data-description="<?= esc($medida->descricao ?? '') ?>">
                                                                                    <?= esc($medida->nome) ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Preço (R$)</label>
                                                                        <input type="number" 
                                                                               class="form-control" 
                                                                               name="especificacoes[<?= $index ?>][preco]" 
                                                                               value="<?= $especificacao->preco ?>"
                                                                               step="0.01" 
                                                                               min="0" 
                                                                               placeholder="0,00" 
                                                                               required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Customizável</label>
                                                                        <div class="form-check">
                                                                            <input type="checkbox" 
                                                                                   class="form-check-input customizavel-switch" 
                                                                                   name="especificacoes[<?= $index ?>][customizavel]" 
                                                                                   value="1"
                                                                                   <?= $especificacao->customizavel ? 'checked' : '' ?>>
                                                                            <label class="form-check-label">
                                                                                Sim
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <button type="button" id="btn-adicionar-especificacao" class="btn btn-info">
                                                <i class="mdi mdi-plus"></i> Adicionar Especificação
                                            </button>
                                        </div>
                                        
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success">
                                                <i class="mdi mdi-content-save"></i> Salvar Especificações
                                            </button>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Especificações Associadas -->
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Especificações Associadas</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($produtoEspecificacoes)): ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="mdi mdi-ruler" style="font-size: 48px;"></i>
                                        <p class="mt-2">Este produto ainda não possui especificações.</p>
                                        <p class="small">Adicione especificações ao lado.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($produtoEspecificacoes as $especificacao): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1"><?= esc($especificacao->medida) ?></h6>
                                                    <span class="badge badge-success">R$ <?= number_format($especificacao->preco, 2, ',', '.') ?></span>
                                                    <?php if ($especificacao->customizavel): ?>
                                                        <span class="badge badge-info ml-1">Customizável</span>
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
                                            Total de especificações: <?= count($produtoEspecificacoes) ?>
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
                            <a href="<?= base_url('admin/medidas') ?>" class="btn btn-info ml-2">
                                <i class="mdi mdi-view-list"></i> Gerenciar Medidas
                            </a>
                            <a href="<?= base_url('admin/medidas/criar') ?>" class="btn btn-success ml-2">
                                <i class="mdi mdi-plus"></i> Criar Nova Medida
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
        let especificacaoIndex = <?= count($produtoEspecificacoes ?? []) ?>;
        
        // Inicializar Select2 para selects existentes
        function initSelect2(element) {
            $(element).select2({
                theme: 'bootstrap-5',
                placeholder: 'Selecione uma medida...',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "Nenhuma medida encontrada";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                },
                templateResult: function(option) {
                    if (!option.id) {
                        return option.text;
                    }
                    
                    var $option = $(option.element);
                    var description = $option.data('description');
                    
                    var $container = $('<div class="medida-info">');
                    var $mainInfo = $('<div>');
                    
                    $mainInfo.append('<strong>' + option.text + '</strong>');
                    
                    if (description) {
                        $mainInfo.append('<div class="medida-description">' + description + '</div>');
                    }
                    
                    $container.append($mainInfo);
                    
                    return $container;
                }
            });
        }
        
        // Inicializar Select2 para todos os selects existentes
        $('.medida-select').each(function() {
            initSelect2(this);
        });
        
        // Função para redimensionar Select2 quando o layout muda
        function resizeSelect2() {
            $('.medida-select').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    // Forçar recalculo da largura
                    $(this).next('.select2-container').css('width', '100%');
                    
                    // Trigger resize event no Select2
                    $(this).trigger('change.select2');
                }
            });
        }
        
        // Função para forçar redimensionamento completo
        function forceResizeSelect2() {
            $('.medida-select').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    const currentValue = $(this).val();
                    $(this).select2('destroy');
                    initSelect2(this);
                    $(this).val(currentValue).trigger('change');
                }
            });
        }
        
        // Listener para mudanças de tamanho da janela
        $(window).on('resize', function() {
            setTimeout(resizeSelect2, 100);
        });
        
        // Listener para mudanças no sidebar (se existir)
        $(document).on('click', '.navbar-toggler', function() {
            setTimeout(forceResizeSelect2, 300);
        });
        
        // Listener para colapso de cards/accordions
        $(document).on('click', '[data-toggle="collapse"]', function() {
            setTimeout(forceResizeSelect2, 350);
        });
        
        // Listener para mudanças de abas
        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function() {
            setTimeout(forceResizeSelect2, 100);
        });
        
        // Observer para mudanças no DOM que possam afetar o layout
        if (window.ResizeObserver) {
            const resizeObserver = new ResizeObserver(function(entries) {
                setTimeout(resizeSelect2, 100);
            });
            
            const container = document.getElementById('especificacoes-container');
            if (container) {
                resizeObserver.observe(container);
            }
        }
        
        // Adicionar nova especificação
        $('#btn-adicionar-especificacao').on('click', function() {
            const novaEspecificacao = `
                <div class="especificacao-container">
                    <div class="especificacao-item nova">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-especificacao">
                            <i class="mdi mdi-close d-sm-inline"></i>
                        </button>
                        
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Medida</label>
                                    <select class="form-control medida-select" 
                                            name="especificacoes[${especificacaoIndex}][medida_id]" 
                                            required>
                                        <option value="">Selecione uma medida...</option>
                                        <?php foreach ($medidas as $medida): ?>
                                            <option value="<?= $medida->id ?>" 
                                                    data-description="<?= esc($medida->descricao ?? '') ?>">
                                                <?= esc($medida->nome) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Preço (R$)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           name="especificacoes[${especificacaoIndex}][preco]" 
                                           step="0.01" 
                                           min="0" 
                                           placeholder="0,00" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Customizável</label>
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input customizavel-switch" 
                                               name="especificacoes[${especificacaoIndex}][customizavel]" 
                                               value="1">
                                        <label class="form-check-label">
                                            Sim
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#especificacoes-container').append(novaEspecificacao);
            
            // Inicializar Select2 para o novo select
            const novoSelect = $('#especificacoes-container .especificacao-container:last-child .medida-select');
            initSelect2(novoSelect);
            
            // Forçar redimensionamento após adição
            setTimeout(function() {
                // Forçar recalculo da largura
                novoSelect.next('.select2-container').css('width', '100%');
                // Pequeno hack para forçar redimensionamento
                novoSelect.select2('open');
                setTimeout(function() {
                    novoSelect.select2('close');
                }, 50);
            }, 150);
            
            especificacaoIndex++;
        });
        
        // Remover especificação
        $(document).on('click', '.btn-remove-especificacao', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $container = $(this).closest('.especificacao-container');
            
            // Confirmação antes de remover
            if (confirm('Tem certeza que deseja remover esta especificação?')) {
                // Destruir Select2 antes de remover o elemento
                const $select = $container.find('.medida-select');
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                
                // Animação de remoção
                $container.fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });
        
        // Confirmação antes de salvar
        $('#form-especificacoes').on('submit', function(e) {
            const especificacoes = $('.especificacao-container').length;
            
            if (especificacoes === 0) {
                if (!confirm('Você está prestes a remover todas as especificações deste produto. Deseja continuar?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Validar se todas as especificações têm medida e preço
            let valido = true;
            $('.especificacao-container').each(function() {
                const medida = $(this).find('.medida-select').val();
                const preco = $(this).find('input[type="number"]').val();
                
                if (!medida || !preco) {
                    valido = false;
                    $(this).find('.especificacao-item').addClass('border-danger');
                }
            });
            
            if (!valido) {
                alert('Por favor, preencha todos os campos obrigatórios (Medida e Preço) para cada especificação.');
                e.preventDefault();
                return false;
            }
            
            // Mostra loading
            const $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Salvando...');
        });
    });
</script>
<?php echo $this->endSection(); ?>