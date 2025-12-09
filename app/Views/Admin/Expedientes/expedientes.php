<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>

<style>
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

    /* Campo readonly do dia com contraste adequado */
    input[name="dia_descricao[]"][readonly] {
        background-color: #f8f9fa !important;
        color: #1a1a1a !important;
        font-weight: 600;
        border: 1px solid #dee2e6;
        cursor: not-allowed;
    }

    /* Estilos para campos inválidos */
    .is-invalid {
        border-color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.1) !important;
        color: var(--text-light) !important;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #ff6b6b;
        font-weight: 500;
    }

    /* Animação para campos inválidos */
    .is-invalid {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% {
            transform: translateX(0);
        }
        25% {
            transform: translateX(-5px);
        }
        75% {
            transform: translateX(5px);
        }
    }

    /* Destaque para linha com erro */
    tr.has-error {
        background-color: rgba(220, 53, 69, 0.05) !important;
    }
    
    /* ===== ESTILOS PARA TOAST NOTIFICATIONS ===== */
    .alert-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 300px;
        max-width: 500px;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: space-between;
        animation: slideInRight 0.3s ease-out;
        border-left: 4px solid;
    }

    .alert-toast.success {
        background: #d4edda;
        border-left-color: #28a745;
        color: #155724;
    }

    .alert-toast.error {
        background: #f8d7da;
        border-left-color: #dc3545;
        color: #721c24;
    }

    .alert-toast-content {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .alert-toast-content i {
        font-size: 1.2em;
    }

    .alert-toast-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        padding: 5px;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .alert-toast-close:hover {
        opacity: 1;
    }

    .alert-toast ul {
        margin: 5px 0 0 0;
        padding-left: 20px;
    }

    .alert-toast ul li {
        margin-bottom: 2px;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* ===== ESTILOS PARA BADGE NO BOTÃO ===== */
    .status-indicator {
        margin-bottom: 10px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 500;
        animation: fadeIn 0.3s ease-in;
    }

    .status-badge.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-badge.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* ===== ESTILOS PARA MENSAGEM NO TÍTULO ===== */
    .card-title small {
        font-size: 0.7em;
        font-weight: normal;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .card-title .text-success {
        background: rgba(40, 167, 69, 0.1);
    }

    .card-title .text-danger {
        background: rgba(220, 53, 69, 0.1);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Auto-remove das mensagens após alguns segundos */
    .alert-toast, .status-badge {
        animation-duration: 0.3s;
    }

    .alert-toast.auto-remove {
        animation: slideOutRight 0.3s ease-in forwards;
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
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

                <!-- Substitua todo o bloco de alerts por este código -->
                <?php if (session()->has('sucesso')): ?>
                    <div class="alert-toast success" role="alert">
                        <div class="alert-toast-content">
                            <i class="fas fa-check-circle"></i>
                            <span><?= session('sucesso') ?></span>
                        </div>
                        <button type="button" class="alert-toast-close" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('erro')): ?>
                    <div class="alert-toast error" role="alert">
                        <div class="alert-toast-content">
                            <i class="fas fa-exclamation-circle"></i>
                            <span><?= session('erro') ?></span>
                        </div>
                        <button type="button" class="alert-toast-close" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('errors_model')): ?>
                    <div class="alert-toast error" role="alert">
                        <div class="alert-toast-content">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>
                                <strong>Erros encontrados:</strong>
                                <ul>
                                    <?php foreach (session('errors_model') as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </span>
                        </div>
                        <button type="button" class="alert-toast-close" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <p class="card-description" style="margin-top: 10px; padding-bottom: 10px;">
                    Esta seção apresenta os registros de atendimento realizados, contendo informações sobre dias, horários e status de funcionamento.
                    <br>Acompanhe facilmente a gestão de horários e a organização da rotina de trabalho.
                </p>

                <form action="<?= site_url('admin/expedientes') ?>" method="POST" class="form-row" id="form-expedientes">
                    <?= csrf_field() ?>

                    <div class="container-btn-salvar" style="text-align: right; margin-bottom: 15px;">
                        <button type="submit" class="btn btn-success" id="btn-salvar">
                            <i class="fas fa-save"></i>
                            Salvar Expedientes
                        </button>
                        <small class="d-block text-muted mt-2">
                            <i class="fas fa-info-circle"></i> Certifique-se de que o horário de fechamento seja posterior ao de abertura
                        </small>
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
                                                <input type="text" name="dia_descricao[]" class="form-control" value="<?= esc($dia->dia_descricao); ?>" readonly>
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
                </form>

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
        // Função para validar horários de uma linha
        function validarHorarios(row) {
            var abertura = row.find('input[name="abertura[]"]').val();
            var fechamento = row.find('input[name="fechamento[]"]').val();
            var isValid = true;

            // Remove erros anteriores
            row.find('input[name="abertura[]"], input[name="fechamento[]"]').removeClass('is-invalid');
            row.find('.invalid-feedback').remove();
            row.removeClass('has-error');

            if (abertura && fechamento) {
                if (fechamento <= abertura) {
                    isValid = false;

                    // Marca ambos os campos como inválidos
                    row.find('input[name="abertura[]"], input[name="fechamento[]"]').addClass('is-invalid');
                    row.addClass('has-error');

                    // Adiciona mensagem de erro no campo de fechamento
                    row.find('input[name="fechamento[]"]').after(
                            '<div class="invalid-feedback">' +
                            '<i class="fas fa-exclamation-triangle"></i> ' +
                            'O horário de fechamento deve ser posterior ao de abertura.' +
                            '</div>'
                            );
                }
            }

            return isValid;
        }

        // Validação de horários em tempo real
        $('input[name="abertura[]"], input[name="fechamento[]"]').on('change blur', function () {
            var row = $(this).closest('tr');
            validarHorarios(row);
        });

        // Validação antes do envio do formulário
        $('#form-expedientes').on('submit', function (e) {
            console.log('=== TENTATIVA DE ENVIO DO FORMULÁRIO ===');

            console.log('=== SUBMIT DO FORMULÁRIO ===');
            console.log('Método:', $(this).attr('method'));
            console.log('Action:', $(this).attr('action'));

            var hasError = false;
            var errorMessages = [];
            var $submitBtn = $(this).find('button[type="submit"]');

            // Valida todas as linhas
            $('.expediente-row').each(function (index) {
                var row = $(this);
                var abertura = row.find('input[name="abertura[]"]').val();
                var fechamento = row.find('input[name="fechamento[]"]').val();
                var diaDescricao = row.find('input[name="dia_descricao[]"]').val();

                console.log('Validando linha ' + index + ': ' + diaDescricao + ' - ' + abertura + ' até ' + fechamento);

                // Valida a linha
                if (!validarHorarios(row)) {
                    hasError = true;
                    errorMessages.push(diaDescricao + ': Horário de fechamento deve ser posterior ao de abertura');
                }
            });

            if (hasError) {
                console.log('Erros encontrados:', errorMessages);
                e.preventDefault();

                // Mostra alerta com os erros
                var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 15px;">' +
                        '<strong><i class="fas fa-exclamation-circle"></i> Erros de Validação:</strong><br>' +
                        '<ul class="mb-0 mt-2">';

                errorMessages.forEach(function (msg) {
                    alertHtml += '<li>' + msg + '</li>';
                });

                alertHtml += '</ul>' +
                        '<button type="button" class="close" data-dismiss="alert">' +
                        '<span>&times;</span>' +
                        '</button>' +
                        '</div>';

                // Remove alertas anteriores
                $('.alert-danger').remove();

                // Adiciona o alerta no topo do formulário
                $('.card-title').after(alertHtml);

                // Scroll para o topo
                $('html, body').animate({
                    scrollTop: $('.card').offset().top - 100
                }, 500);

                return false;
            }

            console.log('=== FORMULÁRIO VÁLIDO ===');
            console.log('Enviando formulário via POST...');

            // Debug ANTES de desabilitar o botão
            var formData = new FormData(this);
            console.log('=== DADOS DO FORMULÁRIO ===');
            var hasData = false;
            var dataCount = 0;
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
                hasData = true;
                dataCount++;
            }

            console.log('Total de campos:', dataCount);

            if (!hasData) {
                console.error('ERRO: Formulário vazio!');
                alert('Erro: Nenhum dado foi encontrado no formulário. Verifique o console.');
                return false;
            }

            // Adiciona indicador visual de carregamento
            $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');

            // Permite o envio
            console.log('Permitindo envio do formulário...');
            return true;
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

    // Auto-remove mensagens após 5 segundos
    function autoRemoveMessages() {
        // Remove toast notifications após 5 segundos
        setTimeout(function () {
            document.querySelectorAll('.alert-toast').forEach(toast => {
                toast.classList.add('auto-remove');
                setTimeout(() => toast.remove(), 300);
            });
        }, 5000);

        // Remove status badges após 5 segundos
        setTimeout(function () {
            document.querySelectorAll('.status-badge').forEach(badge => {
                badge.style.opacity = '0';
                badge.style.transition = 'opacity 0.3s';
                setTimeout(() => badge.remove(), 300);
            });
        }, 5000);
    }

// Executa quando o documento estiver pronto
    $(document).ready(function () {
        autoRemoveMessages();

        // Também remove ao clicar no botão de fechar (fallback)
        $('.alert-toast-close').on('click', function () {
            $(this).closest('.alert-toast').addClass('auto-remove');
            setTimeout(() => $(this).closest('.alert-toast').remove(), 300);
        });
    });
</script>

<?php echo $this->endSection(); ?>