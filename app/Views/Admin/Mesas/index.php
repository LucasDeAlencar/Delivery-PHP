<?php echo $this->extend('Admin/layout/principal'); ?>

<?php echo $this->section('titulo'); ?>
<?php echo $titulo; ?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('conteudos'); ?>
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4"><?php echo $titulo; ?></h1>
        </div>
    </div>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo site_url('admin/home'); ?>">Painel</a></li>
        <li class="breadcrumb-item active"><?php echo $titulo; ?></li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Sistema de Mesas
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0">Ativar Sistema de Mesas</h5>
                    <p class="text-muted mb-0">Disponibiliza opções de mesas para pedidos via retirada</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="form-check form-switch form-check-inline fs-5">
                        <input class="form-check-input" type="checkbox" id="sistema_ativo" style="width:3rem;height:1.5rem;"
                               <?php echo (isset($config->sistema_ativo) && $config->sistema_ativo == 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="sistema_ativo" id="label_sistema_ativo">
                            <?php echo (isset($config->sistema_ativo) && $config->sistema_ativo == 1) ? '<span class="text-success">Ativado</span>' : '<span class="text-secondary">Desativado</span>'; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($config->sistema_ativo) && $config->sistema_ativo == 1): ?>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-chair me-1"></i> Mesas</div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriarMesa">
                    <i class="fas fa-plus"></i> Nova Mesa
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriarSerie">
                    <i class="fas fa-layer-group"></i> Adicionar Série
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php if (!empty($mesas)): ?>
                    <?php foreach ($mesas as $mesa): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 border-<?php echo $mesa->ativo ? ($mesa->ocupado ? 'warning' : 'success') : 'secondary'; ?>">
                                <div class="card-body text-center p-3">
                                    <h4 class="mb-1">
                                        <i class="fas fa-chair text-<?php echo $mesa->ativo ? ($mesa->ocupado ? 'warning' : 'success') : 'secondary'; ?>"></i>
                                        <?php echo $mesa->numero; ?>
                                    </h4>
                                    <p class="small mb-1 text-muted"><?php echo $mesa->capacidade; ?> lugares</p>
                                    <?php if ($mesa->ativo): ?>
                                        <?php if ($mesa->ocupado): ?>
                                            <span class="badge bg-warning text-dark">Ocupada</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Livre</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inativa</span>
                                    <?php endif; ?>
                                    <div class="mt-2 d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="editarMesa(<?php echo $mesa->id; ?>, <?php echo $mesa->capacidade; ?>, <?php echo $mesa->ativo; ?>)" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($mesa->ocupado): ?>
                                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="liberarMesa(<?php echo $mesa->id; ?>)" title="Liberar">
                                                <i class="fas fa-unlock"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="excluirMesa(<?php echo $mesa->id; ?>)" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fas fa-chair fa-3x mb-3"></i>
                        <p class="mb-0">Nenhuma mesa cadastrada</p>
                        <p class="small">Clique em "Nova Mesa" para adicionar</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Criar Mesa -->
<div class="modal fade" id="modalCriarMesa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#2d2d2d;color:#fff;border:1px solid #444;">
            <div class="modal-header" style="border-bottom:1px solid #444;">
                <h5 class="modal-title"><i class="fas fa-chair me-2"></i>Nova Mesa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">O número da mesa será gerado automaticamente em ordem crescente.</p>
                <div class="mb-3">
                    <label for="capacidade_novo" class="form-label">Capacidade (lugares)</label>
                    <input type="number" class="form-control" id="capacidade_novo" value="4" min="1" max="20"
                           style="background:#1a1a1a;color:#fff;border-color:#555;">
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #444;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="criarMesa()">Criar Mesa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Criar Série de Mesas -->
<div class="modal fade" id="modalCriarSerie" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#2d2d2d;color:#fff;border:1px solid #444;">
            <div class="modal-header" style="border-bottom:1px solid #444;">
                <h5 class="modal-title"><i class="fas fa-layer-group me-2"></i>Adicionar Série de Mesas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Cria várias mesas de uma vez, numeradas em sequência a partir da última existente.</p>
                <div class="mb-3">
                    <label for="serie_quantidade" class="form-label">Quantidade de Mesas</label>
                    <input type="number" class="form-control" id="serie_quantidade" value="5" min="1" max="50"
                           style="background:#1a1a1a;color:#fff;border-color:#555;">
                </div>
                <div class="mb-3">
                    <label for="serie_capacidade" class="form-label">Capacidade de cada mesa (lugares)</label>
                    <input type="number" class="form-control" id="serie_capacidade" value="4" min="1" max="20"
                           style="background:#1a1a1a;color:#fff;border-color:#555;">
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #444;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="criarSerie()">Criar Série</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Mesa -->
<div class="modal fade" id="modalEditarMesa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#2d2d2d;color:#fff;border:1px solid #444;">
            <div class="modal-header" style="border-bottom:1px solid #444;">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Mesa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="mesa_id">
                <div class="mb-3">
                    <label for="mesa_capacidade" class="form-label">Capacidade (lugares)</label>
                    <input type="number" class="form-control" id="mesa_capacidade" min="1" max="20"
                           style="background:#1a1a1a;color:#fff;border-color:#555;">
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="mesa_ativo">
                        <label class="form-check-label" for="mesa_ativo">Mesa ativa</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #444;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="salvarMesa()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>
<script>
document.getElementById('sistema_ativo').addEventListener('change', function() {
    fetch('<?php echo site_url('admin/mesas/atualizarConfig'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ sistema_ativo: this.checked ? 1 : 0 })
    }).then(r => r.json()).then(d => { if (d.sucesso) location.reload(); });
});

function criarMesa() {
    const capacidade = document.getElementById('capacidade_novo').value;
    fetch('<?php echo site_url('admin/mesas/criar'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ capacidade })
    }).then(r => r.json()).then(d => { if (d.sucesso) location.reload(); });
}

function criarSerie() {
    const quantidade = document.getElementById('serie_quantidade').value;
    const capacidade = document.getElementById('serie_capacidade').value;
    if (!quantidade || quantidade < 1) return;
    fetch('<?php echo site_url('admin/mesas/criarSerie'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ quantidade, capacidade })
    }).then(r => r.json()).then(d => { if (d.sucesso) location.reload(); });
}

function excluirMesa(id) {
    if (!confirm('Excluir esta mesa?')) return;
    fetch('<?php echo site_url('admin/mesas/excluir'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ id })
    }).then(r => r.json()).then(d => { if (d.sucesso) location.reload(); });
}

function liberarMesa(id) {
    if (!confirm('Liberar esta mesa? O pedido será marcado como finalizado.')) return;
    fetch('<?php echo site_url('admin/mesas/liberar'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ id })
    }).then(r => r.json()).then(d => { if (d.sucesso) location.reload(); });
}

function editarMesa(id, capacidade, ativo) {
    document.getElementById('mesa_id').value = id;
    document.getElementById('mesa_capacidade').value = capacidade;
    document.getElementById('mesa_ativo').checked = ativo == 1;
    new bootstrap.Modal(document.getElementById('modalEditarMesa')).show();
}

function salvarMesa() {
    fetch('<?php echo site_url('admin/mesas/atualizar'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({
            id: document.getElementById('mesa_id').value,
            capacidade: document.getElementById('mesa_capacidade').value,
            ativo: document.getElementById('mesa_ativo').checked ? 1 : 0
        })
    }).then(r => r.json()).then(d => { if (d.sucesso) location.reload(); });
}
</script>
<?php echo $this->endSection(); ?>
