<?php if (!empty($carrinho_itens)): ?>
    <ul class="list-group">
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
    
    <div class="d-flex justify-content-between align-items-center mt-4 p-3" 
        style="background: #3a3a3a; border-radius: 8px; border: 2px solid #f8b531;">
        <h5 class="mb-0 text-light">Valor Total do Pedido:</h5>
        <h4 class="mb-0 text-warning" style="font-weight: 700;">
            R$ <?= number_format($valor_total_pedido, 2, ',', '.'); ?>
        </h4>
    </div>

<?php else: ?>
    <div class="alert alert-dark text-center" role="alert" style="background: #2d2d2d; border: 1px solid #333;">
        <i class="fas fa-box-open mr-2 text-warning"></i> 
        Seu carrinho de compras está vazio. Adicione alguns produtos!
    </div>
<?php endif; ?>
