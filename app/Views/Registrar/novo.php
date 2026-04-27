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
        .form-control:read-only {
            background: #1a1a1a;
            color: #888;
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
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #333;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 10px;
        }
        .step.active {
            background: #28a745;
            color: #fff;
        }
        .step.completed {
            background: #20c997;
            color: #fff;
        }
        .step-line {
            height: 3px;
            width: 50px;
            background: #333;
            align-self: center;
        }
        .step-line.active {
            background: #28a745;
        }
        .hidden {
            display: none !important;
        }
        .required-label {
            color: #ff6b6b;
            font-size: 0.8rem;
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

            <div class="step-indicator">
                <div class="step active" id="step1">1</div>
                <div class="step-line" id="line1"></div>
                <div class="step" id="step2">2</div>
                <div class="step-line" id="line2"></div>
                <div class="step" id="step3">3</div>
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

            <!-- Etapa 1: Verificação de Email -->
            <div id="etapa-verificacao">
                <form id="form-verificacao">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="email">E-mail <span class="required-label">*</span></label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="<?= old('email') ?>" 
                               class="form-control" 
                               placeholder="Digite o seu e-mail"
                               required>
                    </div>
                    <button type="submit" class="btn btn-register" id="btnVerificarEmail">
                        <i class="fas fa-paper-plane mr-2"></i>ENVIAR CÓDIGO
                    </button>
                </form>
            </div>

            <!-- Etapa 2: Verificação de Código -->
            <div id="etapa-codigo" class="hidden">
                <div class="text-center mb-3">
                    <p style="color: #ccc;">Enviamos um código de 6 caracteres para <strong id="email-verificado" style="color: #28a745;"></strong></p>
                    <p style="color: #888; font-size: 0.9rem;">Tempo restante: <span id="tempo-restante" style="color: #0055ff;">5:00</span></p>
                </div>
                
                <div class="form-group">
                    <label for="codigo" style="color: #28a745;">Código de Verificação <span class="required-label">*</span></label>
                    <input type="text" 
                           id="codigo" 
                           class="form-control text-center" 
                           placeholder="000000"
                           maxlength="6"
                           style="font-size: 1.5rem; letter-spacing: 0.5rem; text-transform: uppercase;">
                </div>
                
                <div id="codigo-dev" style="background: #2d5a2d; padding: 10px; border-radius: 5px; margin-top: 10px; display: none;">
                    <small style="color: #90ee90;">Código para desenvolvimento: <strong id="codigo-dev-valor"></strong></small>
                </div>
                
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-outline-success btn-sm" id="btnReenviarCodigo" style="color: #28a745; border-color: #28a745;">
                        <i class="fas fa-redo mr-1"></i>Reenviar código
                    </button>
                    <div id="timer-reenvio" style="display: none; color: #999; font-size: 0.9rem; margin-top: 5px;">
                        Aguarde <span id="segundos-restantes">30</span>s para reenviar
                    </div>
                </div>

                <button type="button" class="btn btn-register" id="btnVerificarCodigo" style="margin-top: 1.5rem;">
                    <i class="fas fa-check mr-2"></i>VERIFICAR CÓDIGO
                </button>
            </div>

            <!-- Etapa 3: Cadastro de Dados -->
            <div id="etapa-cadastro" class="hidden">
                <form id="form-cadastro" action="<?= site_url('registar/criar') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="email" id="email-cadastro">

                    <div class="form-group">
                        <label for="nome">Nome Completo <span class="required-label">*</span></label>
                        <input type="text" 
                               name="nome" 
                               id="nome"
                               value="<?= old('nome') ?>" 
                               class="form-control" 
                               placeholder="Digite seu nome completo"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="telefone">Telefone <span class="required-label">*</span></label>
                        <input type="tel" 
                               name="telefone" 
                               id="telefone"
                               value="<?= old('telefone') ?>" 
                               class="form-control" 
                               placeholder="(00) 00000-0000"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="cep">CEP <span class="required-label">*</span></label>
                        <input type="text" 
                               name="cep" 
                               id="cep"
                               value="<?= old('cep') ?>" 
                               class="form-control" 
                               placeholder="00000-000"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" 
                               name="cidade" 
                               id="cidade"
                               value="<?= old('cidade') ?>" 
                               class="form-control" 
                               placeholder="Campo autopreenchido pelo CEP"
                               readonly>
                    </div>

                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" 
                               name="bairro" 
                               id="bairro"
                               value="<?= old('bairro') ?>" 
                               class="form-control" 
                               placeholder="Campo autopreenchido pelo CEP"
                               readonly>
                    </div>

                    <div class="form-group">
                        <label for="endereco">Logradouro</label>
                        <input type="text" 
                               name="endereco" 
                               id="endereco"
                               value="<?= old('endereco') ?>" 
                               class="form-control" 
                               placeholder="Campo autopreenchido pelo CEP"
                               readonly>
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

                    <button type="submit" class="btn btn-register" id="btnCadastrar">
                        <i class="fas fa-user-plus mr-2"></i>CADASTRAR
                    </button>
                </form>
            </div>

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
        document.addEventListener('DOMContentLoaded', function () {
            let emailAtual = '';
            let intervalTimer = null;
            let timerReenvio = null;
            let podeReenviar = true;

            const btnVerificarEmail = document.getElementById('btnVerificarEmail');
            const btnVerificarCodigo = document.getElementById('btnVerificarCodigo');
            const btnReenviarCodigo = document.getElementById('btnReenviarCodigo');
            const formVerificacao = document.getElementById('form-verificacao');
            const formCadastro = document.getElementById('form-cadastro');
            const inputCodigo = document.getElementById('codigo');
            const inputEmail = document.getElementById('email');

            // FUNÇÕES DE PERSISTÊNCIA LOCALSTORAGE
            function salvarEstado(etapa, email) {
                const estado = {
                    etapa: etapa,
                    email: email,
                    timestamp: Date.now()
                };
                localStorage.setItem('registro_estado', JSON.stringify(estado));
            }

            function limparEstado() {
                localStorage.removeItem('registro_estado');
            }

            function carregarEstado() {
                const estadoSalvo = localStorage.getItem('registro_estado');
                return estadoSalvo ? JSON.parse(estadoSalvo) : null;
            }

            // Verifica se o estado já está na etapa atual (evita salvamentos duplicados)
            function getEtapaAtual() {
                const estado = carregarEstado();
                return estado ? estado.etapa : 1;
            }

            // Verificar se há estado salvo e se ainda é válido no servidor
            async function restaurarSessaoSalva() {
                const estado = carregarEstado();
                if (!estado || !estado.email) {
                    return;
                }

                try {
                    const response = await fetch('<?= site_url('registar/verificarSessao') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        })
                    });
                    const data = await response.json();

                    if (data.sucesso) {
                        // Sessão ainda ativa no servidor
                        emailAtual = estado.email;
                        document.getElementById('email-verificado').textContent = emailAtual;

                        if (estado.etapa >= 2) {
                            // Mostrar etapa de código
                            document.getElementById('etapa-verificacao').classList.add('hidden');
                            document.getElementById('etapa-codigo').classList.remove('hidden');
                            updateSteps(2);

                            // Calcular tempo restante
                            const tempoDecorrido = Math.floor((Date.now() - estado.timestamp) / 1000);
                            const tempoRestante = Math.max(0, 300 - tempoDecorrido);

                            if (tempoRestante > 0) {
                                iniciarTimer(tempoRestante);
                                inputCodigo.focus();
                            } else {
                                // Expirado
                                limparEstado();
                                alert('Código expirado. Preencha o email novamente.');
                                location.reload();
                            }
                        }

                        if (estado.etapa >= 3) {
                            // Código já verificado, mostrar formulário de cadastro
                            document.getElementById('etapa-codigo').classList.add('hidden');
                            document.getElementById('etapa-cadastro').classList.remove('hidden');
                            updateSteps(3);
                            document.getElementById('email-cadastro').value = emailAtual;
                            document.getElementById('nome').focus();
                        }
                    } else {
                        // Sessão expirou no servidor, limpar localStorage
                        limparEstado();
                    }
                } catch (error) {
                    console.error('Erro ao restaurar sessão:', error);
                }
            }

            // Atualizar steps
            function updateSteps(step) {
                document.getElementById('step1').className = step >= 1 ? 'step active' : 'step';
                document.getElementById('step2').className = step >= 2 ? 'step active' : 'step';
                document.getElementById('step3').className = step >= 3 ? 'step active' : 'step';
                document.getElementById('line1').className = step >= 2 ? 'step-line active' : 'step-line';
                document.getElementById('line2').className = step >= 3 ? 'step-line active' : 'step-line';
            }

            // Etapa 1: Verificar Email
            formVerificacao.addEventListener('submit', function(e) {
                e.preventDefault();

                const email = inputEmail.value.trim();
                if (!email) {
                    alert('Por favor, digite um e-mail');
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert('Por favor, digite um e-mail válido');
                    return;
                }

                btnVerificarEmail.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>ENVIANDO...';
                btnVerificarEmail.disabled = true;

                fetch('<?= site_url('registar/enviarCodigo') ?>', {
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
                        emailAtual = email;
                        document.getElementById('email-verificado').textContent = email;

                        if (data.codigo_dev) {
                            document.getElementById('codigo-dev-valor').textContent = data.codigo_dev;
                            document.getElementById('codigo-dev').style.display = 'block';
                        }

                        document.getElementById('etapa-verificacao').classList.add('hidden');
                        document.getElementById('etapa-codigo').classList.remove('hidden');
                        updateSteps(2);
                        iniciarTimer();
                        inputCodigo.focus();

                        // SALVAR ESTADO APENAS SE NÃO ESTIVER JÁ NA ETAPA 2
                        if (getEtapaAtual() < 2) {
                            salvarEstado(2, emailAtual);
                        }
                    } else {
                        alert('Erro ao enviar código: ' + data.msg);
                        btnVerificarEmail.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>ENVIAR CÓDIGO';
                        btnVerificarEmail.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao enviar código. Tente novamente.');
                    btnVerificarEmail.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>ENVIAR CÓDIGO';
                    btnVerificarEmail.disabled = false;
                });
            });

            // Etapa 2: Verificar Código
            btnVerificarCodigo.addEventListener('click', function() {
                const codigo = inputCodigo.value.trim();

                if (codigo.length !== 6) {
                    alert('Digite o código de 6 caracteres');
                    return;
                }

                btnVerificarCodigo.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>VERIFICANDO...';
                btnVerificarCodigo.disabled = true;

                fetch('<?= site_url('registar/verificarCodigo') ?>', {
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

                        document.getElementById('email-cadastro').value = emailAtual;

                        document.getElementById('etapa-codigo').classList.add('hidden');
                        document.getElementById('etapa-cadastro').classList.remove('hidden');
                        updateSteps(3);
                        document.getElementById('nome').focus();

                        // SALVAR ESTADO APENAS SE NÃO ESTIVER JÁ NA ETAPA 3
                        if (getEtapaAtual() < 3) {
                            salvarEstado(3, emailAtual);
                        }
                    } else {
                        alert('Erro: ' + data.msg);
                        btnVerificarCodigo.innerHTML = '<i class="fas fa-check mr-2"></i>VERIFICAR CÓDIGO';
                        btnVerificarCodigo.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao verificar código. Tente novamente.');
                    btnVerificarCodigo.innerHTML = '<i class="fas fa-check mr-2"></i>VERIFICAR CÓDIGO';
                    btnVerificarCodigo.disabled = false;
                });
            });

            // Timer de 5 minutos
            function iniciarTimer(tempoInicial) {
                let tempoRestante = tempoInicial || 300;
                const timerElement = document.getElementById('tempo-restante');

                intervalTimer = setInterval(() => {
                    const minutos = Math.floor(tempoRestante / 60);
                    const segundos = tempoRestante % 60;
                    timerElement.textContent = `${minutos}:${segundos.toString().padStart(2, '0')}`;

                    if (tempoRestante <= 0) {
                        clearInterval(intervalTimer);
                        alert('Código expirado. Preencha o email novamente.');
                        limparEstado();
                        location.reload();
                    }

                    tempoRestante--;
                }, 1000);
            }

            // Reenviar código
            btnReenviarCodigo.addEventListener('click', function() {
                if (!podeReenviar) return;

                podeReenviar = false;
                btnReenviarCodigo.disabled = true;
                btnReenviarCodigo.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Enviando...';

                fetch('<?= site_url('registar/enviarCodigo') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: emailAtual,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        btnReenviarCodigo.innerHTML = '<i class="fas fa-check mr-1"></i>Enviado!';

                        if (data.codigo_dev) {
                            document.getElementById('codigo-dev-valor').textContent = data.codigo_dev;
                            document.getElementById('codigo-dev').style.display = 'block';
                        }

                        if (intervalTimer) clearInterval(intervalTimer);
                        iniciarTimer();

                        setTimeout(() => {
                            btnReenviarCodigo.style.display = 'none';
                            document.getElementById('timer-reenvio').style.display = 'block';

                            let segundosRestantes = 30;
                            const spanSegundos = document.getElementById('segundos-restantes');

                            timerReenvio = setInterval(() => {
                                spanSegundos.textContent = segundosRestantes;
                                segundosRestantes--;

                                if (segundosRestantes < 0) {
                                    clearInterval(timerReenvio);
                                    document.getElementById('timer-reenvio').style.display = 'none';
                                    btnReenviarCodigo.style.display = 'inline-block';
                                    btnReenviarCodigo.innerHTML = '<i class="fas fa-redo mr-1"></i>Reenviar código';
                                    btnReenviarCodigo.disabled = false;
                                    podeReenviar = true;
                                }
                            }, 1000);
                        }, 1000);
                    } else {
                        alert('Erro ao reenviar código: ' + data.msg);
                        btnReenviarCodigo.innerHTML = '<i class="fas fa-redo mr-1"></i>Reenviar código';
                        btnReenviarCodigo.disabled = false;
                        podeReenviar = true;
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro de conexão. Tente novamente.');
                    btnReenviarCodigo.innerHTML = '<i class="fas fa-redo mr-1"></i>Reenviar código';
                    btnReenviarCodigo.disabled = false;
                    podeReenviar = true;
                });
            });

            // Máscara telefone
            document.getElementById('telefone').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    if (value.length < 14) {
                        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                    }
                }
                e.target.value = value;
            });

            // Event listener para submit do formulário de cadastro
            if (formCadastro) {
                formCadastro.addEventListener('submit', function(e) {
                    console.log('Formulário de cadastro enviado');
                    limparEstado(); // Limpar estado salvo
                    const btnCadastrar = document.getElementById('btnCadastrar');
                    btnCadastrar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>CADASTRANDO...';
                    btnCadastrar.disabled = true;
                });
            }

            // Máscara CEP
            document.getElementById('cep').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 8) {
                    value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
                }
                e.target.value = value;

                if (value.replace(/\D/g, '').length === 8) {
                    buscarCEP(value.replace(/\D/g, ''));
                }
            });

            // Buscar CEP
            function buscarCEP(cep) {
                console.log('Buscando CEP:', cep);
                fetch('<?= site_url('registrar/buscar_cep') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({cep: cep})
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.text();
                })
                .then(text => {
                    console.log('Response text:', text);
                    const data = JSON.parse(text);
                    console.log('Data parsed:', data);
                    if (data && data.erro) {
                        alert(data.msg || 'CEP não encontrado');
                    } else if (data) {
                        document.getElementById('bairro').value = data.bairro || '';
                        document.getElementById('cidade').value = data.localidade || '';
                        document.getElementById('endereco').value = data.logradouro || '';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                    alert('Erro ao buscar CEP. Tente novamente.');
                });
            }

            // Permitir apenas letras e números no código
            inputCodigo.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
            });

            // Botão "Voltar ao site" - limpa localStorage antes de navegar
            const linkVoltarSite = document.getElementById('link-voltar-site');
            if (linkVoltarSite) {
                linkVoltarSite.addEventListener('click', function(e) {
                    e.preventDefault(); // Previne navegação imediata
                    limparEstado(); // Limpa todo o estado do registro
                    // Navega após limpeza
                    window.location.href = this.href;
                });
            }

            // Ao finalizar cadastro (redirecionamento), limpar localStorage
            formCadastro.addEventListener('submit', function(e) {
                // O servidor redirecionará após criar, mas vamos limpar o estado
                limparEstado();
            });

            // Restaurar sessão salva ao carregar a página
            restaurarSessaoSalva();
        });
    </script>

</body>
</html>
