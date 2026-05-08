<?php echo $this->extend('Admin/layout/principal'); ?>
<?php echo $this->section('titulo'); ?><?= $titulo ?><?php echo $this->endSection(); ?>

<?php echo $this->section('conteudos'); ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h4 class="card-title mb-0">Bairros Atendidos</h4>
            <small class="text-muted"><?= $totalBairros ?> bairro(s) encontrado(s)</small>
          </div>
          <a href="<?= site_url('admin/bairros/criar') ?>" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Cadastrar Bairro
          </a>
        </div>

        <!-- Filtros -->
        <div class="d-flex gap-2 flex-wrap mb-3">
          <input type="text" id="filtro-nome" class="form-control form-control-sm" style="max-width:200px;"
                 placeholder="Pesquisar por nome..." value="<?= esc($nomeAtual) ?>"
                 onkeydown="if(event.key==='Enter') aplicarFiltros()">

          <select id="filtro-cidade" class="form-select form-select-sm" style="max-width:200px;">
            <option value="">Todas as cidades</option>
            <?php foreach ($cidades as $c): ?>
            <option value="<?= esc($c['cidade']) ?>" <?= $cidadeAtual === $c['cidade'] ? 'selected' : '' ?>>
              <?= esc($c['cidade']) ?>
            </option>
            <?php endforeach; ?>
          </select>

          <button class="btn btn-sm btn-primary" onclick="aplicarFiltros()">
            <i class="fas fa-search"></i> Filtrar
          </button>

          <?php if ($cidadeAtual || $nomeAtual): ?>
          <a href="<?= site_url('admin/bairros') ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-times"></i> Limpar
          </a>
          <?php endif; ?>

          <button class="btn btn-sm btn-warning ms-auto" onclick="desativarTodos()">
            <i class="fas fa-ban me-1"></i>Desativar todos
          </button>
          <button class="btn btn-sm btn-success" onclick="ativarTodos()">
            <i class="fas fa-check me-1"></i>Ativar todos
          </button>
        </div>

        <!-- Tabela -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Cidade</th>
                <th>Taxa de entrega</th>
                <th>Situação</th>
                <th style="width:130px">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($bairros)): ?>
                <?php foreach ($bairros as $bairro): ?>
                <tr <?= $bairro->deletado_em ? 'style="opacity:.6;"' : '' ?>>
                  <td><?= esc($bairro->nome) ?></td>
                  <td><?= esc($bairro->cidade) ?></td>
                  <td class="text-success fw-bold">R$ <?= number_format($bairro->valor_entrega, 2, ',', '.') ?></td>
                  <td>
                    <?php if ($bairro->deletado_em): ?>
                      <span class="badge bg-secondary">Excluído</span>
                    <?php elseif ($bairro->ativo): ?>
                      <span class="badge bg-success">Ativo</span>
                    <?php else: ?>
                      <span class="badge bg-danger">Inativo</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($bairro->deletado_em): ?>
                      <form action="<?= site_url("admin/bairros/desfazer-exclusao/$bairro->id") ?>" method="post" style="display:inline;">
                        <button class="btn btn-outline-success btn-sm" title="Restaurar"><i class="fas fa-undo"></i></button>
                      </form>
                      <form action="<?= site_url("admin/bairros/deletar-definitivamente/$bairro->id") ?>" method="post" style="display:inline;"
                            onsubmit="return confirm('Apagar definitivamente?')">
                        <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                      </form>
                    <?php else: ?>
                      <a href="<?= site_url("admin/bairros/$bairro->id") ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i></a>
                      <a href="<?= site_url("admin/bairros/editar/$bairro->id") ?>" class="btn btn-outline-warning btn-sm"><i class="fas fa-edit"></i></a>
                      <a href="<?= site_url("admin/bairros/excluir/$bairro->id") ?>" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Nenhum bairro encontrado</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Paginação -->
        <?php if ($totalPaginas > 1): ?>
        <div class="d-flex justify-content-center mt-3">
          <nav><ul class="pagination pagination-sm mb-0">
            <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
            <li class="page-item <?= $p === $paginaAtual ? 'active' : '' ?>">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
          </ul></nav>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>
<script>
function aplicarFiltros() {
    const nome   = document.getElementById('filtro-nome').value.trim();
    const cidade = document.getElementById('filtro-cidade').value;
    const url    = new URL(window.location.href);
    nome   ? url.searchParams.set('nome', nome)    : url.searchParams.delete('nome');
    cidade ? url.searchParams.set('cidade', cidade) : url.searchParams.delete('cidade');
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

function desativarTodos() {
    const cidade = document.getElementById('filtro-cidade').value;
    const nome   = document.getElementById('filtro-nome').value.trim();
    const msg    = cidade ? `Desativar todos os bairros de "${cidade}"?` : 'Desativar TODOS os bairros ativos?';
    if (!confirm(msg)) return;
    fetch('<?= site_url('admin/bairros/desativar-todos') ?>', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
        body: JSON.stringify({ cidade, nome })
    }).then(r => r.json()).then(res => {
        if (res.sucesso) location.reload();
        else alert('Erro: ' + (res.msg ?? 'desconhecido'));
    });
}

function ativarTodos() {
    const cidade = document.getElementById('filtro-cidade').value;
    const nome   = document.getElementById('filtro-nome').value.trim();
    const msg    = cidade ? `Ativar todos os bairros de "${cidade}"?` : 'Ativar TODOS os bairros inativos?';
    if (!confirm(msg)) return;
    fetch('<?= site_url('admin/bairros/ativar-todos') ?>', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
        body: JSON.stringify({ cidade, nome })
    }).then(r => r.json()).then(res => {
        if (res.sucesso) location.reload();
        else alert('Erro: ' + (res.msg ?? 'desconhecido'));
    });
}
</script>
<?php echo $this->endSection(); ?>
