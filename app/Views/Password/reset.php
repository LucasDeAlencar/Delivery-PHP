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

                    <?php if (session()->has('errors_model')): ?>
                        <div class="alert alert-danger">
                            <h6>Por favor, corrija os seguintes erros:</h6>
                            <ul class="mb-0">
                                <?php foreach (session('errors_model') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

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

                    <form action="<?= site_url("password/processareset/$token") ?>" method="post">

    <!-- <?= csrf_field() ?> Temporariamente removido -->

                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmation_password">Confirmação de senha</label>
                            <input type="password" class="form-control" name="password_confirmation" id="confirmation_password">
                        </div>
                        
                        <div class="mt-3">
                            <input type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" value="Redefinir senha">
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

    $("form").submit(function () {

        $(this).find(":submit").attr('disabled', 'disabled');

        $("btn-reset-senha").val("Enviado e-mail de recuperação...");
    });
</script>

<?php echo $this->endSection(); ?>

