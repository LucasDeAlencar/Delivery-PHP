/**
 * Fix para o evento de clique nos produtos
 * Este script corrige o problema de eventos duplicados
 */

$(document).ready(function() {
    console.log('🔧 Aplicando correção de clique nos produtos...');
    
    // Remover todos os eventos de clique anteriores
    $('.produto-item').off('click');
    
    // Adicionar novo evento de clique
    $(document).on('click', '.produto-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('=== PRODUTO CLICADO ===');
        console.log('Elemento:', this);
        
        // Verificar se está aberto (variável global do index.php)
        const estaAberto = window.estaAberto || false;
        console.log('Estabelecimento aberto?', estaAberto);
        
        // Se fechado, mostrar aviso
        if (!estaAberto) {
            console.log('❌ Estabelecimento fechado');
            
            // Usar a função do index.php se existir
            if (typeof mostrarAvisoFechado === 'function') {
                mostrarAvisoFechado();
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Estabelecimento Fechado',
                    text: 'Desculpe, estamos fechados no momento. Volte durante nosso horário de funcionamento!',
                    confirmButtonColor: '#f8b531',
                    background: '#1a1a1a',
                    color: '#fff'
                });
            } else {
                alert('Desculpe, estamos fechados no momento!');
            }
            return false;
        }
        
        console.log('✅ Estabelecimento aberto - abrindo modal');
        
        // Obter dados do produto
        const produtoId = $(this).data('produto-id');
        const produtoNome = $(this).data('produto-nome');
        const produtoPreco = parseFloat($(this).data('produto-preco'));
        const produtoCategoria = $(this).data('produto-categoria');
        const produtoDescricao = $(this).data('produto-descricao');
        const produtoImagem = $(this).data('produto-imagem');
        
        console.log('📦 Dados do produto:', {
            id: produtoId,
            nome: produtoNome,
            preco: produtoPreco,
            categoria: produtoCategoria
        });
        
        // Verificar se o modal existe
        if ($('#modalCompra').length === 0) {
            console.error('❌ Modal #modalCompra não encontrado!');
            alert('Erro: Modal não encontrado. Recarregue a página.');
            return false;
        }
        
        // Preencher o modal com os dados do produto
        $('#modal-produto-nome').text(produtoNome);
        $('#modal-categoria-texto').text(produtoCategoria);
        $('#modal-produto-descricao').text(produtoDescricao || 'Sem descrição disponível');
        $('#modal-produto-preco').text('R$ ' + produtoPreco.toFixed(2).replace('.', ','));
        
        // Armazenar o ID do produto no modal para uso posterior
        $('#modalCompra').data('produto-id', produtoId);
        $('#modalCompra').data('produto-preco', produtoPreco);
        
        // Configurar imagem
        if (produtoImagem && produtoImagem !== '') {
            $('#modal-produto-imagem')
                .attr('src', produtoImagem)
                .attr('alt', produtoNome)
                .removeClass('d-none');
            $('#modal-produto-placeholder').addClass('d-none');
        } else {
            $('#modal-produto-imagem').addClass('d-none');
            $('#modal-produto-placeholder').removeClass('d-none');
        }
        
        // Resetar quantidade e observações
        $('#quantidade').val(1);
        $('#observacoes').val('');
        $('#contador-caracteres').text('200');
        
        // Calcular total inicial
        const total = produtoPreco * 1;
        $('#modal-total').text('R$ ' + total.toFixed(2).replace('.', ','));
        
        console.log('🚀 Abrindo modal...');
        
        // Abrir o modal
        $('#modalCompra').modal('show');
        
        // Verificar se abriu
        setTimeout(function() {
            if ($('#modalCompra').hasClass('show')) {
                console.log('✅ Modal aberto com sucesso!');
            } else {
                console.error('❌ Modal não abriu!');
            }
        }, 500);
    });
    
    console.log('✅ Correção aplicada! Total de produtos:', $('.produto-item').length);
});
