<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?>
<?php echo $titulo; ?>
<?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>
<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
    }
    .info-box {
        background-color: #1e3a8a;
        color: #ffffff;
        border: 2px solid #3b82f6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .info-box h6 {
        color: #fbbf24;
        font-weight: bold;
    }
</style>
<?php echo $this->endSection(); ?>

<!-- Área de Conteudos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1">Associar Extra por Categoria</h4>
                        <p class="card-description mb-0">
                            Associe um extra a todos os produtos de uma categoria específica
                        </p>
                    </div>
                    <a href="<?= site_url('admin/extras') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>
                </div>

                <div class="form-container">
                    <div class="info-box">
                        <h6><i class="fas fa-info-circle"></i> Como funciona:</h6>
                        <p class="mb-0">
                            Selecione um ou mais extras e uma categoria. O sistema irá associar automaticamente 
                            os extras selecionados a todos os produtos ativos da categoria escolhida.
                        </p>
                    </div>

                    <?= form_open('admin/extras/processar-associacao', ['class' => 'forms-sample', 'id' => 'form-associacao']) ?>
                        
                        <div class="form-group">
                            <label for="extra_id">Extras <span class="text-danger">*</span></label>
                            <select class="form-control" id="extra_id" name="extra_id[]" multiple required size="8">
                                <?php foreach ($extras as $extra): ?>
                                    <option value="<?= $extra->id ?>" <?= in_array($extra->id, old('extra_id', [])) ? 'selected' : '' ?>>
                                        <?= esc($extra->nome) ?>
                                        <?php if ($extra->preco): ?>
                                            - R$ <?= number_format($extra->preco, 2, ',', '.') ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Segure Ctrl (ou Cmd no Mac) para selecionar múltiplos extras</small>
                            <?php if (session('errors_model.extra_id')): ?>
                                <div class="text-danger mt-1">
                                    Pelo menos um extra deve ser selecionado
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="categoria_id">Categoria <span class="text-danger">*</span></label>
                            <select class="form-control" id="categoria_id" name="categoria_id" required>
                                <option value="">Selecione uma categoria</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria->id ?>" <?= old('categoria_id') == $categoria->id ? 'selected' : '' ?>>
                                        <?= esc($categoria->nome) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors_model.categoria_id')): ?>
                                <div class="text-danger mt-1">
                                    <?= session('errors_model.categoria_id') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary me-2" id="btn-associar">
                                <i class="fas fa-link"></i>
                                <span class="btn-text">Associar Extra</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                            <a href="<?= site_url('admin/extras') ?>" class="btn btn-light">
                                Cancelar
                            </a>
                        </div>

                        <?= csrf_field() ?>
                    <?= form_close() ?>
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
        // Adiciona confirmação e feedback visual antes de enviar o formulário
        $('#form-associacao').on('submit', function(e) {
            var selectedExtras = $('#extra_id option:selected');
            var categoriaText = $('#categoria_id option:selected').text();
            var btnAssociar = $('#btn-associar');
            var btnText = btnAssociar.find('.btn-text');
            var spinner = btnAssociar.find('.spinner-border');
            
            if (selectedExtras.length > 0 && categoriaText && categoriaText !== 'Selecione uma categoria') {
                var extrasTexto = selectedExtras.map(function() { return $(this).text(); }).get().join(', ');
                if (!confirm('Tem certeza que deseja associar os extras "' + extrasTexto + '" a todos os produtos da categoria "' + categoriaText + '"?')) {
                    e.preventDefault();
                    return false;
                }
                
                // Mostra indicador de loading
                btnAssociar.prop('disabled', true);
                btnText.text('Processando...');
                spinner.removeClass('d-none');
            } else {
                e.preventDefault();
                alert('Por favor, selecione pelo menos um extra e uma categoria.');
                return false;
            }
        });
        
        // Validação em tempo real
        $('#extra_id, #categoria_id').on('change', function() {
            var selectedExtras = $('#extra_id option:selected').length;
            var selectedCategoria = $('#categoria_id').val();
            var btnAssociar = $('#btn-associar');
            
            if (selectedExtras > 0 && selectedCategoria) {
                btnAssociar.prop('disabled', false);
            } else {
                btnAssociar.prop('disabled', true);
            }
        });
        
        // Inicializa o estado do botão
        $('#extra_id, #categoria_id').trigger('change');
    });
</script>
<?php echo $this->endSection(); ?>
