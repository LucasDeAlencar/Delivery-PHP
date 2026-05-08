<?= $this->extend('Admin/layout/principal') ?>
<?= $this->section('titulo') ?>Venda Específica<?= $this->endSection() ?>
<?= $this->section('conteudos') ?>

<style>
.ve-tab { display:none; }
.ve-tab.active { display:block; }
.ve-nav-btn { flex:1; border-radius:0; border:none; padding:.6rem .2rem; font-size:.75rem; background:#1a1a1a; color:#aaa; border-top:3px solid transparent; }
.ve-nav-btn.active { color:#ffc107; border-top-color:#ffc107; background:#222; }
.ve-nav-btn i { display:block; font-size:1.2rem; margin-bottom:2px; }
.ve-card { background:#2d2d2d; border:1px solid #333; border-radius:8px; }
.ve-card .ve-card-header { background:#0055ff; color:#000; padding:.5rem .75rem; border-radius:8px 8px 0 0; font-weight:600; }
.comanda-card { background:#1a1a1a; border:2px solid #c47a00; border-radius:8px; padding:.6rem; cursor:pointer; }
.comanda-card:active { opacity:.8; }
#banner-comanda { position:sticky; top:0; z-index:100; }

/* Tabela não estoura em mobile */
#lista-itens, #detalhe-itens table { table-layout:fixed; width:100%; }
#lista-itens td, #detalhe-itens td { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
#produto-autocomplete .list-group-item { white-space:normal; word-break:break-word; }

/* Desktop: 2 colunas */
@media (min-width: 992px) {
  #ve-mobile-nav { display:none !important; }
  #ve-content { padding-bottom:0 !important; display:flex; gap:1rem; align-items:flex-start; overflow:hidden; }
  .ve-tab { display:block !important; }
  .ve-tab.ve-tab-hidden { display:none !important; }
  #ve-col-left { flex:0 0 340px; width:340px; min-width:0; display:flex; flex-direction:column; gap:1rem; overflow:hidden; }
  #ve-col-right { flex:1 1 0; min-width:0; display:flex; flex-direction:column; gap:1rem; overflow:hidden; }
  #tab-cliente.comanda-ativa-desktop { display:none !important; }
  /* Remover padding do container pai nesta página */
  .content-area { padding:0.5rem !important; }
}
</style>

<!-- Banner comanda ativa -->
<div id="banner-comanda" style="display:none;" class="alert alert-warning py-2 px-3 mb-2 d-flex justify-content-between align-items-center rounded-0 mb-0">
  <span><i class="fas fa-folder-open"></i> Comanda <strong id="banner-comanda-id"></strong> — <span id="banner-comanda-nome"></span></span>
  <button class="btn btn-sm btn-outline-dark py-0" onclick="cancelarEdicaoComanda()">✕</button>
</div>

<!-- Badge total flutuante -->
<div class="d-flex justify-content-between align-items-center px-2 py-1" style="background:#111;">
  <span style="color:#0055ff;font-weight:600;"><i class="fas fa-plus-circle"></i> Venda Específica</span>
  <span class="badge bg-warning text-dark" id="badge-itens">0 itens · R$ 0,00</span>
</div>

<!-- Conteúdo das abas -->
<div id="ve-content" style="padding-bottom:70px;">
  <div id="ve-col-left">

  <!-- ABA: Comandas -->
  <div class="ve-tab active" id="tab-comandas">
    <div class="p-2">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0 text-warning"><i class="fas fa-folder-open"></i> Comandas em Aberto</h6>
        <button class="btn btn-sm btn-outline-warning py-0" onclick="carregarComandas()"><i class="fas fa-sync-alt"></i></button>
      </div>
      <div id="painel-comandas"><p class="text-muted text-center small py-3">Carregando...</p></div>
      <!-- Detalhe da comanda selecionada -->
      <div id="detalhe-comanda" style="display:none;" class="mt-2">
        <div class="ve-card">
          <div class="ve-card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list"></i> Itens da Comanda <strong id="detalhe-comanda-id"></strong></span>
            <button class="btn btn-sm btn-outline-light py-0" onclick="fecharDetalheComanda()">✕</button>
          </div>
          <div id="detalhe-itens" class="p-2"></div>
          <div class="p-2 border-top border-secondary d-flex gap-2">
            <button class="btn btn-warning btn-sm flex-grow-1" onclick="retomarComandaAtual()">
              <i class="fas fa-plus"></i> Adicionar Itens
            </button>
            <button class="btn btn-success btn-sm flex-grow-1" id="btn-fechar-comanda-detalhe">
              <i class="fas fa-check"></i> Fechar Comanda
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ABA: Cliente -->
  <div class="ve-tab" id="tab-cliente">
    <div class="p-2">
      <ul class="nav nav-tabs mb-2" id="clienteTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active py-1 px-3 small" id="existing-tab" data-bs-toggle="tab" data-bs-target="#existing" type="button">
            <i class="fas fa-search"></i> Buscar
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link py-1 px-3 small" id="new-tab" data-bs-toggle="tab" data-bs-target="#new" type="button">
            <i class="fas fa-plus"></i> Novo
          </button>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="existing" role="tabpanel">
          <div class="mb-2 position-relative">
            <input type="text" class="form-control bg-dark text-light" id="cliente-busca" placeholder="Nome ou telefone..." autocomplete="off">
            <div id="cliente-autocomplete" class="list-group position-absolute w-100" style="z-index:1000;display:none;max-height:220px;overflow-y:auto;"></div>
          </div>
          <div id="cliente-selecionado" class="alert alert-success py-2 d-none">
            <strong><i class="fas fa-check-circle"></i> <span id="cliente-nome"></span></strong>
            <small class="d-block text-muted" id="cliente-info"></small>
            <button type="button" class="btn btn-sm btn-outline-danger mt-1" onclick="limparCliente()"><i class="fas fa-times"></i> Alterar</button>
          </div>
        </div>
        <div class="tab-pane fade" id="new" role="tabpanel">
          <div class="mb-2">
            <label class="form-label mb-1 small">Nome *</label>
            <input type="text" class="form-control bg-dark text-light" id="novo-cliente-nome">
          </div>
          <div class="mb-2">
            <label class="form-label mb-1 small">Telefone <span class="text-muted">(opcional)</span></label>
            <input type="text" class="form-control bg-dark text-light" id="novo-cliente-telefone" placeholder="(00) 00000-0000">
          </div>
          <div class="mb-2">
            <label class="form-label mb-1 small">Endereço <span class="text-muted">(opcional)</span></label>
            <input type="text" class="form-control bg-dark text-light" id="novo-cliente-endereco">
          </div>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label mb-1 small">Bairro <span class="text-muted">(opcional)</span></label>
              <input type="text" class="form-control bg-dark text-light" id="novo-cliente-bairro">
            </div>
            <div class="col-6">
              <label class="form-label mb-1 small">Cidade <span class="text-muted">(opcional)</span></label>
              <input type="text" class="form-control bg-dark text-light" id="novo-cliente-cidade">
            </div>
          </div>
          <button type="button" class="btn btn-warning w-100" onclick="criarNovoCliente()"><i class="fas fa-save"></i> Cadastrar</button>
          <div id="novo-cliente-result" class="mt-2"></div>
        </div>
      </div>
    </div>
  </div>

  </div><!-- /ve-col-left -->
  <div id="ve-col-right">

  <!-- ABA: Produtos -->
  <div class="ve-tab" id="tab-produtos">
    <div class="p-2">
      <!-- Seleção produto -->
      <div class="row g-2 mb-2 align-items-end">
        <div class="col-8">
          <label class="form-label mb-1 small">Produto</label>
          <div class="position-relative">
            <input type="text" class="form-control bg-dark text-light" id="produto-busca" placeholder="Digite para buscar..." autocomplete="off">
            <div id="produto-autocomplete" class="list-group position-absolute w-100" style="z-index:1000;display:none;max-height:220px;overflow-y:auto;"></div>
          </div>
          <!-- select oculto mantém compatibilidade com JS existente -->
          <select id="produto-select" style="display:none;"></select>
        </div>
        <div class="col-4">
          <label class="form-label mb-1 small">Qtd</label>
          <input type="number" class="form-control bg-dark text-light" id="produto-qtd" value="1" min="1">
        </div>
      </div>
      <!-- Tamanho -->
      <div class="row g-2 mb-2 align-items-end" id="row-tamanho" style="display:none!important;">
        <div class="col-8">
          <label class="form-label mb-1 small">Tamanho</label>
          <select class="form-select bg-dark text-light" id="produto-tamanho"></select>
        </div>
        <div class="col-4">
          <label class="form-label mb-1 small">Preço</label>
          <input type="text" class="form-control bg-dark text-light" id="produto-preco-display" readonly>
        </div>
      </div>
      <button type="button" class="btn btn-warning w-100 mb-3" onclick="adicionarProduto()">
        <i class="fas fa-plus"></i> Adicionar Produto
      </button>

      <!-- Extras -->
      <div id="extras-saches-container" style="display:none;" class="mb-3">
        <div class="ve-card">
          <div class="ve-card-header"><i class="fas fa-list"></i> Extras <span id="extras-badge" class="badge bg-dark ms-1" style="display:none;"></span></div>
          <div class="p-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <small id="extras-info" class="text-muted"></small>
              <div id="extras-paginacao" class="d-flex gap-1"></div>
            </div>
            <div id="extras-list"></div>
          </div>
        </div>
      </div>

      <!-- Itens adicionados -->
      <div class="ve-card">
        <div class="ve-card-header d-flex justify-content-between">
          <span><i class="fas fa-shopping-cart"></i> Itens</span>
          <span id="badge-itens-2" class="badge bg-dark">0</span>
        </div>
        <div class="p-0">
          <div id="lista-itens-wrapper">
          <table class="table table-dark table-sm mb-0">
            <tbody id="lista-itens">
              <tr id="sem-itens"><td colspan="5" class="text-center text-muted py-3 small">Nenhum produto</td></tr>
            </tbody>
            <tfoot>
              <tr><th colspan="3" class="text-end small">Subtotal</th><th class="text-end" id="subtotal" colspan="2">R$ 0,00</th></tr>
              <tr><th colspan="3" class="text-end small">Entrega</th><th class="text-end" id="taxa-entrega" colspan="2">R$ 0,00</th></tr>
              <tr style="background:#0055ff;color:#000;"><th colspan="3" class="text-end">Total</th><th class="text-end" id="valor-total" colspan="2">R$ 0,00</th></tr>
            </tfoot>
          </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ABA: Sachês -->
  <div class="ve-tab ve-tab-hidden" id="tab-saches">
    <div class="p-2">
      <div class="ve-card">
        <div class="ve-card-header d-flex justify-content-between align-items-center">
          <span><i class="fas fa-box"></i> Sachês</span>
          <span id="saches-badge" class="badge bg-dark">0</span>
        </div>
        <div class="p-2">
          <div class="d-flex justify-content-end mb-2">
            <div id="saches-paginacao" class="d-flex gap-1 flex-wrap"></div>
          </div>
          <div id="saches-lista-ve"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- ABA: Entrega -->
  <div class="ve-tab" id="tab-entrega">
    <div class="p-2">
      <!-- Ações finais -->
      <div id="secao-novo-pedido">
        <div class="btn-group w-100 mb-3" role="group">
          <input type="radio" class="btn-check" name="tipo_entrega" id="entrega" value="entrega" checked>
          <label class="btn btn-outline-warning" for="entrega"><i class="fas fa-motorcycle"></i> Entrega</label>
          <input type="radio" class="btn-check" name="tipo_entrega" id="retirada" value="retirada">
          <label class="btn btn-outline-warning" for="retirada"><i class="fas fa-shopping-bag"></i> Retirada</label>
        </div>
        <div id="endereco-entrega">
          <div class="mb-3">
            <label class="form-label mb-1">Endereço</label>
            <input type="text" class="form-control bg-dark text-light" id="endereco" placeholder="Rua, número, complemento">
          </div>
          <div class="mb-3">
            <label class="form-label mb-1">Bairro</label>
            <select class="form-select bg-dark text-light" id="bairro"><option value="">-- Selecione --</option></select>
          </div>
          <div id="aviso-cobertura" class="alert alert-warning py-1 px-2 small d-none">
            <i class="fas fa-exclamation-triangle"></i> Bairro fora da área de cobertura.
          </div>
        </div>
        <div id="campo-mesa" style="display:none;" class="mb-3">
          <label class="form-label mb-1">Mesa</label>
          <select class="form-select bg-dark text-light" id="mesa_id"><option value="">-- Sem mesa --</option></select>
        </div>
        <div class="mb-3">
          <label class="form-label mb-1">Forma de Pagamento</label>
          <select class="form-select bg-dark text-light" id="forma_pagamento" onchange="toggleTrocoVE()">
            <option value="dinheiro">Dinheiro</option>
            <option value="pix">PIX</option>
            <option value="cartao_credito">Cartão de Crédito</option>
            <option value="cartao_debito">Cartão de Débito</option>
          </select>
        </div>
        <div class="mb-3" id="campo-troco-ve" style="display:none;">
          <label class="form-label mb-1">Troco para <small class="text-muted">(valor que o cliente vai pagar)</small></label>
          <div class="input-group">
            <span class="input-group-text bg-dark text-warning border-secondary">R$</span>
            <input type="number" class="form-control bg-dark text-light" id="troco_para_ve" step="0.01" min="0" placeholder="0,00">
          </div>
          <small class="text-muted" id="troco-minimo-ve"></small>
        </div>
        <div class="mb-3">
          <label class="form-label mb-1">Observações</label>
          <textarea class="form-control bg-dark text-light" id="observacoes" rows="3"></textarea>
        </div>
        <div class="d-flex gap-2 mb-2">
          <button type="button" class="btn btn-secondary" onclick="limparFormulario()"><i class="fas fa-trash"></i></button>
          <button type="button" class="btn flex-grow-1" id="btn-pausar"
            style="background:#c47a00;border-color:#c47a00;color:#fff;" onclick="abrirComanda()">
            <i class="fas fa-folder-open"></i> Deixar em Aberto
          </button>
          <button type="button" class="btn btn-warning flex-grow-1" onclick="finalizarVenda()" id="btn-finalizar">
            <i class="fas fa-check-circle"></i> Finalizar
          </button>
        </div>
        <small class="text-muted"><i class="fas fa-info-circle"></i> <em>Deixar em Aberto</em> cria uma comanda — adicione mais itens depois na aba Comandas.</small>
      </div>

      <!-- Modo comanda ativa -->
      <div id="secao-fechar-comanda" style="display:none;">
        <div class="alert alert-warning py-2 px-3 mb-3">
          <i class="fas fa-folder-open"></i> Comanda <strong id="fechar-comanda-label"></strong> selecionada
        </div>
        <div class="mb-3">
          <label class="form-label mb-1">Forma de Pagamento</label>
          <select class="form-select bg-dark text-light" id="forma_pagamento_comanda" onchange="toggleTrocoComanda()">
            <option value="dinheiro">Dinheiro</option>
            <option value="pix">PIX</option>
            <option value="cartao_credito">Cartão de Crédito</option>
            <option value="cartao_debito">Cartão de Débito</option>
          </select>
        </div>
        <div class="mb-3" id="campo-troco-comanda" style="display:none;">
          <label class="form-label mb-1">Troco para <small class="text-muted">(valor que o cliente vai pagar)</small></label>
          <div class="input-group">
            <span class="input-group-text bg-dark text-warning border-secondary">R$</span>
            <input type="number" class="form-control bg-dark text-light" id="troco_para_comanda" step="0.01" min="0" placeholder="0,00">
          </div>
          <small class="text-muted" id="troco-minimo-comanda"></small>
        </div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-success flex-grow-1" id="btn-finalizar-comanda" onclick="">
            <i class="fas fa-check-circle"></i> Fechar Comanda
          </button>
          <button type="button" class="btn btn-warning flex-grow-1" id="btn-adicionar-itens-comanda" onclick="continuarEmAberto()">
            <i class="fas fa-folder-open"></i> Continuar em Aberto
          </button>
          <button type="button" class="btn btn-secondary" onclick="cancelarEdicaoComanda()">
            <i class="fas fa-times"></i> Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>

  </div><!-- /ve-col-right -->
</div>

<!-- Navegação inferior fixa -->
<nav id="ve-mobile-nav" class="d-flex" style="position:fixed;bottom:0;left:0;right:0;z-index:200;border-top:1px solid #333;">
  <button class="ve-nav-btn active" id="nav-comandas" onclick="mudarAba('comandas')">
    <i class="fas fa-folder-open"></i>Comandas
  </button>
  <button class="ve-nav-btn" id="nav-cliente" onclick="mudarAba('cliente')">
    <i class="fas fa-user"></i>Cliente
  </button>
  <button class="ve-nav-btn" id="nav-produtos" onclick="mudarAba('produtos')">
    <i class="fas fa-hamburger"></i>Produtos
  </button>
  <button class="ve-nav-btn" id="nav-saches" onclick="mudarAba('saches')" style="display:none;">
    <i class="fas fa-box"></i>Sachês
  </button>
  <button class="ve-nav-btn" id="nav-entrega" onclick="mudarAba('entrega')">
    <i class="fas fa-receipt"></i>Fechar Conta
  </button>
</nav>

<script>
let clienteSelecionado = null, itensVenda = [], taxaEntrega = 0;
let extrasProdutoAtual = [], configExtrasProduto = {obrigatorio_extras:0,max_extras:0};
let extrasQtd = {}; // quantidades/checks dos extras em memória
let bairrosData = [], sachesVE = [], produtosData = [];
let extrasPage = 0, sachesPage = 0;
const ROWS_PER_PAGE = 3;
let comandaAtiva = null;
let sachesQtd = {}; // quantidades dos sachês mantidas em memória
let _ultimasCategorias = '';
let _sachesCarregando = false;

function mudarAba(aba) {
    document.querySelectorAll('.ve-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.ve-nav-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + aba).classList.add('active');
    document.getElementById('nav-' + aba).classList.add('active');
}

document.addEventListener('DOMContentLoaded', function () {
    carregarProdutos(); carregarBairros(); carregarMesas(); carregarComandas();
    toggleTrocoVE(); toggleTrocoComanda();

    document.getElementById('bairro').addEventListener('change', buscarTaxaEntrega);
    document.querySelectorAll('input[name="tipo_entrega"]').forEach(r => r.addEventListener('change', function () {
        document.getElementById('endereco-entrega').style.display = this.value === 'entrega' ? 'block' : 'none';
        document.getElementById('campo-mesa').style.display = this.value === 'retirada' ? 'block' : 'none';
        taxaEntrega = 0;
        if (this.value === 'entrega') buscarTaxaEntrega(); else atualizarTotais();
    }));

    document.getElementById('produto-select').addEventListener('change', function () {
        const rowTam = document.getElementById('row-tamanho');
        if (!this.value) { rowTam.style.setProperty('display','none','important'); document.getElementById('extras-saches-container').style.display='none'; return; }
        const produto = produtosData.find(p => p.id == this.value);
        if (produto?.tamanhos?.length > 0) {
            const tamSel = document.getElementById('produto-tamanho');
            tamSel.innerHTML = '<option value="">-- Selecione --</option>' +
                produto.tamanhos.map(t => `<option value="${t.id}" data-preco="${t.preco}">${t.nome} - R$ ${parseFloat(t.preco).toFixed(2).replace('.',',')}</option>`).join('');
            document.getElementById('produto-preco-display').value = '';
            rowTam.style.removeProperty('display');
            tamSel.onchange = () => {
                const opt = tamSel.options[tamSel.selectedIndex];
                document.getElementById('produto-preco-display').value = opt.value ? 'R$ '+parseFloat(opt.dataset.preco).toFixed(2).replace('.',',') : '';
            };
        } else { rowTam.style.setProperty('display','none','important'); }
        carregarExtrasProduto(this.value);
    });

    const buscaInput = document.getElementById('cliente-busca');
    let debounce;
    buscaInput.addEventListener('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => buscarClientesAuto(this.value.trim()), 300);
    });
    document.addEventListener('click', e => {
        if (!e.target.closest('#cliente-busca') && !e.target.closest('#cliente-autocomplete'))
            document.getElementById('cliente-autocomplete').style.display = 'none';
    });
});

function buscarClientesAuto(termo) {
    const box = document.getElementById('cliente-autocomplete');
    if (termo.length < 2) { box.style.display='none'; return; }
    fetch('<?= site_url('admin/venda-especifica/clientes') ?>?q='+encodeURIComponent(termo))
        .then(r=>r.json()).then(data => {
            const clientes = data.data||[];
            if (!clientes.length) { box.style.display='none'; return; }
            box.innerHTML = clientes.map(c =>
                `<button type="button" class="list-group-item list-group-item-action bg-dark text-light border-secondary" onclick='selecionarCliente(${JSON.stringify(c)})'>
                    <strong>${c.nome}</strong><small class="text-muted d-block">${c.telefone||''} ${c.bairro?'· '+c.bairro:''}</small>
                </button>`).join('');
            box.style.display='block';
        });
}

function selecionarCliente(c) {
    clienteSelecionado = c;
    document.getElementById('cliente-busca').value = c.nome;
    document.getElementById('cliente-autocomplete').style.display = 'none';
    document.getElementById('cliente-selecionado').classList.remove('d-none');
    document.getElementById('cliente-nome').textContent = c.nome;
    document.getElementById('cliente-info').textContent = (c.telefone||'Sem telefone')+(c.bairro?' · '+c.bairro:'');
    if (c.endereco) document.getElementById('endereco').value = c.endereco;
    if (c.bairro) {
        const sel = document.getElementById('bairro');
        const opt = Array.from(sel.options).find(o => o.value.toLowerCase()===c.bairro.toLowerCase());
        if (opt) { sel.value=opt.value; buscarTaxaEntrega(); }
    }
}

function limparCliente() {
    clienteSelecionado=null;
    document.getElementById('cliente-busca').value='';
    document.getElementById('cliente-selecionado').classList.add('d-none');
    document.getElementById('endereco').value='';
    document.getElementById('bairro').value='';
    taxaEntrega=0; atualizarTotais();
}

function criarNovoCliente() {
    const nome = document.getElementById('novo-cliente-nome').value.trim();
    const resultDiv = document.getElementById('novo-cliente-result');
    if (!nome) { resultDiv.innerHTML='<div class="alert alert-danger py-1">Nome é obrigatório</div>'; return; }
    fetch('<?= site_url('admin/venda-especifica/criar-cliente') ?>', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ nome, telefone:document.getElementById('novo-cliente-telefone').value.trim(),
            endereco:document.getElementById('novo-cliente-endereco').value.trim(),
            bairro:document.getElementById('novo-cliente-bairro').value.trim(),
            cidade:document.getElementById('novo-cliente-cidade').value.trim() })
    }).then(r=>r.json()).then(data => {
        if (data.success) { resultDiv.innerHTML='<div class="alert alert-success py-1">'+data.message+'</div>'; selecionarCliente(data.cliente); setTimeout(()=>new bootstrap.Tab(document.getElementById('existing-tab')).show(),1200); }
        else resultDiv.innerHTML='<div class="alert alert-danger py-1">'+data.message+'</div>';
    });
}

function carregarBairros() {
    fetch('<?= site_url('admin/venda-especifica/bairros') ?>').then(r=>r.json()).then(data => {
        bairrosData = data.data||[];
        const sel = document.getElementById('bairro');
        sel.innerHTML = '<option value="">-- Selecione --</option>';
        bairrosData.forEach(b => {
            const taxa = b.taxa_entrega>0?'R$ '+parseFloat(b.taxa_entrega).toFixed(2).replace('.',','):'Grátis';
            sel.innerHTML += `<option value="${b.nome}" data-taxa="${b.taxa_entrega}" data-ativo="${b.ativo}">${b.nome} - ${taxa}${b.ativo==0?' (inativo)':''}</option>`;
        });
    });
}

function buscarTaxaEntrega() {
    const bairro = document.getElementById('bairro').value;
    const aviso = document.getElementById('aviso-cobertura');
    if (!bairro) { taxaEntrega=0; aviso.classList.add('d-none'); atualizarTotais(); return; }
    const b = bairrosData.find(x=>x.nome.toLowerCase()===bairro.toLowerCase());
    if (b&&b.ativo==1) { taxaEntrega=parseFloat(b.taxa_entrega)||0; aviso.classList.add('d-none'); }
    else { taxaEntrega=0; aviso.classList.remove('d-none'); }
    atualizarTotais();
}

function carregarMesas() {
    fetch('<?= site_url('admin/venda-especifica/mesas') ?>').then(r=>r.json()).then(data => {
        const sel = document.getElementById('mesa_id');
        sel.innerHTML = '<option value="">-- Sem mesa --</option>';
        (data.data||[]).forEach(m => sel.innerHTML += `<option value="${m.id}" ${m.ocupado==1?'style="color:#aaa;"':''}>Mesa ${m.numero}${m.ocupado==1?' (ocupada)':''}</option>`);
    });
}
</script>

<script>
function carregarProdutos() {
    fetch('<?= site_url('admin/venda-especifica/produtos') ?>').then(r=>r.json()).then(data => {
        produtosData = data.data||[];
        // Inicializar autocomplete de produto
        const buscaInput = document.getElementById('produto-busca');
        let debounce;
        buscaInput.addEventListener('input', function () {
            clearTimeout(debounce);
            debounce = setTimeout(() => filtrarProdutos(this.value.trim()), 150);
        });
        document.addEventListener('click', e => {
            if (!e.target.closest('#produto-busca') && !e.target.closest('#produto-autocomplete'))
                document.getElementById('produto-autocomplete').style.display = 'none';
        });
    });
}

function filtrarProdutos(termo) {
    const box = document.getElementById('produto-autocomplete');
    if (!termo) { box.style.display='none'; return; }
    const filtrados = produtosData.filter(p => p.nome.toLowerCase().includes(termo.toLowerCase())).slice(0, 15);
    if (!filtrados.length) { box.style.display='none'; return; }
    box.innerHTML = filtrados.map(p => {
        const preco = parseFloat(p.preco).toFixed(2).replace('.',',');
        return `<button type="button" class="list-group-item list-group-item-action bg-dark text-light border-secondary py-2"
            onclick="selecionarProduto(${p.id})">
            <span>${p.nome}</span> <small class="text-warning float-end">R$ ${preco}</small>
        </button>`;
    }).join('');
    box.style.display = 'block';
}

function selecionarProduto(id) {
    const produto = produtosData.find(p => p.id == id);
    if (!produto) return;
    // Atualizar input visível
    document.getElementById('produto-busca').value = produto.nome;
    document.getElementById('produto-autocomplete').style.display = 'none';
    // Sincronizar select oculto (dispara o change listener existente)
    const sel = document.getElementById('produto-select');
    // Garantir que a option existe
    let opt = sel.querySelector(`option[value="${produto.id}"]`);
    if (!opt) {
        opt = document.createElement('option');
        opt.value = produto.id;
        opt.dataset.nome = produto.nome;
        opt.dataset.preco = produto.preco;
        opt.dataset.categoria = produto.categoria_id || '';
        sel.appendChild(opt);
    }
    sel.value = produto.id;
    sel.dispatchEvent(new Event('change'));
}

let _extrasAbort = null;

function carregarExtrasProduto(produtoId) {
    if (_extrasAbort) _extrasAbort.abort();
    _extrasAbort = new AbortController();
    fetch('<?= site_url('admin/venda-especifica/produto-extras') ?>/'+produtoId, {signal: _extrasAbort.signal})
        .then(r=>r.json()).then(data => {
        extrasProdutoAtual = data.extras||[];
        extrasQtd = {};
        configExtrasProduto = {obrigatorio_extras:data.obrigatorio_extras||0,max_extras:data.max_extras||0};
        extrasPage=0; renderizarExtras();
        // Carregar sachês se ainda não carregados (primeira seleção)
        if (!sachesVE.length) {
            const sel = document.getElementById('produto-select');
            const catId = sel.options[sel.selectedIndex]?.dataset.categoria;
            if (catId) carregarSachesVE([catId]);
        }
        document.getElementById('extras-saches-container').style.display='block';
    }).catch(e=>{ if(e.name!=='AbortError') console.error(e); });
}

function renderizarExtras() {
    const total=extrasProdutoAtual.length, pages=Math.ceil(total/ROWS_PER_PAGE);
    const slice=extrasProdutoAtual.slice(extrasPage*ROWS_PER_PAGE,extrasPage*ROWS_PER_PAGE+ROWS_PER_PAGE);
    let info='';
    if (configExtrasProduto.obrigatorio_extras>0) info+=`<span class="badge bg-danger">${configExtrasProduto.obrigatorio_extras} obrigatório(s)</span> `;
    if (configExtrasProduto.max_extras>0) info+=`<span class="badge bg-info">Máx: ${configExtrasProduto.max_extras}</span>`;
    document.getElementById('extras-info').innerHTML=info||'<span class="text-muted">Opcionais</span>';
    let html='';
    if (!slice.length) html='<p class="text-muted text-center small mb-0">Nenhum extra</p>';
    else slice.forEach(e => {
        const preco=parseFloat(e.preco).toFixed(2).replace('.',',');
        const qtdMem=extrasQtd[e.id]||0;
        html+=`<div class="d-flex justify-content-between align-items-center mb-2 p-2" style="background:#111;border-radius:6px;border:1px solid #333;">
            <div><span class="text-light small">${e.nome}</span>${e.descricao?`<small class="text-muted d-block">${e.descricao}</small>`:''}<small class="text-warning">+ R$ ${preco}</small></div>
            <div class="d-flex align-items-center gap-1">${e.multitude
                ?`<button type="button" class="btn btn-sm btn-secondary" onclick="alterarQtdExtra(${e.id},-1)">−</button><input type="number" id="extra-qtd-${e.id}" class="form-control form-control-sm bg-dark text-light text-center" value="${qtdMem}" min="0" style="width:55px;"><button type="button" class="btn btn-sm btn-secondary" onclick="alterarQtdExtra(${e.id},1)">+</button>`
                :`<input type="checkbox" class="form-check-input" id="extra-check-${e.id}" ${qtdMem?'checked':''} onchange="toggleExtra(${e.id})">`
            }</div></div>`;
    });
    document.getElementById('extras-list').innerHTML=html;
    let pag='';
    if (pages>1) for(let i=0;i<pages;i++) pag+=`<button type="button" class="btn btn-sm ${i===extrasPage?'btn-warning':'btn-outline-secondary'}" onclick="irPaginaExtras(${i})">${i+1}</button>`;
    document.getElementById('extras-paginacao').innerHTML=pag;
    const badge=document.getElementById('extras-badge');
    if (total>0){badge.textContent=total;badge.style.display='';}else badge.style.display='none';
}
function irPaginaExtras(p){extrasPage=p;renderizarExtras();}
function alterarQtdExtra(id,delta){
    extrasQtd[id]=(extrasQtd[id]||0)+delta;
    if(extrasQtd[id]<0)extrasQtd[id]=0;
    const inp=document.getElementById(`extra-qtd-${id}`);
    if(inp)inp.value=extrasQtd[id];
}
function toggleExtra(id){
    extrasQtd[id]=document.getElementById(`extra-check-${id}`)?.checked?1:0;
    const total=extrasProdutoAtual.filter(e=>!e.multitude&&(extrasQtd[e.id]||0)>0).length;
    if(configExtrasProduto.max_extras>0&&total>configExtrasProduto.max_extras){
        extrasQtd[id]=0;
        document.getElementById(`extra-check-${id}`).checked=false;
        alert(`Máximo de ${configExtrasProduto.max_extras} extra(s)`);
    }
}

function carregarSachesVE(categoriaIds) {
    if (!categoriaIds||!categoriaIds.length){sachesVE=[];sachesQtd={};_ultimasCategorias='';renderizarSachesVE();return;}
    const chave = categoriaIds.slice().sort().join(',');
    if (chave === _ultimasCategorias) { renderizarSachesVE(); return; }
    if (_sachesCarregando) return; // evitar fetches concorrentes
    _sachesCarregando = true;
    _ultimasCategorias = chave;
    fetch('<?= site_url('api/saches/disponiveis') ?>',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({categoria_ids:categoriaIds})})
        .then(r=>r.json()).then(data=>{
            const novos = data.saches||[];
            const novoIds = new Set(novos.map(s=>s.id));
            Object.keys(sachesQtd).forEach(id=>{ if(!novoIds.has(parseInt(id))) delete sachesQtd[id]; });
            novos.forEach(s=>{
                if(sachesQtd[s.id]===undefined){
                    const qtdInicial=parseInt(s.qtd_inicial)||0;
                    const limite=calcularLimiteSache(s);
                    sachesQtd[s.id]=Math.min(qtdInicial,limite);
                }
            });
            sachesVE=novos; sachesPage=0; renderizarSachesVE();
        })
        .catch(()=>{sachesVE=[];sachesQtd={};_ultimasCategorias='';renderizarSachesVE();})
        .finally(()=>{ _sachesCarregando=false; });
}

function calcularLimiteSache(s) {
    if (s.limite_tipo==='fixo') return parseInt(s.limite_fixo)||1;
    const sub=itensVenda.reduce((a,i)=>a+i.total,0);
    const porValor=parseFloat(s.limite_por_valor)||0;
    return (parseInt(s.limite_minimo)||0)+(porValor>0?Math.floor(sub/porValor):0);
}

function agruparSaches() {
    const grupos={};
    sachesVE.forEach(s=>{
        const g=s.categoria_sache||'Sem grupo';
        if(!grupos[g]) grupos[g]={nome:g,qtd_inicial:parseInt(s.qtd_inicial)||0,qtd_max:s.qtd_max?parseInt(s.qtd_max):null,itens:[]};
        grupos[g].itens.push(s);
    });
    return Object.values(grupos);
}

function _setSachesTabVisible(visible) {
    const tabEl = document.getElementById('tab-saches');
    const navEl = document.getElementById('nav-saches');
    if (tabEl) tabEl.classList.toggle('ve-tab-hidden', !visible);
    if (navEl) navEl.style.display = visible ? '' : 'none';
}

function renderizarSachesVE() {
    const badge=document.getElementById('saches-badge'),container=document.getElementById('saches-lista-ve'),pagDiv=document.getElementById('saches-paginacao');
    if (!sachesVE.length){_setSachesTabVisible(false);if(badge)badge.style.display='none';if(container)container.innerHTML='';if(pagDiv)pagDiv.innerHTML='';return;}
    _setSachesTabVisible(true);
    badge.textContent=sachesVE.length;badge.style.display='';
    const grupos=agruparSaches(),pages=grupos.length;
    if (sachesPage>=pages) sachesPage=0;
    const grupo=grupos[sachesPage]??grupos[0];
    if (!grupo) return;
    let html=`<div class="mb-1 px-1 d-flex align-items-center gap-2"><span class="badge bg-secondary">${grupo.nome}</span>${grupo.qtd_max?`<small class="text-muted">máx ${grupo.qtd_max}</small>`:''}</div>`;
    grupo.itens.forEach(s=>{
        const limite=calcularLimiteSache(s);
        if (sachesQtd[s.id]===undefined) sachesQtd[s.id]=0;
        const qtdAtual=sachesQtd[s.id];
        const qtdMax=grupo.qtd_max;
        const preco=parseFloat(s.preco)>0?`<small class="text-warning ms-1">+R$ ${parseFloat(s.preco).toFixed(2).replace('.',',')}</small>`:`<small class="text-success ms-1">Grátis</small>`;
        const limiteLabel=s.limite_tipo==='personalizado'?`${parseInt(s.limite_minimo)||0} grátis +1/R$${parseFloat(s.limite_por_valor||0).toFixed(0)}`:`Limite gratuito: ${limite}`;
        html+=`<div class="d-flex justify-content-between align-items-center mb-1 p-2" style="background:#111;border-radius:6px;border:1px solid #333;">
            <div><span class="text-light small">${s.nome}</span>${preco}<small class="text-muted d-block">${limiteLabel}</small></div>
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-sm btn-secondary" onclick="alterarSacheVE(${s.id},-1)">−</button>
                <span id="sache-ve-qtd-${s.id}" style="min-width:22px;text-align:center;color:#fff;">${qtdAtual}</span>
                <button type="button" class="btn btn-sm btn-secondary" onclick="alterarSacheVE(${s.id},1)">+</button>
            </div></div>`;
    });
    container.innerHTML=html;
    let pag='';
    if (pages>1) grupos.forEach((g,i)=>pag+=`<button type="button" class="btn btn-sm ${i===sachesPage?'btn-warning':'btn-outline-secondary'}" onclick="irPaginaSaches(${i})">${g.nome}</button>`);
    pagDiv.innerHTML=pag;
}
function irPaginaSaches(p){sachesPage=p;renderizarSachesVE();}
function alterarSacheVE(id,delta){
    const s=sachesVE.find(x=>x.id==id);
    // qtd_max é do grupo — verificar total do grupo
    const grupo=agruparSaches().find(g=>g.itens.some(x=>x.id==id));
    const qtdMax=grupo?grupo.qtd_max:null;
    let nova=Math.max(0,(sachesQtd[id]||0)+delta);
    if(qtdMax&&delta>0){
        const totalGrupo=grupo.itens.reduce((t,x)=>t+(sachesQtd[x.id]||0),0);
        if(totalGrupo>=qtdMax) return; // já atingiu o máximo do grupo
    }
    sachesQtd[id]=nova;
    const span=document.getElementById(`sache-ve-qtd-${id}`);
    if(span) span.textContent=nova;
    atualizarTotais();
}
function getSachesVE(){
    // Usar sachesVE se disponível para ter nome/preco; fallback para sachesQtd puro
    const vistos = new Set();
    const resultado = [];
    if (sachesVE.length) {
        sachesVE.forEach(s=>{
            if (vistos.has(s.id)) return;
            vistos.add(s.id);
            const qtd = sachesQtd[s.id]||0;
            if (qtd > 0) resultado.push({id:s.id, nome:s.nome, preco:parseFloat(s.preco), quantidade:qtd});
        });
    } else {
        // sachesVE não carregado ainda — enviar apenas id e quantidade
        Object.entries(sachesQtd).forEach(([id, qtd])=>{
            if (qtd > 0) resultado.push({id:parseInt(id), nome:'', preco:0, quantidade:qtd});
        });
    }
    return resultado;
}
</script>

<script>
function adicionarProduto() {
    const sel=document.getElementById('produto-select'),qtd=parseInt(document.getElementById('produto-qtd').value)||1;
    if (!sel.value){alert('Selecione um produto');return;}
    const nome=sel.options[sel.selectedIndex].dataset.nome;
    let preco=parseFloat(sel.options[sel.selectedIndex].dataset.preco),tamanhoId=null,tamanhoNome=null;
    const produto=produtosData.find(p=>p.id==sel.value);
    if (produto?.tamanhos?.length>0){
        const tamSel=document.getElementById('produto-tamanho');
        if (!tamSel.value){alert('Selecione o tamanho');return;}
        preco=parseFloat(tamSel.options[tamSel.selectedIndex].dataset.preco);
        tamanhoId=tamSel.value;tamanhoNome=tamSel.options[tamSel.selectedIndex].text.split(' - ')[0];
    }
    const extras=[];
    extrasProdutoAtual.forEach(e=>{
        if(e.multitude){const q=extrasQtd[e.id]||0;if(q>0)extras.push({id:e.id,nome:e.nome,preco:e.preco,quantidade:q});}
        else{if(extrasQtd[e.id])extras.push({id:e.id,nome:e.nome,preco:e.preco,quantidade:1});}
    });
    if (configExtrasProduto.obrigatorio_extras>0&&extras.length<configExtrasProduto.obrigatorio_extras){alert(`Selecione pelo menos ${configExtrasProduto.obrigatorio_extras} extra(s)`);return;}
    const precoExtras=extras.reduce((s,e)=>s+e.preco*e.quantidade,0);
    itensVenda.push({id:sel.value,nome,preco,quantidade:qtd,extras,total:(preco+precoExtras)*qtd,categoria_id:sel.options[sel.selectedIndex].dataset.categoria||null,tamanho_id:tamanhoId,tamanho_nome:tamanhoNome,observacoes:''});
    sel.value='';document.getElementById('produto-qtd').value=1;
    document.getElementById('produto-busca').value='';
    document.getElementById('row-tamanho').style.setProperty('display','none','important');
    document.getElementById('produto-tamanho').innerHTML='';document.getElementById('produto-preco-display').value='';
    document.getElementById('extras-saches-container').style.display='none';
    extrasProdutoAtual=[];extrasQtd={};atualizarTabela();
}

function removerProduto(idx){
    const item = itensVenda[idx];
    if (item._comanda_item_id && comandaAtiva) {
        if (!confirm('Remover este item da comanda?')) return;
        fetch('<?= site_url('admin/venda-especifica/remover-item-comanda') ?>', {
            method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify({item_id: item._comanda_item_id, pedido_id: comandaAtiva})
        }).then(r=>r.json()).then(data => {
            if (data.success) { itensVenda.splice(idx,1); atualizarTabela(); }
        });
    } else {
        itensVenda.splice(idx,1); atualizarTabela();
    }
}

function atualizarTabela() {
    const tbody=document.getElementById('lista-itens');
    if (!itensVenda.length){
        tbody.innerHTML='<tr id="sem-itens"><td colspan="5" class="text-center text-muted py-3 small">Nenhum produto</td></tr>';
    } else {
        tbody.innerHTML=itensVenda.map((item,i)=>{
            const extrasHtml=item.extras?.length?`<br><small class="text-muted">+${item.extras.map(e=>`${e.nome}(${e.quantidade}x)`).join(', ')}</small>`:'';
            const tamHtml=item.tamanho_nome?`<br><small class="text-info">${item.tamanho_nome}</small>`:'';
            const btnExtras = item.id ? `<button class="btn btn-outline-info btn-sm py-0 px-1" onclick="abrirEditarExtras(${i})" title="Extras"><i class="fas fa-list-ul"></i></button> ` : '';
            return `<tr>
                <td class="small ps-2">${item.nome}${tamHtml}${extrasHtml}</td>
                <td class="text-center" style="width:90px;">
                  <div class="d-flex align-items-center gap-1 justify-content-center">
                    <button class="btn btn-secondary btn-sm py-0 px-1" onclick="alterarQtdItem(${i},-1)">−</button>
                    <span>${item.quantidade}</span>
                    <button class="btn btn-secondary btn-sm py-0 px-1" onclick="alterarQtdItem(${i},1)">+</button>
                  </div>
                </td>
                <td class="text-end">R$ ${item.total.toFixed(2).replace('.',',')}</td>
                <td class="pe-2 text-end">${btnExtras}<button class="btn btn-danger btn-sm py-0 px-1" onclick="removerProduto(${i})"><i class="fas fa-times"></i></button></td>
            </tr>`;
        }).join('');
    }
    atualizarTotais();
    const cats=[...new Set(itensVenda.map(i=>i.categoria_id).filter(Boolean))];
    if (cats.length) carregarSachesVE(cats);
    else if (sachesVE.length) renderizarSachesVE(); // recalcular limites sem novo fetch
}

function alterarQtdItem(idx, delta) {
    const item = itensVenda[idx];
    const novaQtd = Math.max(1, item.quantidade + delta);
    const precoExtras = (item.extras||[]).reduce((s,e)=>s+e.preco*e.quantidade,0);
    item.quantidade = novaQtd;
    item.total = (item.preco + precoExtras) * novaQtd;
    if (item._comanda_item_id && comandaAtiva) {
        fetch('<?= site_url('admin/venda-especifica/alterar-qtd-item-comanda') ?>', {
            method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify({item_id: item._comanda_item_id, pedido_id: comandaAtiva, quantidade: novaQtd})
        });
    }
    atualizarTabela();
}

function getSachesTotalPago() {
    return sachesVE.reduce((s, sache) => {
        const preco = parseFloat(sache.preco) || 0;
        if (preco <= 0) return s;
        const limite = calcularLimiteSache(sache);
        const qtd = sachesQtd[sache.id] || 0;
        return s + Math.max(0, qtd - limite) * preco;
    }, 0);
}

function atualizarTotais() {
    const sub=itensVenda.reduce((s,i)=>s+i.total,0);
    const sachePago=getSachesTotalPago();
    document.getElementById('subtotal').textContent='R$ '+sub.toFixed(2).replace('.',',');
    document.getElementById('taxa-entrega').textContent='R$ '+taxaEntrega.toFixed(2).replace('.',',');
    document.getElementById('valor-total').textContent='R$ '+(sub+taxaEntrega+sachePago).toFixed(2).replace('.',',');
    const label=itensVenda.length+' iten'+(itensVenda.length===1?'':'s')+' · R$ '+(sub+taxaEntrega+sachePago).toFixed(2).replace('.',',');
    document.getElementById('badge-itens').textContent=label;
    if (document.getElementById('badge-itens-2')) document.getElementById('badge-itens-2').textContent=itensVenda.length;
    if (document.getElementById('forma_pagamento').value==='dinheiro') _atualizarMinimoTroco();
    if (document.getElementById('forma_pagamento_comanda')?.value==='dinheiro') _atualizarMinimoTrocoComanda();
}

function limparFormulario() {
    itensVenda=[];taxaEntrega=0;extrasProdutoAtual=[];extrasQtd={};clienteSelecionado=null;
    sachesVE=[];sachesQtd={};_ultimasCategorias='';_sachesCarregando=false;
    document.getElementById('extras-saches-container').style.display='none';
    document.getElementById('produto-busca').value='';
    document.getElementById('row-tamanho').style.setProperty('display','none','important');
    document.getElementById('produto-tamanho').innerHTML='';document.getElementById('produto-preco-display').value='';
    document.getElementById('cliente-busca').value='';document.getElementById('cliente-selecionado').classList.add('d-none');
    document.getElementById('endereco').value='';document.getElementById('bairro').value='';document.getElementById('mesa_id').value='';
    document.getElementById('aviso-cobertura').classList.add('d-none');document.getElementById('novo-cliente-result').innerHTML='';
    ['novo-cliente-nome','novo-cliente-telefone','novo-cliente-endereco','novo-cliente-bairro','novo-cliente-cidade'].forEach(id=>document.getElementById(id).value='');
    document.getElementById('forma_pagamento').value='dinheiro';document.getElementById('observacoes').value='';
    atualizarTabela();
}

function toggleTrocoVE() {
    const isDinheiro = document.getElementById('forma_pagamento').value === 'dinheiro';
    document.getElementById('campo-troco-ve').style.display = isDinheiro ? 'block' : 'none';
    if (isDinheiro) _atualizarMinimoTroco();
}

function toggleTrocoComanda() {
    const isDinheiro = document.getElementById('forma_pagamento_comanda').value === 'dinheiro';
    document.getElementById('campo-troco-comanda').style.display = isDinheiro ? 'block' : 'none';
    if (isDinheiro) _atualizarMinimoTrocoComanda();
}

function _atualizarMinimoTroco() {
    const total = parseFloat(document.getElementById('valor-total').textContent.replace('R$ ','').replace(',','.')) || 0;
    const inp = document.getElementById('troco_para_ve');
    inp.min = total.toFixed(2);
    document.getElementById('troco-minimo-ve').textContent = 'Mínimo: R$ ' + total.toFixed(2).replace('.',',');
}

function _atualizarMinimoTrocoComanda() {
    const sub=itensVenda.reduce((s,i)=>s+i.total,0);
    const total=(sub+taxaEntrega+getSachesTotalPago());
    const inp = document.getElementById('troco_para_comanda');
    inp.min = total.toFixed(2);
    document.getElementById('troco-minimo-comanda').textContent = 'Mínimo: R$ ' + total.toFixed(2).replace('.',',');
}

function _coletarPayload() {
    const formaPag = document.getElementById('forma_pagamento').value;
    const troco = formaPag === 'dinheiro' ? (parseFloat(document.getElementById('troco_para_ve').value) || 0) : null;
    return {
        nome_cliente:    clienteSelecionado?.nome||'',
        telefone:        clienteSelecionado?.telefone||'',
        endereco:        document.getElementById('endereco').value,
        bairro:          document.getElementById('bairro').value,
        forma_pagamento: formaPag,
        troco_para:      troco,
        observacoes:     document.getElementById('observacoes').value,
        tipo_entrega:    document.querySelector('input[name="tipo_entrega"]:checked').value,
        mesa_id:         document.getElementById('mesa_id').value||null,
        itens:           itensVenda,
        saches:          getSachesVE()
    };
}

function _validarFormulario(exigirItens=true) {
    if (!clienteSelecionado){alert('Selecione ou cadastre um cliente');return false;}
    if (exigirItens&&!itensVenda.length){alert('Adicione pelo menos um produto');return false;}
    const tipo=document.querySelector('input[name="tipo_entrega"]:checked').value;
    if (tipo==='entrega'&&!document.getElementById('bairro').value){alert('Selecione o bairro');return false;}
    if (document.getElementById('forma_pagamento').value === 'dinheiro') {
        const total = parseFloat(document.getElementById('valor-total').textContent.replace('R$ ','').replace(',','.')) || 0;
        const troco = parseFloat(document.getElementById('troco_para_ve').value) || 0;
        if (troco < total) { alert('O valor do troco deve ser maior ou igual ao total: R$ ' + total.toFixed(2).replace('.',',')); return false; }
    }
    return true;
}

function _enviarPedido(url,btnId) {
    if (!_validarFormulario()) return;
    const btn=document.getElementById(btnId),orig=btn.innerHTML;
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
    fetch(url,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(_coletarPayload())})
        .then(r=>r.json()).then(data=>{
            if (data.success){alert(data.message+'\nCódigo: '+data.codigo);window.location.href='<?= site_url('admin/pedidos') ?>/'+data.pedido_id;}
            else{alert('Erro: '+data.message);btn.disabled=false;btn.innerHTML=orig;}
        }).catch(()=>{alert('Erro');btn.disabled=false;btn.innerHTML=orig;});
}

function finalizarVenda(){_enviarPedido('<?= site_url('admin/venda-especifica/criar') ?>','btn-finalizar');}

function abrirComanda() {
    if (!clienteSelecionado){alert('Selecione ou cadastre um cliente');return;}
    const btn=document.getElementById('btn-pausar'),orig=btn.innerHTML;
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
    fetch('<?= site_url('admin/venda-especifica/abrir-comanda') ?>',{
        method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(_coletarPayload())
    }).then(r=>r.json()).then(data=>{
        if (data.success){
            alert('Comanda aberta! Código: '+data.codigo);
            limparFormulario();carregarComandas();mudarAba('comandas');
        } else{alert('Erro: '+data.message);}
        btn.disabled=false;btn.innerHTML=orig;
    }).catch(()=>{alert('Erro');btn.disabled=false;btn.innerHTML=orig;});
}

// ── Comandas em Aberto ──
function carregarComandas() {
    fetch('<?= site_url('admin/venda-especifica/comandas-abertas') ?>').then(r=>r.json()).then(data=>{
        const painel=document.getElementById('painel-comandas'),comandas=data.data||[];
        document.getElementById('detalhe-comanda').style.display='none';
        if (!comandas.length){painel.innerHTML='<p class="text-muted text-center small py-3 mb-0">Nenhuma comanda em aberto</p>';return;}
        painel.innerHTML=`<div class="row g-2">${comandas.map(c=>`
            <div class="col-6 col-md-4">
              <div class="comanda-card">
                <div class="d-flex justify-content-between">
                  <strong class="text-warning">#${c.id}</strong>
                  <small class="text-muted">${new Date(c.criado_em).toLocaleTimeString('pt-BR',{hour:'2-digit',minute:'2-digit'})}</small>
                </div>
                <div class="text-light small text-truncate">${c.nome_cliente}</div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                  <span class="text-success small">R$ ${parseFloat(c.valor_total).toFixed(2).replace('.',',')}</span>
                  <small class="text-muted">${c.total_itens} iten${c.total_itens==1?'':'s'}</small>
                </div>
                <div class="d-flex gap-1 mt-2">
                  <button class="btn btn-outline-warning btn-sm flex-grow-1 py-1" onclick="verDetalheComanda(${c.id},'${c.nome_cliente.replace(/'/g,"\\'")}')">
                    <i class="fas fa-eye"></i> Ver
                  </button>
                  <button class="btn btn-warning btn-sm flex-grow-1 py-1" onclick="retomarComanda(${c.id},'${c.nome_cliente.replace(/'/g,"\\'")}')">
                    <i class="fas fa-plus"></i> Add
                  </button>
                </div>
              </div>
            </div>`).join('')}
        </div>`;
    });
}

let comandaDetalheId = null;

function verDetalheComanda(id, nome) {
    comandaDetalheId = id;
    document.getElementById('detalhe-comanda-id').textContent = '#'+id+' — '+nome;
    document.getElementById('detalhe-comanda').style.display = 'block';
    document.getElementById('btn-fechar-comanda-detalhe').onclick = () => finalizarComanda(id);
    document.getElementById('detalhe-itens').innerHTML = '<p class="text-muted small text-center py-2">Carregando...</p>';
    fetch('<?= site_url('admin/venda-especifica/itens-comanda') ?>/'+id)
        .then(r=>r.json()).then(data => renderizarItensComanda(data.data||[], id, data.saches||[]));
}

function renderizarItensComanda(itens, pedidoId, saches) {
    const div = document.getElementById('detalhe-itens');
    if (!itens.length) { div.innerHTML='<p class="text-muted small text-center py-2">Sem itens ainda</p>'; return; }
    let html = `<table class="table table-dark table-sm mb-0">
        <tbody>${itens.map(item=>`
        <tr>
          <td class="small">${item.produto_nome}</td>
          <td class="text-center" style="width:100px;">
            <div class="d-flex align-items-center gap-1 justify-content-center">
              <button class="btn btn-secondary btn-sm py-0 px-1" onclick="alterarQtdComanda(${item.id},${pedidoId},${item.quantidade},-1)">−</button>
              <span id="qtd-ci-${item.id}">${item.quantidade}</span>
              <button class="btn btn-secondary btn-sm py-0 px-1" onclick="alterarQtdComanda(${item.id},${pedidoId},${item.quantidade},1)">+</button>
            </div>
          </td>
          <td class="text-end small" id="total-ci-${item.id}">R$ ${parseFloat(item.preco_total).toFixed(2).replace('.',',')}</td>
          <td><button class="btn btn-danger btn-sm py-0 px-1" onclick="removerItemComanda(${item.id},${pedidoId})"><i class="fas fa-times"></i></button></td>
        </tr>`).join('')}
        </tbody>
    </table>`;
    if (saches && saches.length) {
        html += `<div class="mt-2 px-1"><small class="text-warning"><i class="fas fa-box"></i> Sachês:</small>
        <div class="d-flex flex-wrap gap-1 mt-1">${saches.map(s=>{
            const qtdPaga = Math.max(0, parseInt(s.quantidade) - parseInt(s.quantidade_gratuita || 0));
            const custoExtra = qtdPaga > 0 ? ` <span class="text-warning">+R$${parseFloat(s.preco_total || 0).toFixed(2).replace('.',',')}</span>` : ' <span class="text-success">Grátis</span>';
            return `<span class="badge bg-secondary">${s.nome} ×${s.quantidade}${custoExtra}</span>`;
        }).join('')}</div></div>`;
    }
    div.innerHTML = html;
}

function alterarQtdComanda(itemId, pedidoId, qtdAtual, delta) {
    const novaQtd = Math.max(1, qtdAtual + delta);
    fetch('<?= site_url('admin/venda-especifica/alterar-qtd-item-comanda') ?>', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({item_id: itemId, pedido_id: pedidoId, quantidade: novaQtd})
    }).then(r=>r.json()).then(data => {
        if (data.success) verDetalheComanda(pedidoId, document.getElementById('detalhe-comanda-id').textContent.split(' — ')[1]||'');
    });
}

function removerItemComanda(itemId, pedidoId) {
    if (!confirm('Remover este item?')) return;
    fetch('<?= site_url('admin/venda-especifica/remover-item-comanda') ?>', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({item_id: itemId, pedido_id: pedidoId})
    }).then(r=>r.json()).then(data => {
        if (data.success) { verDetalheComanda(pedidoId, ''); carregarComandas(); }
    });
}

function fecharDetalheComanda() {
    document.getElementById('detalhe-comanda').style.display='none';
    comandaDetalheId = null;
}

function retomarComandaAtual() {
    const id = comandaDetalheId;
    const nome = document.getElementById('detalhe-comanda-id').textContent.replace(/^#\d+ — /,'');
    fecharDetalheComanda();
    retomarComanda(id, nome);
}

function retomarComanda(id,nomeCliente) {
    comandaAtiva=id;
    _modoComanda(true, id, nomeCliente);
    limparFormulario(); mudarAba('produtos');
    // Carregar itens existentes da comanda
    fetch('<?= site_url('admin/venda-especifica/itens-comanda') ?>/'+id)
        .then(r=>r.json()).then(data=>{
            (data.data||[]).forEach(item=>{
                itensVenda.push({
                    id: item.produto_id||null,
                    nome: item.produto_nome,
                    preco: parseFloat(item.preco_unitario),
                    quantidade: parseInt(item.quantidade),
                    extras: (item.extras||[]).map(e=>({id:e.extra_id,nome:e.nome,preco:parseFloat(e.preco),quantidade:parseInt(e.quantidade)})),
                    total: parseFloat(item.preco_total),
                    categoria_id: item.categoria_id||null,
                    tamanho_id: null,
                    tamanho_nome: null,
                    observacoes: item.observacoes||'',
                    _comanda_item_id: item.id
                });
            });
            atualizarTabela();
            // Carregar sachês salvos na comanda
            const sachesComanda = data.saches||[];
            if (sachesComanda.length) _aplicarSachesComanda(sachesComanda);
        });
}

function cancelarEdicaoComanda() {
    comandaAtiva=null;
    _modoComanda(false);
    location.reload();
}

function _modoComanda(ativo, id, nome) {
    // Bloquear aba cliente (mobile: opacity; desktop: ocultar)
    const navCliente = document.getElementById('nav-cliente');
    navCliente.style.opacity = ativo ? '0.4' : '';
    navCliente.style.pointerEvents = ativo ? 'none' : '';
    document.getElementById('tab-cliente').classList.toggle('comanda-ativa-desktop', ativo);

    // Alternar seções na aba Fechar Conta
    document.getElementById('secao-novo-pedido').style.display = ativo ? 'none' : '';
    document.getElementById('secao-fechar-comanda').style.display = ativo ? 'block' : 'none';
    if (ativo) {
        document.getElementById('fechar-comanda-label').textContent = '#'+id+' — '+nome;
        document.getElementById('btn-finalizar-comanda').onclick = () => finalizarComanda(id);
    }

    // Banner
    document.getElementById('banner-comanda-id').textContent = ativo ? '#'+id : '';
    document.getElementById('banner-comanda-nome').textContent = ativo ? nome : '';
    document.getElementById('banner-comanda').style.display = ativo ? 'flex' : 'none';

    // Botões na aba Produtos
    const btnPausar=document.getElementById('btn-pausar'), btnFinalizar=document.getElementById('btn-finalizar');
    if (ativo) {
        btnPausar.innerHTML='<i class="fas fa-plus"></i> Adicionar à Comanda';
        btnPausar.style.background='#1a7a1a'; btnPausar.style.borderColor='#1a7a1a';
        btnPausar.onclick=adicionarNaComanda;
        btnFinalizar.innerHTML='<i class="fas fa-check-circle"></i> Fechar Comanda';
        btnFinalizar.onclick=()=>finalizarComanda(id);
    } else {
        btnPausar.innerHTML='<i class="fas fa-folder-open"></i> Deixar em Aberto';
        btnPausar.style.background='#c47a00'; btnPausar.style.borderColor='#c47a00';
        btnPausar.onclick=abrirComanda;
        btnFinalizar.innerHTML='<i class="fas fa-check-circle"></i> Finalizar';
        btnFinalizar.onclick=finalizarVenda;
    }
}

let _finalizandoComanda = false;

function adicionarNaComanda() {
    const novosItens = itensVenda.filter(i => !i._comanda_item_id);
    if (!novosItens.length){alert('Adicione pelo menos um produto novo');return;}
    if (_finalizandoComanda) return;
    _finalizandoComanda = true;
    const btn=document.getElementById('btn-pausar'),orig=btn.innerHTML;
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
    fetch('<?= site_url('admin/venda-especifica/adicionar-item-comanda') ?>',{
        method:'POST',headers:{'Content-Type':'application/json'},
        body:JSON.stringify({pedido_id:comandaAtiva,itens:novosItens,saches:getSachesVE()})
    }).then(r=>r.json()).then(data=>{
        if (data.success){alert(data.message);cancelarEdicaoComanda();}
        else alert('Erro: '+data.message);
        btn.disabled=false;btn.innerHTML=orig;
    }).catch(()=>{alert('Erro');btn.disabled=false;btn.innerHTML=orig;})
    .finally(()=>{ _finalizandoComanda=false; });
}

function continuarEmAberto() {
    const novosItens = itensVenda.filter(i => !i._comanda_item_id);
    const sachesAtuais = getSachesVE();
    if (!novosItens.length && !sachesAtuais.length) {
        alert('Nenhuma alteração encontrada.');
        cancelarEdicaoComanda();
        return;
    }
    if (_finalizandoComanda) return;
    _finalizandoComanda = true;
    const btn = document.getElementById('btn-adicionar-itens-comanda');
    const orig = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    fetch('<?= site_url('admin/venda-especifica/adicionar-item-comanda') ?>', {
        method: 'POST', headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({pedido_id: comandaAtiva, itens: novosItens, saches: sachesAtuais})
    }).then(r=>r.json()).then(data => {
        if (data.success) { cancelarEdicaoComanda(); }
        else alert('Erro: ' + data.message);
        btn.disabled = false; btn.innerHTML = orig;
    }).catch(() => { alert('Erro'); btn.disabled = false; btn.innerHTML = orig; })
    .finally(() => { _finalizandoComanda = false; });
}


function finalizarComanda(id) {
    if (_finalizandoComanda) return;
    const formaPag = document.getElementById('forma_pagamento_comanda').value;
    const troco = formaPag === 'dinheiro' ? (parseFloat(document.getElementById('troco_para_comanda').value) || 0) : null;
    if (formaPag === 'dinheiro') {
        const sub=itensVenda.reduce((s,i)=>s+i.total,0);
        const total=(sub+taxaEntrega+getSachesTotalPago());
        if ((troco || 0) < total) { alert('O valor do troco deve ser maior ou igual ao total: R$ ' + total.toFixed(2).replace('.',',')); return; }
    }
    _finalizandoComanda = true;
    const novosItens = itensVenda.filter(i => !i._comanda_item_id);
    const payload={pedido_id:id,itens:novosItens,saches:getSachesVE(),finalizar:true,forma_pagamento:formaPag,troco_para:troco};
    fetch('<?= site_url('admin/venda-especifica/adicionar-item-comanda') ?>',{
        method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)
    }).then(r=>r.json()).then(data=>{
        if (data.success) window.location.href='<?= site_url('admin/pedidos') ?>/'+id;
        else { alert('Erro: '+data.message); _finalizandoComanda=false; }
    }).catch(()=>{ alert('Erro'); _finalizandoComanda=false; });
}

// ── Editar extras de um item existente ──
function abrirEditarExtras(idx) {
    const item = itensVenda[idx];
    if (!item.id) return;
    fetch('<?= site_url('admin/venda-especifica/produto-extras') ?>/'+item.id)
        .then(r=>r.json()).then(data=>{
            const extras = data.extras||[];
            if (!extras.length) { alert('Este produto não possui extras cadastrados.'); return; }
            const selecionados = {};
            (item.extras||[]).forEach(e=>{ selecionados[e.id]={preco:e.preco,quantidade:e.quantidade}; });

            let itensHtml = extras.map(e=>{
                const qtdAtual = selecionados[e.id]?.quantidade || 0;
                const preco = parseFloat(e.preco).toFixed(2).replace('.',',');
                if (e.multitude) {
                    return `<div class="d-flex justify-content-between align-items-center mb-2 p-2" style="background:#111;border-radius:6px;">
                        <div><span class="text-light small">${e.nome}</span><small class="text-warning d-block">+R$ ${preco}</small></div>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="var i=document.getElementById('ee-qtd-${e.id}');i.value=Math.max(0,parseInt(i.value||0)-1)">−</button>
                            <input type="number" id="ee-qtd-${e.id}" value="${qtdAtual}" min="0" style="width:50px;text-align:center;background:#333;border:1px solid #555;color:#fff;border-radius:4px;">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="var i=document.getElementById('ee-qtd-${e.id}');i.value=parseInt(i.value||0)+1">+</button>
                        </div>
                    </div>`;
                } else {
                    return `<div class="d-flex justify-content-between align-items-center mb-2 p-2" style="background:#111;border-radius:6px;">
                        <div><span class="text-light small">${e.nome}</span><small class="text-warning d-block">+R$ ${preco}</small></div>
                        <input type="checkbox" id="ee-chk-${e.id}" ${qtdAtual>0?'checked':''} style="width:20px;height:20px;">
                    </div>`;
                }
            }).join('');

            const popup = document.createElement('div');
            popup.id = 'popup-editar-extras';
            popup.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:10002;display:flex;align-items:center;justify-content:center;padding:0 12px;box-sizing:border-box;';
            popup.innerHTML = `
                <div style="background:#1a1a1a;width:100%;max-width:420px;border-radius:12px;overflow:hidden;max-height:85vh;display:flex;flex-direction:column;">
                    <div style="background:#0055ff;padding:12px 16px;display:flex;justify-content:space-between;align-items:center;">
                        <strong style="color:#fff;"><i class="fas fa-list-ul me-2"></i>Extras — ${item.nome}</strong>
                        <button onclick="document.getElementById('popup-editar-extras').remove()" style="background:none;border:none;color:#fff;font-size:1.4rem;cursor:pointer;line-height:1;">&times;</button>
                    </div>
                    <div style="overflow-y:auto;flex:1;padding:12px;">${itensHtml}</div>
                    <div style="padding:12px;border-top:1px solid #333;display:flex;gap:8px;">
                        <button onclick="document.getElementById('popup-editar-extras').remove()" style="flex:1;padding:10px;background:#333;border:none;color:#ccc;border-radius:6px;cursor:pointer;">Cancelar</button>
                        <button onclick="_salvarExtrasItem(${idx},${JSON.stringify(extras).replace(/"/g,'&quot;')})" style="flex:1;padding:10px;background:#0055ff;border:none;color:#fff;border-radius:6px;font-weight:600;cursor:pointer;">Salvar</button>
                    </div>
                </div>`;
            document.getElementById('popup-editar-extras')?.remove();
            document.body.appendChild(popup);
        });
}

function _salvarExtrasItem(idx, extrasDisponiveis) {
    const item = itensVenda[idx];
    const novasExtras = [];
    extrasDisponiveis.forEach(e=>{
        if (e.multitude) {
            const qtd = parseInt(document.getElementById('ee-qtd-'+e.id)?.value)||0;
            if (qtd>0) novasExtras.push({id:e.id,nome:e.nome,preco:parseFloat(e.preco),quantidade:qtd});
        } else {
            if (document.getElementById('ee-chk-'+e.id)?.checked)
                novasExtras.push({id:e.id,nome:e.nome,preco:parseFloat(e.preco),quantidade:1});
        }
    });

    const totalExtras = novasExtras.reduce((s,e)=>s+e.preco*e.quantidade,0);
    item.extras = novasExtras;
    item.total = (item.preco + totalExtras) * item.quantidade;

    document.getElementById('popup-editar-extras')?.remove();

    // Se item já está no banco, persistir
    if (item._comanda_item_id && comandaAtiva) {
        fetch('<?= site_url('admin/venda-especifica/atualizar-extras-item') ?>', {
            method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify({item_id: item._comanda_item_id, pedido_id: comandaAtiva, extras: novasExtras})
        }).then(r=>r.json()).then(d=>{ if(d.success) item.total = d.novo_total; atualizarTabela(); });
    } else {
        atualizarTabela();
    }
}

// ── Aplicar sachês salvos na comanda ──
function _aplicarSachesComanda(sachesComanda) {
    const _tentar = (tentativas) => {
        if (sachesVE.length) {
            sachesComanda.forEach(s=>{
                const sache = sachesVE.find(x=>x.id==s.sache_id);
                const limite = sache ? calcularLimiteSache(sache) : (parseInt(s.quantidade)||0);
                sachesQtd[s.sache_id] = Math.min(parseInt(s.quantidade)||0, limite);
            });
            renderizarSachesVE();
        } else if (tentativas > 0) {
            setTimeout(()=>_tentar(tentativas-1), 300);
        }
    };
    _tentar(10);
}
</script>

<?= $this->endSection() ?>
