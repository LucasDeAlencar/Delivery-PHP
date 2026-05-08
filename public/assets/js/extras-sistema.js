/**
 * Sistema de Extras - Versão Funcional
 * Gerencia seleção de extras com localStorage
 */

 window.ProdutoExtras = {
     produtoAtual: null,
     extrasDisponiveis: [],
     extrasSelecionados: [],
     obrigatorioExtras: 0,
     maxExtras: 0,
     termoPesquisa: '',

    /**
     * Carrega extras de um produto
     */
    async carregarExtras(produtoId) {
        
        this.produtoAtual = produtoId;
        this.extrasSelecionados = [];
        this.extrasDisponiveis = [];
        this.obrigatorioExtras = 0;
        this.maxExtras = 0;
        
        try {
            const response = await fetch(`/api/produto-extras/${produtoId}`);
            const data = await response.json();

            if (data.success && data.extras && data.extras.length > 0) {
                this.extrasDisponiveis = data.extras;
                this.obrigatorioExtras = parseInt(data.obrigatorio_extras) || 0;
                this.maxExtras = parseInt(data.max_extras) || 0;
                
                
                // Mostrar botão de extras
                $('#container-btn-extras').show();
                
                let textoBtn = 'Selecionar Extras';
                if (this.obrigatorioExtras > 0) {
                    $('#badge-obrigatorio').show();
                    textoBtn = `Selecionar Extras (${this.obrigatorioExtras} obrigatório)`;
                } else if (this.maxExtras > 0) {
                    $('#badge-obrigatorio').hide();
                    textoBtn = `Selecionar Extras (Máx. ${this.maxExtras})`;
                } else {
                    $('#badge-obrigatorio').hide();
                    textoBtn = 'Selecionar Extras (Opcional)';
                }
                $('#texto-btn-extras').text(textoBtn);
                
                return true;
            } else {
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
         
         // Mostrar loading
         $('#extras-loading').show();
         $('#extras-lista').hide();
         $('#extras-vazio').hide();
         
         // Limpar pesquisa anterior
         $('#pesquisa-extras').val('');
         $('#limpar-pesquisa').hide();
         this.termoPesquisa = '';
         
         // Configurar evento de pesquisa (se ainda não estiver configurado)
         if (!$('#pesquisa-extras').data('evento-configurado')) {
             $('#pesquisa-extras').on('input', (e) => {
                 const termo = e.target.value.trim().toLowerCase();
                 this.termoPesquisa = termo;
                 this.renderizarExtras();
                 
                 // Mostrar/ocultar botão limpar
                 if (termo.length > 0) {
                     $('#limpar-pesquisa').show();
                 } else {
                     $('#limpar-pesquisa').hide();
                 }
             });
             $('#pesquisa-extras').data('evento-configurado', true);
         }
         
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

         // Filtrar extras pelo termo de pesquisa
         const extrasFiltrados = this.termoPesquisa 
             ? this.extrasDisponiveis.filter(extra => 
                 extra.nome.toLowerCase().includes(this.termoPesquisa) ||
                 (extra.descricao && extra.descricao.toLowerCase().includes(this.termoPesquisa))
               )
             : this.extrasDisponiveis;

         if (extrasFiltrados.length === 0 && this.extrasDisponiveis.length > 0) {
             // Mostrou mensagem de nenhum resultado na pesquisa
             container.html('<p class="text-center text-muted py-4">Nenhum extra encontrado para "<strong>' + this.termoPesquisa + '</strong>"</p>');
             $('#extras-loading').hide();
             $('#extras-lista').show();
             $('#extras-vazio').hide();
             return;
         }

         if (this.extrasDisponiveis.length === 0) {
             $('#extras-loading').hide();
             $('#extras-vazio').show();
             $('#extras-lista').show();
             return;
         }

         // Aviso de obrigatórios e limite máximo
         let avisoTexto = '';
         if (this.obrigatorioExtras > 0) {
             avisoTexto = `Selecione pelo menos ${this.obrigatorioExtras} extra(s).`;
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
         extrasFiltrados.forEach(extra => {
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
        } else {
            // Verificar limite máximo antes de adicionar
            if (this.maxExtras > 0 && (this.getTotalItens() + 1) > this.maxExtras) {
                alert(`Você pode selecionar no máximo ${this.maxExtras} extras.`);
                $(`#chk-${extraId}`).prop('checked', false);
                return;
            }
            
            // Adicionar
            this.extrasSelecionados.push({
                id: extra.id,
                nome: extra.nome,
                preco: parseFloat(extra.preco) || 0,
                quantidade: 1
            });
            $(`.extra-item[data-id="${extraId}"]`).addClass('selected');
        }

        this.atualizarContador();
    },

    /**
     * Aumentar quantidade
     */
    aumentarQtd(extraId) {
        const extra = this.extrasDisponiveis.find(e => e.id == extraId);
        if (!extra) return;

        // Verificar limite máximo antes de adicionar
        if (this.maxExtras > 0 && (this.getTotalItens() + 1) > this.maxExtras) {
            alert(`Você pode selecionar no máximo ${this.maxExtras} extras.`);
            return;
        }

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
        if (this.maxExtras > 0) {
            $('#contador-extras-modal').text(`${total}/${this.maxExtras}`);
        } else {
            $('#contador-extras-modal').text(total);
        }
        
        // Habilitar/desabilitar botão confirmar
        let desabilitar = false;
        if (this.obrigatorioExtras > 0 && total < this.obrigatorioExtras) {
            desabilitar = true;
        }
        if (this.maxExtras > 0 && total > this.maxExtras) {
            desabilitar = true;
        }
        
        if (desabilitar) {
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

        if (this.maxExtras > 0 && total > this.maxExtras) {
            alert(`Você pode selecionar no máximo ${this.maxExtras} extras.`);
            return;
        }
        
        // Atualizar resumo no modal de compra
        this.atualizarResumoCompra();
        
        // Atualizar total no modal de produto
        if (window.SistemaProduto && typeof window.SistemaProduto.atualizarTotal === 'function') {
            window.SistemaProduto.atualizarTotal();
        }
        
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
        
        // Disparar evento para atualizar total
        $(document).trigger('extrasAtualizados');
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
        if (this.maxExtras > 0 && total > this.maxExtras) {
            alert(`Este produto permite no máximo ${this.maxExtras} extras.`);
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
        this.maxExtras = 0;
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

 // Inicializar eventos quando o documento estiver pronto
 $(document).ready(function() {
     // Botão para abrir modal de extras
     $('#btn-selecionar-extras').on('click', function() {
         window.ProdutoExtras.abrirModalExtras();
     });

     // Botão para confirmar seleção de extras
     $('#btn-confirmar-extras').on('click', function() {
         window.ProdutoExtras.confirmarExtras();
     });

     // Limpar pesquisa e extras ao fechar modal de extras
     $('#modalExtras').on('hidden.bs.modal', function() {
         $('#pesquisa-extras').val('');
         $('#limpar-pesquisa').hide();
         window.ProdutoExtras.termoPesquisa = '';
     });

     // Limpar extras ao fechar modal de compra
     $('#modalCompra').on('hidden.bs.modal', function() {
         window.ProdutoExtras.extrasSelecionados = [];
         window.ProdutoExtras.extrasDisponiveis = [];
         window.ProdutoExtras.produtoAtual = null;
         window.ProdutoExtras.obrigatorioExtras = 0;
         window.ProdutoExtras.maxExtras = 0;
         $('#container-btn-extras').hide();
         $('#extras-selecionados-resumo').hide();
         $('#modal-produto-preco-extras').text('Sem extras');
     });

     console.log('🎯 Sistema de extras inicializado');
 });

