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
                     data-produto-descricao="<?= esc($produto->ingredientes ?? '') ?>"
                     data-produto-imagem="<?= !empty($produto->imagem) ? base_url('uploads/produtos/' . esc($produto->imagem)) : '' ?>"
                     data-produto-ativo="<?= $produto->ativo ? '1' : '0' ?>"
                     data-com-tamanho="<?= !empty($produto->com_tamanho) ? '1' : '0' ?>"
                     data-tamanhos="<?= !empty($produto->tamanhos) ? esc(json_encode(array_map(fn($t) => ['id' => $t->id, 'nome' => $t->nome, 'preco' => $t->preco], $produto->tamanhos))) : '[]' ?>">

                    <!-- Info à esquerda -->
                    <div class="card-info">
                        <div class="card-nome"><?= esc($produto->nome) ?></div>
                        <div class="card-descricao">
                            <?= !empty($produto->ingredientes) ? esc(mb_substr($produto->ingredientes, 0, 100)) . (mb_strlen($produto->ingredientes) > 100 ? '...' : '') : '<span class="sem-desc">Clique para ver detalhes</span>' ?>
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

<!-- ===== OVERLAY PRODUTO (sem Bootstrap modal) ===== -->
<!-- Criado dinamicamente pelo popup-produto.js -->

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
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
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
