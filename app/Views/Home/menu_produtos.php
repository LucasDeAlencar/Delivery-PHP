<?php if (!empty($categorias) || !empty($produtos)): ?>

<!-- ===== CATEGORIAS FIXAS (scroll horizontal) ===== -->
<div id="barra-categorias">
    <div id="categorias-scroll">
        <button class="cat-btn active" data-filter="all">Todos</button>
        <?php foreach ($categorias ?? [] as $categoria): ?>
            <button class="cat-btn" data-filter="<?= esc($categoria->slug) ?>"><?= esc($categoria->nome) ?></button>
        <?php endforeach; ?>
    </div>
</div>

<!-- ===== BUSCA ===== -->
<div id="busca-cardapio">
    <i class="fas fa-search"></i>
    <input type="text" id="input-busca" placeholder="Buscar no cardápio..." autocomplete="off">
    <button id="btn-limpar-busca" style="display:none;" onclick="document.getElementById('input-busca').value='';filtrarBusca('');this.style.display='none';"><i class="fas fa-times"></i></button>
</div>

<!-- ===== LISTA DE PRODUTOS ===== -->
<div id="lista-produtos">
    <?php
    // Agrupar produtos por categoria
    $produtosPorCategoria = [];
    foreach ($produtos as $produto) {
        $produtosPorCategoria[$produto->categoria_nome][] = $produto;
    }
    ?>

    <?php foreach ($produtosPorCategoria as $nomeCategoria => $itens): ?>
        <?php
        $slugCategoria = $itens[0]->categoria_slug ?? '';
        ?>
        <div class="secao-categoria" data-categoria="<?= esc($slugCategoria) ?>">
            <h3 class="titulo-categoria"><?= esc($nomeCategoria) ?></h3>
            <div class="cards-grid">
            <?php foreach ($itens as $produto): ?>
                <div class="card-produto <?= $produto->ativo ? '' : 'produto-inativo' ?>"
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

                    <!-- Info à esquerda -->
                    <div class="card-info">
                        <div class="card-nome"><?= esc($produto->nome) ?></div>
                        <div class="card-descricao">
                            <?= !empty($produto->descricao) ? character_limiter(esc($produto->descricao), 100) : '<span class="sem-desc">Clique para ver detalhes</span>' ?>
                        </div>
                        <div class="card-preco">
                            <?php if (!empty($produto->com_tamanho) && !empty($produto->tamanhos)): ?>
                                <span class="preco-partir">A partir de </span>
                                <strong>R$ <?= number_format(min(array_column((array)$produto->tamanhos, 'preco')), 2, ',', '.') ?></strong>
                            <?php else: ?>
                                <strong>R$ <?= number_format($produto->preco, 2, ',', '.') ?></strong>
                            <?php endif; ?>
                        </div>
                        <?php if (!$produto->ativo): ?>
                            <span class="badge-indisponivel">Indisponível</span>
                        <?php endif; ?>
                    </div>

                    <!-- Imagem à direita -->
                    <div class="card-imagem-wrap">
                        <?php
                        $imgSrc = !empty($produto->imagem)
                            ? base_url('uploads/produtos/' . esc($produto->imagem))
                            : 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&h=300&fit=crop';
                        ?>
                        <img src="<?= $imgSrc ?>" alt="<?= esc($produto->nome) ?>" loading="lazy">
                        <?php if ($produto->ativo): ?>
                            <button class="btn-adicionar" aria-label="Adicionar <?= esc($produto->nome) ?>">+</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div><!-- /.cards-grid -->
        </div>
    <?php endforeach; ?>

    <?php if (empty($produtos)): ?>
        <div class="sem-produtos">
            <p>Nenhum produto disponível no momento.</p>
        </div>
    <?php endif; ?>
</div>

<!-- ===== MODAL DE COMPRA ===== -->
<div class="modal fade" id="modalCompra" tabindex="-1" role="dialog" aria-labelledby="modalCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content modal-delivery">
            <div class="modal-header modal-delivery-header">
                <h5 class="modal-title" id="modalCompraLabel">
                    <i class="fas fa-shopping-bag mr-2"></i>Adicionar ao pedido
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-produto-id">
                <input type="hidden" id="modal-produto-imagem-url">

                <div class="row">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <img id="modal-produto-imagem" src="" alt="" class="img-fluid rounded" style="width:100%;max-height:260px;object-fit:cover;">
                    </div>
                    <div class="col-md-7">
                        <div id="modal-produto-imagem-mobile" style="display:none;">
                            <img src="" alt="" id="modal-produto-imagem-mobile-img">
                            <div>
                                <div class="mob-nome" id="modal-produto-nome-mobile"></div>
                                <div class="mob-cat" id="modal-produto-cat-mobile"></div>
                            </div>
                        </div>

                        <h4 id="modal-produto-nome" class="text-light mb-2" style="font-weight:600;font-size:1.1rem;"></h4>
                        <p id="modal-produto-descricao" class="text-light mb-3" style="opacity:.8;font-size:.9rem;line-height:1.5;"></p>

                        <div class="preco-qtd-row mb-3">
                            <div class="preco-col">
                                <label class="text-light d-block" style="font-size:.82rem;font-weight:600;">Preço Unitário</label>
                                <div id="modal-produto-preco" class="h5 text-warning mb-0" style="font-weight:700;"></div>
                                <small class="text-muted" id="modal-produto-preco-extras">Sem extras</small>
                            </div>
                            <div class="qtd-col">
                                <label class="text-light d-block" style="font-size:.82rem;font-weight:600;">Quantidade</label>
                                <div class="qtd-control">
                                    <button class="btn btn-outline-warning" type="button" id="btn-diminuir" style="border-right:none;border-color:#0055ff;color:#0055ff;">−</button>
                                    <input type="number" class="form-control bg-dark text-warning" id="quantidade" value="1" min="1" max="99" style="border-color:#0055ff;border-left:none;border-right:none;font-size:16px;text-align:center;">
                                    <button class="btn btn-outline-warning" type="button" id="btn-aumentar" style="border-left:none;border-color:#0055ff;color:#0055ff;">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 p-3 rounded" style="background:#2d2d2d;border:1px solid #333;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-light" style="font-weight:600;">Total:</span>
                                <span id="modal-total" class="h5 text-warning mb-0" style="font-weight:700;"></span>
                            </div>
                            <small class="text-muted" id="modal-total-detalhe"></small>
                        </div>

                        <div class="mb-3" id="container-tamanhos" style="display:none;">
                            <label class="text-light mb-1" style="font-size:.82rem;font-weight:600;"><i class="fas fa-ruler mr-1"></i>Tamanho <span class="text-danger">*</span></label>
                            <div id="tamanhos-opcoes"></div>
                            <small class="text-danger d-none mt-1" id="aviso-tamanho"><i class="fas fa-exclamation-circle mr-1"></i>Selecione um tamanho.</small>
                        </div>

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

                        <div class="mb-3">
                            <label for="observacoes" class="text-light" style="font-size:.82rem;font-weight:600;">Observações (opcional)</label>
                            <textarea class="form-control bg-dark text-light" id="observacoes" rows="2" placeholder="Alguma observação especial?" style="border:1px solid #333;resize:none;font-size:16px;"></textarea>
                        </div>

                        <p class="text-warning mb-0" style="font-size:.8rem;"><i class="fas fa-tag mr-1"></i><span id="modal-categoria-texto"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#1a1a1a;border-top:1px solid #333;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background:#333;border:1px solid #555;color:#ccc;flex:1;">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </button>
                <button type="button" class="btn" id="btn-adicionar-carrinho" style="background:linear-gradient(135deg,#0055ff,#1a1866);border:none;color:#fff;font-weight:600;flex:1;">
                    <i class="fas fa-shopping-bag mr-2"></i>Adicionar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL DE EXTRAS ===== -->
<div class="modal fade" id="modalExtras" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-delivery" style="max-height:80vh;display:flex;flex-direction:column;">
            <div class="modal-header modal-delivery-header" style="flex-shrink:0;">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Selecionar Extras</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="overflow-y:auto;flex:1 1 auto;-webkit-overflow-scrolling:touch;">
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
                    <span class="text-light">Selecionados: <span class="badge badge-warning" id="contador-extras-modal">0</span></span>
                    <div>
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" style="background:#333;border:1px solid #555;">Cancelar</button>
                        <button type="button" class="btn" id="btn-confirmar-extras" style="background:linear-gradient(135deg,#0055ff,#1a1866);border:none;color:#fff;font-weight:600;">
                            <i class="fas fa-check mr-1"></i>Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="container-fluid py-5">
    <div class="alert alert-warning text-center">
        <h4>Sistema em Configuração</h4>
        <p>O catálogo de produtos está sendo configurado. Volte em breve!</p>
    </div>
</div>
<?php endif; ?>

<style>
#busca-cardapio {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #1a1a1a;
    border-bottom: 1px solid #1e1e1e;
    padding: 10px 16px;
    position: sticky;
    top: 103px;
    z-index: 95;
}
#busca-cardapio i.fa-search { color: #555; font-size: .9rem; flex-shrink: 0; }
#input-busca {
    flex: 1;
    background: none;
    border: none;
    outline: none;
    color: #f0f0f0;
    font-size: .9rem;
    font-family: 'Poppins', sans-serif;
}
#input-busca::placeholder { color: #555; }
#btn-limpar-busca { background: none; border: none; color: #555; cursor: pointer; padding: 0; font-size: .85rem; }
#btn-limpar-busca:hover { color: #f8b531; }
</style>

<script>
function filtrarBusca(termo) {
    termo = termo.toLowerCase().trim();
    document.querySelectorAll('.card-produto').forEach(function(card) {
        const nome = (card.dataset.produtoNome || '').toLowerCase();
        const desc = (card.dataset.produtoDescricao || '').toLowerCase();
        const cat  = (card.dataset.produtoCategoria || '').toLowerCase();
        card.style.display = (!termo || nome.includes(termo) || desc.includes(termo) || cat.includes(termo)) ? '' : 'none';
    });
    // Oculta seções sem nenhum card visível
    document.querySelectorAll('.secao-categoria').forEach(function(sec) {
        const visivel = [...sec.querySelectorAll('.card-produto')].some(c => c.style.display !== 'none');
        sec.style.display = visivel ? '' : 'none';
    });
}

document.getElementById('input-busca').addEventListener('input', function() {
    const btn = document.getElementById('btn-limpar-busca');
    btn.style.display = this.value ? 'block' : 'none';
    filtrarBusca(this.value);
});
</script>
