<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> 
<?php echo $titulo; ?> 
<?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>
<style>
    .danger-zone {
        border: 2px solid #dc3545;
        border-radius: 8px;
        background-color: #f8d7da;
    }
    
    .product-info {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .warning-icon {
        font-size: 64px;
        color: #dc3545;
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
                    <h4 class="card-title text-danger">
                        <i class="mdi mdi-alert-circle"></i> 
                        <?= $titulo ?>
                    </h4>
                    <a href="<?= base_url("admin/produtos/show/$produto->id") ?>" class="btn btn-secondary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Voltar ao Produto
                    </a>
                </div>

                <!-- Exibir mensagens -->
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

                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        
                        <!-- Aviso de Perigo -->
                        <div class="text-center mb-4">
                            <i class="mdi mdi-alert-circle warning-icon"></i>
                            <h3 class="text-danger mt-2">Atenção!</h3>
                            <p class="lead">Você está prestes a excluir um produto. Esta ação pode ser desfeita posteriormente.</p>
                        </div>

                        <!-- Informações do Produto -->
                        <div class="product-info">
                            <h5 class="mb-3">
                                <i class="mdi mdi-package-variant"></i> 
                                Informações do Produto
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Nome:</strong></td>
                                            <td><?= esc($produto->nome) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Categoria:</strong></td>
                                            <td><?= esc($produto->categoria ?? 'Não definida') ?></td>
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
                                        <tr>
                                            <td><strong>Criado em:</strong></td>
                                            <td>
                                                <?php if ($produto->criado_em): ?>
                                                    <?= date('d/m/Y H:i', strtotime($produto->criado_em)) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Não informado</span>
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
                                             style="max-width: 150px; max-height: 150px;">
                                        <p class="text-muted mt-2 small">Imagem atual</p>
                                    <?php else: ?>
                                        <div class="text-muted">
                                            <i class="mdi mdi-image-off" style="font-size: 48px;"></i>
                                            <p class="mt-2">Sem imagem</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Zona de Perigo -->
                        <div class="danger-zone p-4">
                            <h5 class="text-danger mb-3">
                                <i class="mdi mdi-skull-crossbones"></i> 
                                Zona de Perigo
                            </h5>
                            
                            <div class="alert alert-danger">
                                <h6 class="alert-heading">
                                    <i class="mdi mdi-information-outline"></i> 
                                    O que acontecerá:
                                </h6>
                                <ul class="mb-0">
                                    <li>O produto será marcado como excluído (soft delete)</li>
                                    <li>A imagem do produto será removida do servidor</li>
                                    <li>Todos os extras associados serão removidos</li>
                                    <li>O produto não aparecerá mais nas listagens normais</li>
                                    <li><strong>Esta ação pode ser desfeita posteriormente</strong></li>
                                </ul>
                            </div>

                            <form action="<?= site_url("admin/produtos/deletar/$produto->id") ?>" method="post" id="form-excluir">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="confirmar-exclusao" required>
                                        <label class="form-check-label" for="confirmar-exclusao">
                                            Eu entendo as consequências e desejo prosseguir com a exclusão
                                        </label>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <a href="<?= base_url("admin/produtos/show/$produto->id") ?>" class="btn btn-secondary mr-3">
                                        <i class="mdi mdi-arrow-left"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-danger" id="btn-excluir" disabled>
                                        <i class="mdi mdi-delete"></i> Confirmar Exclusão
                                    </button>
                                </div>
                            </form>
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
<script>
    $(document).ready(function () {
        // Habilita/desabilita o botão de exclusão baseado no checkbox
        $('#confirmar-exclusao').on('change', function() {
            const $btnExcluir = $('#btn-excluir');
            
            if ($(this).is(':checked')) {
                $btnExcluir.prop('disabled', false);
            } else {
                $btnExcluir.prop('disabled', true);
            }
        });
        
        // Confirmação final antes de enviar
        $('#form-excluir').on('submit', function(e) {
            if (!confirm('Tem certeza absoluta que deseja excluir este produto?\n\nEsta ação removerá a imagem e todos os extras associados.')) {
                e.preventDefault();
                return false;
            }
            
            // Mostra loading
            const $btnExcluir = $('#btn-excluir');
            $btnExcluir.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Excluindo...');
        });
    });
</script>
<?php echo $this->endSection(); ?>