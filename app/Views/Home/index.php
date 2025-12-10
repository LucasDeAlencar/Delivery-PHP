<?php echo $this->extend('layout/principal_web'); ?>

<!-- Seção do Título -->
<?php echo $this->section('titulo'); ?>
<?php echo $titulo; ?>
<?php echo $this->endSection(); ?>

<!-- Seção de Estilos Personalizados -->
<?php echo $this->section('estilos'); ?>
<link rel="stylesheet" href="<?= site_url('web/src/css/menu-simple.css'); ?>">
<link rel="stylesheet" href="<?= site_url('web/src/css/modal-dark.css'); ?>">
<link rel="stylesheet" href="<?= site_url('assets/css/produto-extras.css'); ?>">
<style>
    /* Força cor preta no campo quantidade */
    #modalCompra #quantidade,
    #modalCompra input#quantidade {
        color: #000000 !important;
    }
    #modalCompra #quantidade:focus,
    #modalCompra #quantidade:active {
        color: #000000 !important;
    }

    /* Força cor preta no X de fechar */
    #modalCompra .close,
    #modalCompra .modal-header .close {
        color: #000000 !important;
        opacity: 0.8 !important;
    }
    #modalCompra .close:hover,
    #modalCompra .modal-header .close:hover {
        color: #000000 !important;
        opacity: 1 !important;
    }
</style>
<?php echo $this->endSection(); ?>

<!-- Seção de Conteúdo Dinâmico do Menu -->
<?php echo $this->section('menu_dinamico'); ?>
<?= $this->include('Home/menu_produtos') ?>
<?php echo $this->endSection(); ?>

<!-- Seção de Scripts Personalizados -->
<?php echo $this->section('scripts'); ?>
<!-- Sistema de Extras -->
<script src="<?= site_url('assets/js/extras-sistema.js'); ?>"></script>
<script>
// Dados do expediente passados do PHP
const estaAberto = <?= json_encode($estaAberto ?? false) ?>;
const expedienteHoje = <?= json_encode($expedienteHoje ?? null) ?>;

// Script para menu com modal escuro e simétrico
$(document).ready(function () {
    console.log('Inicializando menu...');
    console.log('Estabelecimento aberto:', estaAberto);
    console.log('Expediente hoje:', expedienteHoje);
    
    // Funções auxiliares para formatação
    function formatarMoeda(valor) {
        return 'R$ ' + parseFloat(valor).toFixed(2).replace('.', ',');
    }
    
    // Funções para gerenciar extras
    function resetResumoExtras() {
        $('#modal-produto-preco-extras').text('Sem extras adicionados');
        $('#modal-total-detalhe').text('');
        $('#extras-selecionados-resumo').hide();
        $('#valor-extras-resumo').text('+ R$ 0,00');
        $('#contador-extras').text('0 extras selecionados');
        $('#aviso-extra-obrigatorio-modal').hide();
    }
    
    function obterResumoExtras() {
        if (typeof ProdutoExtras === 'undefined') {
            return {
                totalExtrasValor: 0,
                totalExtras: 0,
                obrigatorioExtras: 0,
                totalItensSelecionados: 0
            };
        }
        
        return {
            totalExtrasValor: ProdutoExtras.getTotalExtras(),
            totalExtras: ProdutoExtras.getExtrasSelecionados().length,
            obrigatorioExtras: ProdutoExtras.obrigatorioExtras,
            totalItensSelecionados: ProdutoExtras.getTotalItensSelecionados()
        };
    }
    
    function atualizarResumoExtrasUI(info) {
        const resumo = $('#extras-selecionados-resumo');
        if (info.totalExtras > 0) {
            resumo.show();
            $('#contador-extras').text(`${info.totalExtras} extra${info.totalExtras !== 1 ? 's' : ''} selecionado${info.totalExtras !== 1 ? 's' : ''}`);
            $('#valor-extras-resumo').text(`+ ${formatarMoeda(info.totalExtrasValor)}`);
            $('#modal-produto-preco-extras').text(`Inclui ${formatarMoeda(info.totalExtrasValor)} em extras por unidade`);
        } else {
            resumo.hide();
            $('#modal-produto-preco-extras').text('Sem extras adicionados');
        }
        
        if (info.obrigatorioExtras > 0) {
            $('#badge-obrigatorio').show();
            if (info.totalItensSelecionados < info.obrigatorioExtras) {
                $('#aviso-extra-obrigatorio-modal').text(`Selecione pelo menos ${info.obrigatorioExtras} extra${info.obrigatorioExtras > 1 ? 's' : ''}.`).show();
            } else {
                $('#aviso-extra-obrigatorio-modal').hide();
            }
        } else {
            $('#badge-obrigatorio').hide();
            $('#aviso-extra-obrigatorio-modal').hide();
        }
    }
    
    function recalcularTotaisComExtras(extrasInfo = null) {
        const precoBase = parseFloat($('#modal-produto-preco').data('valor-base') || 0);
        const quantidade = parseInt($('#quantidade').val()) || 1;
        const info = extrasInfo || obterResumoExtras();
        const totalExtras = info.totalExtrasValor * quantidade;
        const total = (precoBase * quantidade) + totalExtras;
        
        $('#modal-total').text(formatarMoeda(total));
        if (info.totalExtrasValor > 0) {
            $('#modal-total-detalhe').text(`Base: ${formatarMoeda(precoBase * quantidade)} | Extras: ${formatarMoeda(totalExtras)}`);
        } else {
            $('#modal-total-detalhe').text('');
        }
        
        atualizarResumoExtrasUI({
            totalExtrasValor: info.totalExtrasValor,
            totalExtras: info.totalExtras,
            obrigatorioExtras: info.obrigatorioExtras,
            totalItensSelecionados: info.totalItensSelecionados
        });
    }

    // Função para mostrar todos os produtos inicialmente
    function showAllProducts() {
        $('.filtr-item').show().addClass('active');
    }

    // Inicializar mostrando todos os produtos
    showAllProducts();

    // Filtros de categoria
    $('.filter-button').click(function (e) {
        e.preventDefault();
        $('.menu_filter ul li').removeClass('active');
        $(this).parent().addClass('active');
        var filterValue = $(this).data('filter');
        $('.filtr-item').fadeOut(200, function () {
            if (filterValue === 'all') {
                $('.filtr-item').fadeIn(300).addClass('active');
            } else {
                $('.filtr-item.filter.' + filterValue).fadeIn(300).addClass('active');
            }
        });
    });

    // Clique nos produtos
    $('.produto-item').click(function (e) {
        e.preventDefault();

        if (!estaAberto) {
            mostrarAvisoFechado();
            return false;
        }

        const $produto = $(this);
        const dados = {
            id: $produto.data('produto-id'),
            nome: $produto.data('produto-nome'),
            preco: parseFloat($produto.data('produto-preco') || 0),
            categoria: $produto.data('produto-categoria'),
            descricao: $produto.data('produto-descricao'),
            imagem: $produto.data('produto-imagem')
        };

        $('.produto-item').removeClass('active');
        $produto.addClass('active');
        $('#modalCompra').data('produtoId', dados.id);
        $('#modalCompra').data('produtoImagem', dados.imagem || '');

        $('#modal-produto-nome').text(dados.nome || 'Produto');
        $('#modal-produto-id').val(dados.id || '');
        $('#modal-produto-imagem-url').val(dados.imagem || '');
        $('#modal-produto-categoria').text('Categoria: ' + (dados.categoria || 'N/A'));
        $('#modal-produto-descricao').text(dados.descricao || 'Sem descrição disponível');
        $('#modal-produto-preco').text(formatarMoeda(dados.preco)).data('valor-base', dados.preco);

        if (dados.imagem) {
            $('#modal-produto-imagem').attr('src', dados.imagem).removeClass('d-none');
            $('#modal-produto-placeholder').addClass('d-none');
        } else {
            $('#modal-produto-imagem').addClass('d-none');
            $('#modal-produto-placeholder').removeClass('d-none');
        }

        $('#quantidade').val(1);
        $('#observacoes').val('');
        resetResumoExtras();
        
        // Carregar extras do produto
        if (typeof ProdutoExtras !== 'undefined') {
            ProdutoExtras.carregarExtras(dados.id);
        }
        
        recalcularTotaisComExtras();
        $('#modalCompra').modal('show');
    });

    // Controles de quantidade
    $('#btn-aumentar').click(function () {
        let quantidade = parseInt($('#quantidade').val()) || 1;
        quantidade = Math.min(quantidade + 1, 99);
        $('#quantidade').val(quantidade);
        recalcularTotaisComExtras();
    });

    $('#btn-diminuir').click(function () {
        let quantidade = parseInt($('#quantidade').val()) || 1;
        quantidade = Math.max(1, quantidade - 1);
        $('#quantidade').val(quantidade);
        recalcularTotaisComExtras();
    });

    $('#quantidade').on('input', function () {
        let quantidade = parseInt($(this).val()) || 1;
        quantidade = Math.min(Math.max(quantidade, 1), 99);
        $(this).val(quantidade);
        recalcularTotaisComExtras();
    });
    
    // Event listeners para botões de extras
    $(document).on('click', '#btn-selecionar-extras', function() {
        console.log('🎨 Abrindo modal de extras...');
        if (typeof ProdutoExtras !== 'undefined') {
            ProdutoExtras.abrirModalExtras();
        }
    });

    $(document).on('click', '#btn-confirmar-extras', function() {
        console.log('🔘 Botão Confirmar clicado!');
        if (typeof ProdutoExtras !== 'undefined') {
            ProdutoExtras.confirmarExtras();
            // Forçar atualização após confirmar
            setTimeout(() => {
                const info = obterResumoExtras();
                atualizarResumoExtrasUI(info);
                recalcularTotaisComExtras(info);
            }, 100);
        }
    });

    // Atualizar resumo de extras quando modal de compra abre
    $('#modalCompra').on('shown.bs.modal', function() {
        setTimeout(() => {
            const info = obterResumoExtras();
            atualizarResumoExtrasUI(info);
            recalcularTotaisComExtras(info);
        }, 100);
    });

    // Adicionar ao carrinho
    $('#btn-adicionar-carrinho').click(function () {
        if (!estaAberto) {
            $('#modalCompra').modal('hide');
            mostrarAvisoFechado();
            return false;
        }

        const produtoId = $('#modal-produto-id').val();
        const produtoNome = $('#modal-produto-nome').text().trim();
        const produtoImagem = $('#modal-produto-imagem-url').val() ||
                $('#modal-produto-imagem').attr('src') ||
                $('#modalCompra').data('produtoImagem') || '';
        const quantidade = parseInt($('#quantidade').val()) || 1;
        const precoBase = parseFloat($('#modal-produto-preco').data('valor-base') || 0);
        const precoTotal = parseFloat($('#modal-total').text().replace('R$ ', '').replace(' ', '').replace(',', '.')) || 0;
        const observacoes = $('#observacoes').val().trim();

        if (!produtoId || !produtoNome || precoBase <= 0 || quantidade <= 0) {
            showNotification('Dados do produto inválidos!', 'error');
            return false;
        }
        
        // Validar extras obrigatórios
        if (typeof ProdutoExtras !== 'undefined' && !ProdutoExtras.validarSelecao()) {
            return false;
        }
        
        // Obter extras selecionados
        const extrasSelecionados = typeof ProdutoExtras !== 'undefined' ? ProdutoExtras.getExtrasSelecionados() : [];
        
        console.log('Dados capturados:', {
            produtoId, produtoNome, precoBase, quantidade, extras: extrasSelecionados
        });
        
        // Adicionar ao carrinho (localStorage)
        if (typeof window.Carrinho !== 'undefined') {
            window.Carrinho.adicionar({
                id: produtoId,
                nome: produtoNome,
                imagem: produtoImagem,
                preco: precoBase,
                quantidade: quantidade,
                observacoes: observacoes,
                extras: extrasSelecionados,
                totalCalculado: precoTotal
            });
            
            // Disparar evento personalizado
            $(document).trigger('carrinhoAtualizado');
            
            $('#modalCompra').modal('hide');
        } else {
            console.error('❌ Objeto Carrinho não encontrado!');
            showNotification('Erro: Sistema de carrinho não carregado', 'error');
        }
    });

    // Função melhorada para mostrar notificação
    function showNotification(message, type = 'success') {
        $('.custom-notification').remove();

        const alertClass = type === 'success' ? 'alert-success' :
                type === 'error' ? 'alert-danger' : 'alert-info';

        const icon = type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';

        // Calcular posição abaixo da navbar
        const navbar = $('#ftco-navbar');
        const navbarHeight = navbar.length ? navbar.outerHeight() : 70;
        const topPosition = navbarHeight + 10;

        const notification = $(`
            <div class="custom-notification alert ${alertClass} position-fixed" 
                 style="top: ${topPosition}px; right: 20px; z-index: 9999; min-width: 300px; 
                        max-width: 400px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
                        border-radius: 8px; border: none; animation: slideInRight 0.3s ease-out;">
                <div class="d-flex align-items-center">
                    <i class="fas ${icon} mr-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <strong>${type === 'success' ? 'Sucesso!' : type === 'error' ? 'Erro!' : 'Informação!'}</strong>
                        <div style="margin-top: 5px;">${message}</div>
                    </div>
                    <button type="button" class="close ml-auto" style="opacity: 0.7;">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
        `);

        $('body').append(notification);

        notification.find('.close').click(function () {
            notification.fadeOut(300, function () {
                $(this).remove();
            });
        });

        setTimeout(function () {
            if (notification.is(':visible')) {
                notification.fadeOut(500, function () {
                    $(this).remove();
                });
            }
        }, 5000);

        if (!$('#notification-styles').length) {
            $('head').append(`
                <style id="notification-styles">
                    @keyframes slideInRight {
                        from {
                            transform: translateX(100%);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                    .custom-notification {
                        animation: slideInRight 0.3s ease-out;
                    }
                </style>
            `);
        }
    }

    // Função para mostrar aviso de estabelecimento fechado
    function mostrarAvisoFechado() {
        let mensagem = '';
        let titulo = '';

        if (!expedienteHoje || expedienteHoje.situacao == 0) {
            titulo = '🚫 Fechado Hoje';
            mensagem = 'Desculpe, estamos fechados hoje. Volte em outro dia!';
        } else {
            titulo = '⏰ Fora do Horário';
            const abertura = expedienteHoje.abertura ? expedienteHoje.abertura.substring(0, 5) : '';
            const fechamento = expedienteHoje.fechamento ? expedienteHoje.fechamento.substring(0, 5) : '';
            mensagem = `Nosso horário de atendimento hoje é das ${abertura} às ${fechamento}. Volte nesse horário para fazer seu pedido!`;
        }

        const aviso = $(`
            <div class="modal fade" id="modalFechado" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="background: #1a1a1a; border: 2px solid #f8b531; border-radius: 15px;">
                        <div class="modal-header" style="background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%); border-bottom: 1px solid #333;">
                            <h5 class="modal-title text-warning" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                ${titulo}
                            </h5>
                            <button type="button" class="close text-light" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center" style="padding: 30px;">
                            <div style="font-size: 4rem; margin-bottom: 20px;">
                                ${expedienteHoje && expedienteHoje.situacao == 1 ? '⏰' : '🚫'}
                            </div>
                            <p class="text-light" style="font-size: 1.1rem; line-height: 1.6; margin-bottom: 20px;">
                                ${mensagem}
                            </p>
                            ${expedienteHoje && expedienteHoje.situacao == 1 ? `
                                <div class="alert" style="background: rgba(248, 181, 49, 0.1); border: 1px solid #f8b531; color: #f8b531;">
                                    <strong>🕒 Horário de Hoje:</strong><br>
                                    ${expedienteHoje.abertura.substring(0, 5)} - ${expedienteHoje.fechamento.substring(0, 5)}
                                </div>
                            ` : ''}
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid #333; justify-content: center;">
                            <button type="button" class="btn btn-warning" data-dismiss="modal" style="padding: 10px 30px;">
                                <i class="fas fa-check"></i> Entendi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `);

        $('#modalFechado').remove();
        $('body').append(aviso);
        $('#modalFechado').modal('show');
        $('#modalFechado').on('hidden.bs.modal', function () {
            $(this).remove();
        });
    }

    // Contador de caracteres para observações
    $('#observacoes').on('input', function () {
        var maxLength = 200;
        var currentLength = $(this).val().length;

        if (currentLength > maxLength) {
            $(this).val($(this).val().substring(0, maxLength));
        }

        var contador = $(this).siblings('.char-counter');
        if (contador.length === 0) {
            contador = $('<small class="char-counter"></small>');
            $(this).after(contador);
        }

        var remaining = maxLength - $(this).val().length;
        contador.text(remaining + ' caracteres restantes');

        if (remaining < 20) {
            contador.addClass('text-warning');
        } else {
            contador.removeClass('text-warning');
        }
    });

    // Melhorar a experiência do modal
    $('#modalCompra').on('shown.bs.modal', function () {
        $('#quantidade').focus();
    });

    // Permitir usar Enter para adicionar ao carrinho
    $('#modalCompra').on('keypress', function (e) {
        if (e.which === 13 && !$('#btn-adicionar-carrinho').prop('disabled')) {
            $('#btn-adicionar-carrinho').click();
        }
    });

    console.log('Menu inicializado com sucesso!');
});
</script>
<?php echo $this->endSection(); ?>
