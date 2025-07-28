<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> 
<?php echo $titulo; ?> 
<?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #007bff;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .required-field::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }
</style>
<?php echo $this->endSection(); ?>

<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Criar Nova Medida</h4>
                    <a href="<?= base_url('admin/medidas') ?>" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                    </a>
                </div>

                <!-- Exibir mensagens de erro -->
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

                <?php if (session()->has('atencao')): ?>
                    <div class="alert alert-warning">
                        <?= session('atencao') ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo site_url('admin/medidas/cadastrar'); ?>" method="post" class="needs-validation" novalidate>

                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                    <div class="row">
                        <!-- Informações Básicas -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Informações Básicas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nome" class="required-field">Nome da Medida</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nome" 
                                               name="nome" 
                                               value="<?php echo old('nome', esc($medida->nome ?? '')); ?>" 
                                               placeholder="Digite o nome da medida (ex: Quilograma, Litro, Unidade)"
                                               required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o nome da medida.
                                        </div>
                                        <small class="text-muted">Exemplos: Quilograma (kg), Litro (L), Unidade (un), Metro (m)</small>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="ativo">Ativar Medida</label>
                                        <div class="d-flex align-items-center">
                                            <label class="switch mr-3">
                                                <input type="checkbox" 
                                                       id="ativo" 
                                                       name="ativo" 
                                                       value="1" 
                                                       <?php echo old('ativo', $medida->ativo ?? 1) ? 'checked' : ''; ?>>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="text-muted">
                                                Medida ficará disponível no sistema
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Descrição</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="descricao">Descrição da Medida</label>
                                        <textarea class="form-control" 
                                                  id="descricao" 
                                                  name="descricao" 
                                                  rows="4" 
                                                  maxlength="1000"
                                                  placeholder="Digite uma descrição para a medida (opcional)"><?php echo old('descricao', esc($medida->descricao ?? '')); ?></textarea>
                                        <small class="text-muted">
                                            <span id="char-count">0</span>/1000 caracteres
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="text-center">
                                <a href="<?= base_url('admin/medidas') ?>" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success ml-2">
                                    <i class="mdi mdi-content-save"></i> Criar Medida
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Contador de caracteres para descrição
        function updateCharCount() {
            const descricao = $('#descricao').val();
            const count = descricao.length;
            $('#char-count').text(count);
            
            // Muda a cor quando se aproxima do limite
            if (count > 900) {
                $('#char-count').addClass('text-danger');
            } else if (count > 800) {
                $('#char-count').addClass('text-warning').removeClass('text-danger');
            } else {
                $('#char-count').removeClass('text-warning text-danger');
            }
        }
        
        // Atualiza contador ao digitar
        $('#descricao').on('input', updateCharCount);
        
        // Atualiza contador no carregamento da página
        updateCharCount();
    });
</script>
<?php echo $this->endSection(); ?>