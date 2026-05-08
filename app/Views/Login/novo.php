<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Entrar - Delivery</title>
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
            color: #0055ff;
            font-size: 2rem;
            font-weight: bold;
        }
        .login-header p {
            color: #ccc;
            margin-top: 0.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            color: #0055ff;
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
            border-color: #0055ff;
            background: #333;
            box-shadow: 0 0 0 0.2rem rgba(248, 181, 49, 0.25);
            color: #fff;
        }
        .btn-login {
            background: linear-gradient(135deg, #0055ff 0%, #1a1866 100%);
            border: none;
            color: #ffffff;
            padding: 15px;
            border-radius: 10px;
            width: 100%;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #0c0940 0%, #2520a3 100%);
            color: #ffffff;
            transform: translateY(-2px);
        }
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        .alert {
            border-radius: 8px;
            padding: 12px;
            margin-top: 1rem;
            text-align: center;
            border: none;
        }
        .alert-danger {
            background: #5c2a2a;
            color: #ff6b6b;
            border: 1px solid #7a3737;
        }
        .alert-success {
            background: #2d5a2d;
            color: #90ee90;
            border: 1px solid #3a6a3a;
        }
        .login-tabs {
            display: flex;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #333;
        }
        .login-tab {
            flex: 1;
            padding: 12px;
            text-align: center;
            color: #888;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            font-weight: 500;
        }
        .login-tab:hover {
            color: #0055ff;
        }
        .login-tab.active {
            color: #0055ff;
            border-bottom-color: #0055ff;
        }
        .login-tab i {
            margin-right: 8px;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .back-link {
            text-align: center;
            margin-top: 1rem;
        }
        .back-link a {
            color: #888;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-link a:hover {
            color: #0055ff;
        }
        .cadastro-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #888;
        }
        .cadastro-link a {
            color: #0055ff;
            text-decoration: none;
            font-weight: 600;
        }
        .cadastro-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>"><span class="fas fa-hotdog mr-1" style="color: #0055ff "></span>Space Burger Dog Do Paulista<br><small style="color: #0055ff ">O delivery favorito da cidade</small></a>
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <div class="brand">
                    <span class="fas fa-hotdog"></span> Space Burger Dog Do Paulista
                </div>
                <h4 style="color: #fff; margin: 1rem 0 0.5rem;">Olá, seja bem-vindo(a)!</h4>
                <p>Por favor, realize o login para continuar</p>
            </div>

            <?php if (session()->has('atencao')): ?>
                <div class="alert alert-danger">
                    <strong>Atenção!</strong> <?= session('atencao'); ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('sucesso')): ?>
                <div class="alert alert-success">
                    <strong>Sucesso!</strong> <?= session('sucesso'); ?>
                </div>
            <?php endif; ?>

            <div class="login-tabs">
                <div class="login-tab active" data-tab="email">
                    <i class="fas fa-envelope"></i>E-mail
                </div>
                <div class="login-tab" data-tab="celular">
                    <i class="fas fa-mobile-alt"></i>Celular
                </div>
            </div>

            <form action="<?= site_url('login/criar') ?>" method="POST" id="loginForm">
                <?= csrf_field() ?>
                <input type="hidden" name="login_tipo" id="login_tipo" value="email">

                <div class="tab-content active" id="tab-email">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="<?= old('email') ?>" 
                               class="form-control" 
                               placeholder="Digite o seu e-mail">
                    </div>
                </div>

                <div class="tab-content" id="tab-celular">
                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input type="tel" 
                               name="celular" 
                               id="celular"
                               value="<?= old('celular') ?>" 
                               class="form-control" 
                               placeholder="(00) 00000-0000">
                    </div>
                </div>

                <div class="form-group" id="campo-senha" style="display: none;">
                    <label for="password">Senha</label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="form-control" 
                           placeholder="Digite a sua senha">
                </div>

                <button type="submit" class="btn btn-login" id="btnEntrar">
                    <i class="fas fa-sign-in-alt mr-2"></i>ENTRAR
                </button>

                <div class="back-link">
                    <a href="<?= site_url('login') ?>">
                        <i class="fas fa-arrow-left mr-1"></i>Voltar
                    </a>
                </div>

                <div class="cadastro-link">
                    Não tem conta? <a href="<?= site_url('registrar') ?>">Cadastre-se aqui</a>
                </div>

            </form>
        </div>
    </div>

    <script src="<?= base_url('web/src/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/bootstrap.min.js') ?>"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const emailSalvo = localStorage.getItem('cliente_email');
            if (emailSalvo) {
                window.location.href = '<?= base_url('/') ?>';
                return;
            }

            const emailInput = document.getElementById('email');
            const celularInput = document.getElementById('celular');
            const campoSenha = document.getElementById('campo-senha');
            let loginTipo = 'email';

            document.querySelectorAll('.login-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    document.querySelectorAll('.login-tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    
                    this.classList.add('active');
                    document.getElementById('tab-' + tabId).classList.add('active');
                    
                    loginTipo = tabId;
                    document.getElementById('login_tipo').value = tabId;
                    
                    if (tabId === 'email') {
                        emailInput.focus();
                    } else {
                        celularInput.focus();
                    }
                });
            });

            celularInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    if (value.length < 14) {
                        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                    }
                }
                e.target.value = value;
            });

            let emailVerificado = false;

            const btnEntrar = document.getElementById('btnEntrar');
            btnEntrar.addEventListener('click', function(e) {
                if (loginTipo === 'email' && !emailVerificado) {
                    e.preventDefault();
                    const email = emailInput.value.trim();
                    
                    if (!email || !email.includes('@')) {
                        alert('Por favor, digite um e-mail válido');
                        return;
                    }

                    fetch('<?= site_url('login/verificarEmail') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ email: email })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.requer_senha) {
                            emailVerificado = true;
                            emailInput.readOnly = true;
                            emailInput.style.opacity = '0.7';
                            campoSenha.style.display = 'block';
                            document.getElementById('password').focus();
                        } else if (data.tipo === 'cliente') {
                            document.getElementById('loginForm').submit();
                        } else {
                            alert('E-mail não cadastrado');
                        }
                    })
                    .catch(() => {
                        alert('Erro ao verificar e-mail');
                    });
                }
            });

            emailInput.focus();
        });
    </script>

</body>
</html>
