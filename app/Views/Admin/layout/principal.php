<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Pizzaria Forno 406 | <?php echo $this->renderSection('titulo') ?></title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="<?php echo site_url('admin/'); ?>vendors/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="<?php echo site_url('admin/'); ?>vendors/base/vendor.bundle.base.css">
        <!-- endinject -->
        <!-- plugin css for this page -->
        <link rel="stylesheet" href="<?php echo site_url('admin/'); ?>vendors/datatables.net-bs4/dataTables.bootstrap4.css">
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <link rel="stylesheet" href="<?php echo site_url('admin/'); ?>css/style.css">
        <!-- endinject -->
        <link rel="shortcut icon" href="<?php echo site_url('admin/'); ?>images/favicon.png" />

        <!-- Essa section rendenizará os estilos especificos da view que estender esse layout -->
        <?php echo $this->renderSection('estilos') ?>

    </head>
    <body>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="navbar-brand-wrapper d-flex justify-content-center">
                    <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
                        <a class="navbar-brand brand-logo" href="<?php echo site_url('admin/home'); ?>"><img src="<?php echo site_url('admin/'); ?>images/logo.svg" alt="logo"/></a>
                        <a class="navbar-brand brand-logo-mini" href="<?php echo site_url('admin/home'); ?>"><img src="<?php echo site_url('admin/'); ?>images/logo-mini.svg" alt="logo"/></a>
                        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                            <span class="mdi mdi-sort-variant"></span>
                        </button>
                    </div>  
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                    <ul class="navbar-nav mr-lg-4 w-100">
                        <li class="nav-item nav-search d-none d-lg-block w-100">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="search">
                                        <i class="mdi mdi-magnify"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Search now" aria-label="search" aria-describedby="search">
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item dropdown mr-1">
                            <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-toggle="dropdown">
                                <i class="mdi mdi-message-text mx-0"></i>
                                <span class="count"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="messageDropdown">
                                <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>
                                <a class="dropdown-item">
                                    <div class="item-thumbnail">
                                        <img src="<?php echo site_url('admin/'); ?>images/faces/face4.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="item-content flex-grow">
                                        <h6 class="ellipsis font-weight-normal">David Grey
                                        </h6>
                                        <p class="font-weight-light small-text text-muted mb-0">
                                            The meeting is cancelled
                                        </p>
                                    </div>
                                </a>
                                <a class="dropdown-item">
                                    <div class="item-thumbnail">
                                        <img src="<?php echo site_url('admin/'); ?>images/faces/face2.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="item-content flex-grow">
                                        <h6 class="ellipsis font-weight-normal">Tim Cook
                                        </h6>
                                        <p class="font-weight-light small-text text-muted mb-0">
                                            New product launch
                                        </p>
                                    </div>
                                </a>
                                <a class="dropdown-item">
                                    <div class="item-thumbnail">
                                        <img src="<?php echo site_url('admin/'); ?>images/faces/face3.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="item-content flex-grow">
                                        <h6 class="ellipsis font-weight-normal"> Johnson
                                        </h6>
                                        <p class="font-weight-light small-text text-muted mb-0">
                                            Upcoming board meeting
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown mr-4">
                            <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center notification-dropdown" id="notificationDropdown" href="#" data-toggle="dropdown">
                                <i class="mdi mdi-bell mx-0"></i>
                                <span class="count"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="notificationDropdown">
                                <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                                <a class="dropdown-item">
                                    <div class="item-thumbnail">
                                        <div class="item-icon bg-success">
                                            <i class="mdi mdi-information mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="item-content">
                                        <h6 class="font-weight-normal">Application Error</h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            Just now
                                        </p>
                                    </div>
                                </a>
                                <a class="dropdown-item">
                                    <div class="item-thumbnail">
                                        <div class="item-icon bg-warning">
                                            <i class="mdi mdi-settings mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="item-content">
                                        <h6 class="font-weight-normal">Settings</h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            Private message
                                        </p>
                                    </div>
                                </a>
                                <a class="dropdown-item">
                                    <div class="item-thumbnail">
                                        <div class="item-icon bg-info">
                                            <i class="mdi mdi-account-box mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="item-content">
                                        <h6 class="font-weight-normal">New user registration</h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            2 days ago
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <img src="<?php echo site_url('admin/'); ?>images/faces/face5.jpg" alt="profile"/>
                                <span class="nav-profile-name"><?php echo service('autenticacao')->pegaUsuarioLogado()->nome; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item">
                                    <i class="mdi mdi-settings text-primary"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="<?php echo site_url('login/logout'); ?>">
                                    <i class="mdi mdi-logout text-primary"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
            </nav>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <!-- partial:partials/_sidebar.html -->
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/home'); ?>">
                                <i class="mdi mdi-home menu-icon"></i>
                                <span class="menu-title">Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/categorias'); ?>">
                                <i class="mdi mdi-box-shadow menu-icon"></i>
                                <span class="menu-title">Categorias</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/extras'); ?>">
                                <i class="mdi mdi-plus-box menu-icon"></i>
                                <span class="menu-title">Extras</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/medidas'); ?>">
                                <i class="mdi mdi-ruler menu-icon"></i>
                                <span class="menu-title">Medidas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/produtos'); ?>">
                                <i class="mdi mdi mdi-package-variant menu-icon"></i>
                                <span class="menu-title">Produtos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/bairros'); ?>">
                                <i class="mdi mdi mdi-city-variant menu-icon"></i>
                                <span class="menu-title">Bairros</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/usuarios'); ?>">
                                <i class="mdi mdi-account-settings menu-icon"></i>
                                <span class="menu-title">Usuarios</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="pages/forms/basic_elements.html">
                                <i class="mdi mdi-view-headline menu-icon"></i>
                                <span class="menu-title">Form elements</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/charts/chartjs.html">
                                <i class="mdi mdi-chart-pie menu-icon"></i>
                                <span class="menu-title">Charts</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/tables/basic-table.html">
                                <i class="mdi mdi-grid-large menu-icon"></i>
                                <span class="menu-title">Tables</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/icons/mdi.html">
                                <i class="mdi mdi-emoticon menu-icon"></i>
                                <span class="menu-title">Icons</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                                <i class="mdi mdi-account menu-icon"></i>
                                <span class="menu-title">User Pages</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="auth">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
                                    <li class="nav-item"> <a class="nav-link" href="pages/samples/login-2.html"> Login 2 </a></li>
                                    <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
                                    <li class="nav-item"> <a class="nav-link" href="pages/samples/register-2.html"> Register 2 </a></li>
                                    <li class="nav-item"> <a class="nav-link" href="pages/samples/lock-screen.html"> Lockscreen </a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="documentation/documentation.html">
                                <i class="mdi mdi-file-document-box-outline menu-icon"></i>
                                <span class="menu-title">Documentation</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- partial -->
                <div class="main-panel">
                    <div class="content-wrapper">

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
                                <strong>Informação!</strong> <?= session('atencao'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                        <?php endif; ?>

                        <!<!-- Captura os erros de CSRF -->
                        <?php if (session()->has('error')): ?>

                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Erro!</strong> <?= session('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                        <?php endif; ?>

                        <!-- Essa section rendenizará os conteudos especificos da view que estender esse layout -->
                        <?php echo $this->renderSection('conteudos') ?> 


                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <footer class="footer">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2020</span>
                            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard template</a> from Bootstrapdash.com</span>
                        </div>
                    </footer>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->

        <!-- plugins:js -->
        <script src="<?php echo site_url('admin/'); ?>vendors/base/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page-->
        <script src="<?php echo site_url('admin/'); ?>vendors/chart.js/Chart.min.js"></script>
        <script src="<?php echo site_url('admin/'); ?>vendors/datatables.net/jquery.dataTables.js"></script>
        <script src="<?php echo site_url('admin/'); ?>vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
        <!-- End plugin js for this page-->
        <!-- inject:js -->
        <script src="<?php echo site_url('admin/'); ?>js/off-canvas.js"></script>
        <script src="<?php echo site_url('admin/'); ?>js/hoverable-collapse.js"></script>
        <script src="<?php echo site_url('admin/'); ?>js/template.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page-->
        <script src="<?php echo site_url('admin/'); ?>js/dashboard.js"></script>
        <script src="<?php echo site_url('admin/'); ?>js/data-table.js"></script>
        <script src="<?php echo site_url('admin/'); ?>js/jquery.dataTables.js"></script>
        <script src="<?php echo site_url('admin/'); ?>js/dataTables.bootstrap4.js"></script>
        <!-- End custom js for this page-->
        <script src="<?php echo site_url('admin/'); ?>js/jquery.cookie.js" type="text/javascript"></script>


        <?php echo $this->renderSection('scripts') ?>


    </body>

</html>

