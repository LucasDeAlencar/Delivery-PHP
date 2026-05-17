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
      * Abre overlay customizado de extras
      */
     abrirModalExtras() {
         this.abrirOverlayExtras();
     },

     abrirOverlayExtras() {
         document.getElementById('pe-overlay')?.remove();

         const obrig = this.obrigatorioExtras;
         const max   = this.maxExtras;
         let subtitulo = 'Opcional';
         if (obrig > 0) subtitulo = `${obrig} obrigatório(s)`;
         else if (max > 0) subtitulo = `Máx. ${max}`;

         const html = `
         <div id="pe-overlay" class="pe-overlay">
             <div class="pe-container">
                 <div class="pe-header">
                     <div>
                         <span class="pe-title"><i class="fas fa-plus-circle"></i> Extras</span>
                         <small class="pe-subtitle">${subtitulo}</small>
                     </div>
                     <button class="pe-fechar" id="pe-btn-fechar">&times;</button>
                 </div>
                 <div class="pe-body">
                     <div class="pe-aviso" id="pe-aviso" style="display:none;"></div>
                     <div id="pe-lista"></div>
                 </div>
                 <div class="pe-footer">
                     <div class="pe-contador" id="pe-contador">0 selecionado(s)</div>
                     <button type="button" id="pe-btn-confirmar" class="pe-btn-confirmar">
                         <i class="fas fa-check"></i> Confirmar
                     </button>
                 </div>
             </div>
         </div>`;

         document.body.insertAdjacentHTML('beforeend', html);

         document.getElementById('pe-btn-fechar').addEventListener('click', () => this._fecharOverlay());
         document.getElementById('pe-overlay').addEventListener('click', (e) => {
             if (e.target.id === 'pe-overlay') this._fecharOverlay();
         });
         document.getElementById('pe-btn-confirmar').addEventListener('click', () => this.confirmarExtras());

         this._renderizarOverlay();
     },

     _fecharOverlay() {
         document.getElementById('pe-overlay')?.remove();
     },

     _renderizarOverlay() {
         const lista = document.getElementById('pe-lista');
         if (!lista) return;
         lista.innerHTML = '';

         if (!this.extrasDisponiveis.length) {
             lista.innerHTML = '<p class="pe-vazio">Nenhum extra disponível.</p>';
             return;
         }

         this.extrasDisponiveis.forEach(extra => {
             const sel = this.extrasSelecionados.find(e => e.id == extra.id);
             const qtd = sel ? sel.quantidade : 0;
             const precoStr = parseFloat(extra.preco) > 0
                 ? `+R$ ${parseFloat(extra.preco).toFixed(2).replace('.', ',')}`
                 : 'Grátis';

             const div = document.createElement('div');
             div.className = `pe-item${qtd > 0 ? ' pe-item-sel' : ''}`;
             div.dataset.id = extra.id;

             if (extra.multitude == 1) {
                 div.innerHTML = `
                     <div class="pe-item-info">
                         <span class="pe-item-nome">${extra.nome}</span>
                         ${extra.descricao ? `<small class="pe-item-desc">${extra.descricao}</small>` : ''}
                         <span class="pe-item-preco">${precoStr}</span>
                     </div>
                     <div class="pe-item-ctrl">
                         <button type="button" class="pe-qty-btn" data-acao="menos" data-id="${extra.id}">−</button>
                         <span class="pe-qty-val" id="pe-qty-${extra.id}">${qtd}</span>
                         <button type="button" class="pe-qty-btn" data-acao="mais" data-id="${extra.id}">+</button>
                     </div>`;
             } else {
                 div.innerHTML = `
                     <label class="pe-item-label">
                         <div class="pe-item-info">
                             <span class="pe-item-nome">${extra.nome}</span>
                             ${extra.descricao ? `<small class="pe-item-desc">${extra.descricao}</small>` : ''}
                             <span class="pe-item-preco">${precoStr}</span>
                         </div>
                         <div class="pe-check-wrap">
                             <input type="checkbox" class="pe-chk" data-id="${extra.id}" ${qtd > 0 ? 'checked' : ''}>
                             <span class="pe-checkmark"></span>
                         </div>
                     </label>`;
             }
             lista.appendChild(div);
         });

         // Eventos dos botões de quantidade
         lista.querySelectorAll('.pe-qty-btn').forEach(btn => {
             btn.addEventListener('click', () => {
                 const id = parseInt(btn.dataset.id);
                 if (btn.dataset.acao === 'mais') this.aumentarQtd(id);
                 else this.diminuirQtd(id);
                 // Atualizar visual
                 const qtdEl = document.getElementById(`pe-qty-${id}`);
                 if (qtdEl) qtdEl.textContent = this.getQtdExtra(id);
                 const item = lista.querySelector(`.pe-item[data-id="${id}"]`);
                 if (item) item.classList.toggle('pe-item-sel', this.getQtdExtra(id) > 0);
                 this._atualizarContadorOverlay();
             });
         });

         // Eventos dos checkboxes
         lista.querySelectorAll('.pe-chk').forEach(chk => {
             chk.addEventListener('change', () => {
                 const id = parseInt(chk.dataset.id);
                 this.toggleExtra(id);
                 const item = lista.querySelector(`.pe-item[data-id="${id}"]`);
                 if (item) item.classList.toggle('pe-item-sel', chk.checked);
                 this._atualizarContadorOverlay();
             });
         });

         this._atualizarContadorOverlay();
     },

     _atualizarContadorOverlay() {
         const total = this.getTotalItens();
         const el = document.getElementById('pe-contador');
         if (!el) return;
         const max = this.maxExtras;
         el.textContent = max > 0 ? `${total}/${max} selecionado(s)` : `${total} selecionado(s)`;

         const btn = document.getElementById('pe-btn-confirmar');
         if (!btn) return;
         const invalido = (this.obrigatorioExtras > 0 && total < this.obrigatorioExtras)
                       || (max > 0 && total > max);
         btn.disabled = invalido;
         btn.style.opacity = invalido ? '.5' : '1';
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
        
        this.atualizarResumoCompra();
        
        if (window.PopupProduto) window.PopupProduto.atualizarTotal();
        else if (window.SistemaProduto) window.SistemaProduto.atualizarTotal();
        
        this._fecharOverlay();
    },

    /**
     * Atualizar resumo no popup de produto
     */
    atualizarResumoCompra() {
        const totalValor = this.getTotalExtras();
        const totalItens = this.extrasSelecionados.length;
        
        const resumo = document.getElementById('extras-selecionados-resumo');
        const contador = document.getElementById('contador-extras');
        const valorEl = document.getElementById('valor-extras-resumo');
        const precosExtras = document.getElementById('modal-produto-preco-extras');

        if (totalItens > 0) {
            if (resumo) resumo.style.display = '';
            if (contador) contador.textContent = `${totalItens} extra(s) selecionado(s)`;
            if (valorEl) valorEl.textContent = `+R$ ${totalValor.toFixed(2).replace('.', ',')}`;
            if (precosExtras) precosExtras.textContent = `+R$ ${totalValor.toFixed(2).replace('.', ',')} em extras`;
        } else {
            if (resumo) resumo.style.display = 'none';
            if (precosExtras) precosExtras.textContent = '';
        }
        
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
     // Botão para abrir extras (delegado — o overlay é criado dinamicamente)
     $(document).on('click', '#btn-selecionar-extras', function() {
         window.ProdutoExtras.abrirOverlayExtras();
     });
 });

