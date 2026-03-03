<?php echo $this->extend('Admin/layout/principal'); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>


<!-- Área de Estilos  -->
<?php echo $this->section('estilos'); ?>

<style>
    /* Coluna de ações com largura fixa */
    .table th:last-child,
    .table td:last-child {
        width: 140px;
        text-align: center;
    }

    /* Checkbox de seleção */
    .table th:first-child,
    .table td:first-child {
        width: 40px;
        text-align: center;
    }

    /* Estilo para preço */
    .preco-produto {
        font-weight: 600;
        color: #28a745;
    }
    
    /* Responsividade para tabela de produtos */
    @media (max-width: 991px) {
        .table th:nth-child(6), /* Data */
        .table td:nth-child(6) {
            display: none;
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


<!-- Área de Conteudos -->
<?php echo $this->section('conteudos'); ?>


<!-- Terceira tabela com bordas -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-1">Relatório de produtos</h4>
                        <p class="card-description mb-0">
                            Dados dos produtos cadastrados
                        </p>
                    </div>
                    <a href="<?= site_url("admin/produtos/criar") ?>" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                        Cadastrar
                    </a>
                </div>

                <!-- Filtros e Pesquisa -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" action="<?= site_url('admin/produtos') ?>">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Pesquisar produto..." value="<?= esc($search ?? '') ?>">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method="GET" action="<?= site_url('admin/produtos') ?>">
                            <input type="hidden" name="search" value="<?= esc($search ?? '') ?>">
                            <select name="categoria" class="form-select" onchange="this.form.submit()">
                                <option value="">Todas as categorias</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat->id ?>" <?= ($categoria_filtro ?? '') == $cat->id ? 'selected' : '' ?>>
                                        <?= esc($cat->nome) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-2">
                        <a href="<?= site_url('admin/produtos') ?>" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times"></i> Limpar
                        </a>
                    </div>
                </div>

                <!-- Ações Coletivas -->
                <div class="row mb-3" id="acoes-coletivas" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-primary d-flex justify-content-between align-items-center mb-0">
                            <div>
                                <i class="mdi mdi-checkbox-marked-circle-outline"></i>
                                <strong id="count-selecionados">0</strong> produto(s) selecionado(s)
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-success" onclick="acaoColetiva('ativar')">
                                    <i class="mdi mdi-check-circle"></i> Ativar
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" onclick="acaoColetiva('inativar')">
                                    <i class="mdi mdi-close-circle"></i> Inativar
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="acaoColetiva('excluir')">
                                    <i class="mdi mdi-delete-forever"></i> Excluir
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="limparSelecao()">
                                    <i class="mdi mdi-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive pt-3" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th>#</th>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Categoria</th>
                                <th>Preço</th>
                                <th>Data de criação</th>
                                <th>Ativo</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($produtos)): ?>
                                <?php foreach ($produtos as $produto): ?>
                                    <tr class="produto-row" data-id="<?= $produto->id ?>">
                                        <td>
                                            <input type="checkbox" class="form-check-input select-produto" value="<?= $produto->id ?>">
                                        </td>
                                        <td><?= esc($produto->id) ?></td>
                                        <td>
                                            <?php if (!empty($produto->imagem)): ?>
                                                <img src="<?= base_url('uploads/produtos/' . $produto->imagem) ?>" 
                                                     alt="<?= esc($produto->nome) ?>" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px; border-radius: 4px;">
                                                    <i class="mdi mdi-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($produto->nome) ?></td>
                                        <td>
                                            <?php if (isset($produto->categoria_nome) && !empty($produto->categoria_nome)): ?>
                                                <span class="badge badge-info"><?= esc($produto->categoria_nome) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Não informado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="preco-produto">
                                                R$ <?= isset($produto->preco) ? number_format($produto->preco, 2, ',', '.') : '0,00' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (isset($produto->criado_em) && is_object($produto->criado_em)): ?>
                                                <?= esc($produto->criado_em->humanize()) ?>
                                            <?php else: ?>
                                                <?= isset($produto->criado_em) ? date('d/m/Y H:i', strtotime($produto->criado_em)) : 'N/A' ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-ativo" type="checkbox" 
                                                       data-produto-id="<?= $produto->id ?>" 
                                                       <?= $produto->ativo ? 'checked' : '' ?>>
                                                <label class="form-check-label">
                                                    <span class="status-text"><?= $produto->ativo ? 'Ativo' : 'Inativo' ?></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botão Visualizar -->
                                                <a href="<?= site_url("admin/produtos/show/$produto->id") ?>" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <!-- Botão Editar -->
                                                <a href="<?= site_url("admin/produtos/editar/$produto->id") ?>" 
                                                   class="btn btn-primary btn-sm" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Botão Excluir -->
                                                <a href="<?= site_url("admin/produtos/excluir/$produto->id") ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   title="Excluir"
                                                   onclick="return confirm('Tem certeza que deseja excluir o produto <?= esc($produto->nome) ?>?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">Nenhum produto encontrado</td>
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
</div>

<?php echo $this->endSection(); ?>

<!-- Área de Scripts -->
<?php echo $this->section('scripts'); ?>

<script>
$(document).ready(function() {
    // Selecionar todos os checkboxes
    $('#select-all').on('change', function() {
        $('.select-produto').prop('checked', $(this).is(':checked'));
        atualizarAcoesColetivas();
    });

    // Atualizar ao selecionar produtos individuais
    $('.select-produto').on('change', function() {
        atualizarAcoesColetivas();
        $('#select-all').prop('checked', $('.select-produto:checked').length === $('.select-produto').length);
    });

    // Toggle ativo/inativo
    $('.toggle-ativo').on('change', function() {
        const produtoId = $(this).data('produto-id');
        const isAtivo = $(this).is(':checked');
        const statusText = $(this).siblings('label').find('.status-text');
        const csrfToken = '<?= csrf_hash() ?>';
        const csrfName = '<?= csrf_token() ?>';
        
        $.ajax({
            url: '<?= site_url('admin/produtos/toggle-ativo') ?>',
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                produto_id: produtoId,
                ativo: isAtivo ? 1 : 0,
                [csrfName]: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    statusText.text(isAtivo ? 'Ativo' : 'Inativo');
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert(response.message);
                    }
                } else {
                    // Reverter o toggle em caso de erro
                    $(this).prop('checked', !isAtivo);
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || 'Erro ao alterar status');
                    } else {
                        alert(response.message || 'Erro ao alterar status');
                    }
                }
            }.bind(this),
            error: function() {
                // Reverter o toggle em caso de erro
                $(this).prop('checked', !isAtivo);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Erro ao alterar status do produto');
                } else {
                    alert('Erro ao alterar status do produto');
                }
            }.bind(this)
        });
    });
});

function atualizarAcoesColetivas() {
    const selecionados = $('.select-produto:checked').length;
    $('#count-selecionados').text(selecionados);
    if (selecionados > 0) {
        $('#acoes-coletivas').slideDown(200);
    } else {
        $('#acoes-coletivas').slideUp(200);
    }
}

function limparSelecao() {
    $('.select-produto, #select-all').prop('checked', false);
    atualizarAcoesColetivas();
}

function acaoColetiva(acao) {
    const ids = $('.select-produto:checked').map(function() {
        return $(this).val();
    }).get();

    if (ids.length === 0) {
        if (typeof toastr !== 'undefined') {
            toastr.warning('Selecione pelo menos um produto');
        } else {
            alert('Selecione pelo menos um produto');
        }
        return;
    }

    let mensagem = '';
    if (acao === 'excluir') {
        mensagem = `Tem certeza que deseja excluir ${ids.length} produto(s)?\nEsta ação não pode ser desfeita!`;
    } else if (acao === 'ativar') {
        mensagem = `Deseja ativar ${ids.length} produto(s)?`;
    } else {
        mensagem = `Deseja inativar ${ids.length} produto(s)?`;
    }

    if (!confirm(mensagem)) return;

    $.ajax({
        url: '<?= site_url('admin/produtos/acao-coletiva') ?>',
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            ids: ids,
            acao: acao,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        success: function(response) {
            if (response.success) {
                if (typeof toastr !== 'undefined') {
                    toastr.success(response.message);
                } else {
                    alert(response.message);
                }
                setTimeout(() => location.reload(), 800);
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(response.message || 'Erro ao executar ação');
                } else {
                    alert(response.message || 'Erro ao executar ação');
                }
            }
        },
        error: function() {
            if (typeof toastr !== 'undefined') {
                toastr.error('Erro ao executar ação coletiva');
            } else {
                alert('Erro ao executar ação coletiva');
            }
        }
    });
}

$(document).ready(function () {
    // Adiciona confirmação para exclusão
    $('.btn-danger').on('click', function(e) {
        var produtoNome = $(this).closest('tr').find('td:nth-child(3)').text();
        if (!confirm('Tem certeza que deseja excluir o produto "' + produtoNome + '"?\nEsta ação ocasionará danos a outros setores associado a esse campo!')) {
            e.preventDefault();
            return false;
        }
    });

    // Formata os preços na tabela
    $('.preco-produto').each(function() {
        var preco = $(this).text().trim();
        if (preco === 'R$ 0,00') {
            $(this).addClass('text-muted');
        }
    });

    // Corrige espaçamento da paginação
    $('.pagination .page-link').each(function() {
        $(this).text($(this).text().trim());
    });
});
</script>

<?php echo $this->endSection(); ?>