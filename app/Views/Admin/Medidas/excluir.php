<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> 
<?php echo $titulo; ?> 
<?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>
<style>
    .confirmation-card {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .user-info-card {
        background-color: #f8f9fa;
        border-left: 4px solid #dc3545;
    }
    
    .warning-icon {
        font-size: 4rem;
        color: #dc3545;
        margin-bottom: 1rem;
    }
    
    .btn-action {
        min-width: 120px;
        margin: 0 10px;
    }
    
    .user-detail {
        margin-bottom: 0.5rem;
    }
    
    .user-detail strong {
        display: inline-block;
        width: 120px;
    }
</style>
<?php echo $this->endSection(); ?>

<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card confirmation-card">
            <div class="card-body text-center">
                
                <!-- Ícone de Aviso -->
                <div class="mb-4">
                    <i class="mdi mdi-alert-circle-outline warning-icon"></i>
                </div>
                
                <!-- Título da Confirmação -->
                <h3 class="card-title text-danger mb-4">Confirmar Exclusão</h3>
                
                <!-- Mensagem de Confirmação -->
                <div class="alert alert-warning" role="alert">
                    <h5 class="alert-heading">
                        <i class="mdi mdi-alert-triangle"></i> Atenção!
                    </h5>
                    <p class="mb-0">
                        Você está prestes a excluir a medida <strong><?= esc($medida->nome) ?></strong>.
                        Esta ação não pode ser desfeita.
                    </p>
                </div>
                
                <!-- Informações do Usuário -->
                <div class="card user-info-card mb-4">
                    <div class="card-body">
                        <h6 class="card-title text-left mb-3">
                            <i class="mdi mdi-ruler"></i> Dados da Medida
                        </h6>
                        
                        <div class="text-left">
                            <div class="user-detail">
                                <strong>Nome:</strong> <?= esc($medida->nome) ?>
                            </div>
                            
                            <?php if ($medida->descricao): ?>
                            <div class="user-detail">
                                <strong>Descrição:</strong> <?= esc(substr($medida->descricao, 0, 100)) ?><?= strlen($medida->descricao) > 100 ? '...' : '' ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="user-detail">
                                <strong>Status:</strong> 
                                <?php if ($medida->ativo): ?>
                                    <span class="badge badge-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Inativo</span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($medida->criado_em): ?>
                            <div class="user-detail">
                                <strong>Criado em:</strong> <?= format_sao_paulo_date($medida->criado_em, 'd/m/Y H:i') ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Formulário de Confirmação -->
                <form action="<?php echo site_url('admin/medidas/deletar/' . $medida->id); ?>" method="post" id="form-exclusao">
                    <?= csrf_field() ?>
                    
                    <!-- Botões de Ação -->
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        
                        <!-- Botão Voltar -->
                        <a href="<?= site_url('admin/medidas') ?>" 
                           class="btn btn-secondary btn-action">
                            <i class="mdi mdi-arrow-left"></i> Voltar
                        </a>
                        
                        <!-- Botão Excluir -->
                        <button type="submit" 
                                class="btn btn-danger btn-action" 
                                id="btn-confirmar-exclusao">
                            <i class="mdi mdi-delete"></i> Excluir Medida
                        </button>
                        
                    </div>
                    
                </form>
                
                <!-- Informação Adicional -->
                <div class="mt-4">
                    <small class="text-muted">
                        <i class="mdi mdi-information"></i>
                        Certifique-se de que realmente deseja excluir esta medida antes de confirmar.
                    </small>
                </div>
                
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    
    // Adiciona confirmação medida ao clicar no botão de exclusão
    $('#btn-confirmar-exclusao').on('click', function(e) {
        e.preventDefault();
        
        // Mostra um modal de confirmação adicional
        if (confirm('Tem certeza absoluta de que deseja excluir a medida "<?= esc($medida->nome) ?>"?\n\nEsta ação é irreversível!')) {
            // Se confirmou, submete o formulário
            $('#form-exclusao').submit();
        }
    });
    
    // Adiciona efeito hover nos botões
    $('.btn-action').hover(
        function() {
            $(this).addClass('shadow');
        },
        function() {
            $(this).removeClass('shadow');
        }
    );
    
});
</script>
<?php echo $this->endSection(); ?>