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
                    <h4 class="card-title"><?=$titulo?></h4>
                    <a href="<?= base_url('admin/categorias') ?>" class="btn btn-primary btn-sm">
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

                <form action="<?php echo site_url('admin/categorias/atualizar/' . $categoria->id); ?>" method="post" class="needs-validation" novalidate>

                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                    <div class="row">
                        <!-- Informações da Categoria -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Informações da Categoria</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nome" class="required-field">Nome da Categoria</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nome" 
                                               name="nome" 
                                               value="<?php echo old('nome', esc($categoria->nome)); ?>" 
                                               placeholder="Digite o nome da categoria"
                                               required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o nome da categoria.
                                        </div>
                                        <small class="text-muted">O slug será gerado automaticamente baseado no nome.</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="slug" class="mb-2">Slug</label>
                                        <input class="form-control" 
                                               id="slug" 
                                               name="slug" 
                                               value="<?php echo old('slug', esc($categoria->slug)); ?>" 
                                               placeholder="slug-da-categoria"
                                               readonly>
                                        <div class="invalid-feedback">
                                            Por favor, informe um slug válido.
                                        </div>
                                        <small class="text-muted">Gerado automaticamente. Clique em "Editar Manualmente" para personalizar.</small>
                                        <br>
                                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="toggle-slug-edit">
                                            <i class="mdi mdi-pencil"></i> Editar Manualmente
                                        </button>
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
                                                <?php if ($categoria->ativo): ?>
                                                    <label class="badge badge-success">Ativo</label>
                                                <?php else: ?>
                                                    <label class="badge badge-danger">Inativo</label>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Data de Criação:</strong></td>
                                            <td>
                                                <?php if ($categoria->criado_em): ?>
                                                    <?= date('d/m/Y H:i', strtotime($categoria->criado_em)) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Não informado</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Última Atualização:</strong></td>
                                            <td>
                                                <?php if ($categoria->atualizado_em): ?>
                                                    <?= date('d/m/Y H:i', strtotime($categoria->atualizado_em)) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Não informado</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <hr>

                                    <div class="form-group">
                                        <label for="ativo">Ativar Categoria</label>
                                        <div class="d-flex align-items-center">
                                            <label class="switch mr-3">
                                                <input type="checkbox" 
                                                       id="ativo" 
                                                       name="ativo" 
                                                       value="1" 
                                                       <?php echo $categoria->ativo ? 'checked' : ''; ?>>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="text-muted">
                                                Categoria ficará visível no sistema
                                            </span>
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
                                <a href="<?= base_url("admin/categorias/show/$categoria->id") ?>" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Voltar à Lista
                                </a>
                                <button type="submit" class="btn btn-primary ml-2">
                                    <i class="mdi mdi-content-save"></i> Salvar Alterações
                                </button>
                                <a href="<?= base_url("admin/categorias/$categoria->id") ?>" class="btn btn-info ml-2">
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
        let slugEditavel = false;
        
        // Função para gerar slug a partir do nome
        function gerarSlug(texto) {
            return texto
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '') // Remove acentos
                .replace(/[^a-z0-9\s-]/g, '') // Remove caracteres especiais
                .trim()
                .replace(/\s+/g, '-') // Substitui espaços por hífens
                .replace(/-+/g, '-'); // Remove hífens duplicados
        }
        
        // Geração automática de slug quando o nome é alterado
        $('#nome').on('input', function() {
            if (!slugEditavel) {
                const nome = $(this).val();
                const slug = gerarSlug(nome);
                $('#slug').val(slug);
            }
        });
        
        // Botão para habilitar/desabilitar edição manual do slug
        $('#toggle-slug-edit').on('click', function() {
            slugEditavel = !slugEditavel;
            const $slugField = $('#slug');
            const $button = $(this);
            
            if (slugEditavel) {
                $slugField.prop('readonly', false).focus();
                $button.html('<i class="mdi mdi-auto-fix"></i> Gerar Automaticamente');
                $button.removeClass('btn-outline-secondary').addClass('btn-outline-primary');
            } else {
                $slugField.prop('readonly', true);
                $button.html('<i class="mdi mdi-pencil"></i> Editar Manualmente');
                $button.removeClass('btn-outline-primary').addClass('btn-outline-secondary');
                
                // Regenera o slug baseado no nome atual
                const nome = $('#nome').val();
                const slug = gerarSlug(nome);
                $('#slug').val(slug);
            }
        });
        
        // Validação do formulário
        $('form.needs-validation').on('submit', function (e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            // Validação do slug
            const slug = $('#slug').val();
            if (slug === '') {
                e.preventDefault();
                e.stopPropagation();
                $('#slug')[0].setCustomValidity('O slug é obrigatório');
                $('#slug').addClass('is-invalid');
            } else {
                $('#slug')[0].setCustomValidity('');
                $('#slug').removeClass('is-invalid');
            }
            
            $(this).addClass('was-validated');
        });
        
        // Limpa validação quando o slug é alterado
        $('#slug').on('input', function() {
            if ($(this).val() !== '') {
                this.setCustomValidity('');
                $(this).removeClass('is-invalid');
            }
        });
    });
</script>
<?php echo $this->endSection(); ?>