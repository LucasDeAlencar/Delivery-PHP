/**
 * Script para mostrar o carrinho após interação com o menu
 */

$(document).ready(function() {
    let menuInteragido = false;
    let carrinhoMostrado = false;
    
    // Detectar quando usuário interage com o menu
    function detectarInteracaoMenu() {
        if (menuInteragido || carrinhoMostrado) return;
        
        menuInteragido = true;
        
        // Aguardar um pouco e mostrar o carrinho se houver itens
        setTimeout(() => {
            if (window.Carrinho && window.Carrinho.itens.length > 0 && !carrinhoMostrado) {
                window.Carrinho.mostrar();
                carrinhoMostrado = true;
            }
        }, 2000); // Mostrar após 2 segundos de interação
    }
    
    // Eventos que indicam interação com o menu
    $(document).on('click', '.produto-item', detectarInteracaoMenu);
    $(document).on('click', '.filter-button', detectarInteracaoMenu);
    $(document).on('scroll', function() {
        const menuSection = $('#menu');
        if (menuSection.length > 0) {
            const menuTop = menuSection.offset().top;
            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            
            // Se o usuário chegou na seção do menu
            if (scrollTop + windowHeight > menuTop + 100) {
                detectarInteracaoMenu();
            }
        }
    });
    
    // Mostrar carrinho quando item for adicionado
    $(document).on('carrinhoAtualizado', function() {
        if (window.Carrinho && window.Carrinho.itens.length > 0) {
            setTimeout(() => {
                window.Carrinho.mostrar();
                carrinhoMostrado = true;
            }, 500);
        }
    });
    
    // Atualizar badge do carrinho na navbar
    function atualizarBadgeNavbar() {
        if (window.Carrinho) {
            const quantidade = window.Carrinho.obterQuantidadeTotal();
            const badge = $('#carrinho-badge');
            
            if (quantidade > 0) {
                const quantidadeAnterior = parseInt(badge.text()) || 0;
                badge.text(quantidade).show();
                
                // Adicionar animação se a quantidade aumentou
                if (quantidade > quantidadeAnterior) {
                    badge.addClass('animate');
                    setTimeout(() => badge.removeClass('animate'), 600);
                }
            } else {
                badge.hide();
            }
        }
    }
    
    // Verificar periodicamente se o carrinho foi atualizado
    setInterval(atualizarBadgeNavbar, 1000);
    
    // Evento para abrir carrinho ao clicar no ícone da navbar
    $('.carrinho-navbar a').on('click', function(e) {
        e.preventDefault();
        if (window.Carrinho) {
            window.Carrinho.mostrar();
        }
    });
});
