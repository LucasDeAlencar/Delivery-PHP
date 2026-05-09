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
        color: #0055ff;
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
        color: #0055ff;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        border-bottom: 2px solid #0055ff;
        padding: 0.75rem;
        white-space: nowrap;
    }
    .table tbody td { padding: 0.75rem; vertical-align: middle; }
    .table tbody tr:hover { background: rgba(248, 181, 49, 0.1); }
    
    /* Responsivo */
    @media (max-width: 768px) {
        .container-fluid { padding: 10px !important; }
        .card-estatistica { margin-bottom: 10px; }
        .card-estatistica h3 { font-size: 1.5rem; }
        .card-estatistica h6 { font-size: 0.65rem; }
        
        .d-flex.justify-content-between { flex-direction: column !important; align-items: stretch !important; gap: 10px; }
        .d-flex.gap-2 { flex-wrap: wrap; }
        
        .btn-sm { padding: 4px 8px; font-size: 0.7rem; }
        
        .pesquisa-pedido { width: 100% !important; }
        
        .filtro-status { font-size: 0.65rem; padding: 4px 8px; }
        
        .table-responsive { margin: 0 -10px; padding: 0 10px; }
        .table { min-width: 500px; font-size: 0.8rem; }
        .table thead th, .table tbody td { padding: 0.5rem; font-size: 0.75rem; }
        
        .pedido-codigo { font-size: 0.85rem; }
        .cliente-nome { font-size: 0.8rem; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .valor-valor { font-weight: 700; }
        
        .badge { font-size: 0.6rem; padding: 2px 5px; }
        
        .acoes-botoes { display: flex; flex-direction: column; gap: 3px; }
        .acoes-botoes .btn { padding: 3px 6px; font-size: 0.65rem; }
        
        .stat-card { font-size: 0.8rem; }
        
        .cabecalho-mobile { display: flex; flex-direction: column; gap: 10px; }
        .cabecalho-mobile .d-flex.gap-2 { justify-content: space-between; }
        
        .filtros-mobile { display: flex; flex-wrap: wrap; gap: 5px; }
        .filtros-mobile .btn { flex: 1 1 auto; text-align: center; min-width: 60px; }
    }
    
    @media (max-width: 576px) {
        h4 { font-size: 1.1rem !important; }
        
        .card { margin-bottom: 10px; }
        .card-body { padding: 10px !important; }
        
        .table { min-width: 400px; }
        
        .mobile-card { 
            display: block !important; 
            background: #2a2a2a; 
            border: 1px solid #444; 
            border-radius: 8px; 
            padding: 10px; 
            margin-bottom: 10px; 
        }
        .mobile-card .row { margin: 0; }
        .mobile-card .col-1, .mobile-card .col-2, .mobile-card .col-3, .mobile-card .col-4, 
        .mobile-card .col-5, .mobile-card .col-6, .mobile-card .col-7, .mobile-card .col-8, 
        .mobile-card .col-9, .mobile-card .col-10, .mobile-card .col-11, .mobile-card .col-12 {
            padding: 2px 5px;
        }
    }
    
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
        border-color: #0055ff;
        outline: none;
    }
    
    .filtro-status {
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 0.75rem;
        margin: 2px;
    }
    .filtro-status.active {
        background: #0055ff !important;
        color: #1a1a1a !important;
        border-color: #0055ff !important;
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
    .pesquisa-pedido {
        background: #2a2a2a;
        border: 2px solid #555;
        color: #fff;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.85rem;
        width: 250px;
    }
    .pesquisa-pedido:focus {
        border-color: #0055ff;
        outline: none;
    }
    .pesquisa-pedido.por-id { color: #ffc107; }
    .pesquisa-pedido.por-codigo { color: #28a745; }
    .pesquisa-pedido.por-mesa { color: #17a2b8; }
    .pesquisa-pedido.por-valor-maior { color: #2ecc71; }
    .pesquisa-pedido.por-valor-menor { color: #e74c3c; }
    .pesquisa-pedido.por-valor { color: #9b59b6; }
    .pesquisa-pedido.por-hora { color: #f39c12; }
    .pesquisa-pedido.por-data { color: #1abc9c; }
</style>
<?php echo $this->endSection(); ?>

<?php echo $this->section('conteudos'); ?>

<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 style="color: #0055ff; font-weight: 700; margin: 0;">
                    <i class="fas fa-shopping-cart me-2"></i> Pedidos
                </h4>
                <small style="color: #999;">Atualização automática a cada 10s</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success" style="padding: 6px 12px; font-size: 0.75rem;">
                    <i class="fas fa-sync-alt me-1" id="icone-sync"></i> Auto-refresh
                </span>
                <a href="<?= site_url('admin/pedidos/csv') ?>" class="btn btn-sm btn-outline-success" style="padding: 5px 10px; font-size: 0.75rem;">
                    <i class="fas fa-file-csv me-1"></i> <?= ($isAdmin ?? false) ? 'Relação CSV' : 'CSV do Dia' ?>
                </a>
                <?php if ($isAdmin): ?>
                <button type="button" class="btn btn-sm btn-outline-danger" style="padding: 5px 10px; font-size: 0.75rem;" onclick="limparPedidos()">
                    <i class="fas fa-trash-alt me-1"></i> Limpar Pedidos
                </button>
                <?php endif; ?>
            </div>
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
                        <h6 class="mb-1">Finalizados</h6>
                        <h3 id="stat-finalizados"><?= $estatisticas['finalizados'] ?? 0 ?></h3>
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
        <div class="card card-estatistica" style="border-left-color: #c47a00; cursor:pointer;" onclick="$('.filtro-status[data-status=em_aberto]').trigger('click')">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Em Aberto</h6>
                        <h3 id="stat-em-aberto" style="color: #c47a00 !important;"><?= $estatisticas['em_aberto'] ?? 0 ?></h3>
                    </div>
                    <div class="icon-circle" style="background: #c47a00;">
                        <i class="fas fa-folder-open text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg mb-2">
        <div class="card card-estatistica" style="border-left-color: #e65100; cursor:pointer;" onclick="$('.filtro-status[data-status=nao_concluido]').trigger('click')">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Não Concluídos</h6>
                        <h3 id="stat-nao-concluido" style="color: #e65100 !important;"><?= $estatisticas['nao_concluido'] ?? 0 ?></h3>
                    </div>
                    <div class="icon-circle" style="background: #e65100;">
                        <i class="fas fa-exclamation-triangle text-white"></i>
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
                            <p style="margin: 0; color: #0055ff; cursor: pointer; word-wrap: break-word;" onclick="toggleSuporte('<?= $codigo ?>')">
                                <i class="fas fa-chevron-down" id="icon-<?= $codigo ?>"></i>
                                <strong>Pedido:</strong> <?= esc($codigo) ?> | 
                                <strong>Cliente:</strong> <?= esc($grupo['cliente_nome']) ?> 
                                <span style="background: #ff9800; color: #000; padding: 2px 8px; border-radius: 10px; font-size: 12px; margin-left: 10px;"><?= count($grupo['mensagens']) ?> mensagem(ns)</span>
                            </p>
                            <p style="margin: 5px 0 0 0; color: #ccc; word-wrap: break-word;"><strong>Telefone:</strong> <?= esc($grupo['cliente_telefone']) ?: 'Não informado' ?></p>
                            
                            <div id="detalhes-<?= $codigo ?>" style="display: none; margin-top: 15px;">
                                <div style="background: #3a3a3a; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                                    <div class="suporte-info-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 10px;">
                                        <h6 style="color: #0055ff; margin: 0;">Mensagens:</h6>
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
                        htmlItens = '<div style="margin-top: 15px;"><h6 style="color: #0055ff; margin-bottom: 10px;">Itens do Pedido:</h6>';
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
                                        <span style="color: #0055ff;">R$ ${precoItem.toFixed(2).replace('.', ',')}</span>
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
                            <div><strong>Status:</strong> <span class="badge bg-${getStatusColor(p.status)}" ${p.status==='em_aberto'?'style="background:#c47a00!important;"':''}>${p.status==='em_aberto'?'Em Aberto':p.status}</span></div>
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
        'em_aberto':    'orange',
        'pendente':     'warning',
        'confirmado':   'info',
        'preparando':   'primary',
        'saiu_entrega': 'info',
        'finalizado':   'success',
        'cancelado':    'danger',
        'nao_concluido':'secondary'
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
                    <h5 style="color: #0055ff; margin: 0;"><i class="fas fa-list me-2"></i> Lista de Pedidos</h5>
                    <button class="btn btn-sm btn-outline-warning" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> Atualizar
                    </button>
                </div>

                <!-- Filtros -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="btn-group btn-group-sm flex-wrap">
                            <button type="button" class="btn btn-outline-secondary filtro-status active" data-status="todos">Todos</button>
                            <button type="button" class="btn btn-outline-warning filtro-status" data-status="pendente">Pendentes</button>
                            <button type="button" class="btn btn-outline-info filtro-status" data-status="confirmado">Confirmados</button>
                            <button type="button" class="btn btn-outline-success filtro-status" data-status="finalizado">Finalizados</button>
                            <button type="button" class="btn btn-outline-danger filtro-status" data-status="cancelado">Cancelados</button>
                            <button type="button" class="btn btn-outline-secondary filtro-status" data-status="inativo">Inativos</button>
                            <button type="button" class="btn filtro-status" data-status="em_aberto" style="border-color:#c47a00;color:#c47a00;">Em Aberto</button>
                            <button type="button" class="btn btn-outline-secondary filtro-status" data-status="nao_concluido">Não Concluídos</button>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-search" style="color: #999;"></i>
                            <input type="text" id="campo-pesquisa" class="pesquisa-pedido" placeholder="Pesquisar..." autocomplete="off">
                            <small class="text-muted" style="font-size: 0.7rem;">
                                <span style="color: #ffc107;">##</span> ID &nbsp;
                                <span style="color: #28a745;">$$</span> Código &nbsp;
                                <span style="color: #2ecc71;">&gt;&gt;</span> Maior &nbsp;
                                <span style="color: #e74c3c;">&lt;&lt;</span> Menor &nbsp;
                                <span style="color: #17a2b8;">@M</span> Mesa &nbsp;
                                <span style="color: #9b59b6;">@V</span> Valor &nbsp;
                                <span style="color: #f39c12;">@H</span> Hora &nbsp;
                                <span style="color: #1abc9c;">@D</span> Data
                            </small>
                        </div>
                    </div>
                </div>
                
                <?php if (!($isAdmin ?? true)): ?>
                <div class="alert alert-info py-2 mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>Você está visualizando apenas os pedidos de hoje.</small>
                </div>
                <?php endif; ?>

                <!-- Tabela -->
                <div class="table-responsive desktop-tabela">
                    <table class="table table-bordered" id="tabela-pedidos">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Telefone</th>
                                <th>Mesa</th>
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
                                    $isFinalizado = $statusPedido === 'finalizado';
                                    $isCancelado = $statusPedido === 'cancelado';
                                    $podeAlterar = !$isInativo && !$isFinalizado && !$isCancelado;
                                    
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
                                    $corCodigo = $isInativo ? '#888' : ($isCancelado ? '#dc3545' : '#0055ff');
                                    
                                    // Mesa
                                    $db = \Config\Database::connect();
                                    $mesaPedido = !empty($pedido->mesa_id)
                                        ? $db->table('mesas')->where('id', $pedido->mesa_id)->get()->getRow()
                                        : null;
                                    $mesaTexto = $mesaPedido ? 'Mesa ' . $mesaPedido->numero : ((!empty($pedido->local_retirada) && $pedido->local_retirada === 'balcao') ? 'Balcão' : '—');
                                    ?>
                                    <tr class="<?= $rowClass ?>" 
                                        data-status="<?= $statusPedido ?>" 
                                        data-id="<?= $pedido->id ?>"
                                        data-codigo="<?= strtolower($pedido->codigo) ?>"
                                        data-criado="<?= $pedido->criado_em ?>"
                                        data-mesa="<?= $mesaPedido ? (int)$mesaPedido->numero : 0 ?>"
                                        data-valor="<?= $pedido->valor_total ?>"
                                        data-hora="<?= date('H:i', strtotime($pedido->criado_em)) ?>"
                                        data-data="<?= date('d/m/Y', strtotime($pedido->criado_em)) ?>">
                                        <td><span style="color: <?= $corCodigo ?>; font-weight: 600;">#<?= $pedido->id ?></span></td>
                                        <td>
                                            <strong style="color: <?= $corCodigo ?>; font-family: monospace;"><?= esc($codigoExibir) ?></strong>
                                            <?php if ($isNovo): ?><span class="badge-novo">NOVO</span><?php endif; ?>
                                            <?php if ($isInativo): ?><span class="badge-inativo">INATIVO</span><?php endif; ?>
                                            <?php if ($isCancelado): ?><span class="badge-cancelado"><i class="fas fa-times-circle me-1"></i>CANCELADO</span><?php endif; ?>
                                        </td>
                                        <td class="cliente-nome">
                                            <?php if ($ocultarDados): ?>
                                                <span class="<?= $isCancelado ? 'status-cancelado-text' : 'status-inativo-text' ?>">---</span>
                                            <?php else: ?>
                                                <?= esc($pedido->nome_cliente) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($pedido->telefone_cliente) ?></td>
                                        <td><?= $mesaTexto ?></td>
                                        <td class="valor-valor">
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
                                            <?php elseif ($isFinalizado): ?>
                                                <span class="badge bg-success"><i class="fas fa-check-double me-1"></i>Finalizado</span>
                                            <?php elseif ($statusPedido === 'em_aberto'): ?>
                                                <a href="<?= site_url('admin/venda-especifica') ?>" class="badge text-decoration-none" style="background:#c47a00;font-size:.8rem;padding:5px 8px;">
                                                    <i class="fas fa-folder-open me-1"></i>Em Aberto
                                                </a>
                                            <?php elseif ($statusPedido === 'pendente'): ?>
                                                <select class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                                    <option value="pendente" selected>⏳ Pendente</option>
                                                    <option value="confirmado">✅ Confirmado</option>
                                                    <option value="cancelado">❌ Cancelado</option>
                                                </select>
                                            <?php elseif ($statusPedido === 'confirmado'): ?>
                                                <select class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                                    <option value="confirmado" selected>✅ Confirmado</option>
                                                    <option value="finalizado">✔️ Finalizado</option>
                                                    <option value="cancelado">❌ Cancelado</option>
                                                </select>
                                            <?php elseif ($statusPedido === 'nao_concluido'): ?>
                                                <select class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                                    <option value="nao_concluido" selected>⚠️ Não Concluído</option>
                                                    <option value="pendente">⏳ Pendente</option>
                                                    <option value="cancelado">❌ Cancelado</option>
                                                </select>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?= esc($statusPedido) ?></span>
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
                
                <!-- Versão Mobile: Cards -->
                <div class="mobile-cards d-none">
                    <?php if (!empty($pedidos)): ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <?php 
                            $dataPedido = strtotime($pedido->criado_em);
                            $cincoMinAtras = time() - (5 * 60);
                            $statusPedido = strtolower(trim($pedido->status ?? 'pendente'));
                            $isNovo = $dataPedido >= $cincoMinAtras && $statusPedido === 'pendente';
                            $isInativo = $statusPedido === 'inativo';
                            $isFinalizado = $statusPedido === 'finalizado';
                            $isCancelado = $statusPedido === 'cancelado';
                            $podeAlterar = !$isInativo && !$isFinalizado && !$isCancelado;
                            $ocultarDados = $isInativo || $isCancelado;
                            
                            if ($isInativo) {
                                $codigoExibir = $pedido->codigo . '-INATIVO';
                            } elseif ($isCancelado) {
                                $codigoExibir = $pedido->codigo . '-CANCELADO';
                            } else {
                                $codigoExibir = $pedido->codigo;
                            }
                            
                            $rowClass = 'pedido-row';
                            if ($isNovo) $rowClass .= ' pedido-novo';
                            if ($isInativo) $rowClass .= ' pedido-inativo';
                            if ($isCancelado) $rowClass .= ' pedido-cancelado';
                            
                            $corCodigo = $isInativo ? '#888' : ($isCancelado ? '#dc3545' : '#0055ff');
                            
                            $db = \Config\Database::connect();
                            $mesaPedido = !empty($pedido->mesa_id)
                                ? $db->table('mesas')->where('id', $pedido->mesa_id)->get()->getRow()
                                : null;
                            $mesaTexto = $mesaPedido ? 'Mesa ' . $mesaPedido->numero : ((!empty($pedido->local_retirada) && $pedido->local_retirada === 'balcao') ? 'Balcão' : '');
                            ?>
                            <div class="<?= $rowClass ?> mobile-card" 
                                data-status="<?= $statusPedido ?>" 
                                data-id="<?= $pedido->id ?>"
                                data-codigo="<?= strtolower($pedido->codigo) ?>"
                                data-criado="<?= $pedido->criado_em ?>">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong style="color: <?= $corCodigo ?>;">#<?= $pedido->id ?> - <?= esc($codigoExibir) ?></strong>
                                        <?php if ($isNovo): ?><span class="badge-novo">NOVO</span><?php endif; ?>
                                        <?php if ($isInativo): ?><span class="badge-inativo">INATIVO</span><?php endif; ?>
                                        <?php if ($isCancelado): ?><span class="badge-cancelado">CANCELADO</span><?php endif; ?>
                                    </div>
                                    <strong class="text-success">R$ <?= number_format($pedido->valor_total, 2, ',', '.') ?></strong>
                                </div>
                                <div class="mb-1"><i class="fas fa-user me-1"></i><?= esc($pedido->nome_cliente) ?></div>
                                <div class="mb-1"><i class="fas fa-phone me-1"></i><?= esc($pedido->telefone_cliente) ?></div>
                                <?php if ($mesaTexto): ?>
                                <div class="mb-1"><i class="fas fa-chair me-1"></i><?= $mesaTexto ?></div>
                                <?php endif; ?>
                                <div class="mb-2"><i class="fas fa-clock me-1"></i><?= date('d/m H:i', strtotime($pedido->criado_em)) ?></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if ($isInativo): ?>
                                            <span class="badge-inativo">Inativo</span>
                                        <?php elseif ($isCancelado): ?>
                                            <span class="badge-cancelado">Cancelado</span>
                                        <?php elseif ($isFinalizado): ?>
                                            <span class="badge bg-success">Finalizado</span>
                                        <?php elseif ($statusPedido === 'pendente'): ?>
                                            <select class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                                <option value="pendente" selected>Pendente</option>
                                                <option value="confirmado">Confirmado</option>
                                                <option value="cancelado">Cancelado</option>
                                            </select>
                                        <?php elseif ($statusPedido === 'confirmado'): ?>
                                            <select class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                                <option value="confirmado" selected>Confirmado</option>
                                                <option value="finalizado">Finalizado</option>
                                                <option value="cancelado">Cancelado</option>
                                            </select>
                                        <?php elseif ($statusPedido === 'nao_concluido'): ?>
                                            <select class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                                <option value="nao_concluido" selected>⚠️ Não Concluído</option>
                                                <option value="pendente">Pendente</option>
                                                <option value="cancelado">Cancelado</option>
                                            </select>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= esc($statusPedido) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="acoes-botoes">
                                        <?php if (!$ocultarDados): ?>
                                            <a href="<?= site_url("admin/pedidos/{$pedido->id}") ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                            <a href="<?= site_url("admin/pedidos/imprimir/{$pedido->id}") ?>" class="btn btn-secondary btn-sm" target="_blank"><i class="fas fa-print"></i></a>
                                            <?php if ($podeAlterar): ?>
                                                <a href="<?= site_url("admin/pedidos/cancelar/{$pedido->id}") ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancelar?')"><i class="fas fa-times"></i></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-2x mb-2" style="color: #444;"></i>
                            <p style="color: #666; margin: 0;">Nenhum pedido</p>
                        </div>
                    <?php endif; ?>
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

// Recupera IDs de cancelamentos já notificados do sessionStorage
let canceladosNotificados = JSON.parse(sessionStorage.getItem('canceladosNotificados') || '[]');



$(document).ready(function() {
    // Alternar entre tabela e cards conforme tamanho da tela
    function alternarVisualizacao() {
        const isMobile = window.innerWidth < 768;
        $('.desktop-tabela').toggleClass('d-none', isMobile);
        $('.mobile-cards').toggleClass('d-none', !isMobile);
    }
    
    alternarVisualizacao();
    $(window).on('resize', alternarVisualizacao);
    
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
     let statusAtual = 'todos';
     
     $('.filtro-status').on('click', function() {
         statusAtual = $(this).data('status');
         $('.filtro-status').removeClass('active');
         $(this).addClass('active');
         
         filtrarPedidos();
     });
     
     function filtrarPedidos() {
         const termo = $('#campo-pesquisa').val().trim();
         // Lê status do botão ativo (garante sincronização)
         const statusFiltro = $('.filtro-status.active').data('status') || 'todos';
         
         $('.pedido-row').each(function() {
             const linha = $(this);
             let mostrar = true;
             
             // Filtro de status
             if (statusFiltro !== 'todos' && linha.data('status') !== statusFiltro) {
                 mostrar = false;
             }
             
             // Filtro de pesquisa
             if (mostrar && termo) {
                 let encontrado = false;
                 
                 if (termo.startsWith('##')) {
                     const idBusca = termo.replace('##', '').trim();
                     encontrado = String(linha.data('id')).includes(idBusca);
                 } else if (termo.startsWith('$$')) {
                     const codBusca = termo.replace('$$', '').trim().toLowerCase();
                     encontrado = (linha.data('codigo') || '').toLowerCase().includes(codBusca);
                 } else if (termo.startsWith('@M')) {
                     const mesaBusca = termo.replace('@M', '').trim();
                     const mesaLinha = linha.data('mesa');
                     encontrado = String(mesaLinha || '').includes(mesaBusca);
                 } else if (termo.startsWith('>>')) {
                     const valorMin = parseFloat(termo.replace('>>', '').trim().replace(',', '.'));
                     const valorLinha = parseFloat(linha.data('valor')) || 0;
                     encontrado = !isNaN(valorMin) && valorLinha > valorMin;
                 } else if (termo.startsWith('<<')) {
                     const valorMax = parseFloat(termo.replace('<<', '').trim().replace(',', '.'));
                     const valorLinha = parseFloat(linha.data('valor')) || 0;
                     encontrado = !isNaN(valorMax) && valorLinha < valorMax;
                 } else if (termo.startsWith('@V')) {
                     const valorBusca = termo.replace('@V', '').trim();
                     const valorLinha = String(Math.floor(parseFloat(linha.data('valor')) || 0));
                     encontrado = valorLinha.includes(valorBusca);
                 } else if (termo.startsWith('@H')) {
                     const horaBusca = termo.replace('@H', '').trim();
                     const horaLinha = linha.data('hora') || '';
                     if (horaBusca.includes(':')) {
                         encontrado = horaLinha === horaBusca;
                     } else {
                         encontrado = horaLinha.startsWith(horaBusca + ':');
                     }
                 } else if (termo.startsWith('@D')) {
                     const dataBusca = termo.replace('@D', '').trim();
                     const dataLinha = linha.data('data') || '';
                     encontrado = dataLinha === dataBusca;
                 } else {
                     const texto = linha.text().toLowerCase();
                     encontrado = texto.includes(termo.toLowerCase());
                 }
                 
                 mostrar = encontrado;
             }
             
             linha.toggle(mostrar);
         });
     }
    
    $('#campo-pesquisa').on('input', function() {
        const termo = $(this).val().trim();
        
        // Atualiza cor
        $(this).removeClass('por-id por-codigo por-mesa por-valor-maior por-valor-menor por-valor por-hora por-data');
        if (termo.startsWith('##')) {
            $(this).addClass('por-id');
        } else if (termo.startsWith('$$')) {
            $(this).addClass('por-codigo');
        } else if (termo.startsWith('@M')) {
            $(this).addClass('por-mesa');
        } else if (termo.startsWith('>>')) {
            $(this).addClass('por-valor-maior');
        } else if (termo.startsWith('<<')) {
            $(this).addClass('por-valor-menor');
        } else if (termo.startsWith('@V')) {
            $(this).addClass('por-valor');
        } else if (termo.startsWith('@H')) {
            $(this).addClass('por-hora');
        } else if (termo.startsWith('@D')) {
            $(this).addClass('por-data');
        }
        
        // Filtra
        filtrarPedidos();
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
            let recarregar = false;
            let mensagem = '';
            let tipoNotificacao = 'info';
            
            // Novos pedidos
            if (response.novos_pedidos && response.novos_pedidos.length > 0) {
                tocarSom();
                mensagem += '🆕 ' + response.novos_pedidos.length + ' novo(s) pedido(s)! ';
                tipoNotificacao = 'warning';
                ultimoPedidoId = response.novos_pedidos[0].id;
                recarregar = true;
            }
            
            // Pedidos cancelados pelo cliente (apenas se não notificados)
            if (response.pedidos_cancelados && response.pedidos_cancelados.length > 0) {
                // Filtrar apenas cancelamentos não notificados
                let novosCancelamentos = response.pedidos_cancelados.filter(p => {
                    return !canceladosNotificados.includes(p.id);
                });
                
                if (novosCancelamentos.length > 0) {
                    tocarSom();
                    mensagem += '❌ ' + novosCancelamentos.length + ' pedido(s) cancelado(s) pelo cliente! ';
                    tipoNotificacao = 'danger';
                    recarregar = true;
                    
                    // Marcar como notificados e salvar no sessionStorage
                    novosCancelamentos.forEach(p => {
                        if (!canceladosNotificados.includes(p.id)) {
                            canceladosNotificados.push(p.id);
                        }
                    });
                    sessionStorage.setItem('canceladosNotificados', JSON.stringify(canceladosNotificados));
                }
            }
            
            // Houve alterações de status (pedidos viraram inativos)
            if (response.recarregar) {
                mensagem += '🔄 Alterações detectadas. ';
                recarregar = true;
            }
            
            if (recarregar) {
                mostrarNotificacao(mensagem, tipoNotificacao);
                setTimeout(() => location.reload(), 2000);
                return;
            }
            
            // Atualizar estatísticas
            if (response.estatisticas) {
                $('#stat-pendentes').text(response.estatisticas.pendentes || 0);
                $('#stat-confirmados').text(response.estatisticas.confirmados || 0);
                $('#stat-finalizados').text(response.estatisticas.finalizados || 0);
                $('#stat-cancelados').text(response.estatisticas.cancelados || 0);
                $('#stat-inativos').text(response.estatisticas.inativos || 0);
                $('#stat-nao-concluido').text(response.estatisticas.nao_concluido || 0);
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
        const agora = ctx.currentTime;
        
        // Som de sino (bell) - similar ao Saipos
        // Harmônicos: fundamental + oitavas + quinta
        const frequenciaBase = 1200;
        const harmonicos = [1, 2, 3, 4.2, 5.4]; // Harmônicos do sino
        const volumes = [0.6, 0.4, 0.25, 0.15, 0.1]; // Decaimento dos harmônicos
        
        harmonicos.forEach((multiplicador, i) => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = frequenciaBase * multiplicador;
            osc.type = 'sine'; // Sino usa onda senoidal
            
            const now = agora;
            const decay = 1.5 + (i * 0.3); // Cada harmônico dura mais
            
            gain.gain.setValueAtTime(0, now);
            gain.gain.linearRampToValueAtTime(volumes[i], now + 0.01);
            gain.gain.exponentialRampToValueAtTime(0.001, now + decay);
            
            osc.start(now);
            osc.stop(now + decay + 0.1);
        });
        
        // Segundo sino (quinta acima) para reforçar
        const freq2 = 1800;
        const harm2 = [1, 2, 3];
        const vol2 = [0.4, 0.25, 0.15];
        
        setTimeout(() => {
            const t = ctx.currentTime;
            harm2.forEach((mult, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = freq2 * mult;
                osc.type = 'sine';
                
                gain.gain.setValueAtTime(0, t);
                gain.gain.linearRampToValueAtTime(vol2[i], t + 0.01);
                gain.gain.exponentialRampToValueAtTime(0.001, t + 1.2);
                
                osc.start(t);
                osc.stop(t + 1.3);
            });
        }, 200);
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

function limparPedidos() {
    if (!confirm('ATENÇÃO: Isso irán APAGAR TODOS os pedidos definitivamente. Continuar?')) return;
    if (!confirm('Tem certeza? Esta ação não pode ser desfeita.')) return;
    
    fetch('<?= site_url('admin/pedidos/limpar') ?>', {
        method: 'POST'
    })
    .then(r => r.json())
    .then(data => {
        if (data.sucesso) {
            alert(data.msg);
            location.reload();
        } else {
            alert('Erro: ' + data.msg);
        }
    })
    .catch(err => {
        alert('Erro ao limpar pedidos.');
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
