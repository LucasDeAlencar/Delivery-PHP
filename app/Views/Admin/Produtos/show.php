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

                <!-- Imagem do Produto -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Imagem do Produto</h5>
                            </div>
                            <div class="card-body text-center">
                                <?php if (!empty($produto->imagem)): ?>
                                    <img src="<?= base_url('uploads/produtos/' . $produto->imagem) ?>" 
                                         alt="<?= esc($produto->nome) ?>" 
                                         class="img-fluid rounded" 
                                         style="max-width: 400px; max-height: 400px; object-fit: cover;">
                                    <p class="text-muted mt-2">Arquivo: <?= esc($produto->imagem) ?></p>
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 300px; height: 300px; margin: 0 auto; border-radius: 8px;">
                                        <div class="text-center">
                                            <i class="mdi mdi-image text-muted" style="font-size: 4rem;"></i>
                                            <p class="text-muted mt-2">Nenhuma imagem cadastrada</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title"><?= $titulo; ?></h4>
                    <a href="<?= base_url('admin/produtos') ?>" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                    </a>
                </div>

                <div class="row">
                    <!-- Informações Básicas -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informações do Produto</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td><?= esc($produto->id) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nome:</strong></td>
                                        <td><?= esc($produto->nome) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Slug:</strong></td>
                                        <td><?= esc($produto->slug ?? 'Não informado') ?></td>
                                    </tr>
                                    </tr>
                                    <tr>
                                        <td><strong>Categoria:</strong></td>
                                        <td><?= esc($produto->categoria ?? 'Não informado') ?></td>
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
                                            <?php if ($produto->ativo && $produto->deletado_em == null): ?>
                                                <label class="badge badge-success">Ativo</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Inativo</label>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Situação:</strong></td>
                                        <td>
                                            <?php if ($produto->deletado_em == null): ?>
                                                <label class="badge badge-success">Disponível</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Excluída</label>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Data de Criação:</strong></td>
                                        <td>
                                            <?php if ($produto->criado_em): ?>
                                                <?= date('d/m/Y H:i:s', strtotime($produto->criado_em)) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Última Atualização:</strong></td>
                                        <td>
                                            <?php if ($produto->atualizado_em): ?>
                                                <?= date('d/m/Y H:i:s', strtotime($produto->atualizado_em)) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php if ($produto->deletado_em): ?>
                                        <tr>
                                            <td><strong>Data de Exclusão:</strong></td>
                                            <td>
                                                <?= date('d/m/Y H:i:s', strtotime($produto->deletado_em)) ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ingredientes -->
                <?php if ($produto->ingredientes): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Ingredientes</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0"><?= nl2br(esc($produto->ingredientes)) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Botões de Ação -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="text-center">
                            <a href="<?= base_url('admin/produtos') ?>" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                            </a>
                            <a href="<?= base_url('admin/produtos/extras/'. $produto->id) ?>" class="btn btn-warning ml-2">
                                <i class="mdi mdi-box-cutter"></i> Extras
                            </a>
                            <a href="<?= base_url('admin/produtos/especificacoes/'. $produto->id) ?>" class="btn btn-info ml-2">
                                <i class="mdi mdi-ruler"></i> Especificações
                            </a>
                            <?php if ($produto->deletado_em == null): ?>
                                <a href="<?= base_url("admin/produtos/editar/$produto->id") ?>" class="btn btn-primary ml-2">
                                    <i class="mdi mdi-pencil"></i> Editar Produto
                                </a>
                                <a href="<?= base_url("admin/produtos/excluir/$produto->id") ?>" class="btn btn-danger ml-2">
                                    <i class="mdi mdi-delete"></i> Excluir Produto
                                </a>
                            <?php else: ?>
                                <form action="<?= base_url("admin/produtos/desfazer-exclusao/$produto->id") ?>" method="post" style="display: inline;" class="ml-2">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="mdi mdi-undo"></i> Restaurar Produto
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