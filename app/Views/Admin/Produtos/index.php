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

    /* Estilo para preço */
    .preco-produto {
        font-weight: 600;
        color: #28a745;
    }
    
    /* Responsividade para tabela de produtos */
    @media (max-width: 991px) {
        .table th:nth-child(6), /* Data */
        .table td:nth-child(6) {
            display: none;
        }
    }
    
    @media (max-width: 768px) {
        .table th:nth-child(4), /* Categoria */
        .table td:nth-child(4) {
            display: none;
        }
        
        .table th:last-child,
        .table td:last-child {
            width: auto;
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .btn-group .btn {
            width: 100%;
            padding: 6px 10px;
        }
    }
    
    @media (max-width: 576px) {
        .table th:nth-child(1), /* ID */
        .table td:nth-child(1),
        .table th:nth-child(2), /* Imagem */
        .table td:nth-child(2) {
            display: none;
        }
        
        .table th,
        .table td {
            padding: 8px 6px;
            font-size: 0.85rem;
        }
        
        .preco-produto {
            font-size: 0.9rem;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 4px 6px;
        }
    }
    
    /* Header responsivo */
    @media (max-width: 576px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 15px;
        }
        
        .d-flex.justify-content-between .btn {
            width: 100%;
        }
        
        .card-title {
            font-size: 1.1rem;
        }
        
        .card-description {
            font-size: 0.85rem;
        }
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
                        <h4 class="card-title mb-1">Relatório de produtos</h4>
                        <p class="card-description mb-0">
                            Dados dos produtos cadastrados
                        </p>
                    </div>
                    <a href="<?= site_url("admin/produtos/criar") ?>" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                        Cadastrar
                    </a>
                </div>

                <div class="table-responsive pt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Categoria</th>
                                <th>Preço</th>
                                <th>Data de criação</th>
                                <th>Ativo</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($produtos)): ?>
                                <?php foreach ($produtos as $produto): ?>
                                    <tr class="produto-row" data-id="<?= $produto->id ?>">
                                        <td><?= esc($produto->id) ?></td>
                                        <td>
                                            <?php if (!empty($produto->imagem)): ?>
                                                <img src="<?= base_url('uploads/produtos/' . $produto->imagem) ?>" 
                                                     alt="<?= esc($produto->nome) ?>" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px; border-radius: 4px;">
                                                    <i class="mdi mdi-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($produto->nome) ?></td>
                                        <td>
                                            <?php if (isset($produto->categoria_nome) && !empty($produto->categoria_nome)): ?>
                                                <span class="badge badge-info"><?= esc($produto->categoria_nome) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="preco-produto">
                                                R$ <?= isset($produto->preco) ? number_format($produto->preco, 2, ',', '.') : '0,00' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (isset($produto->criado_em) && is_object($produto->criado_em)): ?>
                                                <?= esc($produto->criado_em->humanize()) ?>
                                            <?php else: ?>
                                                <?= isset($produto->criado_em) ? date('d/m/Y H:i', strtotime($produto->criado_em)) : 'N/A' ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($produto->ativo): ?>
                                                <label class="badge badge-success">Ativo</label>
                                            <?php else: ?>
                                                <label class="badge badge-danger">Inativo</label>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botão Visualizar -->
                                                <a href="<?= site_url("admin/produtos/show/$produto->id") ?>" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <!-- Botão Editar -->
                                                <a href="<?= site_url("admin/produtos/editar/$produto->id") ?>" 
                                                   class="btn btn-primary btn-sm" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Botão Excluir -->
                                                <a href="<?= site_url("admin/produtos/excluir/$produto->id") ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   title="Excluir"
                                                   onclick="return confirm('Tem certeza que deseja excluir o produto <?= esc($produto->nome) ?>?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Nenhum produto encontrado</td>
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
            var produtoNome = $(this).closest('tr').find('td:nth-child(3)').text();
            if (!confirm('Tem certeza que deseja excluir o produto "' + produtoNome + '"?\nEsta ação ocasionará danos a outros setores associado a esse campo!')) {
                e.preventDefault();
                return false;
            }
        });



        // Formata os preços na tabela
        $('.preco-produto').each(function() {
            var preco = $(this).text().trim();
            if (preco === 'R$ 0,00') {
                $(this).addClass('text-muted');
            }
        });
    });
</script>

<?php echo $this->endSection(); ?>