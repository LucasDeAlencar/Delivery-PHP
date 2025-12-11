<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>

<?php echo $this->endSection(); ?>

<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <!-- Card de Seleção do Modo -->
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modo de Cobrança de Entrega</h4>
                <p class="card-description">Selecione como deseja cobrar as taxas de entrega</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-check-primary">
                            <label class="form-check-label">
                                <input type="radio" name="modo_cobranca" value="bairro" id="radio-bairro" <?= ($configuracao->modo_cobranca ?? 'bairro') === 'bairro' ? 'checked' : '' ?> class="form-check-input">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Cobrança por Bairro
                                <i class="input-helper"></i>
                            </label>
                            <small class="form-text text-muted">Taxa fixa definida para cada bairro específico</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-check-primary">
                            <label class="form-check-label">
                                <input type="radio" name="modo_cobranca" value="km" id="radio-km" <?= ($configuracao->modo_cobranca ?? 'bairro') === 'km' ? 'checked' : '' ?> class="form-check-input">
                                <i class="fas fa-route mr-2"></i>
                                Cobrança por Quilometragem
                                <i class="input-helper"></i>
                            </label>
                            <small class="form-text text-muted">Taxa calculada automaticamente pela distância</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card de Bairros -->
    <div class="col-lg-12 grid-margin stretch-card mb-4" id="card-bairros" style="<?= ($configuracao->modo_cobranca ?? 'bairro') === 'bairro' ? '' : 'display: none;' ?>">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-1">Bairros Atendidos</h4>
                        <p class="card-description mb-0">Gerencie os bairros e suas respectivas taxas</p>
                    </div>
                    <a href="<?= site_url("admin/bairros/criar") ?>" class="btn btn-success">
                        <i class="fas fa-plus"></i> Cadastrar Bairro
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Cidade</th>
                                <th>Taxa de entrega</th>
                                <th>Situação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($bairros)): ?>
                                <?php foreach ($bairros as $bairro): ?>
                                    <tr>
                                        <td><?= esc($bairro->nome) ?></td>
                                        <td><?= esc($bairro->cidade) ?></td>
                                        <td>
                                            <span class="text-success font-weight-bold">
                                                R$ <?= number_format($bairro->taxa_entrega, 2, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($bairro->ativo): ?>
                                                <span class="badge badge-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= site_url("admin/bairros/$bairro->id") ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= site_url("admin/bairros/editar/$bairro->id") ?>" class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= site_url("admin/bairros/excluir/$bairro->id") ?>" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-map-marker-alt fa-2x mb-2 d-block"></i>
                                        Nenhum bairro cadastrado
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Card de Configuração KM -->
    <div class="col-lg-12 grid-margin stretch-card mb-4" id="card-km" style="<?= ($configuracao->modo_cobranca ?? 'bairro') === 'km' ? '' : 'display: none;' ?>">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Configurações de Cobrança por KM</h4>
                <p class="card-description">Configure os parâmetros para cálculo automático da taxa de entrega</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Taxa por KM (R$)</label>
                            <input type="number" id="taxa_por_km" class="form-control" step="0.01" placeholder="Ex: 2.50" value="<?= $configuracao->taxa_por_km ?? '' ?>">
                            <small class="form-text text-muted">Valor cobrado por quilômetro percorrido</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Taxa Mínima (R$)</label>
                            <input type="number" id="taxa_minima" class="form-control" step="0.01" placeholder="Ex: 5.00" value="<?= $configuracao->taxa_minima ?? '' ?>">
                            <small class="form-text text-muted">Valor mínimo de entrega</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Distância Máxima (KM)</label>
                            <input type="number" id="distancia_maxima" class="form-control" step="0.1" placeholder="Ex: 15.0" value="<?= $configuracao->distancia_maxima ?? '' ?>">
                            <small class="form-text text-muted">Distância máxima para entrega</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>CEP da Loja</label>
                            <input type="text" id="cep_loja" class="form-control" placeholder="00000-000" value="<?= $configuracao->cep_loja ?? '' ?>">
                            <small class="form-text text-muted">CEP do restaurante para cálculo da distância</small>
                        </div>
                    </div>
                </div>
                
                <button class="btn btn-success" id="btnSalvarConfig">
                    <i class="fas fa-save"></i> Salvar Configurações
                </button>
                
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle"></i>
                    <strong>Como funciona:</strong> O sistema calculará automaticamente a distância entre o restaurante e o endereço do cliente, aplicando a taxa por quilômetro configurada.
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
    // Alternar entre cards ao mudar radio button
    $('input[name="modo_cobranca"]').on('change', function() {
        const selectedValue = $(this).val();
        
        if (selectedValue === 'bairro') {
            $('#card-bairros').show();
            $('#card-km').hide();
        } else if (selectedValue === 'km') {
            $('#card-bairros').hide();
            $('#card-km').show();
        }
        
        // Salvar modo de cobrança automaticamente
        salvarModoCobranca(selectedValue);
    });
    
    function salvarModoCobranca(modo) {
        $.ajax({
            url: '<?= site_url('admin/bairros/salvarModoCobranca') ?>',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({modo_cobranca: modo}),
            success: function(response) {
                if (!response.sucesso) {
                    console.error('Erro ao salvar modo de cobrança:', response.msg);
                }
            },
            error: function() {
                console.error('Erro ao salvar modo de cobrança');
            }
        });
    }
    
    // Salvar configurações KM
    $('#btnSalvarConfig').click(function() {
        const cepLoja = $('#cep_loja').val();
        
        // Validar CEP se estiver preenchido
        if (cepLoja && cepLoja.replace(/\D/g, '').length === 8) {
            // Verificar se CEP é válido
            fetch('<?= site_url('login/buscar_cep') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({cep: cepLoja.replace(/\D/g, '')})
            })
            .then(response => response.json())
            .then(data => {
                if (data.erro) {
                    alert('CEP inválido: ' + (data.msg || 'CEP não encontrado'));
                    return;
                }
                // CEP válido, salvar configurações
                salvarConfiguracoes();
            })
            .catch(error => {
                alert('Erro ao validar CEP');
            });
        } else if (cepLoja) {
            alert('CEP deve ter 8 dígitos');
        } else {
            // Sem CEP, salvar direto
            salvarConfiguracoes();
        }
    });
    
    function salvarConfiguracoes() {
        const dados = {
            modo_cobranca: $('input[name="modo_cobranca"]:checked').val(),
            taxa_por_km: $('#taxa_por_km').val(),
            taxa_minima: $('#taxa_minima').val(),
            distancia_maxima: $('#distancia_maxima').val(),
            cep_loja: $('#cep_loja').val()
        };
        
        $.ajax({
            url: '<?= site_url('admin/bairros/salvarConfiguracao') ?>',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(dados),
            success: function(response) {
                if (response.sucesso) {
                    toastr.success('Configuração salva com sucesso!');
                } else {
                    toastr.error('Erro: ' + response.msg);
                }
            },
            error: function() {
                toastr.error('Erro ao salvar configuração');
            }
        });
    }
    
    // Máscara para CEP
    $('#cep_loja').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 8) {
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
        }
        $(this).val(value);
    });
});
</script>

<?php echo $this->endSection(); ?>
