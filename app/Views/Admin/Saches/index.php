<?php echo $this->extend('Admin/layout/principal'); ?>
<?php echo $this->section('titulo'); ?><?= $titulo ?><?php echo $this->endSection(); ?>

<?php echo $this->section('conteudos'); ?>
<div class="row g-3">

  <!-- Painel de Grupos -->
  <div class="col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="card-title mb-0"><i class="fas fa-layer-group me-1 text-info"></i>Grupos</h6>
          <button class="btn btn-sm btn-outline-info" onclick="abrirModalGrupo()">
            <i class="fas fa-plus"></i>
          </button>
        </div>

        <div id="lista-grupos">
          <?php if (empty($grupos)): ?>
            <p class="text-muted small text-center py-3">Nenhum grupo criado</p>
          <?php else: foreach ($grupos as $g): ?>
            <?php $qtd = count(array_filter($saches, fn($s) => $s['categoria_sache'] === $g['nome'])); ?>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary" data-id="<?= $g['id'] ?>">
              <div style="cursor:grab;" class="drag-handle me-2 text-muted"><i class="fas fa-grip-vertical"></i></div>
              <div class="flex-grow-1">
                <span class="text-white"><?= esc($g['nome']) ?></span>
                <small class="text-muted ms-1">(<?= $qtd ?>)</small>
                <div>
                  <small class="text-info">Inicial: <?= $g['qtd_inicial'] ?></small>
                  <small class="text-warning ms-2">Máx: <?= $g['qtd_max'] ?: '∞' ?></small>
                </div>
              </div>
              <div class="d-flex gap-1">
                <button class="btn btn-xs btn-outline-warning p-1" style="font-size:11px;" onclick="editarGrupo('<?= esc($g['nome']) ?>',<?= $g['qtd_inicial'] ?>,<?= $g['qtd_max'] ?: 0 ?>)" title="Editar"><i class="fas fa-edit"></i></button>
                <button class="btn btn-xs btn-outline-danger p-1" style="font-size:11px;" onclick="excluirGrupo('<?= esc($g['nome']) ?>')" title="Remover grupo"><i class="fas fa-trash"></i></button>
              </div>
            </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabela de Sachês -->
  <div class="col-md-9">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="card-title mb-0"><i class="fas fa-pepper-hot me-2"></i>Sachês</h4>
          <button class="btn btn-success" onclick="abrirModal()">
            <i class="fas fa-plus me-1"></i>Novo Sachê
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th style="width:30px"></th>
                <th>Nome</th>
                <th>Grupo</th>
                <th>Preço</th>
                <th>Categorias</th>
                <th>Limite</th>
                <th>Status</th>
                <th style="width:110px">Ações</th>
              </tr>
            </thead>
            <tbody id="tbody-saches">
              <?php if (empty($saches)): ?>
              <tr><td colspan="8" class="text-center text-muted py-4">Nenhum sachê cadastrado</td></tr>
              <?php else: foreach ($saches as $s): ?>
              <tr data-id="<?= $s['id'] ?>">
                <td style="cursor:grab;" class="drag-handle text-muted"><i class="fas fa-grip-vertical"></i></td>
                <td><?= esc($s['nome']) ?></td>
                <td>
                  <?php if (!empty($s['categoria_sache'])): ?>
                    <span class="badge" style="background:#9C27B0;"><?= esc($s['categoria_sache']) ?></span>
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
                <td>R$ <?= number_format($s['preco'], 2, ',', '.') ?></td>
                <td><small class="text-muted"><?= esc($s['categorias_nomes'] ?? '—') ?></small></td>
                <td>
                  <?php if ($s['limite_tipo'] === 'fixo'): ?>
                    <span class="badge bg-secondary">Fixo: <?= $s['limite_fixo'] ?></span>
                  <?php else: ?>
                    <span class="badge bg-info text-dark">Min <?= $s['limite_minimo'] ?> +1/R$<?= number_format($s['limite_por_valor'], 0, ',', '.') ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="badge <?= $s['ativo'] ? 'bg-success' : 'bg-danger' ?>">
                    <?= $s['ativo'] ? 'Ativo' : 'Inativo' ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-sm btn-primary" onclick="editarSache(<?= $s['id'] ?>)" title="Editar"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-sm <?= $s['ativo'] ? 'btn-warning' : 'btn-success' ?>" onclick="toggleAtivo(<?= $s['id'] ?>, this)" title="<?= $s['ativo'] ? 'Desativar' : 'Ativar' ?>">
                    <i class="fas fa-<?= $s['ativo'] ? 'ban' : 'check' ?>"></i>
                  </button>
                  <button class="btn btn-sm btn-danger" onclick="excluir(<?= $s['id'] ?>)" title="Excluir"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Sachê -->
<div class="modal fade" id="modalSache" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="background:#2d2d2d;color:#fff;border:1px solid #444;">
      <div class="modal-header" style="border-color:#444;">
        <h5 class="modal-title" id="modalTitulo">Novo Sachê</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="sacheId">

        <div class="row g-3">
          <div class="col-md-5">
            <label class="form-label">Nome *</label>
            <input type="text" id="sacheNome" class="form-control" placeholder="Ex: Ketchup">
          </div>
          <div class="col-md-4">
            <label class="form-label">Grupo</label>
            <select id="sacheCategoriaSache" class="form-select">
              <option value="">— Sem grupo —</option>
              <?php foreach ($grupos as $g): ?>
              <option value="<?= esc($g['nome']) ?>"><?= esc($g['nome']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select id="sacheAtivo" class="form-select">
              <option value="1">Ativo</option>
              <option value="0">Inativo</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Preço (R$)</label>
            <input type="number" id="sachePreco" class="form-control" value="0" min="0" step="0.01">
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">Categorias de produto associadas</label>
          <div class="d-flex flex-wrap gap-2" id="checkCategorias">
            <?php foreach ($categorias as $cat): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input cat-check" type="checkbox" value="<?= $cat['id'] ?>" id="cat<?= $cat['id'] ?>">
              <label class="form-check-label" for="cat<?= $cat['id'] ?>"><?= esc($cat['nome']) ?></label>
            </div>
            <?php endforeach; ?>
          </div>
          <small class="text-muted">O sachê aparece no carrinho quando o cliente tiver produtos dessas categorias.</small>
        </div>

        <hr style="border-color:#444;">
        <h6 class="text-warning">Limite de sachês gratuitos</h6>

        <div class="row g-3">
          <div class="col-12">
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="limiteTipo" id="tipoFixo" value="fixo" checked>
                <label class="form-check-label" for="tipoFixo">Fixo</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="limiteTipo" id="tipoPersonalizado" value="personalizado">
                <label class="form-check-label" for="tipoPersonalizado">Por valor do pedido</label>
              </div>
            </div>
          </div>

          <div class="col-md-4" id="campoFixo">
            <label class="form-label">Qtd máxima grátis</label>
            <input type="number" id="limiteFixo" class="form-control" value="1" min="1">
          </div>

          <div id="campoPersonalizado" style="display:none;" class="col-12">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Mínimo garantido</label>
                <input type="number" id="limiteMinimo" class="form-control" value="1" min="0">
              </div>
              <div class="col-md-4">
                <label class="form-label">+1 a cada R$</label>
                <input type="number" id="limitePorValor" class="form-control" value="40" min="0.01" step="0.01">
              </div>
            </div>
            <small class="text-muted">Ex: mínimo 2, a cada R$40 → com R$80 no carrinho = 2 + 2 = 4 grátis</small>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="border-color:#444;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" onclick="salvar()"><i class="fas fa-save me-1"></i>Salvar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Grupo -->
<div class="modal fade" id="modalGrupo" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" style="background:#2d2d2d;color:#fff;border:1px solid #444;">
      <div class="modal-header" style="border-color:#444;">
        <h6 class="modal-title" id="modalGrupoTitulo">Novo Grupo</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="grupoOriginal">
        <div class="mb-2">
          <label class="form-label">Nome do grupo</label>
          <input type="text" id="grupoNome" class="form-control" placeholder="Ex: Molhos">
        </div>
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label">Qtd inicial</label>
            <input type="number" id="grupoQtdInicial" class="form-control" value="1" min="0">
            <small class="text-muted">Padrão ao abrir</small>
          </div>
          <div class="col-6">
            <label class="form-label">Qtd máxima</label>
            <input type="number" id="grupoQtdMax" class="form-control" placeholder="Sem limite" min="0">
            <small class="text-muted">0 = sem limite</small>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="border-color:#444;">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-info btn-sm" onclick="salvarGrupo()">Salvar</button>
      </div>
    </div>
  </div>
</div>
<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const modal      = new bootstrap.Modal(document.getElementById('modalSache'));
const modalGrupo = new bootstrap.Modal(document.getElementById('modalGrupo'));

/* ── Sachê ── */
function abrirModal(dados) {
  document.getElementById('modalTitulo').textContent = dados ? 'Editar Sachê' : 'Novo Sachê';
  document.getElementById('sacheId').value           = dados?.id ?? '';
  document.getElementById('sacheNome').value         = dados?.nome ?? '';
  document.getElementById('sacheCategoriaSache').value = dados?.categoria_sache ?? '';
  document.getElementById('sachePreco').value        = dados?.preco ?? 0;
  document.getElementById('sacheAtivo').value        = dados?.ativo ?? 1;

  document.querySelectorAll('.cat-check').forEach(c => c.checked = false);
  (dados?.categorias ?? []).forEach(id => {
    const el = document.getElementById('cat' + id);
    if (el) el.checked = true;
  });

  const tipo = dados?.limite_tipo ?? 'fixo';
  document.querySelector(`input[name="limiteTipo"][value="${tipo}"]`).checked = true;
  document.getElementById('limiteFixo').value      = dados?.limite_fixo ?? 1;
  document.getElementById('limiteMinimo').value    = dados?.limite_minimo ?? 1;
  document.getElementById('limitePorValor').value  = dados?.limite_por_valor ?? 40;
  toggleLimiteCampos(tipo);
  modal.show();
}

function toggleLimiteCampos(tipo) {
  document.getElementById('campoFixo').style.display         = tipo === 'fixo' ? '' : 'none';
  document.getElementById('campoPersonalizado').style.display = tipo === 'personalizado' ? '' : 'none';
}
document.querySelectorAll('input[name="limiteTipo"]').forEach(r =>
  r.addEventListener('change', () => toggleLimiteCampos(r.value))
);

function editarSache(id) {
  fetch(`<?= site_url('admin/saches/get') ?>/${id}`, { headers: {'X-Requested-With':'XMLHttpRequest'} })
    .then(r => r.json()).then(dados => abrirModal(dados));
}

function salvar() {
  const tipo = document.querySelector('input[name="limiteTipo"]:checked').value;
  const payload = {
    id:               document.getElementById('sacheId').value || null,
    nome:             document.getElementById('sacheNome').value.trim(),
    categoria_sache:  document.getElementById('sacheCategoriaSache').value || null,
    preco:            document.getElementById('sachePreco').value,
    ativo:            document.getElementById('sacheAtivo').value,
    limite_tipo:      tipo,
    limite_fixo:      document.getElementById('limiteFixo').value,
    limite_minimo:    document.getElementById('limiteMinimo').value,
    limite_por_valor: document.getElementById('limitePorValor').value,
    categorias:       [...document.querySelectorAll('.cat-check:checked')].map(c => c.value),
  };
  if (!payload.nome) { alert('Informe o nome do sachê'); return; }
  fetch('<?= site_url('admin/saches/salvar') ?>', {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify(payload)
  }).then(r => r.json()).then(res => {
    if (res.sucesso) { modal.hide(); location.reload(); }
    else alert('Erro: ' + (res.erro ?? 'desconhecido'));
  });
}

function toggleAtivo(id, btn) {
  fetch(`<?= site_url('admin/saches/toggle') ?>/${id}`, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(r => r.json()).then(res => { if (res.sucesso) location.reload(); });
}

function excluir(id) {
  if (!confirm('Excluir este sachê?')) return;
  fetch(`<?= site_url('admin/saches/excluir') ?>/${id}`, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(r => r.json()).then(res => { if (res.sucesso) location.reload(); });
}

/* ── Grupos ── */
function abrirModalGrupo(nomeAtual, qtdInicial, qtdMax) {
  document.getElementById('modalGrupoTitulo').textContent = nomeAtual ? 'Editar Grupo' : 'Novo Grupo';
  document.getElementById('grupoOriginal').value   = nomeAtual ?? '';
  document.getElementById('grupoNome').value       = nomeAtual ?? '';
  document.getElementById('grupoQtdInicial').value = qtdInicial ?? 1;
  document.getElementById('grupoQtdMax').value     = qtdMax ?? '';
  modalGrupo.show();
}
function editarGrupo(nome, qtdInicial, qtdMax) { abrirModalGrupo(nome, qtdInicial, qtdMax); }

function salvarGrupo() {
  const original  = document.getElementById('grupoOriginal').value;
  const novo      = document.getElementById('grupoNome').value.trim();
  const qtdInicial = parseInt(document.getElementById('grupoQtdInicial').value) || 0;
  const qtdMax    = parseInt(document.getElementById('grupoQtdMax').value) || 0;
  if (!novo) { alert('Informe o nome do grupo'); return; }
  fetch('<?= site_url('admin/saches/salvarGrupo') ?>', {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify({ original, novo, qtd_inicial: qtdInicial, qtd_max: qtdMax })
  }).then(r => r.json()).then(res => {
    if (res.sucesso) { modalGrupo.hide(); location.reload(); }
    else alert('Erro: ' + (res.erro ?? 'desconhecido'));
  });
}

function excluirGrupo(nome) {
  if (!confirm(`Remover o grupo "${nome}"? Os sachês associados ficarão sem grupo.`)) return;
  fetch('<?= site_url('admin/saches/excluirGrupo') ?>', {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify({ nome })
  }).then(r => r.json()).then(res => {
    if (res.sucesso) location.reload();
    else alert('Erro: ' + (res.erro ?? 'desconhecido'));
  });
}

/* ── Ordenação ── */
function salvarOrdem(tipo, ids) {
  fetch('<?= site_url('admin/saches/reordenar') ?>', {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify({ tipo, ids })
  });
}

// Drag-and-drop grupos
const listaGrupos = document.getElementById('lista-grupos');
if (listaGrupos && listaGrupos.children.length > 1) {
  Sortable.create(listaGrupos, {
    handle: '.drag-handle',
    animation: 150,
    onEnd() {
      const ids = [...listaGrupos.querySelectorAll('[data-id]')].map(el => el.dataset.id);
      salvarOrdem('grupos', ids);
    }
  });
}

// Drag-and-drop sachês
const tbodySaches = document.getElementById('tbody-saches');
if (tbodySaches) {
  Sortable.create(tbodySaches, {
    handle: '.drag-handle',
    animation: 150,
    onEnd() {
      const ids = [...tbodySaches.querySelectorAll('[data-id]')].map(el => el.dataset.id);
      salvarOrdem('saches', ids);
    }
  });
}
</script>
<?php echo $this->endSection(); ?>
