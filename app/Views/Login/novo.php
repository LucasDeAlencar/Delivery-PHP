<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title>Login Admin - Restaurante</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="<?= base_url('web/src/css/open-iconic-bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/animate.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/ionicons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/flaticon.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/icomoon.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/style.css') ?>">
    
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('<?= base_url('web/src/images/bg_1.jpg') ?>');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .login-container {
            background: #1a1a1a;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            max-width: 450px;
            width: 100%;
            margin: 140px auto 50px;
            border: 1px solid #333;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header .brand {
            color: #f8b531;
            font-size: 2rem;
            font-weight: bold;
        }
        .login-header p {
            color: #ccc;
            margin-top: 0.5rem;
        }
        
        /* Campos dark */
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            color: #f8b531;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }
        .form-control {
            border: 2px solid #333;
            border-radius: 8px;
            padding: 12px 15px;
            width: 100%;
            color: #fff;
            background: #2d2d2d;
            transition: all 0.3s ease;
        }
        .form-control::placeholder {
            color: #888;
        }
        .form-control:focus {
            border-color: #f8b531;
            background: #333;
            box-shadow: 0 0 0 0.2rem rgba(248, 181, 49, 0.25);
            color: #fff;
        }
        
        /* Checkbox dark */
        .form-check {
            margin-bottom: 1.5rem;
        }
        .form-check-label {
            color: #ccc;
            font-weight: 500;
        }
        .form-check-input {
            background-color: #2d2d2d;
            border: 2px solid #333;
        }
        .form-check-input:checked {
            background-color: #f8b531;
            border-color: #f8b531;
        }
        
        /* Botão com as cores especificadas */
        .btn-login {
            background: linear-gradient(135deg, #f8b531 0%, #fac56e 100%);
            border: none;
            color: #000000;
            padding: 15px;
            border-radius: 10px;
            width: 100%;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(248, 181, 49, 0.3);
            margin-top: 1rem;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #e6a42e 0%, #f0b861 100%);
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(248, 181, 49, 0.4);
        }
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Alertas dark */
        .alert {
            border-radius: 8px;
            padding: 12px;
            margin-top: 1rem;
            text-align: center;
            border: none;
        }
        .alert-success {
            background: #2d5a2d;
            color: #90ee90;
            border: 1px solid #3a6a3a;
        }
        .alert-info {
            background: #2a4a5c;
            color: #87ceeb;
            border: 1px solid #375a6a;
        }
        .alert-danger {
            background: #5c2a2a;
            color: #ff6b6b;
            border: 1px solid #7a3737;
        }
        
        /* Links */
        .auth-link {
            color: #f8b531 !important;
            transition: color 0.3s ease;
            text-decoration: none;
        }
        .auth-link:hover {
            color: #fac56e !important;
            text-decoration: none;
        }
        .text-center {
            text-align: center;
        }
        .mt-3 {
            margin-top: 1rem;
        }
        .mt-4 {
            margin-top: 1.5rem;
        }
        .font-weight-light {
            font-weight: 300;
        }
        .font-weight-medium {
            font-weight: 500;
        }
    </style>
  </head>
  <body>

    <!-- Navbar IDÊNTICA à home -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
		      <a class="navbar-brand" href="<?= base_url('/') ?>"><span class="flaticon-pizza-1 mr-1"></span>No Kapricho<br><small>A melhor pizzaria da cidade</small></a>
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

    <!-- Container de Login Dark -->
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <div class="brand">
                    <span class="flaticon-pizza-1"></span> No Kapricho
                </div>
                <h4 style="color: #fff; margin: 1rem 0 0.5rem;">Olá, seja bem-vindo(a)!</h4>
                <p>Por favor, realize o login para continuar</p>
            </div>

            <!-- Mensagens de alerta -->
            <?php if (session()->has('sucesso')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Perfeito!</strong> <?= session('sucesso'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1);"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Informação!</strong> <?= session('info'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1);"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('atencao')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Atenção!</strong> <?= session('atencao'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1);"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erro!</strong> <?= session('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1);"></button>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('login/criar') ?>" method="POST" id="loginForm">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" 
                           name="email" 
                           id="email"
                           value="<?= old('email') ?>" 
                           class="form-control" 
                           placeholder="Digite o seu e-mail"
                           required>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="form-control" 
                           placeholder="Digite a sua senha"
                           required>
                </div>

                <button type="submit" class="btn btn-login" id="submitBtn">
                    <i class="fas fa-sign-in-alt mr-2"></i>ENTRAR
                </button>
                
                <div class="text-center mt-3">
                    <a href="<?= site_url('/') ?>" class="auth-link">
                        <i class="fas fa-arrow-left mr-1"></i>Voltar ao site
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= base_url('web/src/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/bootstrap.min.js') ?>"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        if (form) {
            form.addEventListener('submit', function (e) {
                const email = emailInput.value.trim();
                const password = passwordInput.value;

                // Validação básica
                if (!email || !password) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos');
                    return false;
                }

                // Validação de email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Por favor, digite um e-mail válido');
                    emailInput.focus();
                    return false;
                }

                // Mostrar loading
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Entrando...';
                submitBtn.disabled = true;

                // Reverter após 5 segundos (caso o submit falhe)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });
        }

        // Melhorar UX com foco automático
        if (emailInput && !emailInput.value) {
            emailInput.focus();
        }
    });
    </script>
    
  </body>
</html>