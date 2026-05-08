<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Space Burger Dog Do Paulista | <?php echo $this->renderSection('titulo') ?></title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            :root {
                --primary-color: #0055ff;
                --secondary-color: #1a1866;
                --dark-bg: #1a1a1a;
                --darker-bg: #2d2d2d;
                --text-light: #ffffff;
                --text-muted: #cccccc;
                --border-color: #333;
            }

            body {
                font-family: 'Poppins', sans-serif;
                background-color: var(--dark-bg);
                color: var(--text-light);
                margin: 0;
                padding: 0;
            }

            .sidebar {
                background: var(--darker-bg);
                min-height: 100vh;
                border-right: 1px solid var(--border-color);
                position: fixed;
                width: 250px;
                left: 0;
                top: 0;
                z-index: 1000;
                height: 100vh;
                overflow-y: auto;
            }

            .main-content {
                margin-left: 250px;
                min-height: 100vh;
                background: var(--dark-bg);
            }

            .navbar {
                background: var(--darker-bg) !important;
                border-bottom: 1px solid var(--border-color);
                padding: 1rem 1.5rem;
            }

            .nav-link {
                color: var(--text-muted);
                padding: 12px 20px;
                border-left: 3px solid transparent;
                transition: all 0.3s ease;
                text-decoration: none;
                display: block;
            }

            .nav-link:hover,
            .nav-link.active {
                color: var(--primary-color);
                background: rgba(248, 181, 49, 0.1);
                border-left-color: var(--primary-color);
            }

            .nav-link i {
                width: 20px;
                margin-right: 10px;
            }

            .card {
                background: var(--darker-bg);
                border: 1px solid var(--border-color);
                border-radius: 10px;
                color: var(--text-light);
            }

            .card-header {
                background: rgba(248, 181, 49, 0.1);
                border-bottom: 1px solid var(--border-color);
                color: var(--primary-color);
                font-weight: 600;
            }

            /* ========================================
               ESTILOS SIMPLIFICADOS PARA BOTÕES
               ======================================== */

            /* Estilos base para todos os botões */
            .btn {
                border-radius: 6px;
                font-weight: 500;
                padding: 0.5rem 1rem;
                transition: all 0.2s ease;
                border: 1px solid transparent;
                text-transform: none;
            }

            .btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            }

            .btn:active {
                transform: translateY(0);
            }

            .btn i {
                margin-right: 5px;
                font-size: 0.95em;
            }

            /* Botões pequenos */
            .btn-sm {
                padding: 0.35rem 0.7rem;
                font-size: 0.875rem;
                border-radius: 4px;
            }

            .btn-sm i {
                margin-right: 0;
                font-size: 1em;
            }

            /* Botão Primary (Editar) */
            .btn-primary {
                background: #0055ff;
                color: #1a1a1a;
                border-color: #0055ff;
            }

            .btn-primary:hover {
                background: #0c0940;
                border-color: #0c0940;
                color: #000;
            }

            /* Botão Success (Criar/Salvar) */
            .btn-success {
                background: #28a745;
                color: #fff;
                border-color: #28a745;
            }

            .btn-success:hover {
                background: #218838;
                border-color: #218838;
                color: #fff;
            }

            /* Botão Danger (Excluir) */
            .btn-danger {
                background: #dc3545;
                color: #fff;
                border-color: #dc3545;
            }

            .btn-danger:hover {
                background: #c82333;
                border-color: #c82333;
                color: #fff;
            }

            /* Botão Warning (Restaurar) */
            .btn-warning {
                background: #ffc107;
                color: #1a1a1a;
                border-color: #ffc107;
            }

            .btn-warning:hover {
                background: #e0a800;
                border-color: #e0a800;
                color: #000;
            }

            /* Botão Info (Visualizar) */
            .btn-info {
                background: #17a2b8;
                color: #fff;
                border-color: #17a2b8;
            }

            .btn-info:hover {
                background: #138496;
                border-color: #138496;
                color: #fff;
            }

            /* Botão Secondary (Voltar/Cancelar) */
            .btn-secondary {
                background: #6c757d;
                color: #fff;
                border-color: #6c757d;
            }

            .btn-secondary:hover {
                background: #5a6268;
                border-color: #5a6268;
                color: #fff;
            }

            /* Botão Dark */
            .btn-dark {
                background: #343a40;
                color: #fff;
                border-color: #343a40;
            }

            .btn-dark:hover {
                background: #23272b;
                border-color: #23272b;
                color: #fff;
            }

            /* Botões desabilitados */
            .btn:disabled,
            .btn.disabled {
                opacity: 0.5;
                cursor: not-allowed;
                transform: none !important;
                box-shadow: none !important;
            }

            .btn:disabled:hover,
            .btn.disabled:hover {
                transform: none !important;
                box-shadow: none !important;
            }

            /* Grupo de botões */
            .btn-group {
                display: inline-flex;
                gap: 4px;
            }

            .btn-group .btn {
                margin: 0;
            }

            /* Botões de ação em tabelas */
            .table .btn-sm {
                padding: 0.35rem 0.7rem;
                font-size: 0.8125rem;
            }

            /* Float right para botões */
            .float-right {
                float: right;
            }

            /* Espaçamento entre botões */
            .ml-2 {
                margin-left: 0.5rem;
            }

            .mr-2 {
                margin-right: 0.5rem;
            }

            .mr-3 {
                margin-right: 1rem;
            }

            .mb-3 {
                margin-bottom: 1rem;
            }

            /* ========================================
               RESPONSIVIDADE GERAL
               ======================================== */

            /* Tablet e abaixo */
            @media (max-width: 991px) {
                .sidebar {
                    transform: translateX(-100%);
                    transition: transform 0.3s ease;
                }

                .sidebar.show {
                    transform: translateX(0);
                }

                .main-content {
                    margin-left: 0;
                }

                .navbar {
                    padding: 0.75rem 1rem;
                }

                .content-area {
                    padding: 1rem;
                }
            }

            /* Mobile */
            @media (max-width: 768px) {
                .btn {
                    padding: 0.4rem 0.9rem;
                    font-size: 0.875rem;
                }

                .btn-sm {
                    padding: 0.3rem 0.6rem;
                    font-size: 0.8125rem;
                }

                .btn-group {
                    flex-wrap: wrap;
                    gap: 4px;
                }

                .content-area {
                    padding: 0.75rem;
                }

                /* Tabelas responsivas */
                .table-responsive {
                    font-size: 0.85rem;
                }

                .table th,
                .table td {
                    padding: 0.5rem;
                    white-space: nowrap;
                }

                /* Cards */
                .card-body {
                    padding: 1rem;
                }

                .card-title {
                    font-size: 1.1rem;
                }

                /* Formulários */
                .form-group.row {
                    margin-bottom: 0.5rem;
                }

                .form-group.row > div {
                    margin-bottom: 1rem;
                }

                /* Botões de ação em tabelas - empilhar verticalmente */
                .table .btn-group {
                    display: flex;
                    flex-direction: column;
                    gap: 4px;
                }

                .table .btn-group .btn {
                    width: 100%;
                }

                /* Imagens em tabelas */
                .table .img-thumbnail {
                    width: 40px !important;
                    height: 40px !important;
                }

                /* Alertas */
                .alert {
                    padding: 0.75rem;
                    font-size: 0.875rem;
                }

                /* Headers */
                h4.card-title {
                    font-size: 1rem;
                }

                .d-flex.justify-content-between {
                    flex-direction: column;
                    gap: 1rem;
                }

                .d-flex.justify-content-between .btn {
                    width: 100%;
                }
            }

            /* Mobile pequeno */
            @media (max-width: 480px) {
                .content-area {
                    padding: 0.5rem;
                }

                .card {
                    border-radius: 8px;
                }

                .card-body {
                    padding: 0.75rem;
                }

                .table th,
                .table td {
                    padding: 0.4rem;
                    font-size: 0.8rem;
                }

                /* Esconder colunas menos importantes em mobile */
                .table .hide-mobile {
                    display: none;
                }

                .btn-sm {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.75rem;
                }

                .form-control {
                    font-size: 16px; /* Previne zoom no iOS */
                }
            }

            /* ── Admin mobile: scroll horizontal em tabelas ── */
            @media (max-width: 991px) {
                input, select, textarea { font-size: 16px !important; }

                /* Wrapper de scroll para tabela de itens da VE */
                #lista-itens-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
                #lista-itens { min-width: 380px; table-layout: auto !important; }
                #lista-itens td:first-child { max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

                /* Todas as tabelas admin com scroll */
                .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            }

            .table {
                color: var(--text-light);
                background: var(--darker-bg);
            }

            .table th {
                border-color: var(--border-color);
                color: var(--primary-color);
            }

            .table td {
                border-color: var(--border-color);
            }

            /* Hover em linhas de tabela com contraste adequado */
            .table tbody tr:hover {
                background-color: rgba(248, 181, 49, 0.15) !important;
                transition: background-color 0.2s ease;
                transform: scale(1.005);
                box-shadow: 0 2px 8px rgba(248, 181, 49, 0.2);
            }

            .table tbody tr {
                transition: all 0.2s ease;
            }

            /* Sobrescrever o table-active do Bootstrap para manter contraste */
            .table-active,
            .table-active > th,
            .table-active > td {
                background-color: rgba(248, 181, 49, 0.15) !important;
                color: var(--text-light) !important;
            }

            /* Garantir que badges mantenham suas cores no hover */
            .table tbody tr:hover .badge {
                opacity: 1;
            }

            /* Garantir que botões mantenham suas cores no hover da linha */
            .table tbody tr:hover .btn {
                position: relative;
                z-index: 1;
            }

            .form-control {
                background: var(--darker-bg);
                border: 1px solid var(--border-color);
                color: var(--text-light);
            }

            .form-control:focus {
                background: var(--darker-bg);
                border-color: var(--primary-color);
                color: var(--text-light);
                box-shadow: 0 0 0 0.2rem rgba(248, 181, 49, 0.25);
            }

            .alert {
                border-radius: 8px;
                border: none;
            }

            .alert-success {
                background: rgba(40, 167, 69, 0.1);
                color: #90ee90;
            }

            .alert-info {
                background: rgba(23, 162, 184, 0.1);
                color: #87ceeb;
            }

            .alert-danger {
                background: rgba(220, 53, 69, 0.1);
                color: #ff6b6b;
            }

            .content-area {
                padding: 2rem;
            }

            .logo {
                color: var(--primary-color);
                font-weight: 700;
                font-size: 1.5rem;
                padding: 1.5rem 1rem;
                border-bottom: 1px solid var(--border-color);
            }

            .user-info {
                padding: 1rem;
                border-top: 1px solid var(--border-color);
                margin-top: auto;
            }

            /* Overlay para sidebar mobile */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .sidebar-overlay.show {
                display: block;
            }

            /* Botão toggle sidebar */
            #sidebarToggle {
                padding: 0.5rem;
                border: none;
                background: transparent;
            }

            #sidebarToggle:hover {
                color: var(--secondary-color) !important;
            }

            /* Navbar brand mobile */
            .navbar-brand {
                font-weight: 700;
                font-size: 1.2rem;
            }
        </style>

        <?php echo $this->renderSection('estilos') ?>
    </head>
    <body>
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-hotdog me-2"></i>
                Space Burger Dog Do Paulista
            </div>

            <nav class="nav flex-column">
                <?php
                $usuarioLogado = service('autenticacao')->pegaUsuarioLogado();
                $isAdmin = $usuarioLogado->is_admin == 1;
                ?>

                <?php if ($isAdmin): ?>
                    <a class="nav-link <?= current_url() == site_url('admin/home') ? 'active' : '' ?>" href="<?= site_url('admin/home') ?>">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                    <a class="nav-link <?= strpos(current_url(), 'categorias') !== false ? 'active' : '' ?>" href="<?= site_url('admin/categorias') ?>">
                        <i class="fas fa-tags"></i>
                        Categorias
                    </a>
                    <a class="nav-link <?= strpos(current_url(), 'extras') !== false ? 'active' : '' ?>" href="<?= site_url('admin/extras') ?>">
                        <i class="fas fa-plus-circle"></i>
                        Extras
                    </a>
    <!--                <a class="nav-link <?= strpos(current_url(), 'medidas') !== false ? 'active' : '' ?>" href="<?= site_url('admin/medidas') ?>">
                        <i class="fas fa-ruler"></i>
                        Medidas
                    </a>-->
                    <a class="nav-link <?= strpos(current_url(), 'produtos') !== false ? 'active' : '' ?>" href="<?= site_url('admin/produtos') ?>">
                        <i class="fas fa-pizza-slice"></i>
                        Produtos
                    </a>

                    <a class="nav-link <?= strpos(current_url(), 'saches') !== false ? 'active' : '' ?>" href="<?= site_url('admin/saches') ?>">
                        <i class="fas fa-pepper-hot"></i>
                        Sachês
                    </a>
                    <a class="nav-link <?= strpos(current_url(), 'bairros') !== false ? 'active' : '' ?>" href="<?= site_url('admin/bairros') ?>">
                        <i class="fas fa-map-marker-alt"></i>
                        Bairros
                    </a>
                    <a class="nav-link <?= strpos(current_url(), 'expedientes') !== false ? 'active' : '' ?>" href="<?= site_url('admin/expedientes') ?>">
                        <i class="fas fa-calendar-alt"></i>
                        Expedientes
                    </a>
                    <a class="nav-link <?= strpos(current_url(), 'usuarios') !== false ? 'active' : '' ?>" href="<?= site_url('admin/usuarios') ?>">
                        <i class="fas fa-users"></i>
                        Usuários
                    </a>
                    <a class="nav-link <?= strpos(current_url(), 'formas-pagamento') !== false ? 'active' : '' ?>" href="<?= site_url('admin/formas-pagamento') ?>">
                        <i class="fas fa-credit-card"></i>
                        Formas de Pagamento
                    </a>
                <?php endif; ?>

                <!-- Pedidos - Visível para todos -->
                <a class="nav-link <?= strpos(current_url(), 'pedidos') !== false ? 'active' : '' ?>" href="<?= site_url('admin/pedidos') ?>">
                    <i class="fas fa-shopping-cart"></i>
                    Pedidos
                </a>

                <?php if ($isAdmin): ?>
                    <a class="nav-link <?= strpos(current_url(), 'venda-especifica') !== false ? 'active' : '' ?>" href="<?= site_url('admin/venda-especifica') ?>">
                        <i class="fas fa-plus-circle"></i> 
                        Venda Específica
                    </a>

                    <!-- Mesas - Apenas Admin -->
                    <a class="nav-link <?= strpos(current_url(), 'mesas') !== false ? 'active' : '' ?>" href="<?= site_url('admin/mesas') ?>">
                        <i class="fas fa-chair"></i> 
                        Mesas
                    </a>

                    <!-- Dados Corporativos - Apenas Admin -->
                    <a class="nav-link <?= strpos(current_url(), 'dados-corporativos') !== false ? 'active' : '' ?>" href="<?= site_url('admin/dados-corporativos') ?>">
                        <i class="fas fa-building"></i>
                        Dados Corporativos
                    </a>
                <?php else: ?>
                    <a class="nav-link <?= strpos(current_url(), 'venda-especifica') !== false ? 'active' : '' ?>" href="<?= site_url('admin/venda-especifica') ?>">
                        <i class="fas fa-plus-circle"></i>
                        Venda Específica
                    </a>
                    <a class="nav-link <?= strpos(current_url(), 'mesas') !== false ? 'active' : '' ?>" href="<?= site_url('admin/mesas') ?>">
                        <i class="fas fa-chair"></i>
                        Mesas
                    </a>
                <?php endif; ?>
            </nav>

            <div class="user-info">
                <div class="d-flex align-items-center">
                    <div class="user-avatar bg-warning rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                        <i class="fas fa-user text-dark"></i>
                    </div>
                    <div>
                        <div class="text-light small">
                            <?php echo service('autenticacao')->pegaUsuarioLogado()->nome; ?>
                            <?php if (!$isAdmin): ?>
                                <span class="badge bg-info ms-1" style="font-size: 0.7rem;">Colaborador</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark ms-1" style="font-size: 0.7rem;">Admin</span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo site_url('login/logout'); ?>" class="text-muted small text-decoration-none">
                            <i class="fas fa-sign-out-alt me-1"></i>Sair
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overlay para fechar sidebar em mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="main-content">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <!-- Botão Menu Mobile -->
                    <button class="btn btn-link text-warning d-lg-none me-2" id="sidebarToggle" type="button">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>

                    <span class="navbar-brand text-warning d-lg-none">Space Burger Dog Do Paulista</span>

                    <div class="navbar-nav ms-auto">
                        <a class="nav-link text-light" href="<?= site_url('/') ?>" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i>
                            <span class="d-none d-sm-inline">Ver Site</span>
                        </a>
                    </div>
                </div>
            </nav>

            <div class="content-area">
                <!-- Alertas -->
                <?php if (session()->has('sucesso')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Perfeito!</strong> <?= session('sucesso'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Informação!</strong> <?= session('info'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('atencao')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Atenção!</strong> <?= session('atencao'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erro!</strong> <?= session('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('erro')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erro!</strong> <?= session('erro'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Conteúdo específico da página -->
                <?php echo $this->renderSection('conteudos') ?>
            </div>
        </div>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            // Configuração global do AJAX para garantir que requisições POST funcionem
            $.ajaxSetup({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        </script>

        <script>
            // Toggle Sidebar Mobile
            document.addEventListener('DOMContentLoaded', function () {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const toggle = document.getElementById('sidebarToggle');

                if (toggle) {
                    toggle.addEventListener('click', function () {
                        sidebar.classList.toggle('show');
                        overlay.classList.toggle('show');
                    });
                }

                if (overlay) {
                    overlay.addEventListener('click', function () {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    });
                }

                // Fechar sidebar ao clicar em um link (mobile)
                const navLinks = document.querySelectorAll('.sidebar .nav-link');
                navLinks.forEach(function (link) {
                    link.addEventListener('click', function () {
                        if (window.innerWidth < 992) {
                            sidebar.classList.remove('show');
                            overlay.classList.remove('show');
                        }
                    });
                });
            });

            // Redirecionar usuários não-admin para pedidos se tentarem acessar outras áreas
<?php if (!$isAdmin): ?>
                const currentPath = window.location.pathname;
                const allowedPaths = ['/admin/pedidos', '/admin/mesas', '/admin/venda-especifica', '/login/logout'];
                const isAllowed = allowedPaths.some(path => currentPath.includes(path));

                if (!isAllowed && currentPath.includes('/admin/')) {
                    console.log('⚠️ Acesso negado. Redirecionando para pedidos...');
                    window.location.href = '<?= site_url('admin/pedidos') ?>';
                }
<?php endif; ?>
        </script>

        <?php echo $this->renderSection('scripts') ?>
    </body>
</html>