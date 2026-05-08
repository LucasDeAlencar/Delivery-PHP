<?php if (!empty($categorias) || !empty($produtos)): ?>
<div class="container-fluid">
    <!-- Título -->
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-md-10 col-lg-8 text-center">
            <h2 class="mb-3">Nossos Produtos</h2>
            <p class="mb-0">Descubra nossos pratos especiais, preparados com ingredientes frescos e muito sabor.</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="menu_filter text-center mb-4">
        <ul>
            <li class="active"><a href="javascript:void(0)" class="filter-button" data-filter="all">Todos</a></li>
            <?php foreach ($categorias ?? [] as $categoria): ?>
                <li><a href="javascript:void(0)" class="filter-button" data-filter="<?= esc($categoria->slug) ?>"><?= esc($categoria->nome) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Grid -->
    <div class="row">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="col-lg-4 col-md-6 col-6 filtr-item" data-category="<?= esc($produto->categoria_slug) ?>">
                    <div class="block produto-item <?= $produto->ativo ? '' : 'produto-inativo' ?>"
                         data-produto-id="<?= esc($produto->id) ?>"
                         data-produto-nome="<?= esc($produto->nome) ?>"
                         data-produto-preco="<?= esc($produto->preco) ?>"
                         data-produto-categoria="<?= esc($produto->categoria_nome) ?>"
                         data-categoria-id="<?= esc($produto->categoria_id) ?>"
                         data-produto-descricao="<?= esc($produto->descricao ?? '') ?>"
                         data-produto-imagem="<?= !empty($produto->imagem) ? base_url('uploads/produtos/' . esc($produto->imagem)) : '' ?>"
                         data-produto-ativo="<?= $produto->ativo ? '1' : '0' ?>"
                         data-com-tamanho="<?= !empty($produto->com_tamanho) ? '1' : '0' ?>"
                         data-tamanhos="<?= !empty($produto->tamanhos) ? esc(json_encode(array_map(fn($t) => ['id' => $t->id, 'nome' => $t->nome, 'preco' => $t->preco], $produto->tamanhos))) : '[]' ?>">
                        <div class="content">
                            <div class="filter_item_img">
                                <?php
                                $imgSrc = !empty($produto->imagem)
                                    ? base_url('uploads/produtos/' . esc($produto->imagem))
                                    : ['https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop',
                                       'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=300&fit=crop',
                                       'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400&h=300&fit=crop'][array_rand([0,1,2])];
                                ?>
                                <img src="<?= $imgSrc ?>" alt="<?= esc($produto->nome) ?>" loading="lazy">
                                <?php if (!$produto->ativo): ?>
                                    <div class="produto-indisponivel-overlay">
                                        <span class="produto-indisponivel-texto">Indisponível</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="info">
                                <div class="name"><?= esc($produto->nome) ?></div>
                                <?php if (!empty($produto->descricao)): ?>
                                    <div class="short"><?= character_limiter(esc($produto->descricao), 80) ?></div>
                                <?php endif; ?>
                                <div class="price-info mt-2">
                                    <?php if (!empty($produto->com_tamanho) && !empty($produto->tamanhos)): ?>
                                        <small class="text-muted">A partir de </small>
                                        <strong class="text-warning">R$ <?= number_format(min(array_column((array)$produto->tamanhos, 'preco')), 2, ',', '.') ?></strong>
                                    <?php else: ?>
                                        <strong class="text-warning">R$ <?= number_format($produto->preco, 2, ',', '.') ?></strong>
                                    <?php endif; ?>
                                </div>
                                <div class="category-badge"><small><?= esc($produto->categoria_nome) ?></small></div>
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

<!-- Modal de Compra -->
<div class="modal fade" id="modalCompra" tabindex="-1" role="dialog" aria-labelledby="modalCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="background:#1a1a1a;border:1px solid #333;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.5);">
            <div class="modal-header" style="background:linear-gradient(135deg,#2d2d2d 0%,#1a1a1a 100%);border-bottom:1px solid #333;border-radius:15px 15px 0 0;padding:16px 20px;">
                <h5 class="modal-title text-warning" id="modalCompraLabel" style="font-family:'Poppins',sans-serif;font-weight:600;">
                    <i class="flaticon-pizza-1 mr-2"></i>Finalizar Compra
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar" style="color:#fff;opacity:.7;font-size:1.5rem;font-weight:300;background:none;border:none;padding:0 4px;line-height:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-produto-id">
                <input type="hidden" id="modal-produto-imagem-url">

                <div class="row">
                    <!-- Imagem (desktop) -->
                    <div class="col-md-5 mb-3 mb-md-0">
                        <img id="modal-produto-imagem" src="" alt="" class="img-fluid rounded" style="width:100%;max-height:260px;object-fit:cover;border:1px solid #333;">
                    </div>

                    <!-- Detalhes -->
                    <div class="col-md-7">
                        <!-- Imagem + nome compactos só no mobile -->
                        <div id="modal-produto-imagem-mobile" style="display:none;">
                            <img src="" alt="" id="modal-produto-imagem-mobile-img">
                            <div>
                                <div class="mob-nome" id="modal-produto-nome-mobile"></div>
                                <div class="mob-cat" id="modal-produto-cat-mobile"></div>
                            </div>
                        </div>

                        <h4 id="modal-produto-nome" class="text-light mb-2" style="font-weight:600;font-size:1.1rem;font-family:'Poppins',sans-serif;"></h4>
                        <p id="modal-produto-descricao" class="text-light mb-3" style="opacity:.8;font-size:.9rem;line-height:1.5;"></p>

                        <!-- Preço + Quantidade na mesma linha -->
                        <div class="preco-qtd-row mb-3">
                            <div class="preco-col">
                                <label class="text-light d-block" style="font-size:.82rem;font-weight:600;font-family:'Poppins',sans-serif;">Preço Unitário</label>
                                <div id="modal-produto-preco" class="h5 text-warning mb-0" style="font-weight:700;font-family:'Poppins',sans-serif;"></div>
                                <small class="text-muted" id="modal-produto-preco-extras">Sem extras</small>
                            </div>
                            <div class="qtd-col">
                                <label class="text-light d-block" style="font-size:.82rem;font-weight:600;font-family:'Poppins',sans-serif;">Quantidade</label>
                                <div class="qtd-control">
                                    <button class="btn btn-outline-warning" type="button" id="btn-diminuir" style="border-right:none;border-color:#0055ff;color:#0055ff;">−</button>
                                    <input type="number" class="form-control bg-dark text-warning" id="quantidade" value="1" min="1" max="99" style="border-color:#0055ff;border-left:none;border-right:none;font-size:16px;text-align:center;">
                                    <button class="btn btn-outline-warning" type="button" id="btn-aumentar" style="border-left:none;border-color:#0055ff;color:#0055ff;">+</button>
                                </div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="mb-3 p-3 rounded" style="background:#2d2d2d;border:1px solid #333;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-light" style="font-weight:600;font-family:'Poppins',sans-serif;">Total:</span>
                                <span id="modal-total" class="h5 text-warning mb-0" style="font-weight:700;font-family:'Poppins',sans-serif;"></span>
                            </div>
                            <small class="text-muted" id="modal-total-detalhe"></small>
                        </div>

                        <!-- Tamanhos -->
                        <div class="mb-3" id="container-tamanhos" style="display:none;">
                            <label class="text-light mb-1" style="font-size:.82rem;font-weight:600;"><i class="fas fa-ruler mr-1"></i>Tamanho <span class="text-danger">*</span></label>
                            <div id="tamanhos-opcoes"></div>
                            <small class="text-danger d-none mt-1" id="aviso-tamanho"><i class="fas fa-exclamation-circle mr-1"></i>Selecione um tamanho.</small>
                        </div>

                        <!-- Extras -->
                        <div class="mb-3" id="container-btn-extras" style="display:none;">
                            <button type="button" class="btn btn-block" id="btn-selecionar-extras" style="border:2px solid #0055ff;color:#0055ff;background:transparent;font-weight:600;padding:10px;">
                                <i class="fas fa-plus-circle mr-2"></i><span id="texto-btn-extras">Selecionar Extras</span>
                                <span id="badge-obrigatorio" class="badge badge-danger ml-2" style="display:none;">*Obrigatório</span>
                            </button>
                            <div id="extras-selecionados-resumo" class="mt-2" style="display:none;">
                                <small class="text-warning d-block"><i class="fas fa-check-circle mr-1"></i><span id="contador-extras">0 extras</span></small>
                                <small class="text-muted" id="valor-extras-resumo">+ R$ 0,00</small>
                            </div>
                            <small class="text-danger d-block mt-1" id="aviso-extra-obrigatorio-modal" style="display:none;"></small>
                        </div>

                        <!-- Observações -->
                        <div class="mb-3">
                            <label for="observacoes" class="text-light" style="font-size:.82rem;font-weight:600;font-family:'Poppins',sans-serif;">Observações (opcional)</label>
                            <textarea class="form-control bg-dark text-light" id="observacoes" rows="2" placeholder="Alguma observação especial?" style="border:1px solid #333;resize:none;font-size:16px;"></textarea>
                        </div>

                        <p class="text-warning mb-0" style="font-size:.8rem;"><i class="fas fa-tag mr-1"></i><span id="modal-categoria-texto"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background:#333;border:1px solid #555;color:#ccc;flex:1;font-family:'Poppins',sans-serif;">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </button>
                <button type="button" class="btn" id="btn-adicionar-carrinho" style="background:linear-gradient(135deg,#0055ff,#1a1866);border:none;color:#fff;font-weight:600;flex:1;font-family:'Poppins',sans-serif;">
                    <i class="flaticon-pizza-1 mr-2"></i>Adicionar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Extras -->
<div class="modal fade" id="modalExtras" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background:#1a1a1a;border:1px solid #333;border-radius:15px;display:flex;flex-direction:column;max-height:80vh;">
            <div class="modal-header" style="background:#1a1a1a;border-bottom:1px solid #333;flex-shrink:0;">
                <h5 class="modal-title text-warning" style="font-family:'Poppins',sans-serif;font-weight:600;"><i class="fas fa-plus-circle mr-2"></i>Selecionar Extras</h5>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:.7;"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="background:#1a1a1a;overflow-y:auto;flex:1 1 auto;-webkit-overflow-scrolling:touch;">
                <div id="extras-loading" class="text-center py-5">
                    <div class="spinner-border text-warning" role="status"><span class="sr-only">Carregando...</span></div>
                    <p class="text-light mt-3">Carregando extras...</p>
                </div>
                <div id="extras-lista" style="display:none;">
                    <div class="mb-3" style="background:#2d2d2d;border-radius:8px;padding:4px 8px;display:flex;align-items:center;">
                        <i class="fas fa-search text-muted mr-2"></i>
                        <input type="text" id="pesquisa-extras" class="form-control" placeholder="Pesquisar extra..." style="background:transparent;border:none;color:#fff;font-size:16px;padding:4px 0;">
                        <button type="button" id="limpar-pesquisa" style="display:none;background:none;border:none;color:#aaa;" onclick="document.getElementById('pesquisa-extras').value='';ProdutoExtras.pesquisar('');this.style.display='none';"><i class="fas fa-times"></i></button>
                    </div>
                    <div id="aviso-obrigatorio" class="alert" style="display:none;background:rgba(0,85,255,.1);border:1px solid #0055ff;color:#6699ff;">
                        <i class="fas fa-exclamation-triangle mr-2"></i><span id="texto-aviso-obrigatorio"></span>
                    </div>
                    <div id="extras-container"></div>
                    <div id="extras-vazio" class="text-center py-4" style="display:none;">
                        <i class="fas fa-inbox text-muted" style="font-size:3rem;opacity:.3;"></i>
                        <p class="text-muted mt-2">Nenhum extra disponível.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#1a1a1a;border-top:1px solid #333;flex-shrink:0;">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <span class="text-light" style="font-family:'Poppins',sans-serif;">Selecionados: <span class="badge badge-warning" id="contador-extras-modal">0</span></span>
                    <div>
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" style="background:#333;border:1px solid #555;">Cancelar</button>
                        <button type="button" class="btn" id="btn-confirmar-extras" style="background:linear-gradient(135deg,#0055ff,#1a1866);border:none;color:#fff;font-weight:600;font-family:'Poppins',sans-serif;">
                            <i class="fas fa-check mr-1"></i>Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Cards de produto */
.block { background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,.08); cursor:pointer; height:100%; display:flex; flex-direction:column; transition:box-shadow .25s,transform .25s; }
.block:hover { box-shadow:0 8px 24px rgba(0,0,0,.14); transform:translateY(-3px); }
.block .content { flex:1; display:flex; flex-direction:column; }
.filter_item_img { height:200px; overflow:hidden; position:relative; }
.filter_item_img img { width:100%; height:100%; object-fit:cover; transition:transform .3s; }
.block:hover .filter_item_img img { transform:scale(1.04); }
.block .info { padding:14px; flex:1; display:flex; flex-direction:column; }
.block .info .name { font-size:1rem; font-weight:600; color:#222; margin-bottom:6px; line-height:1.3; }
.block .info .short { color:#666; font-size:.82rem; line-height:1.4; flex:1; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.block .info .price-info { margin-top:10px; padding-top:8px; border-top:1px solid #eee; }
.block .info .price-info strong { font-size:1.1rem; font-weight:700; }
.block .info .category-badge small { background:#f5f5f5; color:#777; padding:2px 7px; border-radius:10px; font-size:.7rem; margin-top:5px; display:inline-block; }
.produto-inativo { opacity:.65; pointer-events:none; }
.produto-inativo .filter_item_img img { filter:blur(3px); }
.produto-indisponivel-overlay { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:3; }
.produto-indisponivel-texto { background:linear-gradient(135deg,#dc3545,#c82333); color:#fff; padding:6px 16px; border-radius:20px; font-weight:700; font-size:.85rem; }
.filtr-item { margin-bottom:16px; }
@media (max-width:576px) {
    .filter_item_img { height:150px; }
    .block .info { padding:10px; }
    .block .info .name { font-size:.88rem; }
    .filtr-item { margin-bottom:10px; }
}
</style>

<?php else: ?>
<div class="container-fluid">
    <div class="col-12">
        <div class="alert alert-warning text-center">
            <h4>Sistema em Configuração</h4>
            <p>O catálogo de produtos está sendo configurado. Volte em breve!</p>
        </div>
    </div>
</div>
<?php endif; ?>
