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
        this.tamanhoSelecionado = null;
        
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
        
        const comTamanho = dados.com_tamanho == 1 || dados.comTamanho == 1;
        if (comTamanho && dados.tamanhos && dados.tamanhos.length > 0) {
            // Produto com tamanho: não exibir preço base, aguardar seleção
            $('#modal-produto-preco').text('Selecione um tamanho');
            $('#modal-produto-preco').attr('data-valor-base', 0);
        } else {
            $('#modal-produto-preco').text(`R$ ${parseFloat(dados.preco).toFixed(2).replace('.', ',')}`);
            $('#modal-produto-preco').attr('data-valor-base', dados.preco);
        }
        
        $('#modal-produto-categoria').text(`Categoria: ${dados.categoria || 'N/A'}`);
        $('#modal-produto-descricao').text(dados.descricao || 'Produto delicioso');
        
        // Configurar imagem
        if (dados.imagem) {
            $('#modal-produto-imagem').attr('src', dados.imagem).show();
        }
        
        // Reset campos
        $('#quantidade').val(1);
        $('#observacoes').val('');

        // Configurar tamanhos
        this.configurarTamanhos(dados);

        this.atualizarTotal();
        
        // Configurar modal
        $('#modalCompra').attr('data-produto-id', dados.id);
        
        // Carregar extras
        this.carregarExtras(dados.id);
        
        // Abrir modal
        $('#modalCompra').modal('show');
    },

     // Configurar seletor de tamanhos
     configurarTamanhos(dados) {
         const tamanhos = dados.tamanhos || [];
         const comTamanho = dados.com_tamanho == 1 || dados.comTamanho == 1;

         if (comTamanho && tamanhos.length > 0) {
             const $opcoes = $('#tamanhos-opcoes').empty();
             tamanhos.forEach((t, i) => {
                 const preco = parseFloat(t.preco).toFixed(2).replace('.', ',');
                  const $btn = $(`<button type="button" class="btn btn-outline-warning btn-sm btn-tamanho" data-nome="${t.nome}" data-preco="${t.preco}">
                      ${t.nome} — R$ ${preco}
                  </button>`);
                  // Click handler para selecionar tamanho
                  $btn.on('click', (e) => {
                      e.preventDefault();
                      e.stopPropagation();
                      
                      // Remove seleção anterior
                      $('.btn-tamanho').removeClass('active');
                      
                      // Seleciona este
                      $(e.currentTarget).addClass('active');
                      
                      // Atualiza tamanho selecionado
                      this.tamanhoSelecionado = {
                          id: t.id || null,
                          nome: t.nome,
                          preco: parseFloat(t.preco)
                      };
                      
                      console.log('Tamanho selecionado:', this.tamanhoSelecionado);
                      
                      // Atualiza o preço exibido
                      this.atualizarPrecoComTamanho();
                  });
                 
                 $opcoes.append($btn);
             });
             $('#container-tamanhos').show();
             $('#aviso-tamanho').addClass('d-none');
             this.tamanhoSelecionado = null;
         } else {
             $('#container-tamanhos').hide();
             this.tamanhoSelecionado = null;
         }
     },
     
     // Atualizar preço exibido quando tamanho é selecionado
     atualizarPrecoComTamanho() {
         const precoBase = parseFloat($('#modal-produto-preco').attr('data-valor-base')) || 0;
         let precoFinal = precoBase;
         
         if (this.tamanhoSelecionado) {
             precoFinal = this.tamanhoSelecionado.preco;
         }
         
         // Atualiza o preço exibido
         $('#modal-produto-preco').text(`R$ ${precoFinal.toFixed(2).replace('.', ',')}`);
         $('#modal-produto-preco').attr('data-valor-base', precoFinal);
         
         // Atualiza o total
         this.atualizarTotal();
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
        
        // Se produto tem tamanho e nenhum foi selecionado, não calcular
        const comTamanho = this.produtoAtual && (this.produtoAtual.com_tamanho == 1 || this.produtoAtual.comTamanho == 1);
        if (comTamanho && !this.tamanhoSelecionado) {
            $('#modal-total').text('—');
            $('#modal-total-detalhe').text('Selecione um tamanho');
            return;
        }
        
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
         
         // Validar tamanho se o produto tiver tamanho
         const comTamanho = this.produtoAtual.com_tamanho == 1 || this.produtoAtual.comTamanho == 1;
         if (comTamanho && this.produtoAtual.tamanhos && this.produtoAtual.tamanhos.length > 0) {
             if (!this.tamanhoSelecionado) {
                 $('#aviso-tamanho').removeClass('d-none').text('Selecione um tamanho para continuar.');
                 return false;
             }
         }

         // Calcular preço base (usa tamanho se houver)
         let precoFinal = parseFloat(this.produtoAtual.preco);
         if (this.tamanhoSelecionado) {
             precoFinal = this.tamanhoSelecionado.preco;
         }

         const produto = {
             id: this.produtoAtual.id,
             nome: this.produtoAtual.nome,
             preco: precoFinal,
             quantidade: parseInt($('#quantidade').val()) || 1,
             observacoes: $('#observacoes').val() || '',
             extras: window.ProdutoExtras ? window.ProdutoExtras.getExtrasSelecionados() : [],
             categoria_id: this.produtoAtual.categoria_id || null,
             // Informações de tamanho (se houver)
             tamanho: this.tamanhoSelecionado ? {
                 id: this.tamanhoSelecionado.id,
                 nome: this.tamanhoSelecionado.nome,
                 preco: this.tamanhoSelecionado.preco
             } : null,
             tamanho_id: this.tamanhoSelecionado ? this.tamanhoSelecionado.id : null
         };

         // Adicionar ao carrinho
         if (window.CarrinhoMenu) {
             window.CarrinhoMenu.adicionar(produto);
             this.fechar();
             return true;
         } else if (window.CarrinhoSimples) {
             // Fallback para CarrinhoSimples
             window.CarrinhoSimples.adicionarItem(produto);
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
    
    // Clique nos produtos
    $(document).on('click', '.produto-item, .block, .filtr-item', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Subir até o elemento com data-produto-id, ou descer para encontrá-lo
        let elemento = $(this).closest('[data-produto-id]');
        if (!elemento.length) elemento = $(this).find('[data-produto-id]').first();
        if (!elemento.length) return;

        // Verificar se produto está ativo
        if (elemento.attr('data-produto-ativo') === '0') return false;

        // Extrair dados do produto
        let tamanhos = [];
        try {
            const raw = elemento.attr('data-tamanhos') || '[]';
            tamanhos = JSON.parse(raw);
        } catch(err) { tamanhos = []; }

        const dados = {
            id: elemento.attr('data-produto-id'),
            nome: elemento.attr('data-produto-nome'),
            preco: elemento.attr('data-produto-preco'),
            imagem: elemento.attr('data-produto-imagem') || elemento.find('img').first().attr('src') || '',
            categoria: elemento.attr('data-produto-categoria') || 'Produto',
            categoria_id: parseInt(elemento.attr('data-categoria-id')) || null,
            descricao: elemento.attr('data-produto-descricao') || '',
            com_tamanho: elemento.attr('data-com-tamanho') == '1' ? 1 : 0,
            tamanhos: tamanhos
        };

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

