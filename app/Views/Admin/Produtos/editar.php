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
    
    /* Imagem atual */
    .current-image-container {
        text-align: center;
        padding: 15px;
        background: var(--darker-bg);
        border-radius: 6px;
        border: 1px solid var(--border-color);
    }
    
    .current-image-container img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    .current-image-label {
        display: block;
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-bottom: 10px;
        font-weight: 500;
    }
    
    /* Preview da nova imagem */
    .new-image-container {
        text-align: center;
        padding: 15px;
        background: var(--darker-bg);
        border-radius: 6px;
        border: 2px dashed var(--border-color);
        min-height: 150px;
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
        max-height: 200px;
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
    
    .new-image-label {
        display: block;
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-bottom: 10px;
        font-weight: 500;
    }
    
    /* Checkbox de remover imagem */
    .remove-image-checkbox {
        margin-top: 15px;
        padding: 10px;
        background: rgba(220, 53, 69, 0.1);
        border-radius: 6px;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    
    .remove-image-checkbox .form-check-label {
        color: #dc3545;
        font-weight: 500;
    }
    
    /* Responsividade do formulário */
    @media (max-width: 768px) {
        .form-group.row > div {
            margin-bottom: 1rem;
        }
        
        .image-preview-section {
            padding: 15px;
        }
        
        .current-image-container img,
        .new-image-container img {
            max-height: 150px;
        }
        
        .new-image-container {
            min-height: 120px;
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
        
        .current-image-label,
        .new-image-label {
            font-size: 0.8rem;
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
                
                <?php echo form_open_multipart("admin/produtos/atualizar/{$produto->id}"); ?>
                
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome do Produto *</label>
                        <input type="text" 
                               class="form-control <?php echo (session('errors.nome') ? 'is-invalid' : ''); ?>" 
                               id="nome" 
                               name="nome" 
                               value="<?php echo old('nome', $produto->nome); ?>"
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
                                        <?php echo (old('categoria_id', $produto->categoria_id) == $categoria->id ? 'selected' : ''); ?>>
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
                                   value="<?php echo old('preco', number_format($produto->preco, 2, ',', '.')); ?>"
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
                               value="<?php echo old('obrigatorio_extras', $produto->obrigatorio_extras ?? 0); ?>"
                               min="0"
                               max="99"
                               placeholder="0">
                        <small class="form-text text-muted">
                            Quantidade de extras distintos obrigatórios (0 = nenhum)
                        </small>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="max_extras" class="form-label">Máximo de Extras</label>
                        <input type="number" 
                               class="form-control" 
                               id="max_extras" 
                               name="max_extras" 
                               value="<?php echo old('max_extras', $produto->max_extras ?? ''); ?>"
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
                                   <?php echo (old('ativo', $produto->ativo) == 1 ? 'checked' : ''); ?>>
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
                        <label for="ingredientes" class="form-label">Ingredientes *</label>
                        <textarea class="form-control <?php echo (session('errors.ingredientes') ? 'is-invalid' : ''); ?>" 
                                  id="ingredientes" 
                                  name="ingredientes" 
                                  rows="5"
                                  placeholder="Descreva os ingredientes do produto"
                                  required><?php echo old('ingredientes', $produto->ingredientes); ?></textarea>
                        <?php if (session('errors.ingredientes')): ?>
                            <div class="invalid-feedback">
                                <?php echo session('errors.ingredientes'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Checkbox com_tamanho -->
                <div class="form-group row">
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="com_tamanho"
                                   name="com_tamanho"
                                   value="1"
                                   <?= old('com_tamanho', $produto->com_tamanho ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="com_tamanho">
                                <strong>Produto com tamanhos</strong>
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            Habilite para definir preços diferentes por tamanho (ex: Pequeno, Médio, Grande).
                        </small>
                    </div>
                </div>

                <!-- Seção de tamanhos -->
                <div id="tamanhos-section" style="display:none;">
                    <div class="card border-warning mb-3">
                        <div class="card-header bg-warning text-dark">
                            <strong><i class="fas fa-ruler"></i> Preços por Tamanho</strong>
                            <button type="button" class="btn btn-sm btn-outline-dark float-right" id="btn-add-tamanho">
                                <i class="fas fa-plus"></i> Adicionar Tamanho
                            </button>
                        </div>
                        <div class="card-body p-2">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Descrição do Tamanho</th>
                                        <th>Preço</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tamanhos-tbody">
                                    <?php if (!empty($tamanhos_existentes)): ?>
                                        <?php foreach ($tamanhos_existentes as $i => $t): ?>
                                        <tr>
                                            <td><input type="text" class="form-control form-control-sm" name="tamanhos[<?= $i ?>][nome]" value="<?= esc($t->nome) ?>" required></td>
                                            <td><div class="input-group input-group-sm"><span class="input-group-text">R$</span>
                                                <input type="text" class="form-control tamanho-preco" name="tamanhos[<?= $i ?>][preco]" value="<?= number_format($t->preco, 2, ',', '.') ?>" required></div></td>
                                            <td><button type="button" class="btn btn-sm btn-danger btn-remove-tamanho"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="image-preview-section">
                    <h5><i class="fas fa-images"></i> Gerenciamento de Imagem</h5>
                    
                    <div class="row">
                        <!-- Upload de nova imagem -->
                        <div class="col-md-6 mb-3">
                            <label for="imagem" class="form-label">Selecionar Nova Imagem</label>
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
                    </div>
                    
                    <div class="row mt-3">
                        <!-- Imagem Atual -->
                        <?php if (!empty($produto->imagem)): ?>
                        <div class="col-md-6 mb-3">
                            <span class="current-image-label"><i class="fas fa-image"></i> Imagem Atual</span>
                            <div class="current-image-container">
                                <img src="<?php echo base_url('uploads/produtos/' . $produto->imagem); ?>" 
                                     alt="<?php echo esc($produto->nome); ?>">
                            </div>
                            <div class="remove-image-checkbox">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input" 
                                           id="remover_imagem" 
                                           name="remover_imagem" 
                                           value="1">
                                    <label class="form-check-label" for="remover_imagem">
                                        <i class="fas fa-trash"></i> Remover imagem atual
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Preview da Nova Imagem -->
                        <div class="col-md-6 mb-3">
                            <span class="new-image-label"><i class="fas fa-eye"></i> Preview da Nova Imagem</span>
                            <div class="new-image-container" id="new-image-container">
                                <div class="new-image-placeholder">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--text-muted); margin-bottom: 10px;"></i>
                                    <p>Selecione uma imagem para visualizar o preview</p>
                                </div>
                                <img id="preview-imagem" alt="Preview da nova imagem">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-save"></i>
                            Atualizar Produto
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
        
        // Preview da nova imagem
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
        
        // Confirmação se remover imagem
        $('#remover_imagem').change(function() {
            if ($(this).is(':checked')) {
                if (!confirm('Tem certeza que deseja remover a imagem atual?')) {
                    $(this).prop('checked', false);
                }
            }
        });
    
        // Toggle seção de tamanhos
        function toggleTamanhos(checked) {
            if (checked) {
                $("#tamanhos-section").show();
                if ($("#tamanhos-tbody tr").length === 0) adicionarLinhaTamanho();
                $("#preco").prop("disabled", true).prop("required", false);
                if (!$("#preco-hidden").length) {
                    $('<input type="hidden" id="preco-hidden" name="preco" value="' + ($("#preco").val() || "0") + '">').insertAfter("#preco");
                }
            } else {
                $("#tamanhos-section").hide();
                $("#preco").prop("disabled", false).prop("required", true);
                $("#preco-hidden").remove();
            }
        }
        $("#com_tamanho").change(function() { toggleTamanhos($(this).is(":checked")); });
        toggleTamanhos($("#com_tamanho").is(":checked"));

        // Adicionar linha de tamanho
        $("#btn-add-tamanho").click(function() { adicionarLinhaTamanho(); });

        var tamanhoIdx = <?= !empty($tamanhos_existentes) ? count($tamanhos_existentes) : 0 ?>;
        function adicionarLinhaTamanho(nome, preco) {
            var i = tamanhoIdx++;
            var tr = '<tr>' +
                '<td><input type="text" class="form-control form-control-sm" name="tamanhos[' + i + '][nome]" placeholder="Ex: Pequeno" value="' + (nome || '') + '" required></td>' +
                '<td><div class="input-group input-group-sm"><span class="input-group-text">R$</span>' +
                '<input type="text" class="form-control tamanho-preco" name="tamanhos[' + i + '][preco]" placeholder="0,00" value="' + (preco || '') + '" required></div></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger btn-remove-tamanho"><i class="fas fa-trash"></i></button></td>' +
                '</tr>';
            $("#tamanhos-tbody").append(tr);
        }

        $(document).on("click", ".btn-remove-tamanho", function() {
            $(this).closest("tr").remove();
        });
});
</script>

<?php echo $this->endSection(); ?>