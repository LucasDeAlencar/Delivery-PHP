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

    /* Estilo para preço */
    .preco-produto {
        font-weight: 600;
        color: #28a745;
    }
    
    /* Responsividade para tabela de bairros - Nome, Preço e Ações */
    @media (max-width: 768px) {
        /* Ocultar apenas Cidade e Situação */
        .table th:nth-child(2), /* Cidade */
        .table td:nth-child(2),
        .table th:nth-child(4) /* Situação */,
        .table td:nth-child(4) {
            display: none !important;
        }
        
        /* FORÇAR exibição do Nome, Taxa de entrega e Ações */
        .table th:nth-child(1), /* Nome */
        .table td:nth-child(1),
        .table th:nth-child(3), /* Taxa de entrega */
        .table td:nth-child(3),
        .table th:nth-child(5), /* Ações */
        .table td:nth-child(5) {
            display: table-cell !important;
        }
        
        .table th,
        .table td {
            padding: 6px 2px;
            font-size: 0.8rem;
        }
        
        .btn-sm {
            padding: 3px 4px;
            font-size: 0.7rem;
        }
    }
    
    @media (max-width: 576px) {
        .table th,
        .table td {
            padding: 4px 1px;
            font-size: 0.75rem;
        }
        
        .btn-sm {
            padding: 2px 3px;
            font-size: 0.65rem;
        }
    }
    
    @media (max-width: 768px) {
        .table th:nth-child(4), /* Categoria */
        .table td:nth-child(4) {
            display: none;
        }
        
        .table th:last-child,
        .table td:last-child {
            width: auto;
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .btn-group .btn {
            width: 100%;
            padding: 6px 10px;
        }
    }
    
    @media (max-width: 576px) {
        .table th:nth-child(1), /* ID */
        .table td:nth-child(1),
        .table th:nth-child(2), /* Imagem */
        .table td:nth-child(2) {
            display: none;
        }
        
        .table th,
        .table td {
            padding: 8px 6px;
            font-size: 0.85rem;
        }
        
        .preco-produto {
            font-size: 0.9rem;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 4px 6px;
        }
        
        /* Paginação */
        .pagination .page-item {
            margin: 0 5px;
        }
        
        .pagination .page-link {
            padding: 0.375rem 0.75rem;
            line-height: 1.25;
            white-space: nowrap;
            text-align: center;
            min-width: 40px;
        }
        
        /* Filtros */
        .input-group .btn {
            border-left: 0;
        }
        
        .form-select, .form-control {
            border-color: #ddd;
        }
        
        .form-select:focus, .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    }
    
    /* Header responsivo */
    @media (max-width: 576px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 15px;
        }
        
        .d-flex.justify-content-between .btn {
            width: 100%;
        }
        
        .card-title {
            font-size: 1.1rem;
        }
        
        .card-description {
            font-size: 0.85rem;
        }
    }
</style>
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

                <div class="table-responsive pt-3" style="max-height: 600px; overflow-y: auto; overflow-x: auto;">
                    <table class="table table-hover" style="min-width: 600px;">
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
                                    <tr class="produto-row <?= $bairro->deletado_em ? 'table-secondary' : '' ?>" data-id="<?= $bairro->id ?>" <?= $bairro->deletado_em ? 'style="opacity: 0.6;"' : '' ?>>
                                        <td><?= esc($bairro->nome) ?></td>
                                        <td><?= esc($bairro->cidade) ?></td>
                                        <td>
                                            <span class="text-success font-weight-bold">
                                                R$ <?= number_format($bairro->valor_entrega, 2, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($bairro->deletado_em): ?>
                                                <span class="badge badge-secondary">Excluído</span>
                                            <?php elseif ($bairro->ativo): ?>
                                                <span class="badge badge-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($bairro->deletado_em): ?>
                                                <form action="<?= site_url("admin/bairros/desfazer-exclusao/$bairro->id") ?>" method="post" style="display: inline;">
                                                    <button type="submit" class="btn btn-outline-success btn-sm" title="Restaurar">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                                <form action="<?= site_url("admin/bairros/deletar-definitivamente/$bairro->id") ?>" method="post" style="display: inline;" onsubmit="return confirm('ATENÇÃO! Esta ação é IRREVERSÍVEL!\n\nTem certeza que deseja apagar este bairro DEFINITIVAMENTE?');">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Apagar Definitivamente">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <a href="<?= site_url("admin/bairros/$bairro->id") ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= site_url("admin/bairros/editar/$bairro->id") ?>" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= site_url("admin/bairros/excluir/$bairro->id") ?>" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
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
                
                <!-- Paginação -->
                <?php if ($pager->getPageCount() > 1): ?>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted small">
                            Página <?= $pager->getCurrentPage() ?> de <?= $pager->getPageCount() ?>
                        </div>
                        <nav aria-label="Navegação da página">
                            <?= $pager->links('default', 'bootstrap_pagination') ?>
                        </nav>
                    </div>
                <?php endif; ?>
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
                            <input type="text" id="cep_loja" class="form-control" placeholder="Ex: 12345-678" value="<?= $configuracao->cep_loja ?? '' ?>">
                            <small class="form-text text-muted">CEP de origem para cálculo de distância</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" id="salvar-configuracoes">
                            <i class="fas fa-save mr-2"></i>Salvar Configurações
                        </button>
                    </div>
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
