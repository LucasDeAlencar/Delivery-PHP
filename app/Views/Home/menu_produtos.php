<?php if (!empty($categorias) || !empty($produtos)): ?>
    <div class="container-fluid">
        <!-- Título da seção do menu -->
        <div class="row justify-content-center mb-4 pb-2">
            <div class="col-12 col-md-10 col-lg-8 text-center">
                <h2 class="mb-3 mb-md-4">Nossos Produtos</h2>
                <p class="mb-0">Descubra nossos pratos especiais, preparados com ingredientes frescos e muito sabor.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Menu de Filtros por Categoria -->
                <div class="menu_filter text-center">
                    <ul>
                        <li class="active">
                            <a href="javascript:void(0)" class="filter-button" data-filter="all">Todos</a>
                        </li>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <li>
                                    <a href="javascript:void(0)" class="filter-button" data-filter="<?= esc($categoria->slug) ?>">
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
                                <div class="block produto-item <?= $produto->ativo ? '' : 'produto-inativo' ?>" 
                                     data-produto-id="<?= esc($produto->id) ?>"
                                     data-produto-nome="<?= esc($produto->nome) ?>"
                                     data-produto-preco="<?= esc($produto->preco) ?>"
                                     data-produto-categoria="<?= esc($produto->categoria_nome) ?>"
                                     data-produto-descricao="<?= esc($produto->descricao ?? '') ?>"
                                     data-produto-imagem="<?= !empty($produto->imagem) ? base_url('uploads/produtos/' . esc($produto->imagem)) : '' ?>"
                                     data-produto-ativo="<?= $produto->ativo ? '1' : '0' ?>"
                                     style="cursor: <?= $produto->ativo ? 'pointer' : 'default' ?>; pointer-events: <?= $produto->ativo ? 'auto' : 'none' ?>;">
                                    <div class="content">
                                        <div class="filter_item_img">
                                            <?php if (!empty($produto->imagem)): ?>
                                                <img src="<?= base_url('uploads/produtos/' . esc($produto->imagem)) ?>" 
                                                     alt="<?= esc($produto->nome) ?>" 
                                                     class="img-fluid">
                                            <?php else: ?>
                                                <?php
                                                $imagens_hamburger = [
                                                    'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop',
                                                    'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=300&fit=crop',
                                                    'https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?w=400&h=300&fit=crop',
                                                    'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400&h=300&fit=crop',
                                                    'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop'
                                                ];
                                                $imagem = $imagens_hamburger[array_rand($imagens_hamburger)];
                                                ?>
                                                <img src="<?= $imagem ?>" 
                                                     alt="<?= esc($produto->nome) ?>" 
                                                     class="img-fluid"
                                                     style="height: 250px; width: 100%; object-fit: cover;">
                                            <?php endif; ?>
                                            <i class="flaticon-pizza-1"></i>
                                            <?php if (!$produto->ativo): ?>
                                                <div class="produto-indisponivel-overlay">
                                                    <span class="produto-indisponivel-texto">Produto Indisponível</span>
                                                </div>
                                            <?php endif; ?>
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

    <!-- Modal de Compra - Estilo Dark -->
    <div class="modal fade" id="modalCompra" tabindex="-1" role="dialog" aria-labelledby="modalCompraLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-sm-fluid" role="document">
            <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%); border-bottom: 1px solid #333; border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title text-warning" id="modalCompraLabel" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1rem;">
                        <i class="flaticon-pizza-1 mr-2"></i>Finalizar Compra
                    </h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: #1a1a1a; max-height: 70vh; overflow-y: auto;">
                    <input type="hidden" id="modal-produto-id" value="">
                    <input type="hidden" id="modal-produto-imagem-url" value="">
                    <div class="row">
                        <!-- Imagem do Produto -->
                        <div class="col-md-5">
                            <div class="produto-imagem text-center">
                                <img id="modal-produto-imagem" src="" alt="" class="img-fluid rounded" style="max-width: 100%; height: auto; border: 2px solid #333; object-fit: cover;">
                                <div id="modal-produto-placeholder" class="d-none">
                                    <div class="bg-dark rounded d-flex align-items-center justify-content-center" style="height: 200px; border: 2px solid #333;">
                                        <i class="flaticon-pizza-1 text-warning" style="font-size: 3rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalhes do Produto -->
                        <div class="col-md-7">
                            <h4 id="modal-produto-nome" class="mb-3 text-light" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.2rem;"></h4>
                            <p id="modal-produto-descricao" class="mb-4 text-light" style="opacity: 0.8; line-height: 1.5; font-size: 0.9rem;"></p>

                            <!-- Preço Unitário e Quantidade -->
                            <div class="mb-3">
                                <div class="row align-items-center flex-md-nowrap">
                                    <div class="col-6">
                                        <label class="font-weight-bold text-light" style="font-family: 'Poppins', sans-serif; font-size: 0.9rem;">Preço Unitário:</label>
                                        <div id="modal-produto-preco" class="h5 text-warning" style="font-family: 'Poppins', sans-serif; font-weight: 700;"></div>
                                        <small class="text-muted" id="modal-produto-preco-extras">Sem extras adicionados</small>
                                    </div>
                                    <div class="col-6">
                                        <label for="quantidade" class="font-weight-bold text-light" style="font-family: 'Poppins', sans-serif; font-size: 0.9rem;">Quantidade:</label>
                                        <div style="display: flex; max-width: 100%; width: 120px;">
                                            <button class="btn btn-outline-warning" type="button" id="btn-diminuir" style="border-color: #f8b531; color: #f8b531; border-right: none; padding: 8px 12px;">
                                                -
                                            </button>
                                            <input type="number" class="form-control text-center bg-dark text-light" 
                                                   id="quantidade" value="1" min="1" max="99"
                                                   style="border-color: #f8b531; border-left: none; border-right: none; color: #ffc135 !important;">
                                            <button class="btn btn-outline-warning" type="button" id="btn-aumentar" style="border-color: #f8b531; color: #f8b531; border-left: none; padding: 8px 12px;">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="mb-3 p-3 rounded" style="background: #2d2d2d; border: 1px solid #333;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-light" style="font-family: 'Poppins', sans-serif; font-size: 0.9rem;">Total:</span>
                                    <span id="modal-total" class="h5 text-warning mb-0" style="font-family: 'Poppins', sans-serif; font-weight: 700;"></span>
                                </div>
                                <small class="text-muted d-block" id="modal-total-detalhe"></small>
                            </div>

                            <!-- Botão de Extras -->
                            <div class="mb-3" id="container-btn-extras" style="display: none;">
                                <button type="button" class="btn btn-outline-warning btn-block" id="btn-selecionar-extras" style="border: 2px solid #f8b531; color: #f8b531; font-weight: 600; transition: all 0.3s ease; padding: 10px;">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    <span id="texto-btn-extras">Selecionar Extras</span>
                                    <span id="badge-obrigatorio" class="badge badge-danger ml-2" style="display: none;">*Obrigatório</span>
                                </button>
                                <div id="extras-selecionados-resumo" class="mt-2" style="display: none;">
                                    <small class="text-warning d-block">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <span id="contador-extras">0 extras selecionados</span>
                                    </small>
                                    <small class="text-muted d-block" id="valor-extras-resumo">+ R$ 0,00</small>
                                </div>
                                <small class="text-danger d-block mt-1" id="aviso-extra-obrigatorio-modal" style="display: none;"></small>
                            </div>

                            <!-- Observações -->
                            <div class="mb-3">
                                <label for="observacoes" class="font-weight-bold text-light" style="font-family: 'Poppins', sans-serif; font-size: 0.9rem;">Observações (opcional):</label>
                                <textarea class="form-control bg-dark text-light" 
                                          id="observacoes" 
                                          rows="2" 
                                          placeholder="Alguma observação especial?"
                                          style="border: 1px solid #333; resize: none; transition: all 0.3s ease; font-size: 0.9rem;"></textarea>
                                <small class="text-muted" style="font-size: 0.75rem;">Máximo 200 caracteres</small>
                            </div>

                            <!-- Categoria -->
                            <div class="mb-3">
                                <p id="modal-produto-categoria" class="text-warning mb-0" style="font-size: 0.85rem; font-weight: 500;">
                                    <i class="fas fa-tag mr-1"></i><span id="modal-categoria-texto"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #1a1a1a; border-top: 1px solid #333; border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background: #333; border: 1px solid #555; color: #ccc; font-family: 'Poppins', sans-serif; font-weight: 500; padding: 12px 20px; font-size: 0.9rem; flex: 1;">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-warning" id="btn-adicionar-carrinho" style="background: linear-gradient(135deg, #f8b531 0%, #fac56e 100%); border: none; color: #000; font-weight: 600; font-family: 'Poppins', sans-serif; padding: 12px 20px; font-size: 0.9rem; flex: 1;">
                        <i class="flaticon-pizza-1 mr-2"></i>Adicionar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Seleção de Extras -->
    <div class="modal fade" id="modalExtras" tabindex="-1" role="dialog" aria-labelledby="modalExtrasLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333; border-radius: 15px; max-height: 80vh; display: flex; flex-direction: column;">
                <div class="modal-header" style="background: #1a1a1a; border-bottom: 1px solid #333;">
                    <h5 class="modal-title text-warning" id="modalExtrasLabel" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        <i class="fas fa-plus-circle mr-2"></i>Selecionar Extras
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.7;">
                        <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: #1a1a1a; max-height: 500px; overflow-y: auto;">
                    <div id="extras-loading" class="text-center py-5">
                        <div class="spinner-border text-warning" role="status">
                            <span class="sr-only">Carregando...</span>
                        </div>
                        <p class="text-light mt-3">Carregando extras...</p>
                    </div>

                    <div id="extras-lista" style="display: none;">
                        <div id="aviso-obrigatorio" class="alert alert-warning" style="display: none; background: rgba(248, 181, 49, 0.1); border: 1px solid #f8b531; color: #f8b531;">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span id="texto-aviso-obrigatorio"></span>
                        </div>

                        <div id="extras-container"></div>

                        <div id="extras-vazio" class="text-center py-5" style="display: none;">
                            <i class="fas fa-inbox text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                            <p class="text-muted mt-3">Nenhum extra disponível para este produto.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #1a1a1a; border-top: 1px solid #333;">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-light">Selecionados: </span>
                            <span class="badge badge-warning" id="contador-extras-modal">0</span>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background: #333; border: 1px solid #555;">
                                Cancelar
                            </button>
                            <button type="button" class="btn btn-warning" id="btn-confirmar-extras" style="background: linear-gradient(135deg, #f8b531 0%, #fac56e 100%); border: none; color: #000; font-weight: 600;">
                                <i class="fas fa-check mr-2"></i>Confirmar
                            </button>
                        </div>
                    </div>
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
                    <p style="color: #8B8589 !important;">O catálogo de produtos está sendo configurado. Volte em breve!</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    /* Animações para o modal dark */
    .modal-content {
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .btn-outline-warning {
        transition: all 0.3s ease;
    }

    .btn-outline-warning:hover {
        background: #f8b531 !important;
        color: #000 !important;
        transform: scale(1.05);
    }

    .btn-warning {
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(248, 181, 49, 0.4);
    }

    .form-control:focus {
        border-color: #f8b531 !important;
        box-shadow: 0 0 0 0.2rem rgba(248, 181, 49, 0.25) !important;
        background: #2d2d2d !important;
        color: #fff !important;
    }

    /* Efeito de loading nos botões */
    .btn-loading {
        position: relative;
        overflow: hidden;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    /* Notificação estilo dark */
    .notificacao-popup {
        background: #2d2d2d !important;
        border: 1px solid #f8b531 !important;
        color: #fff !important;
        box-shadow: 0 5px 20px rgba(248, 181, 49, 0.3) !important;
        font-family: 'Poppins', sans-serif;
    }

    /* Efeito de foco no textarea */
    #observacoes:focus {
        border-color: #f8b531 !important;
        background: #2d2d2d !important;
    }

    /* Grid de produtos otimizado */
    .filtr-item {
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }

    .filtr-item:hover {
        transform: translateY(-5px);
    }

    .block {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .block:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        transform: translateY(-3px);
    }

    .block .content {
        padding: 0;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .block .filter_item_img {
        height: 250px;
        overflow: hidden;
        position: relative;
    }

    .block .filter_item_img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .block:hover .filter_item_img img {
        transform: scale(1.05);
    }

    .block .info {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .block .info .name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
        line-height: 1.3;
    }

    .block .info .short {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 15px;
        flex: 1;
    }

    .block .info .price-info {
        margin-top: auto;
        padding-top: 10px;
        border-top: 1px solid #eee;
    }

    .block .info .price-info strong {
        font-size: 1.3rem;
        font-weight: 700;
    }

    .block .info .category-badge {
        margin-top: 8px;
    }

    .block .info .category-badge small {
        background: #f8f9fa;
        color: #666;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Placeholder para produtos sem imagem */
    .no-image-placeholder {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px dashed #dee2e6;
    }

    .no-image-placeholder i {
        color: #adb5bd;
        font-size: 3rem;
    }

    /* ========================================
       PRODUTOS INATIVOS - BLUR E OVERLAY
       ======================================== */

    .produto-inativo {
        position: relative;
        opacity: 0.7;
    }

    .produto-inativo .filter_item_img {
        position: relative;
        overflow: hidden;
    }

    .produto-inativo .filter_item_img img {
        filter: blur(4px);
        transition: filter 0.3s ease;
    }

    .produto-indisponivel-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 3;
    }

    .produto-indisponivel-texto {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 1rem;
        text-align: center;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        border: 2px solid rgba(255, 255, 255, 0.3);
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        letter-spacing: 0.5px;
    }

    .produto-inativo:hover {
        transform: none !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }

    .produto-inativo .filter_item_img img:hover {
        transform: none !important;
    }

    /* ========================================
       RESPONSIVIDADE ESPECÍFICA DOS PRODUTOS
       ======================================== */

    /* Tablets */
    @media (max-width: 991px) {
        .block .filter_item_img {
            height: 220px;
        }

        .block .info {
            padding: 15px;
        }

        .block .info .name {
            font-size: 1.1rem;
        }
    }

    /* Mobile */
    @media (max-width: 576px) {
        .filtr-item {
            margin-bottom: 15px;
        }

        .block .filter_item_img {
            height: 180px;
        }

        .block .info {
            padding: 12px;
        }

        .block .info .name {
            font-size: 1rem;
            margin-bottom: 8px;
        }

        .block .info .short {
            font-size: 0.85rem;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .block .info .price-info strong {
            font-size: 1.1rem;
        }

        .block .info .category-badge small {
            font-size: 0.7rem;
            padding: 3px 6px;
        }
    }

    /* Dispositivos muito pequenos */
    @media (max-width: 359px) {
        .block .filter_item_img {
            height: 160px;
        }

        .block .info {
            padding: 10px;
        }

        .block .info .name {
            font-size: 0.95rem;
        }

        .block .info .short {
            font-size: 0.8rem;
        }

        .block .info .price-info strong {
            font-size: 1rem;
        }
    }

    /* ========================================
       RESPONSIVIDADE DOS MODAIS
       ======================================== */

    /* Modal de Compra - Responsivo */
    @media (max-width: 991px) {
        #modalCompra .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }

        #modalCompra .modal-body {
            padding: 15px;
        }

        #modalCompra .modal-body > .row {
            flex-direction: column;
        }

        #modalCompra .modal-body > .row > .col-md-5,
        #modalCompra .modal-body > .row > .col-md-7 {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
        }

        #modalCompra .produto-imagem {
            margin-bottom: 20px;
        }

        #modalCompra .row.align-items-center {
            flex-direction: row !important;
        }

        #modalCompra .row.align-items-center .col-6 {
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }

        #modalCompra .produto-imagem img {
            max-height: 200px !important;
        }
    }

    /* Ajuste para o Modal de Compra no Mobile */
    @media (max-width: 768px) {
        #modalCompra .modal-dialog {
            margin: 0;
            max-width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
        
        #modalCompra .modal-content {
            display: flex;
            flex-direction: column;
            max-height: 85vh;
            overflow: hidden;
            border-radius: 20px 20px 0 0;
        }
        
        #modalCompra .modal-header {
            padding: 12px 15px;
            position: sticky;
            top: 0;
            background: #1a1a1a;
            z-index: 1;
        }
        
        #modalCompra .modal-header .modal-title {
            font-size: 1.1rem;
        }

        #modalCompra .modal-body {
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding: 15px;
        }
        
        #modalCompra .produto-imagem img {
            max-height: 180px;
            object-fit: cover;
        }

        #modalCompra .modal-footer {
            position: sticky;
            bottom: 0;
            background: #1a1a1a;
            z-index: 1050;
            border-top: 1px solid #333;
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        
        /* Ajuste nos botões para ficarem lado a lado e visíveis */
        #modalCompra .modal-footer .btn {
            flex: 1;
            margin: 0 !important;
            padding: 12px 10px;
            font-size: 0.95rem;
        }
        
        /* Ajuste no campo de quantidade */
        #modalCompra #quantidade {
            font-size: 1.1rem;
            padding: 8px;
        }
        
        /* Ajuste no campo de observações */
        #modalCompra #observacoes {
            font-size: 0.95rem;
        }
    }
    
    /* Mobile menor (até 480px) */
    @media (max-width: 480px) {
        #modalCompra .modal-content {
            max-height: 90vh;
        }
        
        #modalCompra .modal-header {
            padding: 10px 12px;
        }
        
        #modalCompra .modal-header .modal-title {
            font-size: 1rem;
        }
        
        #modalCompra .modal-body {
            padding: 12px;
        }
        
        #modalCompra .modal-footer {
            padding: 10px 12px;
            flex-direction: column;
        }
        
        #modalCompra .modal-footer .btn {
            width: 100%;
            margin-bottom: 8px !important;
        }
        
        #modalCompra .modal-footer .btn:last-child {
            margin-bottom: 0 !important;
        }
    }

    /* Modal de Extras - Responsivo */
    @media (max-width: 991px) {
        #modalExtras .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }

        #modalExtras .modal-body {
            max-height: 60vh;
            padding: 15px;
        }
    }
    
    @media (max-width: 768px) {
        #modalExtras .modal-dialog {
            margin: 0;
            max-width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
        
        #modalExtras .modal-content {
            max-height: 80vh;
            border-radius: 20px 20px 0 0;
            background: #1a1a1a;
        }
        
        #modalExtras .modal-header {
            padding: 12px 15px;
            background: #1a1a1a;
            border-bottom: 1px solid #333;
        }
        
        #modalExtras .modal-body {
            max-height: calc(80vh - 120px);
            padding: 15px;
            background: #1a1a1a;
        }
        
        #modalExtras .modal-footer {
            padding: 12px 15px;
            background: #1a1a1a;
            border-top: 1px solid #333;
        }
    }
    
    @media (max-width: 480px) {
        #modalExtras .modal-content {
            max-height: 85vh;
        }
        
        #modalExtras .modal-body {
            max-height: calc(85vh - 110px);
            padding: 12px;
        }
        
        #modalExtras .modal-header,
        #modalExtras .modal-footer {
            padding: 10px 12px;
        }
    }

    @media (max-width: 576px) {
        #modalExtras .modal-dialog {
            margin: 0;
            max-width: 100%;
            height: 100%;
        }

        #modalExtras .modal-content {
            border-radius: 0 !important;
            height: 100%;
        }

        #modalExtras .modal-body {
            max-height: calc(100vh - 140px) !important;
        }

        #modalExtras .modal-header {
            border-radius: 0 !important;
        }

        #modalExtras .modal-footer {
            border-radius: 0 !important;
            flex-wrap: wrap;
            gap: 10px;
        }

        #modalExtras .modal-footer > div {
            width: 100%;
        }

        #modalExtras .modal-footer > div:last-child {
            display: flex;
            gap: 8px;
        }

        #modalExtras .modal-footer > div:last-child .btn {
            flex: 1;
        }
    }

    /* Menu de filtros responsivo */
    .menu_filter {
        margin-bottom: 30px;
        padding: 0 15px;
    }

    .menu_filter ul {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
        padding: 0;
        list-style: none;
    }

    @media (max-width: 576px) {
        .menu_filter {
            margin-bottom: 20px;
            padding: 0 10px;
        }

        .menu_filter ul {
            gap: 5px;
        }

        .menu_filter ul li a {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
    }
</style>

<!-- Script de extras carregado via extras-sistema.js no index.php -->
