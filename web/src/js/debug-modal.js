// Debug para modal de compra
$(document).ready(function() {
    console.log('Debug Modal - Iniciado');
    
    // Verificar se jQuery está carregado
    if (typeof $ === 'undefined') {
        console.error('jQuery não está carregado!');
        return;
    }
    console.log('jQuery carregado:', $.fn.jquery);
    
    // Verificar se Bootstrap está carregado
    if (typeof $.fn.modal === 'undefined') {
        console.error('Bootstrap modal não está carregado!');
        return;
    }
    console.log('Bootstrap modal carregado');
    
    // Verificar se o modal existe
    if ($('#modalCompra').length === 0) {
        console.error('Modal #modalCompra não encontrado!');
        return;
    }
    console.log('Modal encontrado');
    
    // Verificar produtos
    var produtos = $('.produto-item');
    console.log('Produtos encontrados:', produtos.length);
    
    if (produtos.length === 0) {
        console.warn('Nenhum produto encontrado!');
        return;
    }
    
    // Adicionar evento de clique com debug
    produtos.off('click.debug').on('click.debug', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Produto clicado!', this);
        
        var $produto = $(this);
        var dados = {
            id: $produto.data('produto-id'),
            nome: $produto.data('produto-nome'),
            preco: $produto.data('produto-preco'),
            categoria: $produto.data('produto-categoria'),
            descricao: $produto.data('produto-descricao'),
            imagem: $produto.data('produto-imagem')
        };
        
        console.log('Dados do produto:', dados);
        
        // Tentar abrir o modal
        try {
            $('#modalCompra').modal('show');
            console.log('Modal aberto com sucesso');
        } catch (error) {
            console.error('Erro ao abrir modal:', error);
        }
    });
    
    // Debug de eventos do modal
    $('#modalCompra').on('show.bs.modal', function() {
        console.log('Modal está sendo exibido');
    }).on('shown.bs.modal', function() {
        console.log('Modal foi exibido');
    }).on('hide.bs.modal', function() {
        console.log('Modal está sendo ocultado');
    }).on('hidden.bs.modal', function() {
        console.log('Modal foi ocultado');
    });
    
    console.log('Debug Modal - Configurado');
});