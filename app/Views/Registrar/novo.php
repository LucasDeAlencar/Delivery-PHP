<?php echo $this->extend('Admin/layout/principal_autenticacao'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> 
<?php echo $titulo; ?> 
<?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>
<style>
    /* Estilo simples alinhado com a home */
    .auth-form-light {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }
    
    .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
    }
    
    .btn-primary {
        background: #ffc107;
        border-color: #ffc107;
        color: #333;
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: #e0a800;
        border-color: #d39e00;
        color: #333;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    }
    
    .auth-link {
        color: #ffc107 !important;
        transition: color 0.3s ease;
        text-decoration: none;
    }
    
    .auth-link:hover {
        color: #e0a800 !important;
        text-decoration: none;
    }
    
    .alert {
        border-radius: 10px;
        border: none;
    }
    
    .form-group label {
        color: #333;
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    .text-muted {
        color: #6c757d !important;
    }
    
    .cpf-info {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 5px;
    }
    
    h4, h6 {
        color: #333;
    }
    
    .page-body-wrapper {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
</style>
<?php echo $this->endSection(); ?>

<!-- Área de Conteúdo -->
<?php echo $this->section('conteudos'); ?>

<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-lg-6 mx-auto">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">

                    <!-- Mensagens de Feedback -->
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

                    <!-- Exibir erros de validação -->
                    <?php if (session()->has('erros_model')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erros encontrados:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach (session('erros_model') as $erro): ?>
                                    <li><?= esc($erro) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="brand-logo text-center mb-4">
                        <img src="<?= site_url('admin/') ?>images/logo.svg" alt="logo">
                    </div>
                    
                    <h4 class="text-center mb-2">Criar Nova Conta</h4>
                    <h6 class="font-weight-light text-center mb-4">Preencha os dados abaixo para se cadastrar</h6>

                    <?= form_open('registrar/criar', ['id' => 'form-registro']); ?>

                        <div class="form-group">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" 
                                   name="nome" 
                                   id="nome"
                                   value="<?= old('nome') ?>" 
                                   class="form-control form-control-lg" 
                                   placeholder="Digite seu nome completo"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail *</label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="<?= old('email') ?>" 
                                   class="form-control form-control-lg" 
                                   placeholder="Digite seu e-mail"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="cpf">CPF *</label>
                            <input type="text" 
                                   name="cpf" 
                                   id="cpf"
                                   value="<?= old('cpf') ?>" 
                                   class="form-control form-control-lg" 
                                   placeholder="000.000.000-00"
                                   maxlength="14"
                                   required>
                            <div class="cpf-info">
                                <i class="fas fa-info-circle"></i> Formato: 000.000.000-00
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="telefone">Telefone *</label>
                            <input type="text" 
                                   name="telefone" 
                                   id="telefone"
                                   value="<?= old('telefone') ?>" 
                                   class="form-control form-control-lg" 
                                   placeholder="(00) 00000-0000"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="password">Senha *</label>
                            <input type="password" 
                                   name="password" 
                                   id="password"
                                   class="form-control form-control-lg" 
                                   placeholder="Digite uma senha (mínimo 6 caracteres)"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirm">Confirmar Senha *</label>
                            <input type="password" 
                                   name="password_confirm" 
                                   id="password_confirm"
                                   class="form-control form-control-lg" 
                                   placeholder="Digite a senha novamente"
                                   required>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                <i class="fas fa-user-plus mr-2"></i>CRIAR CONTA
                            </button>
                        </div>

                        <div class="text-center mt-4 font-weight-light">
                            Já tem uma conta? 
                            <a href="<?= site_url('login') ?>" class="auth-link">
                                <strong>Fazer login</strong>
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <a href="<?= site_url('/') ?>" class="auth-link">
                                <i class="fas fa-arrow-left mr-1"></i>Voltar ao site
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Máscara para CPF
    const cpfInput = document.getElementById('cpf');
    cpfInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
    });

    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    telefoneInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
        e.target.value = value;
    });

    // Validação do formulário
    const form = document.getElementById('form-registro');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirm');

    form.addEventListener('submit', function (e) {
        let isValid = true;
        let errorMessage = '';

        // Validar nome
        const nome = document.getElementById('nome').value.trim();
        if (nome.length < 4) {
            isValid = false;
            errorMessage += 'O nome deve ter pelo menos 4 caracteres.\n';
        }

        // Validar email
        const email = document.getElementById('email').value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            isValid = false;
            errorMessage += 'Digite um e-mail válido.\n';
        }

        // Validar CPF
        const cpf = document.getElementById('cpf').value.replace(/\D/g, '');
        if (cpf.length !== 11) {
            isValid = false;
            errorMessage += 'CPF deve ter 11 dígitos.\n';
        }

        // Validar telefone
        const telefone = document.getElementById('telefone').value.replace(/\D/g, '');
        if (telefone.length < 10) {
            isValid = false;
            errorMessage += 'Digite um telefone válido.\n';
        }

        // Validar senha
        const password = passwordInput.value;
        if (password.length < 6) {
            isValid = false;
            errorMessage += 'A senha deve ter pelo menos 6 caracteres.\n';
        }

        // Validar confirmação de senha
        const passwordConfirm = passwordConfirmInput.value;
        if (password !== passwordConfirm) {
            isValid = false;
            errorMessage += 'As senhas não coincidem.\n';
        }

        if (!isValid) {
            e.preventDefault();
            alert('Erros encontrados:\n\n' + errorMessage);
            return false;
        }

        // Mostrar loading
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Criando conta...';
        submitBtn.disabled = true;

        // Se chegou até aqui, o formulário será enviado
        console.log('Formulário válido, enviando...');
    });

    // Validação em tempo real das senhas
    function validatePasswords() {
        const password = passwordInput.value;
        const passwordConfirm = passwordConfirmInput.value;
        
        if (passwordConfirm && password !== passwordConfirm) {
            passwordConfirmInput.setCustomValidity('As senhas não coincidem');
            passwordConfirmInput.style.borderColor = '#dc3545';
        } else {
            passwordConfirmInput.setCustomValidity('');
            passwordConfirmInput.style.borderColor = '';
        }
    }

    passwordInput.addEventListener('input', validatePasswords);
    passwordConfirmInput.addEventListener('input', validatePasswords);

    console.log('Sistema de registro inicializado');
});
</script>

<?php echo $this->endSection(); ?>