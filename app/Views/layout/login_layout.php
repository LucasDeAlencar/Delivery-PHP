<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title><?= $this->renderSection('titulo') ?> - Delivery</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('web/src/css/open-iconic-bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/animate.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/owl.carousel.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/owl.theme.default.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/magnific-popup.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/aos.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/ionicons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/bootstrap-datepicker.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/jquery.timepicker.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/flaticon.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/icomoon.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/style.css') ?>">
    
    <style>
        .login-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?= base_url('web/src/images/burger-1.jpg') ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header .brand {
            color: #c49b63;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .login-header .brand small {
            font-size: 1rem;
            color: #666;
            display: block;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #c49b63;
            box-shadow: 0 0 0 0.2rem rgba(196, 155, 99, 0.25);
        }
        .btn-login {
            background: #c49b63;
            border: none;
            color: white;
            padding: 15px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
        }
        .btn-login:hover {
            background: #b08c5a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(196, 155, 99, 0.4);
            color: white;
        }
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
    
    <!-- Estilos personalizados -->
    <?= $this->renderSection('estilos') ?>
  </head>
  <body>
    
    <!-- Navbar igual ao da home -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
		      <a class="navbar-brand" href="<?= base_url('/') ?>"><span class="fas fa-hamburger mr-1"></span>Space Burger Dog Do Paulista<br><small>Hamburgueres & HotDogs</small></a>
		      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
		        <span class="oi oi-menu"></span> Menu
		      </button>
	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item"><a href="<?= base_url('/') ?>" class="nav-link">Home</a></li>
	          <li class="nav-item"><a href="<?= base_url('/#menu') ?>" class="nav-link">Menu</a></li>
	          <li class="nav-item"><a href="<?= base_url('/#services') ?>" class="nav-link">Serviços</a></li>
	          <li class="nav-item"><a href="<?= base_url('/#about') ?>" class="nav-link">Sobre</a></li>
	          <li class="nav-item"><a href="<?= base_url('/#contact') ?>" class="nav-link">Contato</a></li>
	          <li class="nav-item active"><a href="<?= base_url('login') ?>" class="nav-link">Admin</a></li>
	        </ul>
	      </div>
		  </div>
	  </nav>

    <!-- Seção de Login -->
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="login-container">
                        <div class="login-header">
                            <div class="brand">
                                <span class="fas fa-hamburger"></span> Bim das Balas
                                <small>Área Administrativa</small>
                            </div>
                            <p class="text-muted mt-3">Faça login para acessar o painel</p>
                        </div>
                        
                        <!-- Conteúdo da página de login -->
                        <?= $this->renderSection('conteudo') ?>
                        
                        <div class="login-footer">
                            <p>&copy; <?= date('Y') ?> Bim das Balas. Todos os direitos reservados.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="<?= base_url('web/src/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/jquery-migrate-3.0.1.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/jquery.easing.1.3.js') ?>"></script>
    <script src="<?= base_url('web/src/js/jquery.waypoints.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/jquery.stellar.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/owl.carousel.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/jquery.magnific-popup.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/aos.js') ?>"></script>
    <script src="<?= base_url('web/src/js/jquery.animateNumber.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/bootstrap-datepicker.js') ?>"></script>
    <script src="<?= base_url('web/src/js/jquery.timepicker.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/scrollax.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/main.js') ?>"></script>
    
    <!-- Scripts personalizados -->
    <?= $this->renderSection('scripts') ?>
    
  </body>
</html>