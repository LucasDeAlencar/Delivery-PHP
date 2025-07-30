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
        transition: .4s;
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
        transition: .4s;
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

    .loading-cep {
        display: none;
        color: #007bff;
    }

    .cep-error {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
    }

    .cep-success {
        display: none;
        color: #28a745;
        font-size: 0.875rem;
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
                    <h4 class="card-title"><?= $titulo ?></h4>
                    <a href="<?= base_url('admin/bairros') ?>" class="btn btn-primary btn-sm">
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

                <form action="<?php echo site_url("admin/bairros/atualizar/{$bairro->id}"); ?>" method="post" class="needs-validation" novalidate>

                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                    <div class="row">
                        <!-- Informações do Bairro -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Informações do Bairro</h5>
                                </div>
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="nome" class="required-field">Nome do Bairro</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nome" 
                                               name="nome" 
                                               value="<?php echo old('nome', esc($bairro->nome)); ?>" 
                                               placeholder="Digite o nome do bairro"
                                               required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o nome do bairro.
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cep">CEP (para buscar cidade)</label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="cep" 
                                                   name="cep" 
                                                   placeholder="00000-000"
                                                   maxlength="9">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" id="buscar-cep">
                                                    <i class="mdi mdi-magnify"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            Digite o CEP para buscar automaticamente a cidade
                                        </small>
                                        <div class="loading-cep mt-1">
                                            <i class="mdi mdi-loading mdi-spin"></i> Buscando CEP...
                                        </div>
                                        <div class="cep-error mt-1">
                                            CEP não encontrado ou inválido
                                        </div>
                                        <div class="cep-success mt-1">
                                            <i class="mdi mdi-check"></i> CEP encontrado!
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cidade" class="required-field">Cidade</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="cidade" 
                                               name="cidade" 
                                               value="<?php echo old('cidade', esc($bairro->cidade)); ?>" 
                                               placeholder="Digite a cidade"
                                               required>
                                        <div class="invalid-feedback">
                                            Por favor, informe a cidade.
                                        </div>
                                        <small class="text-muted">
                                            Será preenchido automaticamente pelo CEP, mas pode ser editado
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label for="valor_entrega" class="required-field">Valor de Entrega</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">R$</span>
                                            </div>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="valor_entrega" 
                                                   name="valor_entrega" 
                                                   value="<?php echo old('valor_entrega', number_format($bairro->valor_entrega, 2, ',', '.')); ?>" 
                                                   placeholder="0,00"
                                                   required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Por favor, informe o valor de entrega.
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Configurações -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Configurações</h5>
                                </div>
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="ativo">Ativar Bairro</label>
                                        <div class="d-flex align-items-center">
                                            <label class="switch mr-3">
                                                <input type="checkbox" 
                                                       id="ativo" 
                                                       name="ativo" 
                                                       value="1" 
                                                       <?php echo old('ativo', $bairro->ativo) ? 'checked' : ''; ?>>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="text-muted">
                                                Bairro ficará disponível para entregas
                                            </span>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <h6><i class="mdi mdi-information"></i> Informações importantes:</h6>
                                        <ul class="mb-0">
                                            <li>O CEP é usado apenas para buscar a cidade automaticamente</li>
                                            <li>O slug será atualizado automaticamente se o nome for alterado</li>
                                            <li>Bairros inativos não aparecerão nas opções de entrega</li>
                                        </ul>
                                    </div>

                                    <!-- Informações do registro -->
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Informações do Registro</h6>
                                        </div>
                                        <div class="card-body">
                                            <small class="text-muted">
                                                <strong>ID:</strong> <?= $bairro->id ?><br>
                                                <strong>Slug:</strong> <?= esc($bairro->slug) ?><br>
                                                <strong>Criado em:</strong> 
                                                <?php if ($bairro->criado_em): ?>
                                                    <?= $bairro->criado_em->format('d/m/Y H:i:s') ?>
                                                <?php else: ?>
                                                    Não informado
                                                <?php endif; ?><br>
                                                <strong>Atualizado em:</strong> 
                                                <?php if ($bairro->atualizado_em): ?>
                                                    <?= $bairro->atualizado_em->format('d/m/Y H:i:s') ?>
                                                <?php else: ?>
                                                    Nunca
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="text-center">
                                <a href="<?= base_url('admin/bairros') ?>" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success ml-2">
                                    <i class="mdi mdi-content-save"></i> Atualizar Bairro
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        // Máscaras
        $('#cep').mask('00000-000');
        
        // Máscara para valor monetário - formato brasileiro
        $('#valor_entrega').mask('000.000.000.000.000,00', {reverse: true});

        // Validação do formulário
        $('form.needs-validation').on('submit', function (e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            $(this).addClass('was-validated');
        });

        // Função para buscar CEP
        function buscarCEP(cep) {
            // Remove caracteres não numéricos
            cep = cep.replace(/\D/g, '');
            
            if (cep.length !== 8) {
                return;
            }

            $('.loading-cep').show();
            $('.cep-error, .cep-success').hide();

            $.ajax({
                url: `https://viacep.com.br/ws/${cep}/json/`,
                type: 'GET',
                dataType: 'json',
                timeout: 5000,
                success: function(data) {
                    $('.loading-cep').hide();
                    
                    if (data.erro) {
                        $('.cep-error').show();
                        return;
                    }

                    // Preenche a cidade
                    $('#cidade').val(data.localidade);
                    $('.cep-success').show();
                },
                error: function() {
                    $('.loading-cep').hide();
                    $('.cep-error').show();
                }
            });
        }

        // Buscar CEP ao digitar
        $('#cep').on('blur', function() {
            var cep = $(this).val();
            if (cep) {
                buscarCEP(cep);
            }
        });

        // Buscar CEP ao clicar no botão
        $('#buscar-cep').on('click', function() {
            var cep = $('#cep').val();
            if (cep) {
                buscarCEP(cep);
            } else {
                alert('Por favor, digite um CEP válido');
                $('#cep').focus();
            }
        });

        // Buscar CEP ao pressionar Enter
        $('#cep').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                var cep = $(this).val();
                if (cep) {
                    buscarCEP(cep);
                }
            }
        });

        // Limpar mensagens ao digitar novo CEP
        $('#cep').on('input', function() {
            $('.cep-error, .cep-success').hide();
        });
    });
</script>
<?php echo $this->endSection(); ?>