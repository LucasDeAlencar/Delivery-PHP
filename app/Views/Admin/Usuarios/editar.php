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
                    <h4 class="card-title">Editar Usuário</h4>
                    <a href="<?= base_url('admin/usuarios') ?>" class="btn btn-primary btn-sm">
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

                <form action="<?php echo site_url('admin/usuarios/atualizar/' . $usuario->id); ?>" method="post" class="needs-validation" novalidate>

                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                    <div class="row">
                        <!-- Informações Pessoais -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Informações Pessoais</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nome" class="required-field">Nome Completo</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nome" 
                                               name="nome" 
                                               value="<?php echo old ('nome',esc($usuario->nome)); ?>" 
                                               placeholder="Digite o nome completo"
                                               required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o nome completo.
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="required-field">E-mail</label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="<?php echo old ('email', esc($usuario->email)); ?>" 
                                               placeholder="Digite o e-mail"
                                               required>
                                        <div class="invalid-feedback">
                                            Por favor, informe um e-mail válido.
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cpf">CPF</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="cpf" 
                                               name="cpf" 
                                               value="<?php echo old ('cpf', esc($usuario->cpf)); ?>" 
                                               placeholder="000.000.000-00"
                                               data-mask="000.000.000-00">
                                    </div>

                                    <div class="form-group">
                                        <label for="telefone" class="required-field">Telefone</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="telefone" 
                                               name="telefone" 
                                               value="<?php echo old ('telefone',esc($usuario->telefone)); ?>" 
                                               placeholder="(00) 00000-0000"
                                               data-mask="(00) 00000-0000"
                                               required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o telefone.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status e Permissões -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Status e Permissões</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Status Atual:</strong></td>
                                            <td>
                                                <?php if ($usuario->ativo): ?>
                                                    <label class="badge badge-success">Ativo</label>
                                                <?php else: ?>
                                                    <label class="badge badge-danger">Inativo</label>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tipo de Usuário:</strong></td>
                                            <td>
                                                <?php if ($usuario->is_admin): ?>
                                                    <label class="badge badge-warning">Administrador</label>
                                                <?php else: ?>
                                                    <label class="badge badge-info">Usuário Comum</label>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Data de Criação:</strong></td>
                                            <td>
                                                <?php if ($usuario->criado_em): ?>
                                                    <?= date('d/m/Y H:i', strtotime($usuario->criado_em)) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Não informado</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Última Atualização:</strong></td>
                                            <td>
                                                <?php if ($usuario->atualizado_em): ?>
                                                    <?= date('d/m/Y H:i', strtotime($usuario->atualizado_em)) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Não informado</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <hr>

                                    <div class="form-group">
                                        <label for="ativo">Ativar Conta</label>
                                        <div class="d-flex align-items-center">
                                            <label class="switch mr-3">
                                                <input type="checkbox" 
                                                       id="ativo" 
                                                       name="ativo" 
                                                       value="1" 
                                                       <?php echo $usuario->ativo ? 'checked' : ''; ?>>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="text-muted">
                                                Usuário pode acessar o sistema
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="is_admin">Privilégios de Administrador</label>
                                        <div class="d-flex align-items-center">
                                            <label class="switch mr-3">
                                                <input type="checkbox" 
                                                       id="is_admin" 
                                                       name="is_admin" 
                                                       value="1" 
                                                       <?php echo $usuario->is_admin ? 'checked' : ''; ?>>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="text-muted">
                                                Acesso total ao sistema
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alterar Senha -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Alterar Senha (Opcional)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Nova Senha</label>
                                                <div class="input-group">
                                                    <input type="password" 
                                                           class="form-control" 
                                                           id="password" 
                                                           name="password" 
                                                           placeholder="Digite a nova senha">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" 
                                                                type="button" 
                                                                onclick="togglePassword('password')">
                                                            <i class="mdi mdi-eye" id="password-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    Deixe em branco para manter a senha atual
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password_confirm">Confirmar Nova Senha</label>
                                                <div class="input-group">
                                                    <input type="password" 
                                                           class="form-control" 
                                                           id="password_confirm" 
                                                           name="password_confirm" 
                                                           placeholder="Confirme a nova senha">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" 
                                                                type="button" 
                                                                onclick="togglePassword('password_confirm')">
                                                            <i class="mdi mdi-eye" id="password_confirm-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
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
                                <a href="<?= base_url("admin/usuarios/$usuario->id") ?>" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                                </a>
                                <button type="submit" class="btn btn-primary ml-2">
                                    <i class="mdi mdi-content-save"></i> Salvar Alterações
                                </button>
                                <a href="<?= base_url("admin/usuarios/$usuario->id") ?>" class="btn btn-info ml-2">
                                    <i class="mdi mdi-eye"></i> Visualizar
                                </a>
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
                                                                        // Máscaras para os campos
                                                                        $('#cpf').mask('000.000.000-00');
                                                                        $('#telefone').mask('(00) 00000-0000');

                                                                        // Validação do formulário
                                                                        $('form.needs-validation').on('submit', function (e) {
                                                                            if (!this.checkValidity()) {
                                                                                e.preventDefault();
                                                                                e.stopPropagation();
                                                                            }

                                                                            // Validar se as senhas coincidem
                                                                            var password = $('#password').val();
                                                                            var passwordConfirm = $('#password_confirm').val();

                                                                            if (password !== '' && password !== passwordConfirm) {
                                                                                e.preventDefault();
                                                                                e.stopPropagation();
                                                                                $('#password_confirm')[0].setCustomValidity('As senhas não coincidem');
                                                                                $('#password_confirm').addClass('is-invalid');

                                                                                // Adicionar feedback personalizado
                                                                                if (!$('#password_confirm').next('.invalid-feedback').length) {
                                                                                    $('#password_confirm').after('<div class="invalid-feedback">As senhas não coincidem.</div>');
                                                                                }
                                                                            } else {
                                                                                $('#password_confirm')[0].setCustomValidity('');
                                                                                $('#password_confirm').removeClass('is-invalid');
                                                                            }

                                                                            $(this).addClass('was-validated');
                                                                        });

                                                                        // Limpar validação quando o usuário digitar
                                                                        $('#password_confirm').on('input', function () {
                                                                            var password = $('#password').val();
                                                                            var passwordConfirm = $(this).val();

                                                                            if (password === passwordConfirm || passwordConfirm === '') {
                                                                                this.setCustomValidity('');
                                                                                $(this).removeClass('is-invalid');
                                                                            }
                                                                        });
                                                                    });

// Função para mostrar/ocultar senha
                                                                    function togglePassword(fieldId) {
                                                                        const field = document.getElementById(fieldId);
                                                                        const icon = document.getElementById(fieldId + '-icon');

                                                                        if (field.type === 'password') {
                                                                            field.type = 'text';
                                                                            icon.classList.remove('mdi-eye');
                                                                            icon.classList.add('mdi-eye-off');
                                                                        } else {
                                                                            field.type = 'password';
                                                                            icon.classList.remove('mdi-eye-off');
                                                                            icon.classList.add('mdi-eye');
                                                                        }
                                                                    }
</script>
<?php echo $this->endSection(); ?>