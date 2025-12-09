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

    <!-- Modal de Compra - Estilo Dark -->
    <div class="modal fade" id="modalCompra" tabindex="-1" role="dialog" aria-labelledby="modalCompraLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-height: 90vh;">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%); border-bottom: 1px solid #333; border-radius: 15px 15px 0 0; flex-shrink: 0;">
                    <h5 class="modal-title text-warning" id="modalCompraLabel" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        <i class="flaticon-pizza-1 mr-2"></i>Finalizar Compra
                    </h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background: #1a1a1a; overflow-y: auto; max-height: calc(90vh - 140px);">
                    <input type="hidden" id="modal-produto-id" value="">
                    <input type="hidden" id="modal-produto-imagem-url" value="">
                    <div class="row">
                        <!-- Imagem do Produto -->
                        <div class="col-md-5">
                            <div class="produto-imagem text-center">
                                <img id="modal-produto-imagem" src="" alt="" class="img-fluid rounded" style="max-height: 300px; border: 2px solid #333;">
                                <div id="modal-produto-placeholder" class="d-none">
                                    <div class="bg-dark rounded d-flex align-items-center justify-content-center" style="height: 300px; border: 2px solid #333;">
                                        <i class="flaticon-pizza-1 text-warning" style="font-size: 4rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalhes do Produto -->
                        <div class="col-md-7">
                            <h4 id="modal-produto-nome" class="mb-3 text-light" style="font-family: 'Poppins', sans-serif; font-weight: 600;"></h4>
                            <p id="modal-produto-categoria" class="text-warning mb-3" style="font-size: 0.9rem; font-weight: 500;">
                                <i class="fas fa-tag mr-1"></i><span id="modal-categoria-texto"></span>
                            </p>
                            <p id="modal-produto-descricao" class="mb-4 text-light" style="opacity: 0.8; line-height: 1.5; font-size: 0.95rem;"></p>

                            <!-- Preço Unitário -->
                            <div class="mb-4">
                                <label class="font-weight-bold text-light" style="font-family: 'Poppins', sans-serif;">Preço Unitário:</label>
                                <div id="modal-produto-preco" class="h4 text-warning" style="font-family: 'Poppins', sans-serif; font-weight: 700;"></div>
                                <small class="text-muted" id="modal-produto-preco-extras">Sem extras adicionados</small>
                            </div>

                            <!-- Quantidade -->
                            <div class="mb-4">
                                <label for="quantidade" class="font-weight-bold text-light" style="font-family: 'Poppins', sans-serif;">Quantidade:</label>
                                <div class="input-group" style="max-width: 150px;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-warning" type="button" id="btn-diminuir" style="border-color: #f8b531; color: #f8b531; border-right: none;">
                                            -
                                        </button>
                                    </div>
                                    <input type="number" class="form-control text-center bg-dark text-light" 
                                           id="quantidade" value="1" min="1" readonly
                                           style="border-color: #f8b531; border-left: none; border-right: none; color: #ffc135 !important;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning" type="button" id="btn-aumentar" style="border-color: #f8b531; color: #f8b531; border-left: none;">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="mb-4 p-3 rounded" style="background: #2d2d2d; border: 1px solid #333;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-light" style="font-family: 'Poppins', sans-serif;">Total:</span>
                                    <span id="modal-total" class="h3 text-warning mb-0" style="font-family: 'Poppins', sans-serif; font-weight: 700;"></span>
                                </div>
                                <small class="text-muted d-block" id="modal-total-detalhe"></small>
                            </div>

                            <!-- Botão de Extras -->
                            <div class="mb-3" id="container-btn-extras" style="display: none;">
                                <button type="button" class="btn btn-outline-warning btn-block" id="btn-selecionar-extras" style="border: 2px solid #f8b531; color: #f8b531; font-weight: 600; transition: all 0.3s ease;">
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
                                <label for="observacoes" class="font-weight-bold text-light" style="font-family: 'Poppins', sans-serif;">Observações (opcional):</label>
                                <textarea class="form-control bg-dark text-light" 
                                          id="observacoes" 
                                          rows="3" 
                                          placeholder="Alguma observação especial?"
                                          style="border: 1px solid #333; resize: none; transition: all 0.3s ease;"></textarea>
                                <small class="text-muted" style="font-size: 0.8rem;">Máximo 200 caracteres</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #1a1a1a; border-top: 1px solid #333; border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background: #333; border: 1px solid #555; color: #ccc; font-family: 'Poppins', sans-serif; font-weight: 500;">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-warning" id="btn-adicionar-carrinho" style="background: linear-gradient(135deg, #f8b531 0%, #fac56e 100%); border: none; color: #000; font-weight: 600; font-family: 'Poppins', sans-serif; padding: 10px 25px;">
                        <i class="flaticon-pizza-1 mr-2"></i>Adicionar ao Carrinho
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Seleção de Extras -->
    <div class="modal fade" id="modalExtras" tabindex="-1" role="dialog" aria-labelledby="modalExtrasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%); border-bottom: 1px solid #333;">
                    <h5 class="modal-title text-warning" id="modalExtrasLabel" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        <i class="fas fa-plus-circle mr-2"></i>Selecionar Extras
                    </h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
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
                    <p>O catálogo de produtos está sendo configurado. Volte em breve!</p>
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

        #modalCompra .modal-body .row {
            flex-direction: column;
        }

        #modalCompra .modal-body .col-md-5,
        #modalCompra .modal-body .col-md-7 {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
        }

        #modalCompra .produto-imagem {
            margin-bottom: 20px;
        }

        #modalCompra .produto-imagem img {
            max-height: 200px !important;
        }
    }

    @media (max-width: 576px) {
        #modalCompra .modal-dialog {
            margin: 0;
            max-width: 100%;
            height: 100%;
        }

        #modalCompra .modal-content {
            border-radius: 0 !important;
            height: 100%;
            max-height: 100vh !important;
        }

        #modalCompra .modal-body {
            max-height: calc(100vh - 130px) !important;
            padding: 12px;
        }

        #modalCompra .modal-header {
            border-radius: 0 !important;
            padding: 12px 15px;
        }

        #modalCompra .modal-footer {
            border-radius: 0 !important;
            padding: 10px 15px;
            flex-wrap: wrap;
            gap: 8px;
        }

        #modalCompra .modal-footer .btn {
            flex: 1;
            min-width: 120px;
        }

        #modalCompra .produto-imagem img {
            max-height: 150px !important;
        }

        #modalCompra h4 {
            font-size: 1.1rem;
        }

        #modalCompra .h3 {
            font-size: 1.3rem;
        }

        #modalCompra .h4 {
            font-size: 1.1rem;
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
    .menu_filter ul {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
        padding: 0;
        list-style: none;
    }

    @media (max-width: 576px) {
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
