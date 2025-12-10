<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>

<style>
    /* Coluna de ações com largura fixa */
    .table th:last-child,
    .table td:last-child {
        width: 140px;
        text-align: center;
    }
    
    /* Estilos para status */
    .badge {
        font-size: 0.75rem;
    }
    
    /* Truncar texto longo */
    .text-truncate {
        max-width: 200px;
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
                    <div>
                        <h4 class="card-title mb-1">Relatório dos bairros</h4>
                        <p class="card-description mb-0">
                            Dados dos bairros atendidos
                        </p>
                    </div>
                    <a href="<?= site_url("admin/bairros/criar") ?>" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                        Cadastrar
                    </a>
                </div>

                <div class="table-responsive pt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Cidade</th>
                                <th>Valor de entrega</th>
                                <th>Ativo</th>
                                <th>Situação</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($bairros)): ?>
                                <?php foreach ($bairros as $bairro): ?>
                                    <tr class="bairro-row" data-id="<?= $bairro->id ?>">
                                        <td><?= esc($bairro->id) ?></td>
                                        <td><?= esc($bairro->nome) ?></td>
                                        <td><?= esc($bairro->cidade) ?></td>
                                        <td>R$ <?= number_format($bairro->valor_entrega, 2, ',', '.') ?></td>
                                        <td>
                                            <?php if ($bairro->ativo): ?>
                                                <label class="badge badge-success">Ativo</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Inativo</label>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($bairro->ativo && $bairro->deletado_em == null): ?>
                                                <label class="badge badge-success">Disponível</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Indisponível</label>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botão Visualizar -->
                                                <a href="<?= site_url("admin/bairros/show/{$bairro->id}") ?>" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <?php if ($bairro->deletado_em == null): ?>
                                                    <!-- Botão Editar -->
                                                    <a href="<?= site_url("admin/bairros/editar/{$bairro->id}") ?>" 
                                                       class="btn btn-primary btn-sm" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Botão Excluir -->
                                                    <a href="<?= site_url("admin/bairros/excluir/{$bairro->id}") ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       title="Excluir"
                                                       onclick="return confirm('Tem certeza que deseja excluir o bairro <?= esc($bairro->nome) ?>?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <!-- Botão Restaurar -->
                                                    <form action="<?= site_url("admin/bairros/desfazer-exclusao/{$bairro->id}") ?>" 
                                                          method="post" 
                                                          style="display: inline;" 
                                                          onsubmit="return confirm('Tem certeza que deseja restaurar o bairro <?= esc($bairro->nome) ?>?')">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" 
                                                                class="btn btn-warning btn-sm" 
                                                                title="Restaurar">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum bairro encontrado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginação -->
                <?php if (isset($pager)): ?>
                    <div class="d-flex justify-content-center mt-3">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>

<script>
    $(document).ready(function () {
        // Adiciona confirmação para exclusão
        $('.btn-danger').on('click', function(e) {
            var bairroNome = $(this).closest('tr').find('td:nth-child(2)').text().trim();
            if (!confirm('Tem certeza que deseja excluir o bairro "' + bairroNome + '"?\\nEsta ação pode afetar entregas em andamento!')) {
                e.preventDefault();
                return false;
            }
        });

        // Adiciona confirmação para restauração
        $('form[action*="desfazer-exclusao"]').on('submit', function(e) {
            var bairroNome = $(this).closest('tr').find('td:nth-child(2)').text().trim();
            if (!confirm('Tem certeza que deseja restaurar o bairro "' + bairroNome + '"?')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>

<?php echo $this->endSection(); ?>