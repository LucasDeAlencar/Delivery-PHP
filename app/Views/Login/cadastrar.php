<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Criar Conta - Delivery</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('web/src/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/style.css') ?>">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('<?= base_url('web/src/images/burger-1.jpg') ?>');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .register-container {
            background: #1a1a1a;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            max-width: 480px;
            width: 100%;
            margin: 140px auto 50px;
            border: 1px solid #333;
        }
        .register-header { text-align: center; margin-bottom: 2rem; }
        .register-header .brand { color: #28a745; font-size: 2rem; font-weight: bold; }
        .register-header p { color: #ccc; margin-top: 0.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { color: #28a745; font-weight: 600; margin-bottom: 0.5rem; display: block; }
        .form-control {
            border: 2px solid #333; border-radius: 8px; padding: 12px 15px;
            width: 100%; color: #fff; background: #2d2d2d; transition: all 0.3s ease;
        }
        .form-control::placeholder { color: #888; }
        .form-control:focus { border-color: #28a745; background: #333; color: #fff; outline: none; }
        .btn-register {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none; color: #fff; padding: 15px; border-radius: 10px;
            width: 100%; font-size: 1.1rem; font-weight: 600; transition: all 0.3s ease; margin-top: 1rem;
        }
        .btn-register:hover { background: linear-gradient(135deg, #218838 0%, #1aa179 100%); transform: translateY(-2px); }
        .btn-register:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .alert { border-radius: 8px; padding: 12px; margin-bottom: 1rem; text-align: center; border: none; }
        .alert-danger  { background: #5c2a2a; color: #ff6b6b; border: 1px solid #7a3737; }
        .alert-success { background: #2d5a2d; color: #90ee90; border: 1px solid #3a6a3a; }
        .login-link { text-align: center; margin-top: 1.5rem; color: #888; }
        .login-link a { color: #0055ff; text-decoration: none; font-weight: 600; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <span class="fas fa-hotdog mr-1" style="color:#0055ff"></span>Space Burger Dog Do Paulista<br>
                <small style="color:#0055ff">O delivery favorito da cidade</small>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav">
                <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="<?= base_url('/') ?>" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="<?= base_url('/#menu') ?>" class="nav-link">Menu</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="register-container">
            <div class="register-header">
                <div class="brand"><span class="fas fa-user-plus"></span> Criar Conta</div>
                <h4 style="color:#fff; margin:1rem 0 0.5rem;">Cadastre-se!</h4>
                <p>Informe seu nome e celular para criar sua conta</p>
            </div>

            <?php if (session()->has('atencao')): ?>
                <div class="alert alert-danger"><strong>Atenção!</strong> <?= session('atencao') ?></div>
            <?php endif; ?>
            <?php if (session()->has('sucesso')): ?>
                <div class="alert alert-success"><strong>Sucesso!</strong> <?= session('sucesso') ?></div>
            <?php endif; ?>

            <form id="form-cadastro" action="<?= site_url('login/cadastrar') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" name="nome" id="nome" value="<?= old('nome') ?>"
                           class="form-control" placeholder="Digite seu nome completo" required autofocus>
                </div>

                <div class="form-group">
                    <label for="celular">Celular/WhatsApp *</label>
                    <input type="tel" name="celular" id="celular" value="<?= old('celular') ?>"
                           class="form-control" placeholder="(00) 00000-0000" required>
                </div>

                <button type="submit" class="btn btn-register" id="btnCadastrar">
                    <i class="fas fa-user-plus mr-2"></i>CRIAR CONTA
                </button>
            </form>

            <div class="login-link">
                Já tem conta? <a href="<?= site_url('login/entrar') ?>">Entre aqui</a>
            </div>
        </div>
    </div>

    <script src="<?= base_url('web/src/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/bootstrap.min.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('celular').addEventListener('input', function(e) {
                let v = e.target.value.replace(/\D/g, '');
                if (v.length <= 11) {
                    v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
                    v = v.replace(/(\d)(\d{4})$/, '$1-$2');
                }
                e.target.value = v;
            });

            document.getElementById('form-cadastro').addEventListener('submit', function() {
                const btn = document.getElementById('btnCadastrar');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>CADASTRANDO...';
            });
        });
    </script>
</body>
</html>
