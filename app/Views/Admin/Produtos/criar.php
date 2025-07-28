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
                    <h4 class="card-title"><?= $titulo ?></h4>
                    <a href="<?= base_url('admin/produtos') ?>" class="btn btn-primary btn-sm">
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

                <form action="<?php echo site_url('admin/produtos/cadastrar'); ?>" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>

                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                    <div class="row">
                        <!-- Informações do Produto -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Informações do Produto</h5>
                                </div>
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="nome" class="required-field">Nome do Produto</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nome" 
                                               name="nome" 
                                               value="<?php echo old('nome', esc($produto->nome ?? '')); ?>" 
                                               placeholder="Digite o nome do produto"
                                               required>
                                        <div class="invalid-feedback">
                                            Por favor, informe o nome do produto.
                                        </div>
                                        <small class="text-muted">O slug será gerado automaticamente baseado no nome.</small>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="slug" class="mb-2">Slug</label>
                                        <input class="form-control" 
                                               id="slug" 
                                               name="slug" 
                                               value="<?php echo old('slug', esc($produto->slug ?? '')); ?>" 
                                               placeholder="slug-do-produto"
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

                                    <div class="form-group">
                                        <label for="categoria_id" class="required-field">Categoria</label>
                                        <select class="form-control" 
                                                id="categoria_id" 
                                                name="categoria_id" 
                                                required>
                                            <option value="">Selecione uma categoria...</option>
                                            <?php foreach ($categorias as $categoria): ?>
                                                <option value="<?= $categoria->id; ?>" 
                                                        <?= (old('categoria_id') == $categoria->id ? 'selected' : ''); ?>
                                                        data-description="<?= esc($categoria->descricao ?? ''); ?>">
                                                    <?= esc($categoria->nome); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, selecione uma categoria para o produto.
                                        </div>
                                        <small class="text-muted" id="categoria-description">
                                            Selecione a categoria que melhor descreve este produto.
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label for="imagem">Imagem do Produto</label>
                                        <div class="custom-file">
                                            <input type="file" 
                                                   class="custom-file-input" 
                                                   id="imagem" 
                                                   name="imagem" 
                                                   accept="image/*">
                                            <label class="custom-file-label" for="imagem">Escolher arquivo...</label>
                                        </div>
                                        <small class="text-muted">
                                            Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB.
                                        </small>
                                        <div class="mt-2" id="preview-container" style="display: none;">
                                            <img id="image-preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
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
                                    <div class="form-group">
                                        <label for="ativo">Ativar Produto</label>
                                        <div class="d-flex align-items-center">
                                            <label class="switch mr-3">
                                                <input type="checkbox" 
                                                       id="ativo" 
                                                       name="ativo" 
                                                       value="1" 
                                                       <?php echo old('ativo', $produto->ativo ?? 1) ? 'checked' : ''; ?>>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="text-muted">
                                                Produto ficará disponível no sistema
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ingredientes -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Ingredientes</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="ingredientes">Ingredientes do Produto</label>
                                        <textarea class="form-control" 
                                                  id="ingredientes" 
                                                  name="ingredientes" 
                                                  rows="4" 
                                                  maxlength="1000"
                                                  placeholder="Digite os ingredientes do produto (opcional)"><?php echo old('ingredientes', esc($produto->ingredientes ?? '')); ?></textarea>
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
                                <a href="<?= base_url('admin/produtos') ?>" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success ml-2">
                                    <i class="mdi mdi-content-save"></i> Criar Produto
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
        $('#nome').on('input', function () {
            if (!slugEditavel) {
                const nome = $(this).val();
                const slug = gerarSlug(nome);
                $('#slug').val(slug);
            }
        });

        // Botão para habilitar/desabilitar edição manual do slug
        $('#toggle-slug-edit').on('click', function () {
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
        $('#slug').on('input', function () {
            if ($(this).val() !== '') {
                this.setCustomValidity('');
                $(this).removeClass('is-invalid');
            }
        });

        // Contador de caracteres para ingredientes
        function updateCharCount() {
            const ingredientes = $('#ingredientes').val();
            const count = ingredientes.length;
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
        $('#ingredientes').on('input', updateCharCount);

        // Atualiza contador no carregamento da página
        updateCharCount();

        // Funcionalidade do campo categoria
        function updateCategoriaDescription() {
            const $select = $('#categoria_id');
            const $description = $('#categoria-description');
            const selectedOption = $select.find('option:selected');
            const description = selectedOption.data('description');

            if (description && description.trim() !== '') {
                $description.text(description);
            } else {
                $description.text('Selecione a categoria que melhor descreve este produto.');
            }
        }

        // Atualiza descrição quando a categoria é alterada
        $('#categoria_id').on('change', function() {
            updateCategoriaDescription();
        });

        // Atualiza descrição no carregamento da página
        updateCategoriaDescription();

        // Preview da imagem
        $('#imagem').on('change', function() {
            const file = this.files[0];
            const $preview = $('#image-preview');
            const $container = $('#preview-container');
            const $label = $('.custom-file-label');
            
            if (file) {
                // Atualiza o label com o nome do arquivo
                $label.text(file.name);
                
                // Cria preview da imagem
                const reader = new FileReader();
                reader.onload = function(e) {
                    $preview.attr('src', e.target.result);
                    $container.show();
                };
                reader.readAsDataURL(file);
            } else {
                $label.text('Escolher arquivo...');
                $container.hide();
            }
        });
    });
</script>
<?php echo $this->endSection(); ?>