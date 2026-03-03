// Debug para modal de compra
$(document).ready(function() {
    
    // Verificar se jQuery está carregado
    if (typeof $ === 'undefined') {
        console.error('jQuery não está carregado!');
        return;
    }
    
    // Verificar se Bootstrap está carregado
    if (typeof $.fn.modal === 'undefined') {
        console.error('Bootstrap modal não está carregado!');
        return;
    }
    
    // Verificar se o modal existe
    if ($('#modalCompra').length === 0) {
        console.error('Modal #modalCompra não encontrado!');
        return;
    }
    
    // Verificar produtos
    var produtos = $('.produto-item');
    
    if (produtos.length === 0) {
        return;
    }
    
    // Adicionar evento de clique com debug
    produtos.off('click.debug').on('click.debug', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        
        var $produto = $(this);
        var dados = {
            id: $produto.data('produto-id'),
            nome: $produto.data('produto-nome'),
            preco: $produto.data('produto-preco'),
            categoria: $produto.data('produto-categoria'),
            descricao: $produto.data('produto-descricao'),
            imagem: $produto.data('produto-imagem')
        };
        
        
        // Tentar abrir o modal
        try {
            $('#modalCompra').modal('show');
        } catch (error) {
            console.error('Erro ao abrir modal:', error);
        }
    });
    
    // Debug de eventos do modal
    $('#modalCompra').on('show.bs.modal', function() {
    }).on('shown.bs.modal', function() {
    }).on('hide.bs.modal', function() {
    }).on('hidden.bs.modal', function() {
    });
    
});