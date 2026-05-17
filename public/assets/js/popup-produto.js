/**
 * Popup "Adicionar ao Pedido" — overlay customizado (sem Bootstrap modal)
 */

window.PopupProduto = {
    produtoAtual: null,
    tamanhoSelecionado: null,

    abrir(dados) {
        this.produtoAtual = dados;
        this.tamanhoSelecionado = null;

        if (window.ProdutoExtras) {
            window.ProdutoExtras.extrasSelecionados = [];
            window.ProdutoExtras.obrigatorioExtras = 0;
            window.ProdutoExtras.maxExtras = 0;
        }

        this._criarOverlay(dados);
        this._configurarTamanhos(dados);
        this.atualizarTotal();
        this.carregarExtras(dados.id);

        document.body.style.overflow = 'hidden';
    },

    _criarOverlay(dados) {
        document.getElementById('popup-produto-overlay')?.remove();

        const comTamanho = dados.com_tamanho == 1 || dados.comTamanho == 1;
        const precoTexto = (comTamanho && dados.tamanhos?.length)
            ? 'Selecione um tamanho'
            : `R$ ${parseFloat(dados.preco).toFixed(2).replace('.', ',')}`;
        const precoBase = comTamanho ? 0 : dados.preco;

        const html = `
        <div id="popup-produto-overlay" class="pp-overlay">
            <div class="pp-container">

                <div class="pp-header">
                    <span class="pp-title"><i class="fas fa-shopping-bag"></i> Adicionar ao pedido</span>
                    <button class="pp-fechar" id="pp-btn-fechar" title="Fechar">&times;</button>
                </div>

                <div class="pp-body">
                    ${dados.imagem ? `<img class="pp-imagem" src="${dados.imagem}" alt="${dados.nome}">` : ''}

                    <div class="pp-info-bloco">
                        <div class="pp-nome">${dados.nome}</div>
                        ${dados.descricao ? `<div class="pp-descricao">${dados.descricao}</div>` : ''}
                        <div class="pp-categoria"><i class="fas fa-tag"></i> ${dados.categoria || ''}</div>
                    </div>

                    <div class="pp-preco-row">
                        <div>
                            <div class="pp-label">Preço unitário</div>
                            <div class="pp-preco" id="modal-produto-preco" data-valor-base="${precoBase}">${precoTexto}</div>
                            <small class="pp-preco-extras" id="modal-produto-preco-extras"></small>
                        </div>
                        <div>
                            <div class="pp-label">Quantidade</div>
                            <div class="pp-qtd-ctrl">
                                <button type="button" id="btn-diminuir">−</button>
                                <input type="number" id="quantidade" value="1" min="1" max="99">
                                <button type="button" id="btn-aumentar">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="pp-total-bloco">
                        <span class="pp-label">Total</span>
                        <span class="pp-total" id="modal-total">—</span>
                        <small class="pp-total-detalhe" id="modal-total-detalhe"></small>
                    </div>

                    <div id="container-tamanhos" style="display:none;" class="pp-secao">
                        <div class="pp-label"><i class="fas fa-ruler"></i> Tamanho <span style="color:#ea5455;">*</span></div>
                        <div id="tamanhos-opcoes" class="pp-tamanhos"></div>
                        <small class="pp-aviso d-none" id="aviso-tamanho"><i class="fas fa-exclamation-circle"></i> Selecione um tamanho.</small>
                    </div>

                    <div id="container-btn-extras" style="display:none;" class="pp-secao">
                        <button type="button" id="btn-selecionar-extras" class="pp-btn-extras">
                            <i class="fas fa-plus-circle"></i>
                            <span id="texto-btn-extras">Selecionar Extras</span>
                            <span id="badge-obrigatorio" style="display:none;background:#ea5455;color:#fff;font-size:.7rem;padding:2px 7px;border-radius:10px;margin-left:6px;">*Obrigatório</span>
                        </button>
                        <div id="extras-selecionados-resumo" style="display:none;" class="pp-extras-resumo">
                            <small><i class="fas fa-check-circle" style="color:#28c76f;"></i> <span id="contador-extras">0 extras</span></small>
                            <small id="valor-extras-resumo" style="color:#888;"></small>
                        </div>
                        <small class="pp-aviso" id="aviso-extra-obrigatorio-modal" style="display:none;"></small>
                    </div>

                    <div class="pp-secao">
                        <div class="pp-label">Observações <span style="color:#555;font-weight:400;">(opcional)</span></div>
                        <textarea id="observacoes" class="pp-obs" rows="2" placeholder="Alguma observação especial?"></textarea>
                    </div>
                </div>

                <div class="pp-footer">
                    <button type="button" id="pp-btn-cancelar" class="pp-btn pp-btn-sec">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" id="btn-adicionar-carrinho" class="pp-btn pp-btn-prim">
                        <i class="fas fa-shopping-bag"></i> Adicionar
                    </button>
                </div>
            </div>
        </div>`;

        document.body.insertAdjacentHTML('beforeend', html);

        // Eventos
        document.getElementById('pp-btn-fechar').addEventListener('click', () => this.fechar());
        document.getElementById('pp-btn-cancelar').addEventListener('click', () => this.fechar());
        document.getElementById('popup-produto-overlay').addEventListener('click', (e) => {
            if (e.target.id === 'popup-produto-overlay') this.fechar();
        });
        document.getElementById('btn-diminuir').addEventListener('click', (e) => {
            e.preventDefault();
            const inp = document.getElementById('quantidade');
            if (parseInt(inp.value) > 1) { inp.value = parseInt(inp.value) - 1; this.atualizarTotal(); }
        });
        document.getElementById('btn-aumentar').addEventListener('click', (e) => {
            e.preventDefault();
            const inp = document.getElementById('quantidade');
            if (parseInt(inp.value) < 99) { inp.value = parseInt(inp.value) + 1; this.atualizarTotal(); }
        });
        document.getElementById('quantidade').addEventListener('input', () => this.atualizarTotal());
        document.getElementById('btn-adicionar-carrinho').addEventListener('click', () => this.adicionarAoCarrinho());
        document.getElementById('btn-selecionar-extras')?.addEventListener('click', () => {
            if (window.ProdutoExtras) window.ProdutoExtras.abrirOverlayExtras();
        });

        // Fechar com ESC
        this._escHandler = (e) => { if (e.key === 'Escape') this.fechar(); };
        document.addEventListener('keydown', this._escHandler);
    },

    _configurarTamanhos(dados) {
        const tamanhos = dados.tamanhos || [];
        const comTamanho = dados.com_tamanho == 1 || dados.comTamanho == 1;

        if (!comTamanho || !tamanhos.length) {
            document.getElementById('container-tamanhos').style.display = 'none';
            return;
        }

        document.getElementById('container-tamanhos').style.display = '';
        const opcoes = document.getElementById('tamanhos-opcoes');
        opcoes.innerHTML = '';

        tamanhos.forEach(t => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'pp-btn-tamanho';
            btn.dataset.preco = t.preco;
            btn.textContent = `${t.nome} — R$ ${parseFloat(t.preco).toFixed(2).replace('.', ',')}`;
            btn.addEventListener('click', () => {
                document.querySelectorAll('.pp-btn-tamanho').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                this.tamanhoSelecionado = { id: t.id || null, nome: t.nome, preco: parseFloat(t.preco) };
                const el = document.getElementById('modal-produto-preco');
                el.textContent = `R$ ${this.tamanhoSelecionado.preco.toFixed(2).replace('.', ',')}`;
                el.dataset.valorBase = this.tamanhoSelecionado.preco;
                this.atualizarTotal();
            });
            opcoes.appendChild(btn);
        });
    },

    async carregarExtras(produtoId) {
        try {
            const r = await fetch(`/api/produto-extras/${produtoId}`);
            const data = await r.json();

            if (data.success && data.extras?.length) {
                if (window.ProdutoExtras) {
                    window.ProdutoExtras.extrasDisponiveis = data.extras;
                    window.ProdutoExtras.obrigatorioExtras = parseInt(data.obrigatorio_extras) || 0;
                    window.ProdutoExtras.maxExtras = parseInt(data.max_extras) || 0;
                }

                const obrig = parseInt(data.obrigatorio_extras) || 0;
                const max   = parseInt(data.max_extras) || 0;
                const badge = document.getElementById('badge-obrigatorio');
                const texto = document.getElementById('texto-btn-extras');

                if (obrig > 0) {
                    badge.style.display = '';
                    texto.textContent = `Selecionar Extras (${obrig} obrigatório)`;
                } else if (max > 0) {
                    badge.style.display = 'none';
                    texto.textContent = `Selecionar Extras (Máx. ${max})`;
                } else {
                    badge.style.display = 'none';
                    texto.textContent = 'Selecionar Extras (Opcional)';
                }

                document.getElementById('container-btn-extras').style.display = '';
            } else {
                document.getElementById('container-btn-extras').style.display = 'none';
            }
        } catch (e) {
            document.getElementById('container-btn-extras').style.display = 'none';
        }
    },

    atualizarTotal() {
        const el = document.getElementById('modal-produto-preco');
        if (!el) return;
        const precoBase = parseFloat(el.dataset.valorBase) || 0;
        const qtd = parseInt(document.getElementById('quantidade')?.value) || 1;

        const comTamanho = this.produtoAtual && (this.produtoAtual.com_tamanho == 1 || this.produtoAtual.comTamanho == 1);
        if (comTamanho && !this.tamanhoSelecionado) {
            document.getElementById('modal-total').textContent = '—';
            document.getElementById('modal-total-detalhe').textContent = 'Selecione um tamanho';
            return;
        }

        let totalExtras = 0;
        if (window.ProdutoExtras?.extrasSelecionados) {
            totalExtras = window.ProdutoExtras.getTotalExtras();
        }

        const total = (precoBase + totalExtras) * qtd;
        document.getElementById('modal-total').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;

        const detalhe = document.getElementById('modal-total-detalhe');
        const extrasEl = document.getElementById('modal-produto-preco-extras');
        if (totalExtras > 0) {
            detalhe.textContent = `Base: R$ ${(precoBase * qtd).toFixed(2).replace('.', ',')} | Extras: R$ ${(totalExtras * qtd).toFixed(2).replace('.', ',')}`;
            if (extrasEl) extrasEl.textContent = `+ R$ ${totalExtras.toFixed(2).replace('.', ',')} em extras`;
        } else {
            detalhe.textContent = '';
            if (extrasEl) extrasEl.textContent = '';
        }
    },

    adicionarAoCarrinho() {
        if (!this.produtoAtual) return;

        if (window.ProdutoExtras && !window.ProdutoExtras.validarSelecao()) return;

        const comTamanho = this.produtoAtual.com_tamanho == 1 || this.produtoAtual.comTamanho == 1;
        if (comTamanho && this.produtoAtual.tamanhos?.length && !this.tamanhoSelecionado) {
            const av = document.getElementById('aviso-tamanho');
            av.classList.remove('d-none');
            av.textContent = 'Selecione um tamanho para continuar.';
            return;
        }

        const preco = this.tamanhoSelecionado ? this.tamanhoSelecionado.preco : parseFloat(this.produtoAtual.preco);

        const produto = {
            id: this.produtoAtual.id,
            nome: this.produtoAtual.nome,
            preco,
            quantidade: parseInt(document.getElementById('quantidade').value) || 1,
            observacoes: document.getElementById('observacoes').value || '',
            extras: window.ProdutoExtras ? window.ProdutoExtras.getExtrasSelecionados() : [],
            categoria_id: this.produtoAtual.categoria_id || null,
            tamanho: this.tamanhoSelecionado || null,
            tamanho_id: this.tamanhoSelecionado?.id || null
        };

        if (window.CarrinhoMenu) {
            window.CarrinhoMenu.adicionar(produto);
        } else if (window.CarrinhoSimples) {
            window.CarrinhoSimples.adicionarItem(produto);
        }

        this.fechar();
    },

    fechar() {
        document.getElementById('popup-produto-overlay')?.remove();
        document.body.style.overflow = '';
        document.removeEventListener('keydown', this._escHandler);
        this.produtoAtual = null;
        this.tamanhoSelecionado = null;
        if (window.ProdutoExtras) window.ProdutoExtras.limparExtras();
    }
};

// Atualizar total quando extras mudarem (o clique é gerenciado pelo sistema-produto.js)
$(document).ready(function () {
    $(document).on('extrasAtualizados', function () {
        PopupProduto.atualizarTotal();
    });
});
