<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> 
<?php echo $titulo; ?> 
<?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>

<style>
    .money {
        text-align: right;
    }
    
    /* Container de imagens */
    .image-preview-section {
        background: rgba(248, 181, 49, 0.05);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .image-preview-section h5 {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-size: 1rem;
        font-weight: 600;
    }
    
    /* Preview da imagem */
    .new-image-container {
        text-align: center;
        padding: 15px;
        background: var(--darker-bg);
        border-radius: 6px;
        border: 2px dashed var(--border-color);
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    
    .new-image-container.has-image {
        border-color: var(--primary-color);
        border-style: solid;
    }
    
    .new-image-container img {
        max-width: 100%;
        max-height: 250px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        display: none;
    }
    
    .new-image-container.has-image img {
        display: block;
    }
    
    .new-image-placeholder {
        color: var(--text-muted);
        font-size: 0.875rem;
        text-align: center;
    }
    
    .new-image-container.has-image .new-image-placeholder {
        display: none;
    }
    
    /* Responsividade do formulário */
    @media (max-width: 768px) {
        .form-group.row > div {
            margin-bottom: 1rem;
        }
        
        .image-preview-section {
            padding: 15px;
        }
        
        .new-image-container {
            min-height: 150px;
        }
        
        .new-image-container img {
            max-height: 180px;
        }
        
        .new-image-placeholder i {
            font-size: 2rem !important;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .btn:last-child {
            margin-bottom: 0;
        }
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 15px;
        }
        
        .card-title {
            font-size: 1.1rem;
        }
        
        .form-label {
            font-size: 0.9rem;
        }
        
        .form-control {
            font-size: 16px; /* Previne zoom no iOS */
        }
        
        .form-text {
            font-size: 0.75rem;
        }
        
        .input-group-text {
            padding: 0.375rem 0.5rem;
        }
    }
</style>

<?php echo $this->endSection(); ?>

<!-- Área de Conteúdo -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?php echo $titulo; ?></h4>
                
                <?php 
                // Exibe todos os erros de validação
                $errors = session('errors') ?? session('errors_model') ?? [];
                if (!empty($errors)): 
                ?>
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle"></i> Erros encontrados:</h6>
                        <ul class="mb-0">
                            <?php foreach ($errors as $campo => $erro): ?>
                                <li><strong><?= ucfirst($campo) ?>:</strong> <?= is_array($erro) ? implode(', ', $erro) : $erro ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php echo form_open_multipart('admin/produtos/cadastrar', ['method' => 'post']); ?>
                <?php echo csrf_field(); ?>
                
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome do Produto *</label>
                        <input type="text" 
                               class="form-control <?php echo (session('errors.nome') ? 'is-invalid' : ''); ?>" 
                               id="nome" 
                               name="nome" 
                               value="<?php echo old('nome'); ?>"
                               placeholder="Digite o nome do produto"
                               required>
                        <?php if (session('errors.nome')): ?>
                            <div class="invalid-feedback">
                                <?php echo session('errors.nome'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="categoria_id" class="form-label">Categoria *</label>
                        <select class="form-control <?php echo (session('errors.categoria_id') ? 'is-invalid' : ''); ?>" 
                                id="categoria_id" 
                                name="categoria_id"
                                required>
                            <option value="">Selecione uma categoria</option>
                            <?php if (!empty($categorias)): ?>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo $categoria->id; ?>" 
                                        <?php echo (old('categoria_id') == $categoria->id ? 'selected' : ''); ?>>
                                        <?php echo esc($categoria->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (session('errors.categoria_id')): ?>
                            <div class="invalid-feedback">
                                <?php echo session('errors.categoria_id'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="preco" class="form-label">Preço *</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" 
                                   class="form-control money <?php echo (session('errors.preco') ? 'is-invalid' : ''); ?>" 
                                   id="preco" 
                                   name="preco" 
                                   value="<?php echo old('preco'); ?>"
                                   placeholder="0,00"
                                   required>
                        </div>
                        <?php if (session('errors.preco')): ?>
                            <div class="invalid-feedback">
                                <?php echo session('errors.preco'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="obrigatorio_extras" class="form-label">Extras Obrigatórios</label>
                        <input type="number" 
                               class="form-control" 
                               id="obrigatorio_extras" 
                               name="obrigatorio_extras" 
                               value="<?php echo old('obrigatorio_extras', 0); ?>"
                               min="0"
                               max="99"
                               placeholder="0">
                        <small class="form-text text-muted">
                            Use este campo para produtos que requerem escolha do cliente para serem completos (ex: pizza meio a meio). Caso contrário, defina o valor como 0.
                        </small>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="max_extras" class="form-label">Máximo de Extras</label>
                        <input type="number" 
                               class="form-control" 
                               id="max_extras" 
                               name="max_extras" 
                               value="<?php echo old('max_extras', ''); ?>"
                               min="0"
                               max="99"
                               placeholder="Ilimitado">
                        <small class="form-text text-muted">
                            Máximo de extras que podem ser selecionados (vazio = ilimitado)
                        </small>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label d-block">&nbsp;</label>
                        <div class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="ativo" 
                                   name="ativo" 
                                   value="1"
                                   <?php echo (old('ativo') == 1 ? 'checked' : 'checked'); ?>>
                            <label class="form-check-label" for="ativo">
                                Produto ativo
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            Produtos inativos não aparecerão no site.
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <label for="ingredientes" class="form-label">Descrição</label>
                        <textarea class="form-control <?php echo (session('errors.ingredientes') ? 'is-invalid' : ''); ?>" 
                                  id="ingredientes" 
                                  name="ingredientes" 
                                  rows="5"
                                  placeholder="Descreva o produto"><?php echo old('ingredientes'); ?></textarea>
                        <?php if (session('errors.ingredientes')): ?>
                            <div class="invalid-feedback">
                                <?php echo session('errors.ingredientes'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Seção de Imagem -->
                <div class="image-preview-section">
                    <h5><i class="fas fa-images"></i> Imagem do Produto</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="imagem" class="form-label">Selecionar Imagem</label>
                            <input type="file" 
                                   class="form-control <?php echo (session('errors.imagem') ? 'is-invalid' : ''); ?>" 
                                   id="imagem" 
                                   name="imagem"
                                   accept="image/*">
                            <?php if (session('errors.imagem')): ?>
                                <div class="invalid-feedback">
                                    <?php echo session('errors.imagem'); ?>
                                </div>
                            <?php endif; ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Formatos: JPG, PNG, GIF | Tamanho máximo: 2MB
                            </small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fas fa-eye"></i> Preview da Imagem</label>
                            <div class="new-image-container" id="new-image-container">
                                <div class="new-image-placeholder">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 15px;"></i>
                                    <p style="margin: 0;">Selecione uma imagem para visualizar o preview</p>
                                </div>
                                <img id="preview-imagem" alt="Preview da imagem">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success mr-2">
                            <i class="fas fa-save"></i>
                            Cadastrar Produto
                        </button>
                        <a href="<?php echo site_url('admin/produtos'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(document).ready(function() {
        // Máscara para preço
        $('.money').mask('000.000.000.000.000,00', {reverse: true});
        
        // Preview da imagem
        $('#imagem').change(function() {
            const file = this.files[0];
            const container = $('#new-image-container');
            const preview = $('#preview-imagem');
            
            if (file) {
                // Validar tipo de arquivo
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Por favor, selecione uma imagem válida (JPG, PNG ou GIF)');
                    $(this).val('');
                    return;
                }
                
                // Validar tamanho (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('A imagem deve ter no máximo 2MB');
                    $(this).val('');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result);
                    container.addClass('has-image');
                }
                reader.readAsDataURL(file);
            } else {
                container.removeClass('has-image');
                preview.attr('src', '');
            }
        });
        
        // Validação do formulário
        $('form').submit(function() {
            // Remove formatação do preço antes de enviar
            var preco = $('#preco').val();
            preco = preco.replace(/\./g, '').replace(',', '.');
            $('#preco').val(preco);
            
            return true;
        });
        
        // Validação em tempo real
        $('#nome').on('blur', function() {
            if ($(this).val().length < 3) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        $('#preco').on('blur', function() {
            var preco = $(this).val().replace(/\./g, '').replace(',', '.');
            if (preco <= 0) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
    });
</script>

<?php echo $this->endSection(); ?>