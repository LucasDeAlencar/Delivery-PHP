<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Login - Restaurante</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('web/src/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('web/src/css/style.css') ?>">

    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('<?= base_url('web/src/images/bg_1.jpg') ?>');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .choice-container {
            background: #1a1a1a;
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            max-width: 500px;
            width: 100%;
            margin: 140px auto 50px;
            border: 1px solid #333;
            text-align: center;
        }
        .choice-header {
            margin-bottom: 2.5rem;
        }
        .choice-header .brand {
            color: #f8b531;
            font-size: 2.5rem;
            font-weight: bold;
        }
        .choice-header h3 {
            color: #fff;
            margin: 1.5rem 0 0.5rem;
            font-size: 1.5rem;
        }
        .choice-header p {
            color: #aaa;
            font-size: 1rem;
        }
        .btn-choice {
            display: block;
            width: 100%;
            padding: 1.2rem 2rem;
            margin-bottom: 1rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }
        .btn-cadastrar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #fff;
        }
        .btn-cadastrar:hover {
            background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }
        .btn-entrar {
            background: linear-gradient(135deg, #f8b531 0%, #fac56e 100%);
            color: #000;
        }
        .btn-entrar:hover {
            background: linear-gradient(135deg, #e6a42e 0%, #f0b861 100%);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(248, 181, 49, 0.4);
        }
        .btn-choice i {
            margin-right: 10px;
            font-size: 1.3rem;
        }
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #666;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #444;
        }
        .divider span {
            padding: 0 15px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>"><span class="fas fa-hotdog mr-1"></span>Delicias MV<br><small>O delivery favorito da cidade</small></a>
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
        <div class="choice-container">
            <div class="choice-header">
                <div class="brand">
                    <span class="fas fa-hotdog"></span> Delicias MV
                </div>
                <h3>O que você deseja fazer?</h3>
                <p>Escolha uma das opções abaixo para continuar</p>
            </div>

            <a href="<?= site_url('registrar') ?>" class="btn-choice btn-cadastrar">
                <i class="fas fa-user-plus"></i>CADASTRAR CONTA
            </a>

            <div class="divider"><span>ou</span></div>

            <a href="<?= site_url('login/entrar') ?>" class="btn-choice btn-entrar">
                <i class="fas fa-sign-in-alt"></i>ENTRAR
            </a>
        </div>
    </div>

    <script src="<?= base_url('web/src/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/bootstrap.min.js') ?>"></script>

</body>
</html>
