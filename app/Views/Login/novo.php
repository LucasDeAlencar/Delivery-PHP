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
    
    h4, h6 {
        color: #333;
    }
    
    .page-body-wrapper {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
</style>
<?php echo $this->endSection(); ?>


<!-- Área de Conteudos -->
<?php echo $this->section('conteudos'); ?>

<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-lg-5 mx-auto">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">

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

                    <div class="brand-logo text-center mb-4">
                        <img src="<?= site_url('admin/') ?>images/logo.svg" alt="logo">
                    </div>
                    <h4 class="text-center mb-2">Olá, seja bem-vindo(a)!</h4>
                    <h6 class="font-weight-light text-center mb-4">Por favor, realize o login para continuar</h6>

                    <?= form_open('login/criar'); ?>

    <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="<?= old('email') ?>" 
                                   class="form-control form-control-lg" 
                                   placeholder="Digite o seu e-mail"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" 
                                   name="password" 
                                   id="password"
                                   class="form-control form-control-lg" 
                                   placeholder="Digite a sua senha"
                                   required>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                <i class="fas fa-sign-in-alt mr-2"></i>ENTRAR
                            </button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="<?= site_url('password/esqueci') ?>" class="auth-link">
                                <i class="fas fa-key mr-1"></i>Esqueci a minha senha
                            </a>
                        </div>

                        <div class="text-center mt-4 font-weight-light">
                            Ainda não tem uma conta? 
                            <a href="<?= site_url('registrar') ?>" class="auth-link">
                                <strong>Criar conta</strong>
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
    <!-- content-wrapper ends -->
</div>

<?php echo $this->endSection(); ?>


<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('button[type="submit"]');
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
        });
    }

    // Melhorar UX com foco automático
    if (emailInput && !emailInput.value) {
        emailInput.focus();
    }
});
</script>

<?php echo $this->endSection(); ?>

