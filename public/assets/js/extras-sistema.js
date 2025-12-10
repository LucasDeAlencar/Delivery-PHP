/**
 * Sistema de Extras - Versão Funcional
 * Gerencia seleção de extras com localStorage
 */

window.ProdutoExtras = {
    produtoAtual: null,
    extrasDisponiveis: [],
    extrasSelecionados: [],
    obrigatorioExtras: 0,

    /**
     * Carrega extras de um produto
     */
    async carregarExtras(produtoId) {
        console.log('📦 Carregando extras do produto', produtoId);
        
        this.produtoAtual = produtoId;
        this.extrasSelecionados = [];
        this.extrasDisponiveis = [];
        this.obrigatorioExtras = 0;
        
        try {
            const response = await fetch(`/api/produto-extras/${produtoId}`);
            const data = await response.json();

            if (data.success && data.extras && data.extras.length > 0) {
                this.extrasDisponiveis = data.extras;
                this.obrigatorioExtras = parseInt(data.obrigatorio_extras) || 0;
                
                console.log('✅ Extras carregados:', this.extrasDisponiveis.length);
                console.log('⚠️ Obrigatórios:', this.obrigatorioExtras);
                
                // Mostrar botão de extras
                $('#container-btn-extras').show();
                
                if (this.obrigatorioExtras > 0) {
                    $('#badge-obrigatorio').show();
                    $('#texto-btn-extras').text(`Selecionar Extras (${this.obrigatorioExtras} obrigatório)`);
                } else {
                    $('#badge-obrigatorio').hide();
                    $('#texto-btn-extras').text('Selecionar Extras (Opcional)');
                }
                
                return true;
            } else {
                console.log('ℹ️ Produto sem extras');
                $('#container-btn-extras').hide();
                return false;
            }
        } catch (error) {
            console.error('❌ Erro ao carregar extras:', error);
            $('#container-btn-extras').hide();
            return false;
        }
    },

    /**
     * Abre modal de extras
     */
    abrirModalExtras() {
        console.log('🎨 Abrindo modal de extras');
        
        // Mostrar loading
        $('#extras-loading').show();
        $('#extras-lista').hide();
        $('#extras-vazio').hide();
        
        // Abrir modal
        $('#modalExtras').modal('show');
        
        // Renderizar após abrir
        setTimeout(() => this.renderizarExtras(), 200);
    },

    /**
     * Renderiza lista de extras
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

        // Aviso de obrigatórios
        if (this.obrigatorioExtras > 0) {
            $('#texto-aviso-obrigatorio').text(
                `Selecione pelo menos ${this.obrigatorioExtras} extra(s).`
            );
            $('#aviso-obrigatorio').show();
        } else {
            $('#aviso-obrigatorio').hide();
        }

        // Renderizar cada extra
        this.extrasDisponiveis.forEach(extra => {
            const selecionado = this.extrasSelecionados.find(e => e.id == extra.id);
            const quantidade = selecionado ? selecionado.quantidade : 0;
            const isSelected = quantidade > 0;
            
            let html = '';
            
            if (extra.multitude == 1) {
                // Extra com quantidade
                html = `
                    <div class="extra-item ${isSelected ? 'selected' : ''}" data-id="${extra.id}">
                        <div class="extra-info">
                            <span class="extra-nome">${extra.nome}</span>
                            ${extra.descricao ? `<small class="extra-desc">${extra.descricao}</small>` : ''}
                        </div>
                        <div class="extra-preco">
                            ${parseFloat(extra.preco) > 0 ? `+R$ ${parseFloat(extra.preco).toFixed(2).replace('.', ',')}` : 'Grátis'}
                        </div>
                        <div class="extra-controles">
                            <button type="button" class="btn-qty btn-menos" onclick="ProdutoExtras.diminuirQtd(${extra.id})">-</button>
                            <span class="qty-valor" id="qty-${extra.id}">${quantidade}</span>
                            <button type="button" class="btn-qty btn-mais" onclick="ProdutoExtras.aumentarQtd(${extra.id})">+</button>
                        </div>
                    </div>
                `;
            } else {
                // Extra checkbox
                html = `
                    <div class="extra-item ${isSelected ? 'selected' : ''}" data-id="${extra.id}">
                        <div class="extra-check">
                            <input type="checkbox" id="chk-${extra.id}" ${isSelected ? 'checked' : ''} 
                                   onchange="ProdutoExtras.toggleExtra(${extra.id})">
                        </div>
                        <div class="extra-info">
                            <label for="chk-${extra.id}" class="extra-nome">${extra.nome}</label>
                            ${extra.descricao ? `<small class="extra-desc">${extra.descricao}</small>` : ''}
                        </div>
                        <div class="extra-preco">
                            ${parseFloat(extra.preco) > 0 ? `+R$ ${parseFloat(extra.preco).toFixed(2).replace('.', ',')}` : 'Grátis'}
                        </div>
                    </div>
                `;
            }
            
            container.append(html);
        });

        this.atualizarContador();
        
        $('#extras-loading').hide();
        $('#extras-lista').show();
    },

    /**
     * Toggle extra checkbox
     */
    toggleExtra(extraId) {
        const extra = this.extrasDisponiveis.find(e => e.id == extraId);
        if (!extra) return;

        const index = this.extrasSelecionados.findIndex(e => e.id == extraId);
        
        if (index > -1) {
            // Remover
            this.extrasSelecionados.splice(index, 1);
            $(`.extra-item[data-id="${extraId}"]`).removeClass('selected');
            console.log('➖ Removido:', extra.nome);
        } else {
            // Adicionar
            this.extrasSelecionados.push({
                id: extra.id,
                nome: extra.nome,
                preco: parseFloat(extra.preco) || 0,
                quantidade: 1
            });
            $(`.extra-item[data-id="${extraId}"]`).addClass('selected');
            console.log('➕ Adicionado:', extra.nome);
        }

        this.atualizarContador();
    },

    /**
     * Aumentar quantidade
     */
    aumentarQtd(extraId) {
        const extra = this.extrasDisponiveis.find(e => e.id == extraId);
        if (!extra) return;

        let selecionado = this.extrasSelecionados.find(e => e.id == extraId);
        
        if (selecionado) {
            if (selecionado.quantidade < 99) {
                selecionado.quantidade++;
            }
        } else {
            this.extrasSelecionados.push({
                id: extra.id,
                nome: extra.nome,
                preco: parseFloat(extra.preco) || 0,
                quantidade: 1
            });
            $(`.extra-item[data-id="${extraId}"]`).addClass('selected');
        }

        $(`#qty-${extraId}`).text(this.getQtdExtra(extraId));
        this.atualizarContador();
        console.log('➕ Quantidade:', extra.nome, this.getQtdExtra(extraId));
    },

    /**
     * Diminuir quantidade
     */
    diminuirQtd(extraId) {
        const index = this.extrasSelecionados.findIndex(e => e.id == extraId);
        
        if (index > -1) {
            if (this.extrasSelecionados[index].quantidade > 1) {
                this.extrasSelecionados[index].quantidade--;
            } else {
                this.extrasSelecionados.splice(index, 1);
                $(`.extra-item[data-id="${extraId}"]`).removeClass('selected');
            }
        }

        $(`#qty-${extraId}`).text(this.getQtdExtra(extraId));
        this.atualizarContador();
    },

    /**
     * Obter quantidade de um extra
     */
    getQtdExtra(extraId) {
        const selecionado = this.extrasSelecionados.find(e => e.id == extraId);
        return selecionado ? selecionado.quantidade : 0;
    },

    /**
     * Atualizar contador
     */
    atualizarContador() {
        const total = this.getTotalItens();
        $('#contador-extras-modal').text(total);
        
        // Habilitar/desabilitar botão confirmar
        if (this.obrigatorioExtras > 0 && total < this.obrigatorioExtras) {
            $('#btn-confirmar-extras').prop('disabled', true).css('opacity', '0.5');
        } else {
            $('#btn-confirmar-extras').prop('disabled', false).css('opacity', '1');
        }
    },

    /**
     * Confirmar seleção
     */
    confirmarExtras() {
        const total = this.getTotalItens();
        
        if (this.obrigatorioExtras > 0 && total < this.obrigatorioExtras) {
            alert(`Selecione pelo menos ${this.obrigatorioExtras} extra(s).`);
            return;
        }

        console.log('✅ Extras confirmados:', this.extrasSelecionados);
        
        // Atualizar resumo no modal de compra
        this.atualizarResumoCompra();
        
        // Fechar modal
        $('#modalExtras').modal('hide');
    },

    /**
     * Atualizar resumo no modal de compra
     */
    atualizarResumoCompra() {
        const totalValor = this.getTotalExtras();
        const totalItens = this.extrasSelecionados.length;
        
        if (totalItens > 0) {
            $('#extras-selecionados-resumo').show();
            $('#contador-extras').text(`${totalItens} extra(s) selecionado(s)`);
            $('#valor-extras-resumo').text(`+R$ ${totalValor.toFixed(2).replace('.', ',')}`);
            $('#modal-produto-preco-extras').text(`Inclui R$ ${totalValor.toFixed(2).replace('.', ',')} em extras`);
        } else {
            $('#extras-selecionados-resumo').hide();
            $('#modal-produto-preco-extras').text('Sem extras adicionados');
        }
        
        // Recalcular total
        this.recalcularTotal();
    },

    /**
     * Recalcular total do modal
     */
    recalcularTotal() {
        const precoBase = parseFloat($('#modal-produto-preco').data('valor-base')) || 0;
        const quantidade = parseInt($('#quantidade').val()) || 1;
        const totalExtras = this.getTotalExtras();
        const total = (precoBase + totalExtras) * quantidade;
        
        $('#modal-total').text(`R$ ${total.toFixed(2).replace('.', ',')}`);
        
        if (totalExtras > 0) {
            $('#modal-total-detalhe').text(`Base: R$ ${(precoBase * quantidade).toFixed(2).replace('.', ',')} | Extras: R$ ${(totalExtras * quantidade).toFixed(2).replace('.', ',')}`);
        } else {
            $('#modal-total-detalhe').text('');
        }
    },

    /**
     * Obter extras selecionados
     */
    getExtrasSelecionados() {
        return this.extrasSelecionados.map(e => ({
            id: e.id,
            nome: e.nome,
            preco: e.preco,
            quantidade: e.quantidade
        }));
    },

    /**
     * Validar seleção
     */
    validarSelecao() {
        const total = this.getTotalItens();
        if (this.obrigatorioExtras > 0 && total < this.obrigatorioExtras) {
            alert(`Este produto requer ${this.obrigatorioExtras} extra(s).`);
            return false;
        }
        return true;
    },

    /**
     * Limpar extras
     */
    limparExtras() {
        this.produtoAtual = null;
        this.extrasDisponiveis = [];
        this.extrasSelecionados = [];
        this.obrigatorioExtras = 0;
        $('#container-btn-extras').hide();
        $('#extras-selecionados-resumo').hide();
    },

    /**
     * Total em valor
     */
    getTotalExtras() {
        return this.extrasSelecionados.reduce((total, e) => {
            return total + (e.preco * e.quantidade);
        }, 0);
    },

    /**
     * Total de itens
     */
    getTotalItens() {
        return this.extrasSelecionados.reduce((total, e) => {
            return total + e.quantidade;
        }, 0);
    },

    /**
     * Total de extras diferentes
     */
    getTotalItensSelecionados() {
        return this.getTotalItens();
    }
};

console.log('✅ Sistema de Extras carregado');
