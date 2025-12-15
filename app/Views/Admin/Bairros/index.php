<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>

<?php echo $this->endSection(); ?>

<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <!-- Card de Bairros -->
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-1">Bairros Atendidos</h4>
                        <p class="card-description mb-0">Gerencie os bairros e suas respectivas taxas de entrega</p>
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
                                                R$ <?= number_format($bairro->valor_entrega, 2, ',', '.') ?>
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
    // Código JavaScript simplificado - apenas para bairros
    console.log('Página de bairros carregada');
});
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
