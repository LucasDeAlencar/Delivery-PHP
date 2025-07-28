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
                    <h4 class="card-title">Detalhes do Usuário</h4>
                    <a href="<?= base_url('admin/usuarios') ?>" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                    </a>
                </div>

                <div class="row">
                    <!-- Informações Básicas -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informações Pessoais</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td><?= esc($usuario->id) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nome Completo:</strong></td>
                                        <td><?= esc($usuario->nome) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>E-mail:</strong></td>
                                        <td><?= esc($usuario->email) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>CPF:</strong></td>
                                        <td><?= esc($usuario->cpf) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Telefone:</strong></td>
                                        <td><?= esc($usuario->telefone) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Status e Permissões -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Status e Permissões</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <?php if ($usuario->ativo): ?>
                                                <label class="badge badge-success">Ativo</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Inativo</label>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo de Usuário:</strong></td>
                                        <td>
                                            <?php if ($usuario->is_admin): ?>
                                                <label class="badge badge-warning">Administrador</label>
                                            <?php else: ?>
                                                <label class="badge badge-info">Usuário Comum</label>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Data de Criação:</strong></td>
                                        <td>
                                            <?php if ($usuario->criado_em && $usuario->deletado_em != null): ?>
                                                <?= $usuario->criado_em->humanize() ?>
                                            <?php elseif ($usuario->deletado_em == null): ?>
                                                <span class="font-weight-bold">Atualizado:</span>
                                                <?= $usuario->atualizado_em; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Última Atualização:</strong></td>
                                        <td>
                                            <?php if ($usuario->atualizado_em): ?>
                                                <?= $usuario->criado_em ?>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações de Segurança -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informações de Segurança</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Hash de Ativação:</strong></p>
                                        <p class="text-muted small">
                                            <?= $usuario->ativacao_hash ? esc(substr($usuario->ativacao_hash, 0, 20)) . '...' : 'Não definido' ?>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Hash de Reset:</strong></p>
                                        <p class="text-muted small">
                                            <?= $usuario->reset_hash ? esc(substr($usuario->reset_hash, 0, 20)) . '...' : 'Não definido' ?>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Reset Expira em:</strong></p>
                                        <p class="text-muted small">
                                            <?php if ($usuario->reset_expira_em): ?>
                                                <?= date('d/m/Y H:i:s', strtotime($usuario->reset_expira_em)) ?>
                                            <?php else: ?>
                                                Não definido
                                            <?php endif; ?>
                                        </p>
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
                            <a href="<?= base_url('admin/usuarios') ?>" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                            </a>
                            <a href="<?= base_url("admin/usuarios/editar/$usuario->id") ?>" class="btn btn-primary ml-2">
                                <i class="mdi mdi-pencil"></i> Editar Usuário
                            </a>
                            <a href="<?= base_url("admin/usuarios/excluir/$usuario->id") ?>" class="btn btn-danger ml-2">
                                <i class="mdi mdi-delete"></i> Excluir Usuário
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


<?php echo $this->endSection(); ?>