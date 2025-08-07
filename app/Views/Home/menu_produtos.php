<?php if (!empty($categorias) || !empty($produtos)): ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Menu de Filtros por Categoria -->
            <div class="menu_filter text-center">
                <ul>
                    <li class="active">
                        <a href="#" class="filter-button" data-filter="all">Todos</a>
                    </li>
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <li>
                                <a href="#" class="filter-button" data-filter="<?= esc($categoria->slug) ?>">
                                    <?= esc($categoria->nome) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Grid de Produtos -->
            <div class="row">
                <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 filtr-item filter all <?= esc($produto->categoria_slug) ?>" data-category="<?= esc($produto->categoria_slug) ?>">
                            <div class="block">
                                <div class="content">
                                    <div class="filter_item_img">
                                        <?php if (!empty($produto->imagem)): ?>
                                            <img src="<?= base_url('uploads/produtos/' . esc($produto->imagem)) ?>" 
                                                 alt="<?= esc($produto->nome) ?>" 
                                                 class="img-fluid">
                                        <?php else: ?>
                                            <div class="no-image-placeholder d-flex align-items-center justify-content-center" style="height: 250px; background: #f8f9fa;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <i class="fas fa-eye"></i>
                                    </div>
                                    <div class="info">
                                        <div class="name"><?= esc($produto->nome) ?></div>
                                        <?php if (!empty($produto->descricao)): ?>
                                            <div class="short">
                                                <?= character_limiter(esc($produto->descricao), 100) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($produto->preco)): ?>
                                            <div class="price-info mt-2">
                                                <strong class="text-warning">R$ <?= number_format($produto->preco, 2, ',', '.') ?></strong>
                                            </div>
                                        <?php endif; ?>
                                        <div class="category-badge">
                                            <small><?= esc($produto->categoria_nome) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h4>Nenhum produto encontrado</h4>
                            <p>Não há produtos cadastrados no momento.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <h4>Sistema em Configuração</h4>
                <p>O catálogo de produtos está sendo configurado. Volte em breve!</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>