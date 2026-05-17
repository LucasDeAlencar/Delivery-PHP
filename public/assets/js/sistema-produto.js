/**
 * Sistema Unificado de Produto e Carrinho
 * Versão otimizada - Sem scroll ao abrir modal
 */

window.SistemaProduto = {
    produto: null,
    extras: [],
    extrasDisponiveis: [],
    carrinho: [],
    tamanhoSelecionado: null,

    init() {
        this.carregarCarrinho();
        this.bindEventos();
    },

    abrirPopup(elemento) {
        const dados = this.extrairDados(elemento);
        if (!dados.id) return;

        // Delegar para PopupProduto (overlay customizado)
        if (window.PopupProduto) {
            window.PopupProduto.abrir(dados);
            return false;
        }

        // Fallback: Bootstrap modal (legado)
        this.produto = dados;
        this.extras = [];
        this.tamanhoSelecionado = null;

        if (window.ProdutoExtras) {
            window.ProdutoExtras.extrasSelecionados = [];
            window.ProdutoExtras.extrasDisponiveis = [];
            window.ProdutoExtras.produtoAtual = null;
            window.ProdutoExtras.obrigatorioExtras = 0;
            window.ProdutoExtras.maxExtras = 0;
        }

        this.preencherModal(dados);
        this.carregarExtras(dados.id);

        $('#modalCompra').modal({ show: true, backdrop: true, keyboard: true });

        return false;
    },

    extrairDados(elemento) {
        const $el = $(elemento).closest('[data-produto-id]').length
            ? $(elemento).closest('[data-produto-id]')
            : $(elemento).find('[data-produto-id]').first();

        let tamanhos = [];
        try { tamanhos = JSON.parse($el.attr('data-tamanhos') || '[]'); } catch(e) {}

        return {
            id: $el.attr('data-produto-id'),
            nome: $el.attr('data-produto-nome'),
            preco: parseFloat($el.attr('data-produto-preco')),
            imagem: $el.attr('data-produto-imagem'),
            categoria: $el.attr('data-produto-categoria'),
            categoria_id: parseInt($el.attr('data-categoria-id')) || null,
            descricao: $el.attr('data-produto-descricao') || '',
            com_tamanho: $el.attr('data-com-tamanho') == '1' ? 1 : 0,
            tamanhos: tamanhos
        };
    },

    preencherModal(dados) {
        $('#modal-produto-nome').text(dados.nome);

        const comTamanho = dados.com_tamanho == 1 && dados.tamanhos && dados.tamanhos.length > 0;
        if (comTamanho) {
            // Esconde a linha de preço unitário e mostra badge compacto
            $('#modal-produto-preco').closest('.col-6').hide();
            $('#modal-produto-preco').text('').attr('data-valor-base', 0);
        } else {
            $('#modal-produto-preco').closest('.col-6').show();
            $('#modal-produto-preco')
                .text(`R$ ${dados.preco.toFixed(2).replace('.', ',')}`)
                .attr('data-valor-base', dados.preco);
        }

        $('#modal-produto-categoria').text(dados.categoria);
        $('#modal-produto-descricao').text(dados.descricao);
        $('#modal-produto-imagem').attr('src', dados.imagem);

        // Bloco mobile: imagem pequena + nome inline
        const $mob = $('#modal-produto-imagem-mobile');
        if ($mob.length) {
            $('#modal-produto-imagem-mobile-img').attr('src', dados.imagem || '');
            $('#modal-produto-nome-mobile').text(dados.nome);
            $('#modal-produto-cat-mobile').text(dados.categoria);
        }

        $('#quantidade').val(1);
        $('#observacoes').val('');
        $('#extras-selecionados-resumo').hide();
        $('#modal-produto-preco-extras').text('Sem extras');

        // Configurar seletor de tamanhos
        this.tamanhoSelecionado = null;
        if (comTamanho) {
            const $opcoes = $('#tamanhos-opcoes').empty();
            dados.tamanhos.forEach(t => {
                const preco = parseFloat(t.preco).toFixed(2).replace('.', ',');
                const $btn = $(`<button type="button" class="btn btn-sm btn-tamanho" data-nome="${t.nome}" data-preco="${t.preco}">
                    ${t.nome} — R$ ${preco}
                </button>`);
                $btn.on('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    $('.btn-tamanho').removeClass('active');
                    $(e.currentTarget).addClass('active');
                    this.tamanhoSelecionado = { id: t.id || null, nome: t.nome, preco: parseFloat(t.preco) };
                    $('#modal-produto-preco').text(`R$ ${this.tamanhoSelecionado.preco.toFixed(2).replace('.', ',')}`).attr('data-valor-base', this.tamanhoSelecionado.preco);
                    $('#aviso-tamanho').addClass('d-none');
                    this.atualizarTotal();
                });
                $opcoes.append($btn);
            });
            $('#container-tamanhos').show();
            $('#aviso-tamanho').addClass('d-none');
        } else {
            $('#container-tamanhos').hide();
        }

        this.atualizarTotal();
    },

    async carregarExtras(produtoId) {
        // Guardar o id do produto que disparou esta chamada para evitar race condition
        const _produtoId = produtoId;
        try {
            const response = await fetch(`/api/produto-extras/${produtoId}`);
            const data = await response.json();

            // Se o produto atual mudou enquanto aguardávamos, ignorar resultado
            if (!this.produto || String(this.produto.id) !== String(_produtoId)) return;

            const temExtras = data.success && data.extras?.length > 0;

            if (temExtras) {
                this.extrasDisponiveis = data.extras;
                $('#container-btn-extras').show();

                if (window.ProdutoExtras) {
                    window.ProdutoExtras.extrasDisponiveis = data.extras;
                    window.ProdutoExtras.extrasSelecionados = []; // garantir limpeza
                    window.ProdutoExtras.obrigatorioExtras = parseInt(data.obrigatorio_extras) || 0;
                    window.ProdutoExtras.maxExtras = parseInt(data.max_extras) || 0;
                }

                let textoBtn = 'Selecionar Extras';
                if (data.obrigatorio_extras > 0) textoBtn = `Selecionar Extras (${data.obrigatorio_extras} obrigatório)`;
                else if (data.max_extras > 0) textoBtn = `Selecionar Extras (Máx. ${data.max_extras})`;
                else textoBtn = 'Selecionar Extras (Opcional)';
                $('#texto-btn-extras').text(textoBtn);
            } else {
                if (window.ProdutoExtras && data.obrigatorio_extras !== undefined) {
                    window.ProdutoExtras.obrigatorioExtras = parseInt(data.obrigatorio_extras) || 0;
                    window.ProdutoExtras.maxExtras = parseInt(data.max_extras) || 0;
                    window.ProdutoExtras.extrasSelecionados = [];
                    window.ProdutoExtras.extrasDisponiveis = [];
                }
                this.extrasDisponiveis = [];
                $('#container-btn-extras').hide();
            }
        } catch (error) {
            console.error('Erro ao carregar extras:', error);
            this.extrasDisponiveis = [];
            $('#container-btn-extras').hide();
        }
    },

    atualizarTotal() {
        const comTamanho = this.produto?.com_tamanho == 1 && this.produto?.tamanhos?.length > 0;
        if (comTamanho && !this.tamanhoSelecionado) {
            $('#modal-total').text('—');
            return;
        }

        const precoBase = parseFloat($('#modal-produto-preco').attr('data-valor-base')) || 0;
        const quantidade = parseInt($('#quantidade').val()) || 1;
        
        let extrasSelecionados = [];
        if (window.ProdutoExtras && typeof window.ProdutoExtras.getExtrasSelecionados === 'function') {
            extrasSelecionados = window.ProdutoExtras.getExtrasSelecionados();
        }
        
        const totalExtras = extrasSelecionados.reduce((t, e) => t + (e.preco * e.quantidade), 0);
        const total = (precoBase + totalExtras) * quantidade;

        $('#modal-total').text(`R$ ${total.toFixed(2).replace('.', ',')}`);
    },

    adicionarAoCarrinho() {
        if (!this.produto) return;

        // Recarregar carrinho do localStorage antes de adicionar
        this.carregarCarrinho();

        // Validar tamanho obrigatório
        const comTamanho = this.produto.com_tamanho == 1 && this.produto.tamanhos?.length > 0;
        if (comTamanho && !this.tamanhoSelecionado) {
            $('#aviso-tamanho').removeClass('d-none');
            $('#container-tamanhos').get(0)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Piscar os botões para chamar atenção
            $('#tamanhos-opcoes .btn-tamanho').css('border-color', '#dc3545').delay(400).queue(function(next) {
                $(this).css('border-color', '#0055ff'); next();
                $(this).css('margin_left', '20px'); next();
            });
            return;
        }

        // Validar extras obrigatórios
        if (window.ProdutoExtras && typeof window.ProdutoExtras.validarSelecao === 'function') {
            if (!window.ProdutoExtras.validarSelecao()) {
                return;
            }
        }

        let extrasSelecionados = [];
        if (window.ProdutoExtras && typeof window.ProdutoExtras.getExtrasSelecionados === 'function') {
            extrasSelecionados = window.ProdutoExtras.getExtrasSelecionados();
        }

        // Preço final: tamanho tem prioridade sobre preço base
        const precoFinal = this.tamanhoSelecionado ? this.tamanhoSelecionado.preco : this.produto.preco;

        const item = {
            id: this.produto.id,
            nome: this.produto.nome,
            preco: precoFinal,
            quantidade: parseInt($('#quantidade').val()) || 1,
            observacoes: $('#observacoes').val() || '',
            extras: extrasSelecionados,
            tamanho: this.tamanhoSelecionado ? { id: this.tamanhoSelecionado.id, nome: this.tamanhoSelecionado.nome, preco: this.tamanhoSelecionado.preco } : null,
            tamanho_id: this.tamanhoSelecionado?.id || null,
            categoria_id: this.produto.categoria_id || null,
            total: this.calcularTotalItem(extrasSelecionados)
        };

        const chave = this.gerarChave(item);
        const existente = this.carrinho.find(c => this.gerarChave(c) === chave);

        if (existente) {
            existente.quantidade += item.quantidade;
            existente.total = this.calcularTotalItem(extrasSelecionados, existente.quantidade);
        } else {
            this.carrinho.push(item);
        }

        this.salvarCarrinho();
        this.fecharModal();
        this.mostrarModalMobile('Sucesso!', 'Produto adicionado ao carrinho!', 'success');
    },

    calcularTotalItem(extras = null, quantidade = null) {
        const qtd = quantidade !== null ? quantidade : (parseInt($('#quantidade').val()) || 1);
        const extrasUsar = extras !== null ? extras : (window.ProdutoExtras && typeof window.ProdutoExtras.getExtrasSelecionados === 'function' ? window.ProdutoExtras.getExtrasSelecionados() : []);
        const precoBase = this.tamanhoSelecionado ? this.tamanhoSelecionado.preco : (this.produto?.preco || 0);
        const totalExtras = extrasUsar.reduce((t, e) => t + (e.preco * e.quantidade), 0);
        return (precoBase + totalExtras) * qtd;
    },

    gerarChave(item) {
        const extrasKey = item.extras.map(e => `${e.id}:${e.quantidade}`).sort().join(',');
        const tamanhoKey = item.tamanho ? item.tamanho.nome : '';
        return `${item.id}_${tamanhoKey}_${item.observacoes}_${extrasKey}`;
    },

    fecharModal() {
        $('#modalCompra').modal('hide');
        this.produto = null;
        this.extras = [];
        this.tamanhoSelecionado = null;
        if (window.ProdutoExtras) {
            window.ProdutoExtras.extrasSelecionados = [];
            window.ProdutoExtras.extrasDisponiveis = [];
            window.ProdutoExtras.produtoAtual = null;
            window.ProdutoExtras.obrigatorioExtras = 0;
            window.ProdutoExtras.maxExtras = 0;
        }
    },

    salvarCarrinho() {
        if (this.carrinho.length === 0) {
            localStorage.removeItem('carrinho');
        } else {
            localStorage.setItem('carrinho', JSON.stringify(this.carrinho));
        }
    },

    carregarCarrinho() {
        const dados = localStorage.getItem('carrinho');
        this.carrinho = dados ? JSON.parse(dados) : [];
        
        // Garantir que o array está limpo se não houver dados
        if (!dados || dados === '[]' || dados === 'null') {
            this.carrinho = [];
            localStorage.removeItem('carrinho');
        }
    },

    bindEventos() {
        // Salvar scroll ANTES do Bootstrap travar o body
        $('#modalCompra').on('show.bs.modal', () => {
            document.body.dataset.scrollY = window.scrollY || window.pageYOffset || 0;
        });

        // Após abrir: desfazer tudo que o Bootstrap fez no body
        $('#modalCompra').on('shown.bs.modal', () => {
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            document.documentElement.style.overflow = 'hidden'; // travar no <html> sem mover body
        });

        // Ao fechar: restaurar e rolar de volta
        $('#modalCompra').on('hide.bs.modal', () => {
            document.documentElement.style.overflow = '';
        });

        $('#modalCompra').on('hidden.bs.modal', () => {
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            window.scrollTo(0, parseInt(document.body.dataset.scrollY || '0', 10));
        });

        $(document).on('extrasAtualizados', () => {
            this.atualizarTotal();
        });

        $(document).on('click', '[data-produto-id]:not(#modalCompra)', (e) => {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            // Bloquear se estabelecimento fechado
            if (typeof window.estaAberto !== 'undefined' && !window.estaAberto) {
                return false;
            }

            // Modo 3: exige login antes de abrir o modal do produto
            if (window.modoCadastro === 3 && window.clienteLogado && !window.clienteLogado.logado) {
                const overlay = document.getElementById('overlay-login-modo3');
                if (overlay) {
                    overlay.classList.add('ativo');
                    const input = document.getElementById('popup-celular');
                    if (input) setTimeout(() => input.focus(), 100);
                    return false;
                }
                // overlay não encontrado no DOM — prossegue normalmente
            }

            const elemento = e.currentTarget;
            const that = this;
            
            setTimeout(function() {
                that.abrirPopup(elemento);
            }, 0);
            
            return false;
        });

        $(document).on('extrasAtualizados', () => {
            this.atualizarTotal();
        });
    },

    mostrarModalMobile(titulo, mensagem, tipo = 'info') {
        const cores = {
            success: '#28a745',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8'
        };

        const isMobile = window.innerWidth <= 480;
        
        const html = `
            <div id="modal-mobile" class="modal-mobile-container" style="
                position: fixed; 
                top: 0; 
                left: 0; 
                width: 100%; 
                height: 100%; 
                background: rgba(0,0,0,0.8); 
                z-index: 10000; 
                display: flex; 
                ${isMobile ? 'align-items: flex-end;' : 'align-items: center;'}
                justify-content: center;
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
            ">
                <div class="modal-mobile-content" style="
                    background: #1a1a1a; 
                    width: ${isMobile ? '100%' : '90%'}; 
                    max-width: ${isMobile ? 'none' : '350px'}; 
                    border-radius: ${isMobile ? '20px 20px 0 0' : '15px'}; 
                    padding: ${isMobile ? '25px 20px 30px' : '20px'}; 
                    color: white; 
                    text-align: center; 
                    border-top: 4px solid ${cores[tipo]};
                    ${isMobile ? 'animation: slideUp 0.3s ease-out;' : ''}
                ">
                    <h4 style="color: ${cores[tipo]}; margin-bottom: 15px; font-size: ${isMobile ? '1.3rem' : '1.1rem'};">${titulo}</h4>
                    <p style="margin-bottom: 20px; font-size: ${isMobile ? '1rem' : '0.95rem'}; line-height: 1.5;">${mensagem}</p>
                    <button onclick="$('#modal-mobile').remove()" style="
                        background: ${cores[tipo]}; 
                        color: white; 
                        border: none; 
                        padding: ${isMobile ? '14px 30px' : '12px 25px'}; 
                        border-radius: 8px; 
                        cursor: pointer;
                        font-size: ${isMobile ? '1rem' : '0.95rem'};
                        font-weight: 600;
                        width: ${isMobile ? '100%' : 'auto'};
                        max-width: ${isMobile ? '200px' : 'none'};
                    ">
                        OK
                    </button>
                </div>
            </div>
        `;

        $('body').append(html);

        setTimeout(() => {
            $('#modal-mobile').fadeOut(300, function () {
                $(this).remove();
            });
        }, 3000);
    }
};

$(document).ready(() => {
    SistemaProduto.init();
});
