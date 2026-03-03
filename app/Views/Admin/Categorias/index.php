<?php echo $this->extend('Admin/layout/principal'); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('estilos'); ?>

<style>
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-1">Relatório de categorias</h4>
                        <p class="card-description mb-0">
                            Dados das categorias
                        </p>
                    </div>
                    <a href="<?= site_url("admin/categorias/criar") ?>" class="btn btn-success">
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
                                <th>Ordem</th>
                                <th>Data de criação</th>
                                <th>Ativo</th>
                                <th>Situação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categorias)): ?>
                                <?php foreach ($categorias as $categoria): ?>
                                    <tr class="categoria-row" data-id="<?= $categoria->id ?>">
                                        <td><?= esc($categoria->id) ?></td>
                                        <td><?= esc($categoria->nome) ?></td>
                                        <td>
                                            <input type="number" 
                                                   class="form-control form-control-sm ordem-input" 
                                                   value="<?= esc($categoria->ordem ?? 0) ?>" 
                                                   data-id="<?= $categoria->id ?>"
                                                   min="0" 
                                                   style="width: 80px;">
                                        </td>
                                        <td><?= esc($categoria->criado_em) ?></td>
                                        <td>
                                            <?php if ($categoria->ativo && $categoria->deletado_em == null): ?>
                                                <label class="badge badge-success">Ativo</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Inativo</label>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($categoria->ativo && $categoria->deletado_em == null): ?>
                                                <label class="badge badge-success">Disponivel</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Indisponivel</label>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botão Visualizar -->
                                                <a href="<?= site_url("admin/categorias/show/$categoria->id") ?>" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <?php if ($categoria->deletado_em == null): ?>
                                                    <!-- Botão Editar -->
                                                    <a href="<?= site_url("admin/categorias/editar/$categoria->id") ?>" 
                                                       class="btn btn-primary btn-sm" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Botão Excluir -->
                                                    <a href="<?= site_url("admin/categorias/excluir/$categoria->id") ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       title="Excluir"
                                                       onclick="return confirm('Tem certeza que deseja excluir a categoria <?= esc($categoria->nome) ?>?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <!-- Botão Restaurar -->
                                                    <form action="<?= site_url("admin/categorias/desfazerExclusao/$categoria->id") ?>" 
                                                          method="post" 
                                                          style="display: inline;" 
                                                          onsubmit="return confirm('Tem certeza que deseja restaurar a categoria <?= esc($categoria->nome) ?>?')">
                                                        <button type="submit" 
                                                                class="btn btn-success btn-sm" 
                                                                title="Restaurar">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </form>
                                                    <!-- Botão Apagar Definitivamente -->
                                                    <form action="<?= site_url("admin/categorias/deletar-definitivamente/$categoria->id") ?>" 
                                                          method="post" 
                                                          style="display: inline;" 
                                                          onsubmit="return confirm('ATENÇÃO! Esta ação é IRREVERSÍVEL!\n\nTem certeza que deseja apagar esta categoria DEFINITIVAMENTE?');">
                                                        <button type="submit" 
                                                                class="btn btn-danger btn-sm" 
                                                                title="Apagar Definitivamente">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Nenhuma categoria encontrada</td>
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
        // Adiciona confirmação para exclusão
        $('.btn-danger').on('click', function(e) {
            var categoriaNome = $(this).closest('tr').find('td:nth-child(2)').text();
            if (!confirm('Tem certeza que deseja excluir a categoria "' + categoriaNome + '"?\nEsta ação ocasionará danos a outros setores associado a esse campo!')) {
                e.preventDefault();
                return false;
            }
        });

        // Adiciona confirmação para restauração
        $('form[action*="desfazerExclusao"]').on('submit', function(e) {
            var categoriaNome = $(this).closest('tr').find('td:nth-child(2)').text();
            if (!confirm('Tem certeza que deseja restaurar a categoria "' + categoriaNome + '"?')) {
                e.preventDefault();
                return false;
            }
        });

        // Edição inline da ordem
        $('.ordem-input').on('change', function() {
            var input = $(this);
            var categoriaId = input.data('id');
            var novaOrdem = input.val();
            
            console.log('Atualizando categoria ID:', categoriaId, 'para ordem:', novaOrdem);
            
            $.ajax({
                url: '<?= site_url('admin/categorias/atualizarOrdem') ?>',
                method: 'POST',
                data: {
                    id: categoriaId,
                    ordem: novaOrdem,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    console.log('Response:', response);
                    if (response.sucesso) {
                        // Recarrega a página para mostrar nova ordenação
                        location.reload();
                    } else {
                        alert('Erro: ' + (response.msg || 'Erro desconhecido'));
                        input.focus();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', xhr.responseText);
                    alert('Erro AJAX: ' + error);
                    input.focus();
                }
            });
        });
    });
</script>

<?php echo $this->endSection(); ?>
