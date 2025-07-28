<?php echo $this->extend('Admin/layout/principal'); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('estilos'); ?>


<?php echo $this->endSection(); ?>


<!-- Área de Conteudos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
            <div class="d-flex align-items-end flex-wrap">
                <div class="mr-md-3 mr-xl-5">
                    <h2>Bem-vindo ao painel administrativo!</h2>
                    <p class="mb-md-0">Aqui você pode gerenciar todo o sistema.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Usuários</h4>
                <p class="card-description">Gerencie os usuários do sistema</p>
                <a href="<?php echo site_url('admin/usuarios'); ?>" class="btn btn-primary">Gerenciar Usuários</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Configurações</h4>
                <p class="card-description">Configure o sistema</p>
                <button class="btn btn-secondary" disabled>Em breve</button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>


<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>


<?php echo $this->endSection(); ?>
