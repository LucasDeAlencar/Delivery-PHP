<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?>
<?php echo $titulo; ?>
<?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>

<style>
    /* Coluna de ações com largura fixa */
    .table th:last-child,
    .table td:last-child {
        width: 140px;
        text-align: center;
    }
    
    /* Estilos para área de pesquisa */
    .search-form {
        margin-bottom: 20px;
    }
    
    .search-form .form-group {
        margin-bottom: 0;
    }
    
    .search-form .form-control {
        border-radius: 4px;
    }
    
    .btn-group-actions {
        gap: 8px;
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
                        <h4 class="card-title mb-1">Relatório de extras</h4>
                        <p class="card-description mb-0">
                            Dados dos extras dos produtos
                        </p>
                    </div>
                    <div class="d-flex gap-2 btn-group-actions">
                        <a href="<?= site_url("admin/extras/associar-categoria") ?>" class="btn btn-warning">
                            <i class="fas fa-link"></i>
                            Associar por Categoria
                        </a>
                        <a href="<?= site_url("admin/extras/criar") ?>" class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            Cadastrar
                        </a>
                    </div>
                </div>

                <!-- Área de Pesquisa -->
                <div class="search-form">
                    <form method="GET" action="<?= site_url('admin/extras') ?>" class="d-flex gap-2 align-items-end">
                        <div class="form-group flex-grow-1">
                            <label for="search">Pesquisar:</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   placeholder="Digite o nome ou descrição do extra..."
                                   value="<?= esc($search ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="ativo">Status:</label>
                            <select class="form-control" id="ativo" name="ativo">
                                <option value="">Todos</option>
                                <option value="1" <?= ($ativo === '1') ? 'selected' : '' ?>>Ativo</option>
                                <option value="0" <?= ($ativo === '0') ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                                Pesquisar
                            </button>
                        </div>
                        <?php if ($search || $ativo !== null): ?>
                            <div class="form-group">
                                <a href="<?= site_url('admin/extras') ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                    Limpar
                                </a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>

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
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <?php if ($extra->deletado_em == null): ?>
                                                    <!-- Botão Editar -->
                                                    <a href="<?= site_url("admin/extras/editar/$extra->id") ?>" 
                                                       class="btn btn-primary btn-sm" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Botão Excluir -->
                                                    <a href="<?= site_url("admin/extras/excluir/$extra->id") ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       title="Excluir"
                                                       onclick="return confirm('Tem certeza que deseja excluir a extra ' + <?= json_encode($extra->nome) ?> + '?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php else: ?>
                                                     <!-- Botão Restaurar -->
                                                     <form action="<?= site_url("admin/extras/desfazer-exclusao/$extra->id") ?>" 
                                                           method="post" 
                                                           style="display: inline;" 
                                                            onsubmit="return confirm('Tem certeza que deseja restaurar a extra ' + <?= json_encode($extra->nome) ?> + '?')">
                                                         <?= csrf_field() ?>
                                                         <button type="submit" 
                                                                 class="btn btn-success btn-sm" 
                                                                 title="Restaurar">
                                                             <i class="fas fa-undo"></i>
                                                         </button>
                                                     </form>
                                                     <!-- Botão Apagar Definitivamente -->
                                                     <form action="<?= site_url("admin/extras/deletar-definitivamente/$extra->id") ?>" 
                                                           method="post" 
                                                           style="display: inline;" 
                                                           onsubmit="return confirm('ATENÇÃO! Esta ação é IRREVERSÍVEL!\n\nTem certeza que deseja apagar a extra ' + <?= json_encode($extra->nome) ?> + ' DEFINITIVAMENTE?')">
                                                         <?= csrf_field() ?>
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
