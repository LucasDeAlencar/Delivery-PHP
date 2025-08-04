<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>

<style>
    .expediente-row:hover {
        background-color: #f8f9fa !important;
        transition: background-color 0.2s ease;
    }

    .expediente-row {
        transition: background-color 0.2s ease;
    }

    .table-responsive .table tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Estilos para os botões de ação */
    .btn-group .btn {
        margin-right: 2px;
        border-radius: 4px !important;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    /* Ajusta o tamanho dos botões */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Efeito hover nos botões */
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: all 0.2s ease;
    }

    /* Coluna de ações com largura fixa */
    .table th:last-child,
    .table td:last-child {
        width: 140px;
        text-align: center;
    }

    /* Estilos para status */
    .badge {
        font-size: 0.75rem;
    }

    /* Truncar texto longo */
    .text-truncate {
        max-width: 200px;
    }
    
    /* Estilos para campos inválidos */
    .is-invalid {
        border-color: #dc3545 !important;
        background-color: #fff5f5 !important;
    }
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
    }
    
    /* Animação para campos inválidos */
    .is-invalid {
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
</style>

<?php echo $this->endSection(); ?>

<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Relatório dos expedientes</h4>

                <?php if (session()->has('sucesso')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session('sucesso') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('erro')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session('erro') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('errors_model')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach (session('errors_model') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <p class="card-description" style="margin-top: 10px; padding-bottom: 10px;">
                    Esta seção apresenta os registros de atendimento realizados, contendo informações sobre dias, horários e status de funcionamento.
                <br>Acompanhe facilmente a gestão de horários e a organização da rotina de trabalho.
                </p>

                <?= form_open("admin/expedientes", ['class' => 'form-row', 'id' => 'form-expedientes']); ?>
                <?= csrf_field() ?>
                
                <div class="container-btn-salvar" style="text-align: right; margin-bottom: 15px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save btn-icon-prepend"></i>
                        Salvar Expedientes
                    </button>
                </div>

                
                <div class="table-responsive pt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Dia</th>
                                <th>Abertura</th>
                                <th>Fechamento</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($expedientes)): ?>
                                <?php foreach ($expedientes as $dia): ?>
                                    <tr class="expediente-row" data-id="<?= $dia->id ?>">
                                        <td>
                                            <?= $dia->id ?>
                                            <input type="hidden" name="id[]" value="<?= $dia->id ?>">
                                        </td>
                                        <td>
                                            <input type="text" name="dia_descricao[]" class="form-control" value="<?= esc($dia->dia_descricao); ?>" readonly style="background-color: #f8f9fa;">
                                        </td>
                                        <td>
                                            <input type="time" name="abertura[]" class="form-control" value="<?= esc($dia->abertura); ?>" required>
                                        </td>
                                        <td>
                                            <input type="time" name="fechamento[]" class="form-control" value="<?= esc($dia->fechamento); ?>" required>
                                        </td>
                                        <td>
                                            <select class="form-control" name="situacao[]" required>
                                                <option value="1" <?= $dia->situacao == 1 ? 'selected' : '' ?>>Aberto</option>
                                                <option value="0" <?= $dia->situacao == 0 ? 'selected' : '' ?>>Fechado</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum expediente encontrado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?= form_close(); ?>

                <!-- Paginação -->
                <?php if (isset($pager)): ?>
                    <div class="d-flex justify-content-center mt-3">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>

<script>
    $(document).ready(function () {
        // Adiciona efeito hover nas linhas
        $('.expediente-row').hover(
                function () {
                    $(this).addClass('table-active');
                },
                function () {
                    $(this).removeClass('table-active');
                }
        );

        // Validação de horários em tempo real
        $('input[name="abertura[]"], input[name="fechamento[]"]').on('change', function() {
            var row = $(this).closest('tr');
            var abertura = row.find('input[name="abertura[]"]').val();
            var fechamento = row.find('input[name="fechamento[]"]').val();
            
            if (abertura && fechamento) {
                if (fechamento <= abertura) {
                    $(this).addClass('is-invalid');
                    
                    // Remove mensagem anterior se existir
                    row.find('.invalid-feedback').remove();
                    
                    // Adiciona mensagem de erro
                    $(this).after('<div class="invalid-feedback">O horário de fechamento deve ser posterior ao de abertura.</div>');
                } else {
                    row.find('input[name="abertura[]"], input[name="fechamento[]"]').removeClass('is-invalid');
                    row.find('.invalid-feedback').remove();
                }
            }
        });
        
        // Validação antes do envio do formulário
        $('#form-expedientes').on('submit', function(e) {
            console.log('Formulário sendo enviado...');
            
            var hasError = false;
            var errorMessages = [];
            
            // Debug dos dados que serão enviados
            var formData = new FormData(this);
            console.log('Dados do formulário:');
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            $('.expediente-row').each(function(index) {
                var abertura = $(this).find('input[name="abertura[]"]').val();
                var fechamento = $(this).find('input[name="fechamento[]"]').val();
                var diaDescricao = $(this).find('input[name="dia_descricao[]"]').val();
                
                console.log('Validando linha ' + index + ': ' + diaDescricao + ' - ' + abertura + ' até ' + fechamento);
                
                if (abertura && fechamento && fechamento <= abertura) {
                    hasError = true;
                    errorMessages.push(diaDescricao + ': Horário de fechamento deve ser posterior ao de abertura');
                }
            });
            
            if (hasError) {
                console.log('Erros encontrados:', errorMessages);
                e.preventDefault();
                alert('Corrija os seguintes erros:\n\n' + errorMessages.join('\n'));
                return false;
            }
            
            console.log('Formulário válido, enviando...');
            // Adiciona indicador visual de carregamento
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Salvando...');
        });

        // Adiciona confirmação para exclusão
        $('.btn-danger').on('click', function (e) {
            var expedienteNome = $(this).closest('tr').find('td:nth-child(2)').text().trim();
            if (!confirm('Tem certeza que deseja excluir o expediente "' + expedienteNome + '"?\\nEsta ação pode afetar entregas em andamento!')) {
                e.preventDefault();
                return false;
            }
        });

        // Adiciona confirmação para restauração
        $('form[action*="desfazer-exclusao"]').on('submit', function (e) {
            var expedienteNome = $(this).closest('tr').find('td:nth-child(2)').text().trim();
            if (!confirm('Tem certeza que deseja restaurar o expediente "' + expedienteNome + '"?')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>

<?php echo $this->endSection(); ?>