<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Login Admin - Restaurante</title>
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
    </style>
</head>
<body>

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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <div class="brand">
                    <span class="flaticon-pizza-1"></span> No Kapricho
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

            <form action="<?= site_url('login/criar') ?>" method="POST" id="loginForm">
                <?= csrf_field() ?>
                <input type="hidden" name="acao" id="acao" value="verificar">

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

                <div class="form-group" id="campo-senha" style="display: none;">
                    <label for="password">Senha</label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="form-control" 
                           placeholder="Digite a sua senha"
                           required>
                </div>

                <div id="campos-cliente" style="display: none;">
                    <div class="form-group">
                        <label for="nome">Nome Completo *</label>
                        <input type="text" 
                               name="nome" 
                               id="nome"
                               value="<?= old('nome') ?>" 
                               class="form-control" 
                               placeholder="Digite seu nome completo"
                               data-required="true">
                    </div>

                    <div class="form-group">
                        <label for="telefone">Telefone *</label>
                        <input type="tel" 
                               name="telefone" 
                               id="telefone"
                               value="<?= old('telefone') ?>" 
                               class="form-control" 
                               placeholder="(00) 00000-0000"
                               data-required="true">
                    </div>

                    <div class="form-group">
                        <label for="cep">CEP *</label>
                        <input type="text" 
                               name="cep" 
                               id="cep"
                               value="<?= old('cep') ?>" 
                               class="form-control" 
                               placeholder="00000-000"
                               data-required="true">
                    </div>

                    <div class="form-group">
                        <label for="cidade">Cidade *</label>
                        <input type="text" 
                               name="cidade" 
                               id="cidade"
                               value="<?= old('cidade') ?>" 
                               class="form-control" 
                               placeholder="Campo autopreenchido pelo CEP"
                               readonly
                               data-required="true">
                    </div>

                    <div class="form-group">
                        <label for="bairro">Bairro *</label>
                        <input type="text" 
                               name="bairro" 
                               id="bairro"
                               value="<?= old('bairro') ?>" 
                               class="form-control" 
                               placeholder="Campo autopreenchido pelo CEP"
                               readonly
                               data-required="true">
                    </div>

                    <div class="form-group">
                        <label for="endereco">Logradouro *</label>
                        <input type="text" 
                               name="endereco" 
                               id="endereco"
                               value="<?= old('endereco') ?>" 
                               class="form-control" 
                               placeholder="Campo autopreenchido pelo CEP"
                               readonly
                               data-required="true">
                    </div>

                    <div class="form-group">
                        <label for="numero">Número</label>
                        <input type="text" 
                               name="numero" 
                               id="numero"
                               value="<?= old('numero') ?>" 
                               class="form-control" 
                               placeholder="Número da residência">
                    </div>

                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" 
                               name="complemento" 
                               id="complemento"
                               value="<?= old('complemento') ?>" 
                               class="form-control" 
                               placeholder="Apartamento, bloco, etc.">
                    </div>
                </div>

                <button type="button" class="btn btn-login" id="btnVerificar">
                    <i class="fas fa-search mr-2"></i>VERIFICAR E-MAIL
                </button>

                <button type="submit" class="btn btn-login" id="btnEntrar" style="display: none;">
                    <i class="fas fa-sign-in-alt mr-2"></i>ENTRAR
                </button>

                <button type="submit" class="btn btn-login" id="btnCadastrar" style="display: none;">
                    <i class="fas fa-user-plus mr-2"></i>CADASTRAR CLIENTE
                </button>

            </form>
        </div>
    </div>

    <!-- Modal de Verificação de Código -->
    <div class="modal fade" id="modalVerificacao" tabindex="-1" role="dialog" aria-labelledby="modalVerificacaoLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333;">
                <div class="modal-header" style="border-bottom: 1px solid #333;">
                    <h5 class="modal-title" id="modalVerificacaoLabel" style="color: #f8b531;">
                        <i class="fas fa-envelope mr-2"></i>Verificação de Email
                    </h5>
                </div>
                <div class="modal-body" style="color: #fff;">
                    <p>Enviamos um código de 6 caracteres para seu email.</p>
                    <p><strong>Tempo restante: <span id="tempo-restante">5:00</span></strong></p>
                    
                    <div class="form-group">
                        <label for="codigo-verificacao" style="color: #f8b531;">Digite o código:</label>
                        <input type="text" 
                               id="codigo-verificacao" 
                               class="form-control text-center" 
                               placeholder="000000"
                               maxlength="6"
                               style="font-size: 1.5rem; letter-spacing: 0.5rem; text-transform: uppercase;">
                    </div>
                    
                    <div id="codigo-dev" style="background: #2d5a2d; padding: 10px; border-radius: 5px; margin-top: 10px; display: none;">
                        <small style="color: #90ee90;">Código para desenvolvimento: <strong id="codigo-dev-valor"></strong></small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #333;">
                    <button type="button" class="btn btn-login" id="btnVerificarCodigo">
                        <i class="fas fa-check mr-2"></i>VERIFICAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('web/src/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('web/src/js/bootstrap.min.js') ?>"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Verificar se já está logado como cliente
            const emailSalvo = localStorage.getItem('cliente_email');
            if (emailSalvo) {
                window.location.href = '<?= base_url('/') ?>';
                return;
            }

            const form = document.getElementById('loginForm');
            const btnVerificar = document.getElementById('btnVerificar');
            const btnEntrar = document.getElementById('btnEntrar');
            const btnCadastrar = document.getElementById('btnCadastrar');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const campoSenha = document.getElementById('campo-senha');
            const camposCliente = document.getElementById('campos-cliente');

            btnVerificar.addEventListener('click', function () {
                const email = emailInput.value.trim();

                if (!email) {
                    alert('Por favor, digite um e-mail');
                    emailInput.focus();
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert('Por favor, digite um e-mail válido');
                    emailInput.focus();
                    return;
                }

                const originalText = btnVerificar.innerHTML;
                btnVerificar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>VERIFICANDO...';
                btnVerificar.disabled = true;

                fetch('<?= site_url('login/verificarEmail') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: email,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.tipo === 'admin') {
                        // Admin encontrado - ir direto para campo senha (sem verificação de email)
                        mostrarCampoSenha();
                    } else if (data.tipo === 'cliente') {
                        // Cliente encontrado - enviar código de verificação
                        enviarCodigoVerificacao(email, 'cliente');
                    } else {
                        // Email não encontrado - enviar código para cadastro
                        enviarCodigoVerificacao(email, 'cadastro');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao verificar e-mail. Tente novamente.');
                    btnVerificar.innerHTML = originalText;
                    btnVerificar.disabled = false;
                });
            });

            // Máscaras
            document.getElementById('telefone').addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    if (value.length < 14) {
                        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                    }
                }
                e.target.value = value;
            });

            document.getElementById('cep').addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 8) {
                    value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
                }
                e.target.value = value;

                if (value.replace(/\D/g, '').length === 8) {
                    buscarCEP(value.replace(/\D/g, ''));
                }
            });

            function buscarCEP(cep) {
                const bairroInput = document.getElementById('bairro');
                const cidadeInput = document.getElementById('cidade');
                const enderecoInput = document.getElementById('endereco');

                fetch('<?= site_url('login/buscar_cep') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({cep: cep})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        bairroInput.value = '';
                        cidadeInput.value = '';
                        enderecoInput.value = '';
                        alert(data.msg || 'CEP não encontrado');
                    } else {
                        bairroInput.value = data.bairro || '';
                        cidadeInput.value = data.localidade || '';
                        enderecoInput.value = data.logradouro || '';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                    bairroInput.value = '';
                    cidadeInput.value = '';
                    enderecoInput.value = '';
                    alert('Erro ao buscar CEP. Tente novamente.');
                });
            }

            emailInput.focus();

            // Funções de verificação por código
            let tipoVerificacao = '';
            let intervalTimer = null;

            function enviarCodigoVerificacao(email, tipo) {
                tipoVerificacao = tipo;
                
                fetch('<?= site_url('login/enviarCodigo') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: email,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        // Mostrar código para desenvolvimento se disponível
                        if (data.codigo_dev) {
                            document.getElementById('codigo-dev-valor').textContent = data.codigo_dev;
                            document.getElementById('codigo-dev').style.display = 'block';
                        }
                        
                        $('#modalVerificacao').modal('show');
                        iniciarTimer();
                        document.getElementById('codigo-verificacao').focus();
                    } else {
                        alert('Erro ao enviar código: ' + data.msg);
                        btnVerificar.innerHTML = originalText;
                        btnVerificar.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao enviar código. Tente novamente.');
                    btnVerificar.innerHTML = originalText;
                    btnVerificar.disabled = false;
                });
            }

            function iniciarTimer() {
                let tempoRestante = 300; // 5 minutos em segundos
                const timerElement = document.getElementById('tempo-restante');
                
                intervalTimer = setInterval(() => {
                    const minutos = Math.floor(tempoRestante / 60);
                    const segundos = tempoRestante % 60;
                    timerElement.textContent = `${minutos}:${segundos.toString().padStart(2, '0')}`;
                    
                    if (tempoRestante <= 0) {
                        clearInterval(intervalTimer);
                        $('#modalVerificacao').modal('hide');
                        alert('Código expirado. Tente novamente.');
                        btnVerificar.innerHTML = originalText;
                        btnVerificar.disabled = false;
                    }
                    
                    tempoRestante--;
                }, 1000);
            }

            // Verificar código
            document.getElementById('btnVerificarCodigo').addEventListener('click', function() {
                const codigo = document.getElementById('codigo-verificacao').value.trim();
                
                if (codigo.length !== 6) {
                    alert('Digite o código de 6 caracteres');
                    return;
                }

                fetch('<?= site_url('login/verificarCodigo') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        codigo: codigo,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        clearInterval(intervalTimer);
                        $('#modalVerificacao').modal('hide');
                        
                        // Processar baseado no tipo
                        if (tipoVerificacao === 'admin') {
                            mostrarCampoSenha();
                        } else if (tipoVerificacao === 'cliente') {
                            localStorage.setItem('cliente_email', emailInput.value);
                            window.location.href = '<?= base_url('/') ?>';
                        } else if (tipoVerificacao === 'cadastro') {
                            mostrarCamposCadastro();
                        }
                    } else {
                        alert('Erro: ' + data.msg);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao verificar código. Tente novamente.');
                });
            });

            function mostrarCampoSenha() {
                campoSenha.style.display = 'block';
                btnVerificar.style.display = 'none';
                btnEntrar.style.display = 'block';
                passwordInput.focus();
                emailInput.readOnly = true;
                emailInput.style.backgroundColor = '#1a1a1a';
                document.getElementById('acao').value = 'login';
                document.querySelectorAll('#campos-cliente input[data-required]').forEach(input => {
                    input.removeAttribute('required');
                });
            }

            function mostrarCamposCadastro() {
                camposCliente.style.display = 'block';
                btnVerificar.style.display = 'none';
                btnCadastrar.style.display = 'block';
                emailInput.readOnly = true;
                emailInput.style.backgroundColor = '#1a1a1a';
                document.getElementById('nome').focus();
                document.getElementById('acao').value = 'cadastro';
                document.querySelectorAll('#campos-cliente input[data-required]').forEach(input => {
                    input.setAttribute('required', 'required');
                });
                passwordInput.removeAttribute('required');
            }

            // Permitir apenas letras e números no código
            document.getElementById('codigo-verificacao').addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
            });
        });
    </script>

</body>
</html>
