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
                            <div class="block produto-item" 
                                 data-produto-id="<?= esc($produto->id) ?>"
                                 data-produto-nome="<?= esc($produto->nome) ?>"
                                 data-produto-preco="<?= esc($produto->preco) ?>"
                                 data-produto-categoria="<?= esc($produto->categoria_nome) ?>"
                                 data-produto-descricao="<?= esc($produto->descricao ?? '') ?>"
                                 data-produto-imagem="<?= !empty($produto->imagem) ? base_url('uploads/produtos/' . esc($produto->imagem)) : '' ?>"
                                 style="cursor: pointer;">
                                <div class="content">
                                    <div class="filter_item_img">
                                        <?php if (!empty($produto->imagem)): ?>
                                            <img src="<?= base_url('uploads/produtos/' . esc($produto->imagem)) ?>" 
                                                 alt="<?= esc($produto->nome) ?>" 
                                                 class="img-fluid">
                                        <?php else: ?>
                                            <div class="no-image-placeholder d-flex align-items-center justify-content-center" style="height: 250px; background: #f8f9fa;">
                                                <i class="flaticon-pizza-1 fa-3x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <i class="flaticon-pizza-1"></i>
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

<!-- Modal de Compra - Layout Simétrico -->
<div class="modal fade" id="modalCompra" tabindex="-1" role="dialog" aria-labelledby="modalCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCompraLabel">
                    <i class="flaticon-pizza-1"></i>Detalhes do Produto
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Grid Simétrico -->
                <div class="modal-grid">
                    <!-- Lado Esquerdo - Imagem -->
                    <div class="modal-left">
                        <div class="produto-imagem">
                            <img id="modal-produto-imagem" src="" alt="" class="d-none">
                            <div id="modal-produto-placeholder">
                                <i class="flaticon-pizza-1"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lado Direito - Detalhes -->
                    <div class="modal-right">
                        <div class="produto-detalhes">
                            <h4 id="modal-produto-nome"></h4>
                            <div id="modal-produto-categoria"></div>
                            <p id="modal-produto-descricao"></p>
                            
                            <!-- Preço -->
                            <div class="preco-section">
                                <div id="modal-produto-preco"></div>
                            </div>
                        </div>
                        
                        <!-- Controles -->
                        <div class="controles-section">
                            <!-- Quantidade -->
                            <div class="form-group">
                                <label class="form-label">Quantidade</label>
                                <div class="quantidade-control">
                                    <button class="btn" type="button" id="btn-diminuir">-</button>
                                    <input type="number" id="quantidade" value="1" min="1" readonly>
                                    <button class="btn" type="button" id="btn-aumentar">+</button>
                                </div>
                            </div>
                            
                            <!-- Observações -->
                            <div class="form-group">
                                <label for="observacoes" class="form-label">Observações</label>
                                <textarea id="observacoes" rows="3" placeholder="Alguma observação especial?"></textarea>
                            </div>
                            
                            <!-- Total -->
                            <div class="total-section">
                                <div class="form-label">Total</div>
                                <div id="modal-total"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn" id="btn-adicionar-carrinho">
                    <i class="flaticon-pizza-1"></i>Adicionar ao Carrinho
                </button>
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