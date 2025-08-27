<?php echo $this->extend('layout/principal_web'); ?>

<!-- Seção do Título -->
<?php echo $this->section('titulo'); ?>
<?php echo $titulo; ?>
<?php echo $this->endSection(); ?>

<!-- Seção de Estilos Personalizados -->
<?php echo $this->section('estilos'); ?>
<link rel="stylesheet" href="<?= site_url('web/src/css/menu-simple.css'); ?>">
<link rel="stylesheet" href="<?= site_url('web/src/css/modal-enhanced.css'); ?>">
<?php echo $this->endSection(); ?>

<!-- Seção de Conteúdo Dinâmico do Menu -->
<?php echo $this->section('menu_dinamico'); ?>
<?= $this->include('Home/menu_produtos') ?>
<?php echo $this->endSection(); ?>

<!-- Seção de Scripts Personalizados -->
<?php echo $this->section('scripts'); ?>
<script>
// Script para filtros dinâmicos - versão melhorada
$(document).ready(function() {
    console.log('Inicializando menu...');
    
    // Função para mostrar todos os produtos inicialmente
    function showAllProducts() {
        $('.filtr-item').show().addClass('active');
    }
    
    // Inicializar mostrando todos os produtos
    showAllProducts();
    
    // Filtros de categoria
    $('.filter-button').click(function(e) {
        e.preventDefault();
        
        // Remove active class from all buttons
        $('.menu_filter ul li').removeClass('active');
        
        // Add active class to clicked button
        $(this).parent().addClass('active');
        
        // Get filter value
        var filterValue = $(this).data('filter');
        
        // Hide all items first
        $('.filtr-item').fadeOut(200, function() {
            // Show filtered items
            if (filterValue === 'all') {
                $('.filtr-item').fadeIn(300).addClass('active');
            } else {
                $('.filtr-item.filter.' + filterValue).fadeIn(300).addClass('active');
            }
        });
    });
    
    // Clique nos produtos
    $('.produto-item').click(function(e) {
        e.preventDefault();
        
        var $produto = $(this);
        var dados = {
            id: $produto.data('produto-id'),
            nome: $produto.data('produto-nome'),
            preco: $produto.data('produto-preco'),
            categoria: $produto.data('produto-categoria'),
            descricao: $produto.data('produto-descricao'),
            imagem: $produto.data('produto-imagem')
        };
        
        // Preencher modal
        $('#modal-produto-nome').text(dados.nome || 'Produto');
        $('#modal-produto-categoria').text('Categoria: ' + (dados.categoria || 'N/A'));
        $('#modal-produto-descricao').text(dados.descricao || 'Sem descrição disponível');
        $('#modal-produto-preco').text('R$ ' + parseFloat(dados.preco || 0).toFixed(2).replace('.', ','));
        
        // Imagem
        if (dados.imagem) {
            $('#modal-produto-imagem')
                .attr('src', dados.imagem)
                .removeClass('d-none');
            $('#modal-produto-placeholder').addClass('d-none');
        } else {
            $('#modal-produto-imagem').addClass('d-none');
            $('#modal-produto-placeholder').removeClass('d-none');
        }
        
        // Resetar quantidade e observações
        $('#quantidade').val(1);
        $('#observacoes').val('');
        
        // Calcular total inicial
        var precoUnitario = parseFloat(dados.preco || 0);
        $('#modal-total').text('R$ ' + precoUnitario.toFixed(2).replace('.', ','));
        
        // Abrir modal
        $('#modalCompra').modal('show');
    });
    
    // Controles de quantidade
    $('#btn-aumentar').click(function() {
        var quantidade = parseInt($('#quantidade').val()) || 1;
        $('#quantidade').val(quantidade + 1);
        calcularTotal();
    });
    
    $('#btn-diminuir').click(function() {
        var quantidade = parseInt($('#quantidade').val()) || 1;
        if (quantidade > 1) {
            $('#quantidade').val(quantidade - 1);
            calcularTotal();
        }
    });
    
    $('#quantidade').on('input', function() {
        var quantidade = parseInt($(this).val()) || 1;
        if (quantidade < 1) quantidade = 1;
        if (quantidade > 99) quantidade = 99;
        $(this).val(quantidade);
        calcularTotal();
    });
    
    function calcularTotal() {
        var preco = parseFloat($('#modal-produto-preco').text().replace('R$ ', '').replace(',', '.')) || 0;
        var quantidade = parseInt($('#quantidade').val()) || 1;
        var total = preco * quantidade;
        $('#modal-total').text('R$ ' + total.toFixed(2).replace('.', ','));
    }
    
    // Botão adicionar ao carrinho
    $('#btn-adicionar-carrinho').click(function() {
        var $btn = $(this);
        var originalText = $btn.html();
        
        $btn.addClass('btn-loading').prop('disabled', true);
        
        // Simular processamento
        setTimeout(function() {
            $btn.removeClass('btn-loading').prop('disabled', false).html(originalText);
            $('#modalCompra').modal('hide');
            
            // Mostrar notificação melhorada
            showSuccessNotification('Produto adicionado ao carrinho com sucesso!');
        }, 1500);
    });
    
    // Função para mostrar notificação de sucesso
    function showSuccessNotification(message) {
        // Criar notificação
        var notification = $('<div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
            '<i class="fas fa-check-circle mr-2"></i>' + message +
            '<button type="button" class="close" data-dismiss="alert">' +
            '<span>&times;</span>' +
            '</button>' +
            '</div>');
        
        $('body').append(notification);
        
        // Remover após 4 segundos
        setTimeout(function() {
            notification.alert('close');
        }, 4000);
    }
    
    // Contador de caracteres para observações
    $('#observacoes').on('input', function() {
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
    $('#modalCompra').on('shown.bs.modal', function() {
        // Focar no campo de quantidade quando o modal abrir
        $('#quantidade').focus().select();
    });
    
    // Permitir usar Enter para adicionar ao carrinho
    $('#modalCompra').on('keypress', function(e) {
        if (e.which === 13 && !$('#btn-adicionar-carrinho').prop('disabled')) {
            $('#btn-adicionar-carrinho').click();
        }
    });
    
    // Efeito visual ao alterar quantidade
    $('#quantidade').on('change input', function() {
        $(this).addClass('success-animation');
        setTimeout(function() {
            $('#quantidade').removeClass('success-animation');
        }, 400);
    });
    
    console.log('Menu inicializado com sucesso!');
});
</script>
<?php echo $this->endSection(); ?>