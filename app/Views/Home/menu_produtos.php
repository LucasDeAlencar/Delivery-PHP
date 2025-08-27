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

<!-- Modal de Compra -->
<div class="modal fade" id="modalCompra" tabindex="-1" role="dialog" aria-labelledby="modalCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCompraLabel">
                    <i class="flaticon-pizza-1 mr-2"></i>Finalizar Compra
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Imagem do Produto -->
                    <div class="col-md-5">
                        <div class="produto-imagem text-center">
                            <img id="modal-produto-imagem" src="" alt="" class="img-fluid rounded" style="max-height: 300px;">
                            <div id="modal-produto-placeholder" class="d-none">
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="flaticon-pizza-1" style="font-size: 4rem; color: #ccc;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detalhes do Produto -->
                    <div class="col-md-7">
                        <h4 id="modal-produto-nome" class="mb-3"></h4>
                        <p id="modal-produto-categoria" class="text-muted mb-3"></p>
                        <p id="modal-produto-descricao" class="mb-4"></p>
                        
                        <!-- Preço Unitário -->
                        <div class="mb-4">
                            <label class="font-weight-bold">Preço Unitário:</label>
                            <div id="modal-produto-preco" class="h4 text-primary"></div>
                        </div>
                        
                        <!-- Quantidade -->
                        <div class="mb-4">
                            <label for="quantidade" class="font-weight-bold">Quantidade:</label>
                            <div class="input-group" style="max-width: 150px;">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-diminuir">-</button>
                                </div>
                                <input type="number" class="form-control text-center" id="quantidade" value="1" min="1" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-aumentar">+</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">Total:</span>
                                <span id="modal-total" class="h3 text-success mb-0"></span>
                            </div>
                        </div>
                        
                        <!-- Observações -->
                        <div class="mb-3">
                            <label for="observacoes" class="font-weight-bold">Observações (opcional):</label>
                            <textarea class="form-control" id="observacoes" rows="3" placeholder="Alguma observação especial?"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-adicionar-carrinho">
                    <i class="flaticon-pizza-1 mr-2"></i>Adicionar ao Carrinho
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

<script>
$(document).ready(function() {
    let precoUnitario = 0;
    let produtoAtual = {};
    
    // Função para formatar preço
    function formatarPreco(valor) {
        return 'R$ ' + parseFloat(valor).toFixed(2).replace('.', ',');
    }
    
    // Função para calcular total
    function calcularTotal() {
        const quantidade = parseInt($('#quantidade').val()) || 1;
        const total = precoUnitario * quantidade;
        $('#modal-total').text(formatarPreco(total));
        return total;
    }
    
    // Função para mostrar notificação
    function mostrarNotificacao(titulo, mensagem, tipo = 'success') {
        const icone = tipo === 'success' ? '✅' : '❌';
        const cor = tipo === 'success' ? '#28a745' : '#dc3545';
        
        // Criar notificação
        const notificacao = $(`
            <div class="notificacao-popup" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${cor};
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                z-index: 9999;
                max-width: 300px;
                transform: translateX(100%);
                transition: all 0.3s ease;
            ">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 20px;">${icone}</span>
                    <div>
                        <div style="font-weight: bold; margin-bottom: 5px;">${titulo}</div>
                        <div style="font-size: 14px; opacity: 0.9;">${mensagem}</div>
                    </div>
                </div>
            </div>
        `);
        
        $('body').append(notificacao);
        
        // Animar entrada
        setTimeout(() => {
            notificacao.css('transform', 'translateX(0)');
        }, 100);
        
        // Remover após 4 segundos
        setTimeout(() => {
            notificacao.css('transform', 'translateX(100%)');
            setTimeout(() => notificacao.remove(), 300);
        }, 4000);
    }
    
    // Clique no produto com efeito visual
    $('.produto-item').click(function() {
        const $produto = $(this);
        
        // Efeito visual de clique
        $produto.addClass('success-animation');
        setTimeout(() => $produto.removeClass('success-animation'), 600);
        
        // Extrair dados do produto
        produtoAtual = {
            id: $produto.data('produto-id'),
            nome: $produto.data('produto-nome'),
            preco: $produto.data('produto-preco'),
            categoria: $produto.data('produto-categoria'),
            descricao: $produto.data('produto-descricao'),
            imagem: $produto.data('produto-imagem')
        };
        
        precoUnitario = parseFloat(produtoAtual.preco) || 0;
        
        // Preencher modal com animação
        $('#modal-produto-nome').text(produtoAtual.nome);
        $('#modal-produto-categoria').text('Categoria: ' + produtoAtual.categoria);
        $('#modal-produto-descricao').text(produtoAtual.descricao || 'Sem descrição disponível');
        $('#modal-produto-preco').text(formatarPreco(precoUnitario));
        
        // Imagem com efeito de loading
        if (produtoAtual.imagem) {
            $('#modal-produto-imagem')
                .attr('src', produtoAtual.imagem)
                .removeClass('d-none')
                .css('opacity', '0')
                .animate({opacity: 1}, 300);
            $('#modal-produto-placeholder').addClass('d-none');
        } else {
            $('#modal-produto-imagem').addClass('d-none');
            $('#modal-produto-placeholder').removeClass('d-none');
        }
        
        // Resetar quantidade e observações
        $('#quantidade').val(1);
        $('#observacoes').val('');
        
        // Calcular total inicial
        calcularTotal();
        
        // Abrir modal com delay para efeito visual
        setTimeout(() => {
            $('#modalCompra').modal('show');
        }, 200);
    });
    
    // Botões de quantidade com efeitos
    $('#btn-aumentar').click(function() {
        const $btn = $(this);
        $btn.addClass('btn-loading');
        
        setTimeout(() => {
            const quantidade = parseInt($('#quantidade').val()) || 1;
            $('#quantidade').val(quantidade + 1);
            calcularTotal();
            $btn.removeClass('btn-loading');
            
            // Efeito visual no total
            $('#modal-total').addClass('success-animation');
            setTimeout(() => $('#modal-total').removeClass('success-animation'), 600);
        }, 150);
    });
    
    $('#btn-diminuir').click(function() {
        const $btn = $(this);
        const quantidade = parseInt($('#quantidade').val()) || 1;
        
        if (quantidade > 1) {
            $btn.addClass('btn-loading');
            
            setTimeout(() => {
                $('#quantidade').val(quantidade - 1);
                calcularTotal();
                $btn.removeClass('btn-loading');
                
                // Efeito visual no total
                $('#modal-total').addClass('success-animation');
                setTimeout(() => $('#modal-total').removeClass('success-animation'), 600);
            }, 150);
        } else {
            // Shake effect se tentar diminuir abaixo de 1
            $btn.css('animation', 'shake 0.5s ease-in-out');
            setTimeout(() => $btn.css('animation', ''), 500);
        }
    });
    
    // Mudança manual na quantidade
    $('#quantidade').on('input', function() {
        let quantidade = parseInt($(this).val()) || 1;
        if (quantidade < 1) quantidade = 1;
        if (quantidade > 99) quantidade = 99; // Limite máximo
        $(this).val(quantidade);
        calcularTotal();
    });
    
    // Adicionar ao carrinho com efeitos
    $('#btn-adicionar-carrinho').click(function() {
        const $btn = $(this);
        const quantidade = parseInt($('#quantidade').val()) || 1;
        const total = calcularTotal();
        const observacoes = $('#observacoes').val().trim();
        
        // Efeito de loading
        $btn.addClass('btn-loading').prop('disabled', true);
        
        const pedido = {
            produto: produtoAtual,
            quantidade: quantidade,
            precoUnitario: precoUnitario,
            total: total,
            observacoes: observacoes,
            timestamp: new Date().toISOString()
        };
        
        console.log('Pedido:', pedido);
        
        // Simular processamento
        setTimeout(() => {
            // Aqui você pode implementar a lógica para adicionar ao carrinho
            // Por exemplo: enviar para o servidor, salvar no localStorage, etc.
            
            // Adicionar ao carrinho usando o CarrinhoManager
            if (window.carrinhoManager) {
                window.carrinhoManager.adicionarProduto(pedido);
            } else {
                // Fallback para localStorage se o CarrinhoManager não estiver disponível
                let carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
                carrinho.push(pedido);
                localStorage.setItem('carrinho', JSON.stringify(carrinho));
            }
            
            $btn.removeClass('btn-loading').prop('disabled', false);
            
            // Mostrar notificação de sucesso
            mostrarNotificacao(
                'Produto Adicionado!',
                `${produtoAtual.nome} (${quantidade}x) - ${formatarPreco(total)}`
            );
            
            // Fechar modal com delay
            setTimeout(() => {
                $('#modalCompra').modal('hide');
            }, 1000);
            
        }, 1500); // Simular delay de processamento
    });
    
    // Efeitos de hover melhorados
    $('.produto-item').hover(
        function() {
            $(this).find('.filter_item_img i').addClass('animated pulse');
        },
        function() {
            $(this).find('.filter_item_img i').removeClass('animated pulse');
        }
    );
    
    // Validação em tempo real do campo observações
    $('#observacoes').on('input', function() {
        const maxLength = 200;
        const currentLength = $(this).val().length;
        
        if (currentLength > maxLength) {
            $(this).val($(this).val().substring(0, maxLength));
        }
        
        // Mostrar contador de caracteres
        let contador = $(this).siblings('.char-counter');
        if (contador.length === 0) {
            contador = $('<small class="char-counter text-muted"></small>');
            $(this).after(contador);
        }
        
        const remaining = maxLength - $(this).val().length;
        contador.text(`${remaining} caracteres restantes`);
        
        if (remaining < 20) {
            contador.addClass('text-warning').removeClass('text-muted');
        } else {
            contador.addClass('text-muted').removeClass('text-warning');
        }
    });
    
    // Adicionar CSS para animações
    $('<style>').text(`
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .char-counter {
            display: block;
            margin-top: 5px;
            font-size: 12px;
        }
    `).appendTo('head');
});
</script>