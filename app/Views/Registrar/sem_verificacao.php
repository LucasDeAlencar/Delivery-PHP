<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Cadastrar - Delivery</title>
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
            max-width: 500px;
            width: 100%;
            margin: 140px auto 50px;
            border: 1px solid #333;
        }
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .register-header .brand {
            color: #28a745;
            font-size: 2rem;
            font-weight: bold;
        }
        .register-header p {
            color: #ccc;
            margin-top: 0.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            color: #28a745;
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
            border-color: #28a745;
            background: #333;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            color: #fff;
        }
        .btn-register {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: #fff;
            padding: 15px;
            border-radius: 10px;
            width: 100%;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
            color: #fff;
            transform: translateY(-2px);
        }
        .btn-register:disabled {
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
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #888;
        }
        .login-link a {
            color: #0055ff;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .required-label {
            color: #ff6b6b;
            font-size: 0.8rem;
        }
        select.form-control option {
            background: #2d2d2d;
            color: #fff;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>"><span class="fas fa-hotdog mr-1" style="color: #0055ff !important "></span>Space Burger Dog Do Paulista<br><small style="color: #0055ff !important; margin-top: 4px">O delivery favorito da cidade</small></a>
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
        <div class="register-container">
            <div class="register-header">
                <div class="brand">
                    <span class="fas fa-user-plus"></span> Space Burger Dog Do Paulista
                </div>
                <h4 style="color: #fff; margin: 1rem 0 0.5rem;">Crie sua conta!</h4>
                <p>Preencha os dados abaixo para se cadastrar</p>
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

            <!-- Formulário de Cadastro Simplificado -->
            <form id="form-cadastro" action="<?= site_url('registrar/criarSemVerificacao') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" 
                           name="nome" 
                           id="nome"
                           value="<?= old('nome') ?>" 
                           class="form-control" 
                           placeholder="Digite seu nome completo"
                           required>
                </div>

                <div class="form-group">
                    <label for="telefone">Celular/WhatsApp *</label>
                    <input type="tel" 
                           name="telefone" 
                           id="telefone"
                           value="<?= old('telefone') ?>" 
                           class="form-control" 
                           placeholder="(00) 00000-0000"
                           required>
                </div>

                <div class="form-group" style="position:relative;">
                    <label for="cidade">Cidade *</label>
                    <input type="text" name="cidade" id="cidade" value="<?= old('cidade') ?>"
                           class="form-control" placeholder="Digite sua cidade" required autocomplete="off">
                    <div id="cidade-sugestoes" style="display:none;position:absolute;top:100%;left:0;right:0;background:#2d2d2d;border:1px solid #555;border-radius:0 0 8px 8px;z-index:100;max-height:160px;overflow-y:auto;"></div>
                </div>

                <div class="form-group" style="position:relative;">
                    <label for="bairro">Bairro *</label>
                    <input type="text" name="bairro" id="bairro" value="<?= old('bairro') ?>"
                           class="form-control" placeholder="Digite seu bairro" required autocomplete="off">
                    <div id="bairro-sugestoes" style="display:none;position:absolute;top:100%;left:0;right:0;background:#2d2d2d;border:1px solid #555;border-radius:0 0 8px 8px;z-index:100;max-height:160px;overflow-y:auto;"></div>
                </div>

                <div class="form-group">
                    <label for="endereco">Logradouro (Rua, Avenida) *</label>
                    <input type="text" 
                           name="endereco" 
                           id="endereco"
                           value="<?= old('endereco') ?>" 
                           class="form-control" 
                           placeholder="Rua, Avenida, etc."
                           required>
                </div>

                <div class="form-group">
                    <label for="numero">Número *</label>
                    <input type="text" 
                           name="numero" 
                           id="numero"
                           value="<?= old('numero') ?>" 
                           class="form-control" 
                           placeholder="Número"
                           required>
                </div>

                <div class="form-group">
                    <label for="complemento">Complemento</label>
                    <input type="text" 
                           name="complemento" 
                           id="complemento"
                           value="<?= old('complemento') ?>" 
                           class="form-control" 
                           placeholder="Apto, bloco, etc. (opcional)">
                </div>

                <button type="submit" class="btn btn-register" id="btnCadastrar">
                    <i class="fas fa-user-plus mr-2"></i>CADASTRAR
                </button>
            </form>

            <div class="login-link">
                Já tem conta? <a href="<?= site_url('login/entrar') ?>">Entre aqui</a>
            </div>

            <div class="login-link" style="margin-top: 1rem;">
                <a href="<?= site_url('/') ?>" id="link-voltar-site">
                    <i class="fas fa-arrow-left mr-1"></i>Voltar ao site
                </a>
            </div>
        </div>
    </div>

    <script src="<?= base_url('web/src/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/bootstrap.min.js') ?>"></script>

    <script>
        const bairrosCobertura = <?= json_encode(array_map(fn($b) => ['cidade' => $b->cidade, 'nome' => $b->nome], $bairros)) ?>;
        const cidadesCobertura = [...new Set(bairrosCobertura.map(b => b.cidade))].sort();

        document.addEventListener('DOMContentLoaded', function () {
            const cidadeInput  = document.getElementById('cidade');
            const cidadeSugest = document.getElementById('cidade-sugestoes');
            const bairroInput  = document.getElementById('bairro');
            const bairroSugest = document.getElementById('bairro-sugestoes');

            function itemSugestao(texto, onClick) {
                const d = document.createElement('div');
                d.textContent = texto;
                d.style.cssText = 'padding:9px 12px;cursor:pointer;color:#fff;font-size:.9rem;border-bottom:1px solid #3a3a3a;';
                d.addEventListener('mousedown', onClick);
                d.addEventListener('mouseover', () => d.style.background = '#3a3a3a');
                d.addEventListener('mouseout',  () => d.style.background = '');
                return d;
            }

            cidadeInput.addEventListener('input', function () {
                const q = this.value.trim().toLowerCase();
                cidadeSugest.innerHTML = '';
                bairroInput.value = '';
                if (!q) { cidadeSugest.style.display = 'none'; return; }
                const matches = cidadesCobertura.filter(c => c.toLowerCase().includes(q));
                if (!matches.length) { cidadeSugest.style.display = 'none'; return; }
                matches.forEach(c => cidadeSugest.appendChild(itemSugestao(c, () => {
                    cidadeInput.value = c;
                    cidadeSugest.style.display = 'none';
                    bairroInput.focus();
                })));
                cidadeSugest.style.display = 'block';
            });
            cidadeInput.addEventListener('blur', () => setTimeout(() => cidadeSugest.style.display = 'none', 150));

            bairroInput.addEventListener('input', function () {
                const q = this.value.trim().toLowerCase();
                bairroSugest.innerHTML = '';
                if (!q) { bairroSugest.style.display = 'none'; return; }
                const cidadeDigitada = cidadeInput.value.trim().toLowerCase();
                const pool = bairrosCobertura.filter(b =>
                    (!cidadeDigitada || b.cidade.toLowerCase() === cidadeDigitada) &&
                    b.nome.toLowerCase().includes(q)
                );
                if (!pool.length) { bairroSugest.style.display = 'none'; return; }
                pool.forEach(b => {
                    const label = b.nome + (cidadeDigitada ? '' : ' — ' + b.cidade);
                    bairroSugest.appendChild(itemSugestao(label, () => {
                        bairroInput.value = b.nome;
                        if (!cidadeInput.value.trim()) cidadeInput.value = b.cidade;
                        bairroSugest.style.display = 'none';
                    }));
                });
                bairroSugest.style.display = 'block';
            });
            bairroInput.addEventListener('blur', () => setTimeout(() => bairroSugest.style.display = 'none', 150));

            // Máscara telefone
            document.getElementById('telefone').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                    value = value.replace(/(\d)(\d{4})$/, '$1-$2');
                }
                e.target.value = value;
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
