<?php echo $this->extend('Admin/layout/principal'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('estilos'); ?>
<style>
    .card-estatistica {
        background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
        border: 1px solid #333;
        border-left: 4px solid;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .card-estatistica:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(248, 181, 49, 0.2);
    }
    .card-estatistica h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #f8b531;
        margin: 0;
    }
    .card-estatistica h6 {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #ccc;
    }
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table { min-width: 800px; margin-bottom: 0; }
    .table thead th {
        color: #f8b531;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        border-bottom: 2px solid #f8b531;
        padding: 0.75rem;
        white-space: nowrap;
    }
    .table tbody td { padding: 0.75rem; vertical-align: middle; }
    .table tbody tr:hover { background: rgba(248, 181, 49, 0.1); }
    
    .status-select {
        background: #2d2d2d;
        border: 2px solid #555;
        color: #fff;
        border-radius: 6px;
        padding: 5px 8px;
        font-size: 0.8rem;
        cursor: pointer;
        min-width: 120px;
    }
    .status-select:focus {
        border-color: #f8b531;
        outline: none;
    }
    
    .filtro-status {
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 0.75rem;
        margin: 2px;
    }
    .filtro-status.active {
        background: #f8b531 !important;
        color: #1a1a1a !important;
        border-color: #f8b531 !important;
    }
    
    @keyframes pulseNew {
        0%, 100% { background-color: rgba(248, 181, 49, 0.1); }
        50% { background-color: rgba(248, 181, 49, 0.25); }
    }
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    .pedido-novo { animation: pulseNew 1.5s infinite; }
    .badge-novo {
        animation: blink 1s infinite;
        background: #ffc107;
        color: #000;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 8px;
        font-size: 0.6rem;
        margin-left: 5px;
    }
    
    #btn-testar-som {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        border-radius: 50%;
        width: 45px;
        height: 45px;
    }
    
    /* Estilo para pedidos inativos - ÊNFASE CINZA */
    .pedido-inativo {
        background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%) !important;
        border-left: 4px solid #6c757d !important;
        opacity: 0.7;
    }
    .pedido-inativo:hover {
        opacity: 0.85;
        background: linear-gradient(135deg, #333 0%, #222 100%) !important;
    }
    .pedido-inativo td {
        color: #888 !important;
    }
    .badge-inativo {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: #ddd;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    .status-inativo-text {
        color: #777;
        font-style: italic;
        font-weight: 500;
    }
    
    /* Estilo para pedidos cancelados - VERMELHO MAIS CLARO */
    .pedido-cancelado {
        background: linear-gradient(135deg, #3d1f1f 0%, #2a1515 100%) !important;
        border-left: 4px solid #dc3545 !important;
    }
    .pedido-cancelado td {
        color: #e88888 !important;
    }
    .badge-cancelado {
        background: #dc3545;
        color: #fff;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        animation: pulseCancelado 2s infinite;
    }
    @keyframes pulseCancelado {
        0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
        50% { box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
    }
    .status-cancelado-text {
        color: #dc3545;
        font-style: italic;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .card-estatistica h3 { font-size: 1.5rem; }
        .filtro-status { font-size: 0.65rem; padding: 4px 8px; }
        .suporte-header { flex-direction: column !important; gap: 10px !important; }
        .suporte-actions { width: 100%; }
        .suporte-actions button { flex: 1; min-width: 80px; }
        .suporte-info-header { flex-direction: column !important; align-items: flex-start !important; }
        .suporte-info-header button { width: 100%; }
    }
</style>
<?php echo $this->endSection(); ?>

<?php echo $this->section('conteudos'); ?>

<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 style="color: #f8b531; font-weight: 700; margin: 0;">
                    <i class="fas fa-shopping-cart me-2"></i> Pedidos
                </h4>
                <small style="color: #999;">Atualização automática a cada 10s</small>
            </div>
            <span class="badge bg-success" style="padding: 6px 12px; font-size: 0.75rem;">
                <i class="fas fa-sync-alt me-1" id="icone-sync"></i> Auto-refresh
            </span>
        </div>
    </div>
</div>

<!-- Estatísticas -->
<div class="row mb-3">
    <div class="col-6 col-lg mb-2">
        <div class="card card-estatistica" style="border-left-color: #ffc107;">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Pendentes</h6>
                        <h3 id="stat-pendentes"><?= $estatisticas['pendentes'] ?? 0 ?></h3>
                    </div>
                    <div class="icon-circle" style="background: #ffc107;">
                        <i class="fas fa-clock text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg mb-2">
        <div class="card card-estatistica" style="border-left-color: #17a2b8;">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Confirmados</h6>
                        <h3 id="stat-confirmados"><?= $estatisticas['confirmados'] ?? 0 ?></h3>
                    </div>
                    <div class="icon-circle" style="background: #17a2b8;">
                        <i class="fas fa-check text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg mb-2">
        <div class="card card-estatistica" style="border-left-color: #28a745;">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Entregues</h6>
                        <h3 id="stat-entregues"><?= $estatisticas['entregues'] ?? 0 ?></h3>
                    </div>
                    <div class="icon-circle" style="background: #28a745;">
                        <i class="fas fa-check-double text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg mb-2">
        <div class="card card-estatistica" style="border-left-color: #dc3545;">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Cancelados</h6>
                        <h3 id="stat-cancelados"><?= $estatisticas['cancelados'] ?? 0 ?></h3>
                    </div>
                    <div class="icon-circle" style="background: #dc3545;">
                        <i class="fas fa-times text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg mb-2">
        <div class="card card-estatistica" style="border-left-color: #6c757d;">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Inativos</h6>
                        <h3 id="stat-inativos" style="color: #6c757d !important;"><?= $estatisticas['inativos'] ?? 0 ?></h3>
                    </div>
                    <div class="icon-circle" style="background: #6c757d;">
                        <i class="fas fa-ban text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg mb-2">
        <div class="card card-estatistica" style="border-left-color: #ff9800; cursor: pointer;" onclick="toggleSuportes()">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Suporte</h6>
                        <h3 id="stat-suporte" style="color: #ff9800 !important;"><?= $total_suportes ?? 0 ?></h3>
                    </div>
                    <div class="icon-circle" style="background: #ff9800;">
                        <i class="fas fa-headset text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Seção de Suporte (oculta por padrão) -->
<?php if (!empty($suportes_pendentes)): ?>
<?php 
// Agrupar suportes por código de pedido
$suportesAgrupados = [];
foreach ($suportes_pendentes as $suporte) {
    $codigo = $suporte['codigo_pedido'];
    if (!isset($suportesAgrupados[$codigo])) {
        $suportesAgrupados[$codigo] = [
            'cliente_nome' => $suporte['cliente_nome'],
            'cliente_telefone' => $suporte['cliente_telefone'],
            'pedido_id' => $suporte['pedido_id'],
            'mensagens' => []
        ];
    }
    $suportesAgrupados[$codigo]['mensagens'][] = $suporte;
}
?>
<div class="row mb-3" id="secao-suporte" style="display: none;">
    <div class="col-12">
        <div class="card" style="background: #2d2d2d; border: 1px solid #ff9800;">
            <div class="card-header" style="background: #ff9800; color: white;">
                <h5 class="mb-0"><i class="fas fa-headset"></i> Solicitações de Suporte Pendentes</h5>
            </div>
            <div class="card-body">
                <?php foreach ($suportesAgrupados as $codigo => $grupo): ?>
                <div class="suporte-grupo" style="border-bottom: 1px solid #444; padding: 15px 0;">
                    <div class="suporte-header" style="display: flex; justify-content: space-between; align-items: start; gap: 15px;">
                        <div style="flex: 1; min-width: 0;">
                            <p style="margin: 0; color: #f8b531; cursor: pointer; word-wrap: break-word;" onclick="toggleSuporte('<?= $codigo ?>')">
                                <i class="fas fa-chevron-down" id="icon-<?= $codigo ?>"></i>
                                <strong>Pedido:</strong> <?= esc($codigo) ?> | 
                                <strong>Cliente:</strong> <?= esc($grupo['cliente_nome']) ?> 
                                <span style="background: #ff9800; color: #000; padding: 2px 8px; border-radius: 10px; font-size: 12px; margin-left: 10px;"><?= count($grupo['mensagens']) ?> mensagem(ns)</span>
                            </p>
                            <p style="margin: 5px 0 0 0; color: #ccc; word-wrap: break-word;"><strong>Telefone:</strong> <?= esc($grupo['cliente_telefone']) ?: 'Não informado' ?></p>
                            
                            <div id="detalhes-<?= $codigo ?>" style="display: none; margin-top: 15px;">
                                <div style="background: #3a3a3a; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                                    <div class="suporte-info-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 10px;">
                                        <h6 style="color: #f8b531; margin: 0;">Mensagens:</h6>
                                        <button class="btn btn-warning btn-sm" onclick="carregarInfoPedido('<?= $codigo ?>', <?= $grupo['pedido_id'] ?>)" id="btn-info-<?= $codigo ?>">
                                            <i class="fas fa-info-circle"></i> <span class="d-none d-sm-inline">Ver Informações do Cliente</span><span class="d-inline d-sm-none">Info</span>
                                        </button>
                                    </div>
                                    
                                    <div id="info-cliente-<?= $codigo ?>" style="display: none; background: #2a2a2a; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                                        <div id="conteudo-info-<?= $codigo ?>"></div>
                                    </div>
                                    
                                    <?php foreach ($grupo['mensagens'] as $msg): ?>
                                    <div style="background: #2a2a2a; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                                        <p style="margin: 0 0 5px 0; color: #fff; word-wrap: break-word;"><strong>Mensagem:</strong> <?= esc($msg['razao']) ?></p>
                                        <small style="color: #999;"><?= date('d/m/Y H:i', strtotime($msg['criado_em'])) ?></small>
                                        <div style="margin-top: 8px; display: flex; gap: 5px; flex-wrap: wrap;">
                                            <button class="btn btn-success btn-sm" onclick="abrirWhatsApp('<?= preg_replace('/[^0-9]/', '', $msg['cliente_telefone']) ?>', '<?= addslashes($msg['cliente_nome']) ?>', '<?= $msg['codigo_pedido'] ?>')" title="Contato via WhatsApp">
                                                <i class="fab fa-whatsapp"></i> <span class="d-none d-sm-inline">WhatsApp</span>
                                            </button>
                                            <button class="btn btn-primary btn-sm" onclick="resolverSuporte(<?= $msg['id'] ?>)" title="Marcar como resolvido">
                                                <i class="fas fa-check"></i> <span class="d-none d-sm-inline">Resolvido</span>
                                            </button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="suporte-actions" style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <button class="btn btn-info btn-sm" onclick="toggleSuporte('<?= $codigo ?>')" title="Ver Mensagens">
                                <i class="fas fa-eye"></i> <span class="d-none d-sm-inline">Ver Mensagens</span>
                            </button>
                            <button class="btn btn-success btn-sm" onclick="abrirWhatsApp('<?= preg_replace('/[^0-9]/', '', $grupo['cliente_telefone']) ?>', '<?= addslashes($grupo['cliente_nome']) ?>', '<?= $codigo ?>')" title="Contato via WhatsApp">
                                <i class="fab fa-whatsapp"></i> <span class="d-none d-sm-inline">Contato</span>
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="resolverTodosSuporte('<?= $codigo ?>')" title="Marcar todos como resolvido">
                                <i class="fas fa-check-double"></i> <span class="d-none d-sm-inline">Resolver Todos</span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSuporte(codigo) {
    const detalhes = document.getElementById('detalhes-' + codigo);
    const icon = document.getElementById('icon-' + codigo);
    
    if (detalhes.style.display === 'none') {
        detalhes.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        detalhes.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}

function carregarInfoPedido(codigo, pedidoId) {
    const btn = document.getElementById('btn-info-' + codigo);
    const infoDiv = document.getElementById('info-cliente-' + codigo);
    const conteudoDiv = document.getElementById('conteudo-info-' + codigo);
    
    if (infoDiv.style.display === 'none') {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
        
        fetch('/suporte/info-pedido/' + pedidoId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const p = data.pedido;
                    const itens = data.itens || [];
                    
                    let htmlItens = '';
                    if (itens.length > 0) {
                        htmlItens = '<div style="margin-top: 15px;"><h6 style="color: #f8b531; margin-bottom: 10px;">Itens do Pedido:</h6>';
                        itens.forEach(item => {
                            const precoUnitario = parseFloat(item.preco_unitario) || 0;
                            const quantidade = parseInt(item.quantidade) || 1;
                            const precoItem = precoUnitario * quantidade;
                            let htmlExtras = '';
                            if (item.extras && item.extras.length > 0) {
                                item.extras.forEach(extra => {
                                    const precoExtra = parseFloat(extra.extra_preco) || 0;
                                    htmlExtras += `<div style="color: #aaa; font-size: 12px; margin-left: 10px;">+ ${extra.quantidade}x ${extra.extra_nome} (R$ ${precoExtra.toFixed(2).replace('.', ',')})</div>`;
                                });
                            }
                            htmlItens += `
                                <div style="background: #222; padding: 8px; border-radius: 5px; margin-bottom: 8px; font-size: 13px;">
                                    <div style="display: flex; justify-content: space-between;">
                                        <span>${quantidade}x ${item.produto_nome || item.nome_produto || 'Produto'}</span>
                                        <span style="color: #f8b531;">R$ ${precoItem.toFixed(2).replace('.', ',')}</span>
                                    </div>
                                    ${precoUnitario > 0 ? `<div style="color: #666; font-size: 11px;">(R$ ${precoUnitario.toFixed(2).replace('.', ',')} cada)</div>` : ''}
                                    ${htmlExtras ? `<div style="margin-top: 5px;">${htmlExtras}</div>` : ''}
                                    ${item.observacoes ? `<div style="color: #888; font-style: italic; font-size: 12px;">Obs: ${item.observacoes}</div>` : ''}
                                </div>
                            `;
                        });
                        htmlItens += '</div>';
                    }
                    
                    const valorTotal = parseFloat(p.valor_total) || 0;
                    conteudoDiv.innerHTML = `
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div><strong>Nome:</strong> ${p.nome_cliente || 'Não informado'}</div>
                            <div><strong>Telefone:</strong> ${p.telefone_cliente || 'Não informado'}</div>
                            <div><strong>Endereço:</strong> ${p.endereco_entrega || 'Não informado'}</div>
                            <div><strong>Bairro:</strong> ${p.bairro_nome || 'Não informado'}</div>
                            <div><strong>Status:</strong> <span class="badge bg-${getStatusColor(p.status)}">${p.status}</span></div>
                            <div><strong>Data:</strong> ${new Date(p.criado_em).toLocaleString('pt-BR')}</div>
                            <div><strong>Valor Total:</strong> R$ ${valorTotal.toFixed(2).replace('.', ',')}</div>
                            <div><strong>Pagamento:</strong> ${p.forma_pagamento || 'Não informado'}</div>
                        </div>
                        ${htmlItens}
                        <div style="margin-top: 15px;">
                            <button class="btn btn-sm btn-primary" onclick="window.location.href='/admin/pedidos/${pedidoId}'">
                                <i class="fas fa-external-link-alt"></i> Ver Pedido Completo
                            </button>
                        </div>
                    `;
                    infoDiv.style.display = 'block';
                    btn.innerHTML = '<i class="fas fa-info-circle"></i> Ocultar Informações';
                } else {
                    alert('Erro ao carregar informações: ' + data.message);
                    btn.innerHTML = '<i class="fas fa-info-circle"></i> Ver Informações do Cliente';
                }
            })
            .catch(err => {
                alert('Erro ao carregar informações');
                btn.innerHTML = '<i class="fas fa-info-circle"></i> Ver Informações do Cliente';
            });
    } else {
        infoDiv.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-info-circle"></i> Ver Informações do Cliente';
    }
}

function getStatusColor(status) {
    const colors = {
        'pendente': 'warning',
        'confirmado': 'info',
        'preparando': 'primary',
        'saiu_entrega': 'info',
        'entregue': 'success',
        'cancelado': 'danger'
    };
    return colors[status] || 'secondary';
}

function resolverTodosSuporte(codigo) {
    if (confirm('Marcar todas as mensagens deste pedido como resolvidas?')) {
        fetch('/suporte/resolver-todos', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ codigo_pedido: codigo })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        });
    }
}
</script>
<?php endif; ?>

<!-- Lista de Pedidos -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 style="color: #f8b531; margin: 0;"><i class="fas fa-list me-2"></i> Lista de Pedidos</h5>
                    <button class="btn btn-sm btn-outline-warning" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> Atualizar
                    </button>
                </div>

                <!-- Filtros -->
                <div class="mb-3">
                    <div class="btn-group btn-group-sm flex-wrap">
                        <button type="button" class="btn btn-outline-secondary filtro-status active" data-status="todos">Todos</button>
                        <button type="button" class="btn btn-outline-warning filtro-status" data-status="pendente">Pendentes</button>
                        <button type="button" class="btn btn-outline-info filtro-status" data-status="confirmado">Confirmados</button>
                        <button type="button" class="btn btn-outline-success filtro-status" data-status="entregue">Entregues</button>
                        <button type="button" class="btn btn-outline-danger filtro-status" data-status="cancelado">Cancelados</button>
                        <button type="button" class="btn btn-outline-secondary filtro-status" data-status="inativo">Inativos</button>
                    </div>
                </div>
                
                <?php if (!($isAdmin ?? true)): ?>
                <div class="alert alert-info py-2 mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>Você está visualizando apenas os pedidos de hoje.</small>
                </div>
                <?php endif; ?>

                <!-- Tabela -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabela-pedidos">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Telefone</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-pedidos">
                            <?php if (!empty($pedidos)): ?>
                                <?php foreach ($pedidos as $pedido): ?>
                                    <?php 
                                    $dataPedido = strtotime($pedido->criado_em);
                                    $cincoMinAtras = time() - (5 * 60);
                                    $statusPedido = strtolower(trim($pedido->status ?? 'pendente'));
                                    $isNovo = $dataPedido >= $cincoMinAtras && $statusPedido === 'pendente';
                                    $isInativo = $statusPedido === 'inativo';
                                    $isEntregue = $statusPedido === 'entregue';
                                    $isCancelado = $statusPedido === 'cancelado';
                                    $podeAlterar = !$isInativo && !$isEntregue && !$isCancelado;
                                    
                                    // Cancelados e inativos têm dados ocultos
                                    $ocultarDados = $isInativo || $isCancelado;
                                    
                                    // Código modificado para inativos e cancelados
                                    if ($isInativo) {
                                        $codigoExibir = $pedido->codigo . '-INATIVO';
                                    } elseif ($isCancelado) {
                                        $codigoExibir = $pedido->codigo . '-CANCELADO';
                                    } else {
                                        $codigoExibir = $pedido->codigo;
                                    }
                                    
                                    // Classes CSS da linha
                                    $rowClass = 'pedido-row';
                                    if ($isNovo) $rowClass .= ' pedido-novo';
                                    if ($isInativo) $rowClass .= ' pedido-inativo';
                                    if ($isCancelado) $rowClass .= ' pedido-cancelado';
                                    
                                    // Cor do código - cinza para inativos, vermelho para cancelados
                                    $corCodigo = $isInativo ? '#888' : ($isCancelado ? '#dc3545' : '#f8b531');
                                    ?>
                                    <tr class="<?= $rowClass ?>" 
                                        data-status="<?= $statusPedido ?>" 
                                        data-id="<?= $pedido->id ?>"
                                        data-criado="<?= $pedido->criado_em ?>">
                                        <td><span style="color: <?= $corCodigo ?>; font-weight: 600;">#<?= $pedido->id ?></span></td>
                                        <td>
                                            <strong style="color: <?= $corCodigo ?>; font-family: monospace;"><?= esc($codigoExibir) ?></strong>
                                            <?php if ($isNovo): ?><span class="badge-novo">NOVO</span><?php endif; ?>
                                            <?php if ($isInativo): ?><span class="badge-inativo">INATIVO</span><?php endif; ?>
                                            <?php if ($isCancelado): ?><span class="badge-cancelado"><i class="fas fa-times-circle me-1"></i>CANCELADO</span><?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($ocultarDados): ?>
                                                <span class="<?= $isCancelado ? 'status-cancelado-text' : 'status-inativo-text' ?>">---</span>
                                            <?php else: ?>
                                                <?= esc($pedido->nome_cliente) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($pedido->telefone_cliente) ?></td>
                                        <td>
                                            <?php if ($ocultarDados): ?>
                                                <span class="<?= $isCancelado ? 'status-cancelado-text' : 'status-inativo-text' ?>">---</span>
                                            <?php else: ?>
                                                <strong class="text-success">R$ <?= number_format($pedido->valor_total, 2, ',', '.') ?></strong>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($isInativo): ?>
                                                <span class="badge-inativo"><i class="fas fa-ban me-1"></i>Inativo</span>
                                            <?php elseif ($isCancelado): ?>
                                                <span class="badge-cancelado"><i class="fas fa-times me-1"></i>Cancelado</span>
                                            <?php elseif ($isEntregue): ?>
                                                <span class="badge bg-success"><i class="fas fa-check-double me-1"></i>Entregue</span>
                                            <?php elseif ($statusPedido === 'pendente'): ?>
                                                <select class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                                    <option value="pendente" selected>⏳ Pendente</option>
                                                    <option value="confirmado">✅ Confirmado</option>
                                                    <option value="cancelado">❌ Cancelado</option>
                                                </select>
                                            <?php elseif ($statusPedido === 'confirmado'): ?>
                                                <select class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                                    <option value="confirmado" selected>✅ Confirmado</option>
                                                    <option value="entregue">✔️ Entregue</option>
                                                    <option value="cancelado">❌ Cancelado</option>
                                                </select>
                                            <?php else: ?>
                                                <select class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                                    <option value="pendente">⏳ Pendente</option>
                                                    <option value="confirmado">✅ Confirmado</option>
                                                    <option value="entregue">✔️ Entregue</option>
                                                    <option value="cancelado">❌ Cancelado</option>
                                                </select>
                                            <?php endif; ?>
                                        </td>
                                        <td><small><?= date('d/m/Y H:i', strtotime($pedido->criado_em)) ?></small></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <?php if ($ocultarDados): ?>
                                                    <span class="<?= $isCancelado ? 'text-danger' : 'text-muted' ?>"><i class="fas fa-lock"></i></span>
                                                <?php else: ?>
                                                    <a href="<?= site_url("admin/pedidos/{$pedido->id}") ?>" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
                                                    <a href="<?= site_url("admin/pedidos/imprimir/{$pedido->id}") ?>" class="btn btn-secondary btn-sm" title="Imprimir" target="_blank"><i class="fas fa-print"></i></a>
                                                    <?php if ($podeAlterar): ?>
                                                        <a href="<?= site_url("admin/pedidos/cancelar/{$pedido->id}") ?>" class="btn btn-danger btn-sm" title="Cancelar" onclick="return confirm('Cancelar?')"><i class="fas fa-times"></i></a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="sem-pedidos">
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x mb-2" style="color: #444;"></i>
                                        <p style="color: #666; margin: 0;">Nenhum pedido</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<button id="btn-testar-som" class="btn btn-outline-info" title="Testar som">
    <i class="fas fa-bell"></i>
</button>

<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>
<script>
let ultimoPedidoId = <?= !empty($pedidos) ? $pedidos[0]->id : 0 ?>;
const INTERVALO = 10000;
const TEMPO_NOVO = 5 * 60 * 1000;
let suporteAberto = false;



$(document).ready(function() {
    // Auto-refresh
    setInterval(function() {
        $('#icone-sync').addClass('fa-spin');
        verificarNovosPedidos();
        verificarNovosSuportes();
        setTimeout(() => $('#icone-sync').removeClass('fa-spin'), 1000);
    }, INTERVALO);
    
    // Verificar badges expirados
    setInterval(verificarBadgesExpirados, 30000);
    
    // Mudança de status
    $(document).on('change', '.status-select', function() {
        const pedidoId = $(this).data('pedido-id');
        const novoStatus = $(this).val();
        const $select = $(this);
        
        console.log('Atualizando status:', pedidoId, novoStatus);
        
        $.ajax({
            url: '<?= site_url('admin/pedidos/atualizar-status') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                pedido_id: pedidoId,
                status: novoStatus
            },
            beforeSend: function() {
                $select.prop('disabled', true);
            },
            success: function(response) {
                console.log('Resposta:', response);
                if (response.success) {
                    mostrarNotificacao('✅ Status atualizado!', 'success');
                    $select.closest('tr').attr('data-status', novoStatus);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    mostrarNotificacao('❌ Erro: ' + response.message, 'danger');
                    $select.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro AJAX:', xhr.responseText);
                mostrarNotificacao('❌ Erro de conexão: ' + error, 'danger');
                $select.prop('disabled', false);
            }
        });
    });
    
    // Filtros
    $('.filtro-status').on('click', function() {
        const status = $(this).data('status');
        $('.filtro-status').removeClass('active');
        $(this).addClass('active');
        
        if (status === 'todos') {
            $('.pedido-row').show();
        } else {
            $('.pedido-row').hide();
            $(`.pedido-row[data-status="${status}"]`).show();
        }
    });
    
    // Testar som
    $('#btn-testar-som').on('click', function() {
        tocarSom();
        $(this).addClass('btn-success').removeClass('btn-outline-info');
        setTimeout(() => $(this).removeClass('btn-success').addClass('btn-outline-info'), 500);
    });
    
    // Habilitar som no primeiro clique
    $(document).one('click', habilitarSom);
});

function verificarNovosPedidos() {
    $.get('<?= site_url('admin/pedidos/verificar-novos') ?>/' + ultimoPedidoId, function(response) {
        if (response.success) {
            // Novos pedidos
            if (response.novos_pedidos && response.novos_pedidos.length > 0) {
                tocarSom();
                mostrarNotificacao('🆕 ' + response.novos_pedidos.length + ' novo(s) pedido(s)!', 'warning');
                ultimoPedidoId = response.novos_pedidos[0].id;
                setTimeout(() => location.reload(), 2000);
                return;
            }
            
            // Houve alterações de status (pedidos viraram inativos)
            if (response.recarregar) {
                mostrarNotificacao('🔄 Atualizando lista...', 'info');
                setTimeout(() => location.reload(), 1000);
                return;
            }
            
            // Atualizar estatísticas
            if (response.estatisticas) {
                $('#stat-pendentes').text(response.estatisticas.pendentes || 0);
                $('#stat-confirmados').text(response.estatisticas.confirmados || 0);
                $('#stat-entregues').text(response.estatisticas.entregues || 0);
                $('#stat-cancelados').text(response.estatisticas.cancelados || 0);
                $('#stat-inativos').text(response.estatisticas.inativos || 0);
            }
        }
    });
}

function verificarBadgesExpirados() {
    const agora = Date.now();
    $('.pedido-row').each(function() {
        const criado = $(this).data('criado');
        if (criado && (agora - new Date(criado).getTime()) > TEMPO_NOVO) {
            $(this).removeClass('pedido-novo').find('.badge-novo').remove();
        }
    });
}

function tocarSom() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        [0, 0.2, 0.4].forEach((t, i) => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = i % 2 === 0 ? 1200 : 900;
            osc.type = 'sine';
            const now = ctx.currentTime + t;
            gain.gain.setValueAtTime(0, now);
            gain.gain.linearRampToValueAtTime(0.3, now + 0.01);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.4);
            osc.start(now);
            osc.stop(now + 0.4);
        });
    } catch(e) { console.warn('Som falhou:', e); }
}

function habilitarSom() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        ctx.resume();
    } catch(e) {}
}

function mostrarNotificacao(msg, tipo) {
    const cores = { success: '#28a745', warning: '#ffc107', danger: '#dc3545' };
    const notif = $('<div>').css({
        position: 'fixed', top: '20px', right: '20px', zIndex: 9999,
        background: cores[tipo], color: tipo === 'warning' ? '#000' : '#fff',
        padding: '12px 20px', borderRadius: '8px', fontWeight: 500,
        boxShadow: '0 4px 15px rgba(0,0,0,0.3)'
    }).text(msg);
    $('body').append(notif);
    setTimeout(() => notif.fadeOut(300, function() { $(this).remove(); }), 3000);
}

// Funções de Suporte
function toggleSuportes() {
    const secao = $('#secao-suporte');
    secao.slideToggle();
    suporteAberto = secao.is(':visible');
}

function verificarNovosSuportes() {
    $.get('/suporte/listar', function(response) {
        if (response.success) {
            const pendentes = response.suportes.filter(s => s.status === 'pendente').length;
            $('#stat-suporte').text(pendentes);
            
            if (pendentes > totalSuportesAnterior) {
                mostrarNotificacao(`${pendentes - totalSuportesAnterior} nova(s) solicitação(ões) de suporte!`, 'warning');
                tocarSom();
                setTimeout(() => location.reload(), 2000);
            }
            totalSuportesAnterior = pendentes;
        }
    });
}

function abrirWhatsApp(telefone, nome, codigoPedido) {
    if (!telefone || telefone === '') {
        alert('Telefone não cadastrado para este cliente.');
        return;
    }
    
    const mensagem = encodeURIComponent(`Olá ${nome}, sobre seu pedido ${codigoPedido}...`);
    const url = `https://wa.me/55${telefone}?text=${mensagem}`;
    window.open(url, '_blank');
}

function resolverSuporte(id) {
    if (!confirm('Marcar este suporte como resolvido e remover da lista?')) return;
    
    $.ajax({
        url: '/suporte/deletar/' + id,
        method: 'POST',
        success: function(response) {
            if (response.success) {
                mostrarNotificacao('Suporte resolvido e removido!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                mostrarNotificacao('Erro ao resolver suporte', 'danger');
            }
        },
        error: function() {
            mostrarNotificacao('Erro ao resolver suporte', 'danger');
        }
    });
}

// Verificar novos suportes
let totalSuportesAnterior = <?= $total_suportes ?? 0 ?>;

// Salvar estado da aba de suporte antes de recarregar
window.addEventListener('beforeunload', function() {
    if (suporteAberto) {
        sessionStorage.setItem('suporteAberto', 'true');
    } else {
        sessionStorage.removeItem('suporteAberto');
    }
});

// Restaurar estado da aba de suporte após recarregar
$(document).ready(function() {
    if (sessionStorage.getItem('suporteAberto') === 'true') {
        $('#secao-suporte').show();
        suporteAberto = true;
    }
});
</script>
<?php echo $this->endSection(); ?>
