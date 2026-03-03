/**
 * Sistema de Popup de Produtos - Versão Simplificada
 */

window.PopupProduto = {
    produtoAtual: null,
    extras: [],

    // Abrir popup do produto
    abrir(dados) {
        this.produtoAtual = dados;
        this.extras = [];
        
        // Resetar extras no objeto global
        if (window.ProdutoExtras) {
            window.ProdutoExtras.extrasSelecionados = [];
            window.ProdutoExtras.obrigatorioExtras = 0;
            window.ProdutoExtras.maxExtras = 0;
            $('#extras-selecionados-resumo').hide();
            $('#modal-produto-preco-extras').text('Sem extras adicionados');
        }
        
        // Preencher dados básicos
        $('#modal-produto-nome').text(dados.nome);
        $('#modal-produto-preco').text(`R$ ${parseFloat(dados.preco).toFixed(2).replace('.', ',')}`);
        $('#modal-produto-preco').attr('data-valor-base', dados.preco);
        $('#modal-produto-categoria').text(`Categoria: ${dados.categoria || 'N/A'}`);
        $('#modal-produto-descricao').text(dados.descricao || 'Produto delicioso');
        
        // Configurar imagem
        if (dados.imagem) {
            $('#modal-produto-imagem').attr('src', dados.imagem).show();
        }
        
        // Reset campos
        $('#quantidade').val(1);
        $('#observacoes').val('');
        this.atualizarTotal();
        
        // Configurar modal
        $('#modalCompra').attr('data-produto-id', dados.id);
        
        // Carregar extras
        this.carregarExtras(dados.id);
        
        // Abrir modal
        $('#modalCompra').modal('show');
    },

    // Carregar extras do produto
    async carregarExtras(produtoId) {
        try {
            const response = await fetch(`/api/produto-extras/${produtoId}`);
            const data = await response.json();

            if (data.success && data.extras && data.extras.length > 0) {
                this.extras = data.extras;
                
                // Configurar no objeto global ProdutoExtras
                if (window.ProdutoExtras) {
                    window.ProdutoExtras.extrasDisponiveis = data.extras;
                    window.ProdutoExtras.obrigatorioExtras = parseInt(data.obrigatorio_extras) || 0;
                    window.ProdutoExtras.maxExtras = parseInt(data.max_extras) || 0;
                    console.log('Extras carregados:', {
                        obrigatorio: window.ProdutoExtras.obrigatorioExtras,
                        max: window.ProdutoExtras.maxExtras
                    });
                }
                
                $('#container-btn-extras').show();
                
                let textoBtn = 'Selecionar Extras';
                if (data.obrigatorio_extras > 0) {
                    $('#badge-obrigatorio').show();
                    textoBtn = `Selecionar Extras (${data.obrigatorio_extras} obrigatório)`;
                } else if (data.max_extras > 0) {
                    $('#badge-obrigatorio').hide();
                    textoBtn = `Selecionar Extras (Máx. ${data.max_extras})`;
                } else {
                    $('#badge-obrigatorio').hide();
                    textoBtn = 'Selecionar Extras (Opcional)';
                }
                $('#texto-btn-extras').text(textoBtn);
            } else {
                // Sem extras disponíveis - ainda assim configurar valores
                if (window.ProdutoExtras) {
                    window.ProdutoExtras.obrigatorioExtras = parseInt(data.obrigatorio_extras) || 0;
                    window.ProdutoExtras.maxExtras = parseInt(data.max_extras) || 0;
                }
                $('#container-btn-extras').hide();
            }
        } catch (error) {
            console.error('Erro ao carregar extras:', error);
            $('#container-btn-extras').hide();
        }
    },

    // Atualizar total do produto
    atualizarTotal() {
        const precoBase = parseFloat($('#modal-produto-preco').attr('data-valor-base')) || 0;
        const quantidade = parseInt($('#quantidade').val()) || 1;
        
        let totalExtras = 0;
        if (window.ProdutoExtras && window.ProdutoExtras.extrasSelecionados) {
            totalExtras = window.ProdutoExtras.extrasSelecionados.reduce((total, extra) => {
                return total + (parseFloat(extra.preco) * parseInt(extra.quantidade));
            }, 0);
        }
        
        const total = (precoBase + totalExtras) * quantidade;
        $('#modal-total').text(`R$ ${total.toFixed(2).replace('.', ',')}`);
        
        // Mostrar detalhes se há extras
        if (totalExtras > 0) {
            $('#modal-total-detalhe').text(`Base: R$ ${(precoBase * quantidade).toFixed(2).replace('.', ',')} | Extras: R$ ${(totalExtras * quantidade).toFixed(2).replace('.', ',')}`);
            $('#modal-produto-preco-extras').text(`Inclui R$ ${totalExtras.toFixed(2).replace('.', ',')} em extras por unidade`);
        } else {
            $('#modal-total-detalhe').text('');
            $('#modal-produto-preco-extras').text('Sem extras adicionados');
        }
    },

    // Adicionar produto ao carrinho
    adicionarAoCarrinho() {
        if (!this.produtoAtual) return false;

        // Validar extras obrigatórios
        if (window.ProdutoExtras && !window.ProdutoExtras.validarSelecao()) {
            return false;
        }

        const produto = {
            id: this.produtoAtual.id,
            nome: this.produtoAtual.nome,
            preco: parseFloat(this.produtoAtual.preco),
            quantidade: parseInt($('#quantidade').val()) || 1,
            observacoes: $('#observacoes').val() || '',
            extras: window.ProdutoExtras ? window.ProdutoExtras.getExtrasSelecionados() : []
        };

        // Adicionar ao carrinho
        if (window.CarrinhoMenu) {
            window.CarrinhoMenu.adicionar(produto);
            this.fechar();
            return true;
        }

        return false;
    },

    // Fechar popup
    fechar() {
        $('#modalCompra').modal('hide');
        this.produtoAtual = null;
        this.extras = [];
        
        // Limpar extras
        if (window.ProdutoExtras) {
            window.ProdutoExtras.limparExtras();
        }
    }
};

// Eventos
$(document).ready(function() {
    
    // Teste simples de clique
    $(document).on('click', '*[data-produto-id]:not(#modalCompra)', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const elemento = $(this);
        
        // Dados básicos para teste
        const dados = {
            id: elemento.data('produto-id'),
            nome: elemento.data('produto-nome') || 'Produto Teste',
            preco: elemento.data('produto-preco') || '10.00',
            imagem: elemento.data('produto-imagem') || '',
            categoria: 'Teste',
            descricao: 'Produto de teste'
        };
        
        PopupProduto.abrir(dados);
    });

    // Clique nos produtos (versão completa)
    $(document).on('click', '.produto-item, .block, .filtr-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        
        const elemento = $(this);
        
        // Verificar se produto está ativo
        const ativo = elemento.attr('data-produto-ativo');
        if (ativo === '0') {
            e.stopImmediatePropagation();
            return false;
        }
        
        // Extrair dados do produto
        let dados = {
            id: elemento.data('produto-id') || elemento.attr('data-produto-id') || elemento.find('[data-produto-id]').first().data('produto-id'),
            nome: elemento.data('produto-nome') || elemento.find('h3, .produto-nome, .heading, .card-title').first().text().trim(),
            preco: elemento.data('produto-preco') || elemento.attr('data-produto-preco'),
            imagem: elemento.data('produto-imagem') || elemento.find('img').first().attr('src'),
            categoria: elemento.data('produto-categoria') || 'Produto',
            descricao: elemento.data('produto-descricao') || elemento.find('.card-text, .descricao').first().text().trim()
        };
        
        
        // Se preço não está em data, tentar extrair do texto
        if (!dados.preco) {
            const precoTexto = elemento.find('.price, .preco, .valor, .card-text').text();
            const precoMatch = precoTexto.match(/R\$\s*([\d,]+)/);
            if (precoMatch) {
                dados.preco = precoMatch[1].replace(',', '.');
            } else {
                // Tentar pegar apenas números
                const numeroMatch = precoTexto.match(/([\d,]+)/);
                if (numeroMatch) {
                    dados.preco = numeroMatch[1].replace(',', '.');
                }
            }
        }
        
        
        // Validar dados essenciais
        if (dados.id && dados.nome && dados.preco) {
            PopupProduto.abrir(dados);
        } else {
            console.error('❌ Dados do produto incompletos:', dados);
        }
    });

    // Botões de quantidade - seletores mais específicos
    $(document).off('click', '#btn-aumentar').on('click', '#btn-aumentar', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const input = $('#quantidade');
        let valor = parseInt(input.val()) || 1;
        if (valor < 99) {
            input.val(valor + 1);
            PopupProduto.atualizarTotal();
        }
    });

    $(document).off('click', '#btn-diminuir').on('click', '#btn-diminuir', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const input = $('#quantidade');
        let valor = parseInt(input.val()) || 1;
        if (valor > 1) {
            input.val(valor - 1);
            PopupProduto.atualizarTotal();
        }
    });

    // Mudança na quantidade
    $(document).on('input change', '#quantidade', function() {
        PopupProduto.atualizarTotal();
    });

    // Botão adicionar ao carrinho
    $(document).on('click', '#btn-adicionar-carrinho', function(e) {
        e.preventDefault();
        PopupProduto.adicionarAoCarrinho();
    });

    // Atualizar total quando extras mudarem
    $(document).on('extrasAtualizados', function() {
        PopupProduto.atualizarTotal();
    });
});

