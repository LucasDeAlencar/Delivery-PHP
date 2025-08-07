<?php echo $this->extend('layout/principal_web'); ?>

<!-- Seção do Título -->
<?php echo $this->section('titulo'); ?>
<?php echo $titulo; ?>
<?php echo $this->endSection(); ?>

<!-- Seção de Estilos Personalizados -->
<?php echo $this->section('estilos'); ?>
<link rel="stylesheet" href="<?= site_url('web/src/css/menu-dinamico.css'); ?>">
<?php echo $this->endSection(); ?>

<!-- Seção de Conteúdo Dinâmico do Menu -->
<?php echo $this->section('menu_dinamico'); ?>
<?= $this->include('Home/menu_produtos') ?>
<?php echo $this->endSection(); ?>

<!-- Seção de Scripts Personalizados -->
<?php echo $this->section('scripts'); ?>
<script>
// Script para filtros dinâmicos com animações
$(document).ready(function() {
    console.log('Inicializando filtros de categoria...');
    
    // Debug: Verificar elementos
    console.log('Botões de filtro encontrados:', $('.filter-button').length);
    console.log('Items de filtro encontrados:', $('.filtr-item').length);
    
    // Função para mostrar todos os produtos inicialmente
    function showAllProducts() {
        $('.filtr-item').show().addClass('active');
        console.log('Mostrando todos os produtos');
    }
    
    // Inicializar mostrando todos os produtos
    showAllProducts();
    
    $('.filter-button').click(function(e) {
        e.preventDefault();
        
        console.log('Filtro clicado!');
        
        // Remove active class from all buttons
        $('.menu_filter ul li').removeClass('active');
        
        // Add active class to clicked button
        $(this).parent().addClass('active');
        
        // Get filter value
        var filterValue = $(this).data('filter');
        console.log('Filtro selecionado:', filterValue);
        
        // Hide all items first with animation
        $('.filtr-item').fadeOut(200, function() {
            // Show filtered items
            if (filterValue === 'all') {
                console.log('Mostrando todos os produtos');
                $('.filtr-item').fadeIn(300).addClass('active');
            } else {
                console.log('Mostrando produtos da categoria:', filterValue);
                $('.filtr-item.filter.' + filterValue).fadeIn(300).addClass('active');
            }
            
            // Debug: Verificar quantos items estão visíveis
            setTimeout(function() {
                var visibleItems = $('.filtr-item:visible').length;
                console.log('Items visíveis após filtro:', visibleItems);
                
                if (visibleItems === 0 && filterValue !== 'all') {
                    // Mostrar mensagem se não houver produtos na categoria
                    var noProductsMsg = '<div class="col-12 no-products-message"><div class="alert alert-warning text-center"><h5>Nenhum produto encontrado</h5><p>Não há produtos nesta categoria no momento.</p></div></div>';
                    $('.filtr-item').parent().append(noProductsMsg);
                    setTimeout(function() {
                        $('.no-products-message').remove();
                    }, 3000);
                }
            }, 350);
        });
    });
    
    // Efeito hover nos items
    $('.filtr-item .block').hover(
        function() {
            $(this).find('.filter_item_img i').addClass('animated pulse');
            $(this).addClass('hover-effect');
        },
        function() {
            $(this).find('.filter_item_img i').removeClass('animated pulse');
            $(this).removeClass('hover-effect');
        }
    );
    
    // Adicionar efeito de loading
    $('.filter-button').click(function() {
        var $this = $(this);
        var originalText = $this.text();
        $this.text('Carregando...');
        
        setTimeout(function() {
            $this.text(originalText);
        }, 500);
    });
    
    console.log('Filtros inicializados com sucesso!');
});
</script>
<?php echo $this->endSection(); ?>