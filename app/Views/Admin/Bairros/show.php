<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>

<?php echo $this->endSection(); ?>

<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Detalhes do Bairro</h4>
                    <a href="<?= base_url('admin/bairros') ?>" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                    </a>
                </div>

                <div class="row">
                    <!-- Informações Básicas -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informações do Bairro</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td><?= esc($bairro->id) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nome:</strong></td>
                                        <td><?= esc($bairro->nome) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Slug:</strong></td>
                                        <td><label class="badge badge-info"><?= esc($bairro->slug) ?></label></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cidade:</strong></td>
                                        <td><?= esc($bairro->cidade) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Valor de Entrega:</strong></td>
                                        <td>
                                            <span class="text-success font-weight-bold">
                                                R$ <?= number_format($bairro->valor_entrega, 2, ',', '.') ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Status e Configurações -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Status e Configurações</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <?php if ($bairro->ativo): ?>
                                                <label class="badge badge-success">Ativo</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Inativo</label>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Disponibilidade:</strong></td>
                                        <td>
                                            <?php if ($bairro->ativo && $bairro->deletado_em == null): ?>
                                                <label class="badge badge-success">
                                                    <i class="mdi mdi-truck-delivery"></i> Disponível para entrega
                                                </label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">
                                                    <i class="mdi mdi-truck-remove"></i> Indisponível
                                                </label>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Data de Criação:</strong></td>
                                        <td>
                                            <?php if ($bairro->criado_em): ?>
                                                <?= $bairro->criado_em->humanize() ?>
                                                <br>
                                                <small class="text-muted"><?= $bairro->criado_em->humanize() ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Última Atualização:</strong></td>
                                        <td>
                                            <?php if ($bairro->atualizado_em && $bairro->atualizado_em != $bairro->criado_em): ?>
                                                <?= $bairro->atualizado_em->humanize() ?>
                                                <br>
                                                <small class="text-muted"><?= $bairro->atualizado_em->humanize() ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">Nunca atualizado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php if ($bairro->deletado_em): ?>
                                    <tr>
                                        <td><strong>Data de Exclusão:</strong></td>
                                        <td>
                                            <span class="text-danger">
                                                <?= $bairro->deletado_em->format('d/m/Y H:i:s') ?>
                                                <br>
                                                <small><?= $bairro->deletado_em->humanize() ?></small>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações Adicionais -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informações Adicionais</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="alert alert-info">
                                            <h6><i class="mdi mdi-map-marker-radius"></i> Área de Cobertura</h6>
                                            <p class="mb-0">
                                                Este bairro está localizado em <strong><?= esc($bairro->cidade) ?></strong> 
                                                e tem valor de entrega de <strong>R$ <?= number_format($bairro->valor_entrega, 2, ',', '.') ?></strong>.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-warning">
                                            <h6><i class="mdi mdi-truck-delivery"></i> Status de Entrega</h6>
                                            <p class="mb-0">
                                                <?php if ($bairro->ativo && $bairro->deletado_em == null): ?>
                                                    Entregas <strong>ativas</strong> para este bairro.
                                                <?php else: ?>
                                                    Entregas <strong>suspensas</strong> para este bairro.
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-secondary">
                                            <h6><i class="mdi mdi-link-variant"></i> URL Slug</h6>
                                            <p class="mb-0">
                                                Slug: <code><?= esc($bairro->slug) ?></code>
                                                <br>
                                                <small class="text-muted">Usado para URLs facilitadas</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="text-center">
                            <a href="<?= base_url('admin/bairros') ?>" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                            </a>
                            
                            <?php if ($bairro->deletado_em == null): ?>
                                <a href="<?= site_url("admin/bairros/editar/{$bairro->id}") ?>" class="btn btn-primary ml-2">
                                    <i class="mdi mdi-pencil"></i> Editar Bairro
                                </a>
                                <a href="<?= site_url("admin/bairros/excluir/{$bairro->id}") ?>" class="btn btn-danger ml-2">
                                    <i class="mdi mdi-delete"></i> Excluir Bairro
                                </a>
                            <?php else: ?>
                                <form action="<?= site_url("admin/bairros/desfazer-exclusao/{$bairro->id}") ?>" 
                                      method="post" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Tem certeza que deseja restaurar este bairro?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-warning ml-2">
                                        <i class="mdi mdi-undo"></i> Restaurar Bairro
                                    </button>
                                </form>
                            <?php endif; ?>
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
        // Confirmação para restauração
        $('form[action*="desfazer-exclusao"]').on('submit', function(e) {
            if (!confirm('Tem certeza que deseja restaurar este bairro?\\nEle voltará a ficar disponível no sistema.')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>

<?php echo $this->endSection(); ?>