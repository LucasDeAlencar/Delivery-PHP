<?php echo $this->extend('Admin/layout/principal'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('estilos'); ?>
<style>
    .form-group {
        margin-bottom: 1rem;
    }
</style>
<?php echo $this->endSection(); ?>

<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-1">Dados Corporativos</h4>
                        <p class="card-description mb-0">
                            Informações da empresa exibidas no site
                        </p>
                    </div>
                </div>

                <form id="formDadosCorporativos">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="endereco">Endereço</label>
                                <input type="text" 
                                       id="endereco" 
                                       class="form-control" 
                                       placeholder="Rua, Avenida, etc."
                                       value="<?= esc($dados->endereco ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numero">Telefone</label>
                                <input type="text" 
                                       id="numero" 
                                       class="form-control" 
                                       placeholder="(00) 00000-0000"
                                       value="<?= esc($dados->numero ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" 
                                       id="email" 
                                       class="form-control" 
                                       placeholder="contato@empresa.com"
                                       value="<?= esc($dados->email ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instagram">Instagram</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">@</span>
                                    </div>
                                    <input type="text" 
                                           id="instagram" 
                                           class="form-control" 
                                           placeholder="nokapricho"
                                           value="<?= esc($dados->instagram ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="twitter">Twitter</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">@</span>
                                    </div>
                                    <input type="text" 
                                           id="twitter" 
                                           class="form-control" 
                                           placeholder="nokapricho"
                                           value="<?= esc($dados->twitter ?? '') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="facebook">Facebook</label>
                                <input type="text" 
                                       id="facebook" 
                                       class="form-control" 
                                       placeholder="nokapricho"
                                       value="<?= esc($dados->facebook ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-success" id="btnSalvar">
                            <i class="fas fa-save mr-2"></i>Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>

<script>
$(document).ready(function() {
    // Máscara para telefone
    $('#numero').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            if (value.length < 14) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            }
        }
        $(this).val(value);
    });

    // Salvar dados
    $('#btnSalvar').click(function() {
        const dados = {
            endereco: $('#endereco').val(),
            numero: $('#numero').val(),
            email: $('#email').val(),
            instagram: $('#instagram').val(),
            twitter: $('#twitter').val(),
            facebook: $('#facebook').val()
        };

        $.ajax({
            url: '<?= site_url('admin/dados-corporativos/atualizar') ?>',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(dados),
            success: function(response) {
                if (response.sucesso) {
                    // Usar o sistema de notificação do admin
                    toastr.success('Dados atualizados com sucesso!');
                } else {
                    toastr.error('Erro: ' + response.msg);
                }
            },
            error: function() {
                toastr.error('Erro ao salvar dados');
            }
        });
    });
});
</script>

<?php echo $this->endSection(); ?>
