/**
 * CLIQUE EXTREMO - FORÇA CLIQUE EM TUDO QUE PAREÇA PRODUTO
 */

setTimeout(() => {
    
    // Procura por QUALQUER elemento que possa ser um produto
    const seletores = [
        '.produto-item',
        '.produto',
        '.block',
        '[data-produto-id]',
        '.filter_item',
        '.filtr-item'
    ];
    
    let produtosEncontrados = 0;
    
    seletores.forEach(seletor => {
        const elementos = document.querySelectorAll(seletor);
        
        elementos.forEach(elemento => {
            // Verifica se tem dados de produto
            const id = elemento.getAttribute('data-produto-id');
            const nome = elemento.getAttribute('data-produto-nome');
            
            if (id && nome) {
                produtosEncontrados++;
                
                // FORÇA ESTILO CLICÁVEL
                elemento.style.cursor = 'pointer';
                
                // REMOVE TODOS OS EVENTOS ANTERIORES
                elemento.replaceWith(elemento.cloneNode(true));
                
                // ADICIONA NOVO EVENTO
                const novoElemento = document.querySelector(`[data-produto-id="${id}"]`);
                if (novoElemento) {
                    const ativo = novoElemento.getAttribute('data-produto-ativo');
                    if (ativo === '0') {
                        return;
                    }
                    novoElemento.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        
                        // Salvar posição do scroll ANTES de qualquer ação
                        window.__scrollPos = window.pageYOffset || document.documentElement.scrollTop;
                        
                        const dados = {
                            id: this.getAttribute('data-produto-id'),
                            nome: this.getAttribute('data-produto-nome'),
                            preco: this.getAttribute('data-produto-preco') || '25.90',
                            imagem: this.getAttribute('data-produto-imagem'),
                            categoria: this.getAttribute('data-produto-categoria'),
                            descricao: this.getAttribute('data-produto-descricao') || 'Produto delicioso'
                        };
                        
                        abrirModalExtremo(dados);
                        
                        return false;
                    });
                }
            }
        });
    });
    
    
    if (produtosEncontrados === 0) {
        console.error('❌ NENHUM PRODUTO ENCONTRADO! Verificando HTML...');
    }
    
}, 2000);

function abrirModalExtremo(dados) {
    
    // PREENCHE TODOS OS CAMPOS DO MODAL
    const modalNome = document.getElementById('modal-produto-nome');
    const modalPreco = document.getElementById('modal-produto-preco');
    const modalCategoria = document.getElementById('modal-produto-categoria');
    const modalDescricao = document.getElementById('modal-produto-descricao');
    const modalImagem = document.getElementById('modal-produto-imagem');
    const modalImagemUrl = document.getElementById('modal-produto-imagem-url');
    const modal = document.getElementById('modalCompra');
    
    if (modalNome) modalNome.textContent = dados.nome;
    if (modalPreco) {
        modalPreco.textContent = `R$ ${parseFloat(dados.preco).toFixed(2).replace('.', ',')}`;
        modalPreco.setAttribute('data-valor-base', dados.preco);
    }
    if (modalCategoria) modalCategoria.textContent = `Categoria: ${dados.categoria || 'N/A'}`;
    if (modalDescricao) modalDescricao.textContent = dados.descricao || 'Produto delicioso';
    
    // CONFIGURA IMAGEM
    if (modalImagem && dados.imagem) {
        modalImagem.src = dados.imagem;
        modalImagem.style.display = 'block';
        modalImagem.style.margin = '0 auto';
        modalImagem.style.textAlign = 'center';
        modalImagem.classList.remove('d-none');
        modalImagem.classList.add('d-block', 'mx-auto');
    }
    if (modalImagemUrl && dados.imagem) {
        modalImagemUrl.value = dados.imagem;
    }
    
    // CONFIGURA MODAL
    if (modal) {
        modal.setAttribute('data-produto-id', dados.id);
        
        // Reset campos
        const quantidade = document.getElementById('quantidade');
        const observacoes = document.getElementById('observacoes');
        const modalTotal = document.getElementById('modal-total');
        
        if (quantidade) quantidade.value = 1;
        if (observacoes) observacoes.value = '';
        if (modalTotal) modalTotal.textContent = `R$ ${parseFloat(dados.preco).toFixed(2).replace('.', ',')}`;
        
        // ABRE MODAL
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $(modal).modal('show');
        }
        
    }
}

// FUNCIONALIDADE DOS BOTÕES + E - DE QUANTIDADE
document.addEventListener('click', function(e) {
    // BOTÃO DIMINUIR (-)
    if (e.target.closest('.btn-diminuir, .diminuir-quantidade, [onclick*="diminuir"]')) {
        e.preventDefault();
        const quantidadeInput = document.getElementById('quantidade');
        const precoBase = parseFloat(document.getElementById('modal-produto-preco').getAttribute('data-valor-base')) || 0;
        
        if (quantidadeInput) {
            let quantidade = parseInt(quantidadeInput.value) || 1;
            if (quantidade > 1) {
                quantidade--;
                quantidadeInput.value = quantidade;
                
                // ATUALIZA TOTAL
                const total = precoBase * quantidade;
                const modalTotal = document.getElementById('modal-total');
                if (modalTotal) {
                    modalTotal.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
                }
                
            }
        }
    }
    
    // BOTÃO AUMENTAR (+)
    if (e.target.closest('.btn-aumentar, .aumentar-quantidade, [onclick*="aumentar"]')) {
        e.preventDefault();
        const quantidadeInput = document.getElementById('quantidade');
        const precoBase = parseFloat(document.getElementById('modal-produto-preco').getAttribute('data-valor-base')) || 0;
        
        if (quantidadeInput) {
            let quantidade = parseInt(quantidadeInput.value) || 1;
            quantidade++;
            quantidadeInput.value = quantidade;
            
            // ATUALIZA TOTAL
            const total = precoBase * quantidade;
            const modalTotal = document.getElementById('modal-total');
            if (modalTotal) {
                modalTotal.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
            }
            
        }
    }
});

// MUDANÇA MANUAL NO CAMPO QUANTIDADE
document.addEventListener('input', function(e) {
    if (e.target.id === 'quantidade') {
        const quantidade = parseInt(e.target.value) || 1;
        const precoBase = parseFloat(document.getElementById('modal-produto-preco').getAttribute('data-valor-base')) || 0;
        
        // ATUALIZA TOTAL
        const total = precoBase * quantidade;
        const modalTotal = document.getElementById('modal-total');
        if (modalTotal) {
            modalTotal.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
        }
        
    }
});

