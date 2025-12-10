/**
 * Sistema de Seleção de Extras para Produtos
 * Gerencia a seleção de extras no modal de compra
 */

const ProdutoExtras = {
    produtoAtual: null,
    extrasDisponiveis: [],
    extrasSelecionados: [],
    obrigatorioExtras: 0,
    onChangeCallback: null,

    /**
     * Inicializa o sistema de extras
     */
    init() {
        console.log('🎯 Inicializando sistema de extras...');
        this.bindEvents();
    },

    /**
     * Vincula eventos aos elementos
     */
    bindEvents() {
        // Botão para abrir modal de extras
        $('#btn-selecionar-extras').on('click', () => {
            this.abrirModalExtras();
        });

        // Botão para confirmar seleção de extras
        $('#btn-confirmar-extras').on('click', () => {
            this.confirmarExtras();
        });

        // Limpar extras ao fechar modal de compra
        $('#modalCompra').on('hidden.bs.modal', () => {
            this.limparExtras();
        });
    },

    /**
     * Carrega os extras de um produto
     */
    async carregarExtras(produtoId) {
        console.log(`📦 Carregando extras do produto ${produtoId}...`);
        
        this.produtoAtual = produtoId;
        this.extrasSelecionados = [];
        this.emitirAtualizacao();
        
        try {
            const response = await fetch(`${window.location.origin}/api/produto-extras/${produtoId}`);
            const data = await response.json();

            if (data.success) {
                this.extrasDisponiveis = data.extras;
                this.obrigatorioExtras = data.obrigatorio_extras;

                this.emitirAtualizacao();

                console.log(`✅ ${data.total_extras} extras carregados`);
                console.log(`⚠️ Obrigatório: ${this.obrigatorioExtras} extras`);

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
     * Abre o modal de seleção de extras
     */
    abrirModalExtras() {
        console.log('🎨 Abrindo modal de extras...');
        
        // Mostrar loading
        $('#extras-loading').show();
        $('#extras-lista').hide();
        
        // Abrir modal
        $('#modalExtras').modal('show');
        
        // Renderizar extras
        setTimeout(() => {
            this.renderizarExtras();
        }, 300);
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

        // Mostrar aviso se houver extras obrigatórios
        if (this.obrigatorioExtras > 0) {
            $('#texto-aviso-obrigatorio').text(
                `Você deve selecionar pelo menos ${this.obrigatorioExtras} extra${this.obrigatorioExtras > 1 ? 's' : ''} diferente${this.obrigatorioExtras > 1 ? 's' : ''}.`
            );
            $('#aviso-obrigatorio').show();
        } else {
            $('#aviso-obrigatorio').hide();
        }

        // Renderizar cada extra
        this.extrasDisponiveis.forEach(extra => {
            const quantidadeSelecionada = this.getQuantidadeExtra(extra.id);
            const isSelected = quantidadeSelecionada > 0;
            
            let extraHtml = '';
            
            if (extra.multitude) {
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
                        <div class="extra-quantidade-input">
                            <label for="qty-input-${extra.id}" class="qty-label">Qtd:</label>
                            <input type="number" 
                                   id="qty-input-${extra.id}" 
                                   class="qty-input" 
                                   min="0" 
                                   max="99" 
                                   value="${quantidadeSelecionada}"
                                   onchange="ProdutoExtras.alterarQuantidadeExtra(${extra.id}, this.value)"
                                   oninput="ProdutoExtras.alterarQuantidadeExtra(${extra.id}, this.value)">
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
        
        if (index > -1) {
            // Remover
            this.extrasSelecionados.splice(index, 1);
            $(`.extra-item[data-extra-id="${extraId}"]`).removeClass('selected');
            console.log(`➖ Extra removido: ${extra.nome}`);
        } else {
            // Adicionar com quantidade 1
            this.extrasSelecionados.push({...extra, quantidade: 1});
            $(`.extra-item[data-extra-id="${extraId}"]`).addClass('selected');
            console.log(`➕ Extra adicionado: ${extra.nome}`);
        }

        this.atualizarContador();
        this.emitirAtualizacao();
    },

    /**
     * Altera a quantidade de um extra (multitude) via input number
     */
    alterarQuantidadeExtra(extraId, quantidade) {
        const extra = this.extrasDisponiveis.find(e => e.id === extraId);
        if (!extra) return;

        // Converter para número e validar
        quantidade = parseInt(quantidade) || 0;
        if (quantidade < 0) quantidade = 0;
        if (quantidade > 99) quantidade = 99;

        // Atualizar o input com o valor validado
        $(`#qty-input-${extraId}`).val(quantidade);

        const index = this.extrasSelecionados.findIndex(e => e.id === extraId);
        
        if (quantidade > 0) {
            if (index > -1) {
                // Atualizar quantidade existente
                this.extrasSelecionados[index].quantidade = quantidade;
            } else {
                // Adicionar novo extra
                this.extrasSelecionados.push({...extra, quantidade: quantidade});
            }
            $(`.extra-item[data-extra-id="${extraId}"]`).addClass('selected');
            console.log(`✏️ Extra ${extra.nome}: ${quantidade}x`);
        } else {
            // Quantidade = 0, remover extra
            if (index > -1) {
                this.extrasSelecionados.splice(index, 1);
            }
            $(`.extra-item[data-extra-id="${extraId}"]`).removeClass('selected');
            console.log(`➖ Extra ${extra.nome} removido`);
        }
        
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
        const total = this.getTotalItensSelecionados();
        $('#contador-extras-modal').text(total);
        $('#contador-extras').text(`${total} extra${total !== 1 ? 's' : ''} selecionado${total !== 1 ? 's' : ''}`);
        
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
        const totalSelecionados = this.getTotalItensSelecionados();
        if (this.obrigatorioExtras > 0 && totalSelecionados < this.obrigatorioExtras) {
            alert(`Você deve selecionar pelo menos ${this.obrigatorioExtras} extra${this.obrigatorioExtras > 1 ? 's' : ''}.`);
            return;
        }

        console.log('✅ Extras confirmados:', this.extrasSelecionados);

        // Mostrar resumo
        if (this.extrasSelecionados.length > 0) {
            $('#extras-selecionados-resumo').show();
        } else {
            $('#extras-selecionados-resumo').hide();
        }

        // Fechar modal
        $('#modalExtras').modal('hide');
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
        const totalSelecionados = this.getTotalItensSelecionados();
        if (this.obrigatorioExtras > 0 && totalSelecionados < this.obrigatorioExtras) {
            alert(`⚠️ Este produto requer a seleção de pelo menos ${this.obrigatorioExtras} extra${this.obrigatorioExtras > 1 ? 's' : ''}.`);
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
        $('#container-btn-extras').hide();
        $('#extras-selecionados-resumo').hide();
        this.emitirAtualizacao();
    },

    getTotalExtras() {
        if (this.extrasSelecionados.length === 0) {
            return 0;
        }

        return this.extrasSelecionados.reduce((total, extra) => {
            const quantidade = extra.multitude ? (extra.quantidade || 0) : 1;
            const preco = parseFloat(extra.preco || 0);
            return total + (preco * quantidade);
        }, 0);
    },

    getTotalItensSelecionados() {
        if (this.extrasSelecionados.length === 0) {
            return 0;
        }

        return this.extrasSelecionados.reduce((total, extra) => {
            const quantidade = extra.multitude ? (extra.quantidade || 0) : 1;
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
$(document).ready(function() {
    ProdutoExtras.init();
});
