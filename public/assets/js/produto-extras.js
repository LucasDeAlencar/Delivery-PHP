/**
 * Sistema de Seleção de Extras para Produtos
 * Gerencia a seleção de extras no modal de compra
 */

const ProdutoExtras = {
    produtoAtual: null,
    extrasDisponiveis: [],
    extrasSelecionados: [],
    obrigatorioExtras: 0,
    maxExtras: 0,
    onChangeCallback: null,

    /**
     * Inicializa o sistema de extras
     */
    init() {
        this.bindEvents();
    },

    /**
     * Vincula eventos aos elementos
     */
    bindEvents() {
        // Limpar extras ao fechar overlay de produto
        $(document).on('popupProdutoFechado', () => {
            this.limparExtras();
        });
    },

    /**
     * Carrega os extras de um produto
     */
    async carregarExtras(produtoId) {

        this.produtoAtual = produtoId;
        this.extrasSelecionados = [];
        this.emitirAtualizacao();

        try {
            const response = await fetch(`${window.location.origin}/api/produto-extras/${produtoId}`);
            const data = await response.json();

            if (data.success) {
                this.extrasDisponiveis = data.extras;
                this.obrigatorioExtras = parseInt(data.obrigatorio_extras) || 0;
                this.maxExtras = parseInt(data.max_extras) || 0;
                
                    obrigatorio: this.obrigatorioExtras,
                    max: this.maxExtras
                });

                // Atualizar texto do botão para mostrar o limite se existir
                let textoBotao = `Selecionar Extras`;
                if (this.obrigatorioExtras > 0) {
                    textoBotao += ` (${this.obrigatorioExtras} obrigatório${this.obrigatorioExtras > 1 ? 's' : ''})`;
                } else if (this.maxExtras > 0) {
                    textoBotao += ` (Máx. ${this.maxExtras})`;
                }
                $('#texto-btn-extras').text(textoBotao);

                this.emitirAtualizacao();


                // Mostrar/ocultar botão de extras
                if (data.total_extras > 0) {
                    $('#container-btn-extras').show();

                    // Atualizar texto do botão
                    if (this.obrigatorioExtras > 0) {
                        $('#badge-obrigatorio').show();
                        $('#texto-btn-extras').text(`Selecionar Extras (${this.obrigatorioExtras} obrigatório${this.obrigatorioExtras > 1 ? 's' : ''})`);
                    } else {
                        $('#badge-obrigatorio').hide();
                        $('#texto-btn-extras').text('Selecionar Extras (Opcional)');
                    }
                } else {
                    $('#container-btn-extras').hide();
                }

                return true;
            } else {
                console.error('❌ Erro ao carregar extras:', data.message);
                $('#container-btn-extras').hide();
                return false;
            }
        } catch (error) {
            console.error('❌ Erro na requisição:', error);
            $('#container-btn-extras').hide();
            return false;
        }
    },

    /**
     * Abre o overlay de seleção de extras
     */
    abrirOverlayExtras() {
        document.getElementById('extras-overlay')?.remove();

        const html = `
        <div id="extras-overlay" class="pp-overlay" style="z-index:10001;">
            <div class="pp-container">
                <div class="pp-header">
                    <span class="pp-title"><i class="fas fa-plus-circle"></i> Selecionar Extras</span>
                    <button class="pp-fechar" id="extras-btn-fechar">&times;</button>
                </div>
                <div class="pp-body" style="flex:1;overflow-y:auto;">
                    <div id="extras-loading" class="text-center" style="padding:40px 0;">
                        <div class="spinner-border text-warning" role="status"></div>
                        <p style="color:#aaa;margin-top:12px;">Carregando extras...</p>
                    </div>
                    <div id="extras-lista" style="display:none;">
                        <div id="aviso-obrigatorio" style="display:none;background:rgba(0,85,255,.1);border:1px solid #0055ff;color:#6699ff;padding:10px 14px;border-radius:8px;margin-bottom:12px;font-size:.85rem;">
                            <i class="fas fa-exclamation-triangle"></i> <span id="texto-aviso-obrigatorio"></span>
                        </div>
                        <div id="extras-container"></div>
                        <div id="extras-vazio" style="display:none;text-align:center;padding:40px 0;color:#555;">
                            <i class="fas fa-inbox" style="font-size:2.5rem;opacity:.3;"></i>
                            <p style="margin-top:10px;">Nenhum extra disponível.</p>
                        </div>
                    </div>
                </div>
                <div class="pp-footer">
                    <span style="color:#aaa;font-size:.85rem;">Selecionados: <strong id="contador-extras-modal" style="color:#f8b531;">0</strong></span>
                    <div style="display:flex;gap:8px;">
                        <button type="button" id="extras-btn-cancelar" class="pp-btn pp-btn-sec">Cancelar</button>
                        <button type="button" id="btn-confirmar-extras" class="pp-btn pp-btn-prim">
                            <i class="fas fa-check"></i> Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>`;

        document.body.insertAdjacentHTML('beforeend', html);

        document.getElementById('extras-btn-fechar').addEventListener('click', () => this._fecharOverlayExtras());
        document.getElementById('extras-btn-cancelar').addEventListener('click', () => this._fecharOverlayExtras());
        document.getElementById('btn-confirmar-extras').addEventListener('click', () => this.confirmarExtras());
        document.getElementById('extras-overlay').addEventListener('click', (e) => {
            if (e.target.id === 'extras-overlay') this._fecharOverlayExtras();
        });

        setTimeout(() => this.renderizarExtras(), 200);
    },

    _fecharOverlayExtras() {
        document.getElementById('extras-overlay')?.remove();
    },

    /**
     * @deprecated use abrirOverlayExtras
     */
    abrirModalExtras() {
        this.abrirOverlayExtras();
    },

    /**
     * Renderiza a lista de extras
     */
    renderizarExtras() {
        const container = $('#extras-container');
        container.empty();

        if (this.extrasDisponiveis.length === 0) {
            $('#extras-loading').hide();
            $('#extras-vazio').show();
            $('#extras-lista').show();
            return;
        }

        // Mostrar aviso se houver extras obrigatórios ou limite máximo
        let avisoTexto = '';
        if (this.obrigatorioExtras > 0) {
            avisoTexto = `Você deve selecionar pelo menos ${this.obrigatorioExtras} extra${this.obrigatorioExtras > 1 ? 's' : ''} diferente${this.obrigatorioExtras > 1 ? 's' : ''}.`;
        }
        if (this.maxExtras > 0) {
            if (avisoTexto) avisoTexto += ' ';
            avisoTexto += `Máximo permitido: ${this.maxExtras} extras.`;
        }
        
        if (avisoTexto) {
            $('#texto-aviso-obrigatorio').text(avisoTexto);
            $('#aviso-obrigatorio').show();
        } else {
            $('#aviso-obrigatorio').hide();
        }

        // Renderizar cada extra
        this.extrasDisponiveis.forEach(extra => {
            const quantidadeSelecionada = this.getQuantidadeExtra(extra.id);
            const isSelected = quantidadeSelecionada > 0;

            let extraHtml = '';

            // Converter multitude para boolean se necessário
            const isMultitude = extra.multitude === 1 || extra.multitude === true || extra.multitude === '1';

            if (isMultitude) {
                // Extra com multitude - caixa numérica
                extraHtml = `
                    <div class="extra-item ${isSelected ? 'selected' : ''}" data-extra-id="${extra.id}">
                        <div class="extra-info">
                            <label class="extra-nome">${extra.nome}</label>
                            ${extra.descricao ? `<p class="extra-descricao">${extra.descricao}</p>` : ''}
                        </div>
                        <div class="extra-preco">
                            ${extra.preco > 0 ? `<span class="preco-valor">+ ${extra.preco_formatado}</span>` : '<span class="preco-gratis">Gratuito</span>'}
                        </div>
                        <div class="extra-controles">
                            <button type="button" class="btn-qty btn-menos" onclick="ProdutoExtras.diminuirQtd(${extra.id})">-</button>
                            <span class="qty-valor" id="qty-${extra.id}">${quantidadeSelecionada}</span>
                            <button type="button" class="btn-qty btn-mais" onclick="ProdutoExtras.aumentarQtd(${extra.id})">+</button>
                        </div>
                    </div>
                `;
            } else {
                // Extra normal - checkbox simples
                extraHtml = `
                    <div class="extra-item ${isSelected ? 'selected' : ''}" data-extra-id="${extra.id}">
                        <div class="extra-checkbox">
                            <input type="checkbox" 
                                   id="extra-${extra.id}" 
                                   ${isSelected ? 'checked' : ''}
                                   onchange="ProdutoExtras.toggleExtra(${extra.id})">
                        </div>
                        <div class="extra-info">
                            <label for="extra-${extra.id}" class="extra-nome">${extra.nome}</label>
                            ${extra.descricao ? `<p class="extra-descricao">${extra.descricao}</p>` : ''}
                        </div>
                        <div class="extra-preco">
                            ${extra.preco > 0 ? `<span class="preco-valor">+ ${extra.preco_formatado}</span>` : '<span class="preco-gratis">Gratuito</span>'}
                        </div>
                    </div>
                `;
            }

            container.append(extraHtml);
        });

        // Atualizar contador
        this.atualizarContador();

        // Ocultar loading e mostrar lista
        $('#extras-loading').hide();
        $('#extras-lista').show();
    },

    /**
     * Alterna a seleção de um extra
     */
    toggleExtra(extraId) {
        const extra = this.extrasDisponiveis.find(e => e.id === extraId);
        if (!extra) return;

        const index = this.extrasSelecionados.findIndex(e => e.id === extraId);
        const isChecked = $(`#extra-${extraId}`).is(':checked');

        if (isChecked) {
            // Verificar limite máximo antes de adicionar
            const isMultitude = extra.multitude === 1 || extra.multitude === true || extra.multitude === '1';
            const quantidadeAtual = isMultitude ? 1 : 1;
            if (this.maxExtras > 0 && (this.getTotalItensSelecionados() + quantidadeAtual) > this.maxExtras) {
                alert(`Você pode selecionar no máximo ${this.maxExtras} extras.`);
                $(`#extra-${extraId}`).prop('checked', false);
                return;
            }
            
            // Adicionar extra
            if (index === -1) {
                this.extrasSelecionados.push({
                    id: extra.id,
                    nome: extra.nome,
                    preco: parseFloat(extra.preco) || 0,
                    quantidade: 1,
                    multitude: extra.multitude
                });
            }
            $(`.extra-item[data-extra-id="${extraId}"]`).addClass('selected');
        } else {
            // Remover extra
            if (index > -1) {
                this.extrasSelecionados.splice(index, 1);
            }
            $(`.extra-item[data-extra-id="${extraId}"]`).removeClass('selected');
        }

        this.atualizarContador();
        this.emitirAtualizacao();
    },

    /**
     * Aumentar quantidade de um extra
     */
    aumentarQtd(extraId) {
        const extra = this.extrasDisponiveis.find(e => e.id === extraId);
        if (!extra) return;

        // Verificar limite máximo antes de adicionar (soma 1 para o que será adicionado)
        if (this.maxExtras > 0 && (this.getTotalItensSelecionados() + 1) > this.maxExtras) {
            alert(`Você pode selecionar no máximo ${this.maxExtras} extras.`);
            return;
        }

        let selecionado = this.extrasSelecionados.find(e => e.id === extraId);
        
        if (selecionado) {
            if (selecionado.quantidade < 99) {
                selecionado.quantidade++;
            }
        } else {
            this.extrasSelecionados.push({
                id: extra.id,
                nome: extra.nome,
                preco: parseFloat(extra.preco) || 0,
                quantidade: 1,
                multitude: extra.multitude
            });
            $(`.extra-item[data-extra-id="${extraId}"]`).addClass('selected');
        }

        $(`#qty-${extraId}`).text(this.getQuantidadeExtra(extraId));
        this.atualizarContador();
        this.emitirAtualizacao();
    },

    /**
     * Diminuir quantidade de um extra
     */
    diminuirQtd(extraId) {
        const index = this.extrasSelecionados.findIndex(e => e.id === extraId);
        
        if (index > -1) {
            if (this.extrasSelecionados[index].quantidade > 1) {
                this.extrasSelecionados[index].quantidade--;
            } else {
                this.extrasSelecionados.splice(index, 1);
                $(`.extra-item[data-extra-id="${extraId}"]`).removeClass('selected');
            }
        }

        $(`#qty-${extraId}`).text(this.getQuantidadeExtra(extraId));
        this.atualizarContador();
        this.emitirAtualizacao();
    },

    /**
     * Retorna a quantidade selecionada de um extra
     */
    getQuantidadeExtra(extraId) {
        const extra = this.extrasSelecionados.find(e => e.id === extraId);
        return extra ? extra.quantidade : 0;
    },

    /**
     * Atualiza o contador de extras selecionados
     */
    atualizarContador() {
        const total = this.extrasSelecionados.length;
        $('#contador-extras-modal').text(total);
        $('#contador-extras').text(`${total} extra${total !== 1 ? 's' : ''} selecionado${total !== 1 ? 's' : ''}`);

        // Mostrar limite se existir
        if (this.maxExtras > 0) {
            $('#contador-extras-modal').text(`${total}/${this.maxExtras}`);
        }

        // Habilitar/desabilitar botão de confirmar
        if (this.obrigatorioExtras > 0 && total < this.obrigatorioExtras) {
            $('#btn-confirmar-extras').prop('disabled', true).css('opacity', '0.5');
        } else {
            $('#btn-confirmar-extras').prop('disabled', false).css('opacity', '1');
        }
    },

    /**
     * Confirma a seleção de extras
     */
    confirmarExtras() {
        // Validar quantidade mínima
        const totalSelecionados = this.extrasSelecionados.length;
        if (this.obrigatorioExtras > 0 && totalSelecionados < this.obrigatorioExtras) {
            alert(`Você deve selecionar pelo menos ${this.obrigatorioExtras} extra${this.obrigatorioExtras > 1 ? 's' : ''}.`);
            return;
        }

        // Validar quantidade máxima
        const totalItens = this.getTotalItensSelecionados();
        if (this.maxExtras > 0 && totalItens > this.maxExtras) {
            alert(`Você pode selecionar no máximo ${this.maxExtras} extras.`);
            return;
        }


        // Mostrar resumo
        if (this.extrasSelecionados.length > 0) {
            const resumo = document.getElementById('extras-selecionados-resumo');
            if (resumo) resumo.style.display = '';
            const contador = document.getElementById('contador-extras');
            if (contador) contador.textContent = `${this.extrasSelecionados.length} extra${this.extrasSelecionados.length !== 1 ? 's' : ''} selecionado${this.extrasSelecionados.length !== 1 ? 's' : ''}`;
            const valorResumo = document.getElementById('valor-extras-resumo');
            if (valorResumo) {
                const total = this.getTotalExtras();
                valorResumo.textContent = total > 0 ? `+ R$ ${total.toFixed(2).replace('.', ',')}` : '';
            }
        } else {
            const resumo = document.getElementById('extras-selecionados-resumo');
            if (resumo) resumo.style.display = 'none';
        }

        // Fechar overlay
        this._fecharOverlayExtras();
    },

    /**
     * Retorna os extras selecionados
     */
    getExtrasSelecionados() {
        return this.extrasSelecionados;
    },

    /**
     * Valida se a seleção de extras está completa
     */
    validarSelecao() {
        const totalItens = this.getTotalItensSelecionados();
        
        if (this.obrigatorioExtras > 0 && totalItens < this.obrigatorioExtras) {
            alert(`⚠️ Este produto requer a seleção de pelo menos ${this.obrigatorioExtras} extra${this.obrigatorioExtras > 1 ? 's' : ''}.`);
            return false;
        }
        if (this.maxExtras > 0 && totalItens > this.maxExtras) {
            alert(`⚠️ Este produto permite no máximo ${this.maxExtras} extras.`);
            return false;
        }
        return true;
    },

    /**
     * Limpa a seleção de extras
     */
    limparExtras() {
        this.produtoAtual = null;
        this.extrasDisponiveis = [];
        this.extrasSelecionados = [];
        this.obrigatorioExtras = 0;
        this.maxExtras = 0;
        $('#container-btn-extras').hide();
        $('#extras-selecionados-resumo').hide();
        this.emitirAtualizacao();
    },

    getTotalExtras() {
        if (this.extrasSelecionados.length === 0) {
            return 0;
        }

        return this.extrasSelecionados.reduce((total, extra) => {
            const isMultitude = extra.multitude === 1 || extra.multitude === true || extra.multitude === '1';
            const quantidade = isMultitude ? (extra.quantidade || 0) : 1;
            const preco = parseFloat(extra.preco || 0);
            return total + (preco * quantidade);
        }, 0);
    },

    getTotalItensSelecionados() {
        if (this.extrasSelecionados.length === 0) {
            return 0;
        }

        return this.extrasSelecionados.reduce((total, extra) => {
            const isMultitude = extra.multitude === 1 || extra.multitude === true || extra.multitude === '1';
            const quantidade = isMultitude ? (extra.quantidade || 0) : 1;
            return total + quantidade;
        }, 0);
    },

    setChangeListener(callback) {
        this.onChangeCallback = callback;
        this.emitirAtualizacao();
    },

    emitirAtualizacao() {
        if (typeof this.onChangeCallback === 'function') {
            this.onChangeCallback({
                extrasSelecionados: this.getExtrasSelecionados(),
                totalExtrasValor: this.getTotalExtras(),
                totalExtras: this.getTotalItensSelecionados(),
                obrigatorioExtras: this.obrigatorioExtras
            });
        }
    }
};

// Inicializar quando o documento estiver pronto
$(document).ready(function () {
    ProdutoExtras.init();
});
