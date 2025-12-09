<?php echo $this->extend('Admin/layout/principal'); ?>


<!-- Área de Título  -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('estilos'); ?>


<?php echo $this->endSection(); ?>


<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Detalhes do Extra</h4>
                    <a href="<?= base_url('admin/extras') ?>" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                    </a>
                </div>

                <div class="row">
                    <!-- Informações Básicas -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informações do Extra</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td><?= esc($extra->id) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nome:</strong></td>
                                        <td><?= esc($extra->nome) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Slug:</strong></td>
                                        <td><?= esc($extra->slug ?? 'Não informado') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Preço:</strong></td>
                                        <td>
                                            <?php if ($extra->preco): ?>
                                                R$ <?= number_format($extra->preco, 2, ',', '.') ?>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Status e Datas -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Status e Datas</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <?php if ($extra->ativo && $extra->deletado_em == null): ?>
                                                <label class="badge badge-success">Ativo</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Inativo</label>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Situação:</strong></td>
                                        <td>
                                            <?php if ($extra->deletado_em == null): ?>
                                                <label class="badge badge-success">Disponível</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Excluída</label>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Data de Criação:</strong></td>
                                        <td>
                                            <?php if ($extra->criado_em): ?>
                                                <?= date('d/m/Y H:i:s', strtotime($extra->criado_em)) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Última Atualização:</strong></td>
                                        <td>
                                            <?php if ($extra->atualizado_em): ?>
                                                <?= date('d/m/Y H:i:s', strtotime($extra->atualizado_em)) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php if ($extra->deletado_em): ?>
                                    <tr>
                                        <td><strong>Data de Exclusão:</strong></td>
                                        <td>
                                            <?= date('d/m/Y H:i:s', strtotime($extra->deletado_em)) ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descrição -->
                <?php if ($extra->descricao): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Descrição</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0"><?= nl2br(esc($extra->descricao)) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Botões de Ação -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="text-center">
                            <a href="<?= base_url('admin/extras') ?>" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                            </a>
                            <?php if ($extra->deletado_em == null): ?>
                                <a href="<?= base_url("admin/extras/editar/$extra->id") ?>" class="btn btn-primary ml-2">
                                    <i class="mdi mdi-pencil"></i> Editar Extra
                                </a>
                                <a href="<?= base_url("admin/extras/excluir/$extra->id") ?>" class="btn btn-danger ml-2">
                                    <i class="mdi mdi-delete"></i> Excluir Extra
                                </a>
                            <?php else: ?>
                                <form action="<?= base_url("admin/extras/desfazer-exclusao/$extra->id") ?>" method="post" style="display: inline;" class="ml-2">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="mdi mdi-undo"></i> Restaurar Extra
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


<?php echo $this->endSection(); ?>