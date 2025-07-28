<?php echo $this->extend('Admin/layout/principal'); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('estilos'); ?>

<style>
    .extra-row:hover {
        background-color: #f8f9fa !important;
        transition: background-color 0.2s ease;
    }

    .extra-row {
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
        width: 140px;
        text-align: center;
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
                <h4 class="card-title">Relatório de extras</h4>
                <p class="card-description">
                    Dados dos extras dos produtos
                </p>

                <a href="<?= site_url("admin/extras/criar") ?>" class="btn btn-success float-right">
                    <i class="mdi mdi-plus btn-icon-prepend"></i>
                    Cadastrar
                </a>

                <div class="table-responsive pt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Preço</th>
                                <th>Descrição</th>
                                <th>Data de criação</th>
                                <th>Ativo</th>
                                <th>Situação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($extras)): ?>
                                <?php foreach ($extras as $extra): ?>
                                    <tr class="extra-row" data-id="<?= $extra->id ?>">
                                        <td><?= esc($extra->id) ?></td>
                                        <td><?= esc($extra->nome) ?></td>
                                        <td>
                                            <?php if ($extra->preco): ?>
                                                R$ <?= number_format($extra->preco, 2, ',', '.') ?>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($extra->descricao): ?>
                                                <?= esc(substr($extra->descricao, 0, 50)) ?><?= strlen($extra->descricao) > 50 ? '...' : '' ?>
                                            <?php else: ?>
                                                <span class="text-muted">Não informada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($extra->criado_em->humanize()) ?></td>
                                        <td>
                                            <?php if ($extra->ativo && $extra->deletado_em == null): ?>
                                                <label class="badge badge-success">Ativo</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Inativo</label>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($extra->ativo && $extra->deletado_em == null): ?>
                                                <label class="badge badge-success">Disponivel</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Indisponivel</label>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botão Visualizar -->
                                                <a href="<?= site_url("admin/extras/show/$extra->id") ?>" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Visualizar">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                
                                                <?php if ($extra->deletado_em == null): ?>
                                                    <!-- Botão Editar -->
                                                    <a href="<?= site_url("admin/extras/editar/$extra->id") ?>" 
                                                       class="btn btn-primary btn-sm" 
                                                       title="Editar">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    
                                                    <!-- Botão Excluir -->
                                                    <a href="<?= site_url("admin/extras/excluir/$extra->id") ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       title="Excluir"
                                                       onclick="return confirm('Tem certeza que deseja excluir a extra <?= esc($extra->nome) ?>?')">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <!-- Botão Restaurar -->
                                                    <form action="<?= site_url("admin/extras/desfazerExclusao/$extra->id") ?>" 
                                                          method="post" 
                                                          style="display: inline;" 
                                                          onsubmit="return confirm('Tem certeza que deseja restaurar a extra <?= esc($extra->nome) ?>?')">
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
                                    <td colspan="8" class="text-center">Nenhuma extra encontrada</td>
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
        $('.extra-row').hover(
                function () {
                    $(this).addClass('table-active');
                },
                function () {
                    $(this).removeClass('table-active');
                }
        );

        // Adiciona confirmação para exclusão
        $('.btn-danger').on('click', function(e) {
            var extraNome = $(this).closest('tr').find('td:nth-child(2)').text();
            if (!confirm('Tem certeza que deseja excluir a extra "' + extraNome + '"?\nEsta ação ocasionará danos a outros setores associado a esse campo!')) {
                e.preventDefault();
                return false;
            }
        });

        // Adiciona confirmação para restauração
        $('form[action*="desfazerExclusao"]').on('submit', function(e) {
            var extraNome = $(this).closest('tr').find('td:nth-child(2)').text();
            if (!confirm('Tem certeza que deseja restaurar a extra "' + extraNome + '"?')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>

<?php echo $this->endSection(); ?>
