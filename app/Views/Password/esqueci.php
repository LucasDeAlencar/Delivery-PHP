<?php echo $this->extend('Admin/layout/principal_autenticacao'); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('estilos'); ?>


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

                    <div class="brand-logo">
                        <img src="<?= site_url('admin/') ?>images/logo.svg" alt="logo">
                    </div>
                    <h4>Recuperando a senha!</h4>
                    <h6 class="font-weight-light">Esqueci a minha senha</h6>

                    <form action="<?= site_url('password/processaesqueci') ?>" method="post">

    <!-- <?= csrf_field() ?> Temporariamente removido -->

                        <div class="form-group">
                            <input type="email" name="email" value="<?= old('email') ?>" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Digite o seu e-mail">
                        </div>
                        <div class="mt-3">
                            <button id="btn-reset-senha" type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >ENTRAR</button>
                        </div>
                        
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <a href="<?= site_url('login') ?>" class="auth-link text-black">Lembrei a minha senha</a>
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
// Debug do formulário
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('button[type="submit"]');

        console.log('Formulário encontrado:', form);
        console.log('Botão encontrado:', submitBtn);

        if (form) {
            form.addEventListener('submit', function (e) {
                console.log('=== SUBMIT DO FORMULÁRIO ===');
                console.log('Action:', this.action);
                console.log('Method:', this.method);

                const email = document.querySelector('input[name="email"]').value;
                const password = document.querySelector('input[name="password"]').value;

                console.log('Email:', email);
                console.log('Password preenchida:', password ? 'SIM' : 'NÃO');

                if (!email || !password) {
                    alert('Por favor, preencha todos os campos');
                    e.preventDefault();
                    return false;
                }

                console.log('Enviando formulário...');
            });
        }

        if (submitBtn) {
            submitBtn.addEventListener('click', function (e) {
                console.log('Botão clicado!');
            });
        }
    });
    
    $("form").submit(function(){
        
        $(this).find(":submit").attr('disabled', 'disabled');
        
        $("btn-reset-senha").val("Enviado e-mail de recuperação...");
    });
</script>

<?php echo $this->endSection(); ?>

