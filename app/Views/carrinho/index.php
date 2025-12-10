<!-- CARRINHO RESPONSIVO v2.0 - Atualizado em <?= date('Y-m-d H:i:s') ?> -->
<style>
/* Força responsividade dos botões do carrinho */
.btn-limpar-carrinho,
.btn-finalizar-carrinho {
    min-height: 44px !important;
    white-space: nowrap !important;
}

@media (max-width: 768px) {
    .row .col-12.col-md-6 {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    #btn-limpar-carrinho,
    #btn-finalizar-pedido {
        font-size: 0.9rem !important;
        padding: 10px 15px !important;
        width: 100% !important;
    }
    
    #btn-limpar-carrinho i,
    #btn-finalizar-pedido i {
        display: none !important;
    }
}
</style>

<?php if (!empty($carrinho_itens)): ?>

    <ul class="list-group" style="padding-bottom: 160px;">
        <?php
        $valor_total_pedido = 0;
        foreach ($carrinho_itens as $item):
            $valor_total_pedido += $item['preco_total'];
            ?>

            <li class="list-group-item d-flex justify-content-between align-items-center mb-2" 
                style="background: #2d2d2d; border: 1px solid #333; border-radius: 8px;">

                <div class="d-flex align-items-center flex-grow-1">
                    <?php if (!empty($item['produto_imagem'])): ?>
                        <img src="<?= $item['produto_imagem']; ?>" alt="<?= esc($item['produto_nome']); ?>" 
                             class="img-fluid mr-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                         <?php endif; ?>
                    <div>
                        <h6 class="mb-0 text-light"><?= esc($item['produto_nome']); ?></h6>
                        <small class="text-muted">
                            Preço Unitário: R$ <?= number_format($item['preco_unitario'], 2, ',', '.'); ?>
                        </small>
                    </div>
                </div>

                <div class="text-right ml-3">
                    <span class="badge badge-warning badge-pill p-2 mr-3" style="font-size: 1rem;">
                        <?= esc($item['quantidade']); ?>x
                    </span>
                    <span class="text-warning" style="font-weight: bold; font-size: 1.1rem;">
                        R$ <?= number_format($item['preco_total'], 2, ',', '.'); ?>
                    </span>
                </div>
            </li>

            <?php if (!empty($item['observacoes'])): ?>
                <li class="list-group-item bg-dark text-white-50 small" style="margin-top: -10px; border-top: none; border-radius: 0 0 8px 8px; border: 1px solid #333;">
                    <i class="fas fa-comment-dots text-warning mr-1"></i> Obs: <?= esc($item['observacoes']); ?>
                </li>
            <?php endif; ?>

        <?php endforeach; ?>
    </ul>

    <div style="position: sticky; bottom: 0; left: 0; right: 0; background: #232323; z-index: 999; padding: 10px 15px; border-top: 1px solid #444; box-shadow: 0 -5px 20px rgba(0,0,0,0.6);">

        <div class="d-flex justify-content-between align-items-center mb-2 p-2" 
             style="background: #3a3a3a; border-radius: 8px; border: 1px solid #f8b531;">
            <h5 class="mb-0 text-light" style="font-size: 0.95rem;">Total do Pedido:</h5>
            <h4 class="mb-0 text-warning" style="font-weight: 700; font-size: 1.2rem;">
                R$ <?= number_format($valor_total_pedido, 2, ',', '.'); ?>
            </h4>
        </div>

        <div class="row mt-2" style="margin: 0;">
            <div class="col-12 col-md-6 mb-2 mb-md-0" style="padding: 0 5px;">
                <button type="button" class="btn btn-limpar-carrinho w-100" id="btn-limpar-carrinho" onclick="Carrinho.limpar()">
                    <i class="fas fa-trash mr-2"></i>Limpar Carrinho
                </button>
            </div>
            <div class="col-12 col-md-6" style="padding: 0 5px;">
                <button type="button" class="btn btn-finalizar-carrinho w-100" id="btn-finalizar-pedido" onclick="Carrinho.finalizar()">
                    <i class="fas fa-check-circle mr-2"></i>Finalizar Pedido
                </button>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="alert alert-dark text-center" role="alert" style="background: #2d2d2d; border: 1px solid #333; margin: 20px;">
        <i class="fas fa-box-open mr-2 text-warning"></i> 
        Seu carrinho de compras está vazio. Adicione alguns produtos!
    </div>
<?php endif; ?>