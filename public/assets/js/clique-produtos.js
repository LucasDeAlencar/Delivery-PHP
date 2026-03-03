/**
 * FORÇA CLIQUE NOS PRODUTOS - VERSÃO EXTREMA
 */

// FORÇA CLIQUE IMEDIATAMENTE
setTimeout(() => {
    
    // Remove TODOS os event listeners anteriores
    document.querySelectorAll('.produto-item').forEach(item => {
        item.replaceWith(item.cloneNode(true));
    });
    
    // Adiciona clique DIRETO
    document.querySelectorAll('.produto-item').forEach(item => {
        const ativo = item.getAttribute('data-produto-ativo');
        if (ativo === '0') {
            return;
        }
        item.style.cursor = 'pointer';
        item.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            
            const dados = {
                id: this.getAttribute('data-produto-id'),
                nome: this.getAttribute('data-produto-nome'),
                preco: this.getAttribute('data-produto-preco'),
                imagem: this.getAttribute('data-produto-imagem'),
                categoria: this.getAttribute('data-produto-categoria'),
                descricao: this.getAttribute('data-produto-descricao')
            };
            
            abrirModalForçado(dados);
        });
    });
    
    
    // TESTE VISUAL - ADICIONA BORDA VERMELHA NOS PRODUTOS
    document.querySelectorAll('.produto-item').forEach(item => {
        item.style.border = '2px solid red';
        item.style.boxShadow = '0 0 10px red';
    });
    
}, 1000);

function abrirModalForçado(dados) {
    
    // Preenche dados básicos
    document.getElementById('modal-produto-nome').textContent = dados.nome;
    document.getElementById('modal-produto-preco').textContent = `R$ ${parseFloat(dados.preco).toFixed(2).replace('.', ',')}`;
    document.getElementById('modal-produto-preco').setAttribute('data-valor-base', dados.preco);
    
    if (dados.descricao) {
        document.getElementById('modal-produto-descricao').textContent = dados.descricao;
    }
    
    if (dados.imagem) {
        document.getElementById('modal-produto-imagem').src = dados.imagem;
    }
    
    // Define ID no modal
    document.getElementById('modalCompra').setAttribute('data-produto-id', dados.id);
    
    // Reset campos
    document.getElementById('quantidade').value = 1;
    document.getElementById('observacoes').value = '';
    document.getElementById('modal-total').textContent = `R$ ${parseFloat(dados.preco).toFixed(2).replace('.', ',')}`;
    
    // FORÇA ABERTURA DO MODAL
    $('#modalCompra').modal('show');
    
}


// FUNÇÃO DE TESTE GLOBAL
window.testarCliqueProdutos = function() {
    
    const produtos = document.querySelectorAll('.produto-item');
    
    produtos.forEach((produto, index) => {
            id: produto.getAttribute('data-produto-id'),
            nome: produto.getAttribute('data-produto-nome'),
            preco: produto.getAttribute('data-produto-preco')
        });
    });
    
    if (produtos.length > 0) {
        produtos[0].click();
    } else {
        console.error('❌ NENHUM PRODUTO ENCONTRADO!');
    }
};
