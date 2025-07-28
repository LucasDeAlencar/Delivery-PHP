<?php echo $this->extend('Admin/layout/principal'); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('estilos'); ?>

<style>
    .usuario-row:hover {
        background-color: #f8f9fa !important;
        transition: background-color 0.2s ease;
    }

    .usuario-row {
        transition: background-color 0.2s ease;
    }

    .table-responsive .table tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Estilos para os botões de ação */
    .btn-group .btn {
        margin-right: 2px;
        border-radius: 4px !important;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    /* Ajusta o tamanho dos botões */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Efeito hover nos botões */
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: all 0.2s ease;
    }

    /* Coluna de ações com largura fixa */
    .table th:last-child,
    .table td:last-child {
        width: 180px;
        text-align: center;
    }

    /* Badge de admin */
    .badge-admin {
        background-color: #6f42c1;
        color: white;
    }
</style>

<?php echo $this->endSection(); ?>


<!-- Área de Conteudos -->
<?php echo $this->section('conteudos'); ?>


<!-- Terceira tabela com bordas -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Relatório de Usuários</h4>
                <p class="card-description">
                    Dados dos usuários
                </p>

                <a href="<?= site_url("admin/usuarios/criar") ?>" class="btn btn-success float-right">
                    <i class="mdi mdi-plus btn-icon-prepend"></i>
                    Cadastrar
                </a>

                <div class="table-responsive pt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome Completo</th>
                                <th>E-mail</th>
                                <th>CPF</th>
                                <th>Perfil</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($usuarios)): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr class="usuario-row" data-id="<?= $usuario->id ?>">
                                        <td><?= esc($usuario->id) ?></td>
                                        <td><?= esc($usuario->nome) ?></td>
                                        <td><?= esc($usuario->email) ?></td>
                                        <td><?= esc($usuario->cpf ?? 'Não informado') ?></td>
                                        <td>
                                            <?php if ($usuario->is_admin): ?>
                                                <span class="badge badge-admin">Admin</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Usuário</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($usuario->deletado_em == null): ?>
                                                <?php if ($usuario->ativo): ?>
                                                    <span class="badge badge-success">Ativo</span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Inativo</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Excluído</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botão Visualizar -->
                                                <a href="<?= site_url("admin/usuarios/show/$usuario->id") ?>" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Visualizar">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                
                                                <?php if ($usuario->deletado_em == null): ?>
                                                    <!-- Botão Editar -->
                                                    <a href="<?= site_url("admin/usuarios/editar/$usuario->id") ?>" 
                                                       class="btn btn-primary btn-sm" 
                                                       title="Editar">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    
                                                    <!-- Botão Excluir (apenas se não for admin) -->
                                                    <?php if (!$usuario->is_admin): ?>
                                                        <a href="<?= site_url("admin/usuarios/excluir/$usuario->id") ?>" 
                                                           class="btn btn-danger btn-sm" 
                                                           title="Excluir"
                                                           onclick="return confirm('Tem certeza que deseja excluir o usuário <?= esc($usuario->nome) ?>?')">
                                                            <i class="mdi mdi-delete"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="btn btn-secondary btn-sm disabled" 
                                                              title="Administradores não podem ser excluídos">
                                                            <i class="mdi mdi-shield-account"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <!-- Botão Restaurar -->
                                                    <form action="<?= site_url("admin/usuarios/desfazer-exclusao/$usuario->id") ?>" 
                                                          method="post" 
                                                          style="display: inline;" 
                                                          onsubmit="return confirm('Tem certeza que deseja restaurar o usuário <?= esc($usuario->nome) ?>?')">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" 
                                                                class="btn btn-warning btn-sm" 
                                                                title="Restaurar">
                                                            <i class="mdi mdi-undo"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum usuário encontrado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
        // Adiciona efeito hover nas linhas
        $('.usuario-row').hover(
                function () {
                    $(this).addClass('table-active');
                },
                function () {
                    $(this).removeClass('table-active');
                }
        );

        // Adiciona confirmação para exclusão
        $('.btn-danger').on('click', function(e) {
            var usuarioNome = $(this).closest('tr').find('td:nth-child(2)').text();
            if (!confirm('Tem certeza que deseja excluir o usuário "' + usuarioNome + '"?\n\nEsta ação não pode ser desfeita!')) {
                e.preventDefault();
                return false;
            }
        });

        // Adiciona confirmação para restauração
        $('form[action*="desfazer-exclusao"]').on('submit', function(e) {
            var usuarioNome = $(this).closest('tr').find('td:nth-child(2)').text();
            if (!confirm('Tem certeza que deseja restaurar o usuário "' + usuarioNome + '"?')) {
                e.preventDefault();
                return false;
            }
        });

        // Tooltip para botões desabilitados
        $('[title]').tooltip();
    });
</script>

<?php echo $this->endSection(); ?>
