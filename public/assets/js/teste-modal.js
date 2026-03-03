/**
 * Script de teste para verificar funcionamento do carrinho e botões
 */
$(document).ready(function() {
    
    // Verificar se elementos existem
    setTimeout(function() {
        const elementos = {
            'btn-aumentar': $('#btn-aumentar'),
            'btn-diminuir': $('#btn-diminuir'),
            'quantidade': $('#quantidade'),
            'btn-adicionar-carrinho': $('#btn-adicionar-carrinho'),
            'modalCompra': $('#modalCompra')
        };
        
        Object.keys(elementos).forEach(key => {
            const elemento = elementos[key];
            
            if (elemento.length > 0 && key.includes('btn-')) {
            }
        });
        
        // Verificar se Carrinho existe
        
        // Teste dos botões
        
        // Simular clique no botão aumentar
        if ($('#btn-aumentar').length > 0) {
            $('#btn-aumentar').trigger('click');
        }
        
    }, 2000);
    
    // Monitor de mudanças no campo quantidade
    $(document).on('change input', '#quantidade', function() {
    });
    
    // Monitor de cliques nos botões
    $(document).on('click', '#btn-aumentar', function() {
    });
    
    $(document).on('click', '#btn-diminuir', function() {
    });
    
    $(document).on('click', '#btn-adicionar-carrinho', function() {
    });
});
