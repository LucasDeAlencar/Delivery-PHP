<?php echo $this->extend('Admin/layout/principal'); ?>

<!-- Área de Título -->
<?php echo $this->section('titulo'); ?>
<?php echo $titulo; ?>
<?php echo $this->endSection(); ?>

<!-- Área de Estilos -->
<?php echo $this->section('estilos'); ?>
<style>
    /* Card de forma de pagamento */
    .payment-card {
        background: var(--darker-bg);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(248, 181, 49, 0.2);
        border-color: var(--primary-color);
    }

    .payment-card.inactive {
        opacity: 0.6;
    }

    .payment-card.inactive:hover {
        opacity: 0.8;
    }

    /* Ícone da forma de pagamento */
    .payment-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .payment-card.inactive .payment-icon {
        color: var(--text-muted);
    }

    /* Nome da forma de pagamento */
    .payment-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 10px;
    }

    .payment-card.inactive .payment-name {
        color: var(--text-muted);
    }

    /* Switch toggle */
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
        background-color: #555;
        transition: .4s;
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
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: var(--primary-color);
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    /* Badge de status */
    .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.active {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border: 1px solid #28a745;
    }

    .status-badge.inactive {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 1px solid #dc3545;
    }

    /* Animação de salvamento */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .saving {
        animation: pulse 0.5s ease-in-out;
    }
</style>
<?php echo $this->endSection(); ?>

<!-- Área de Conteúdos -->
<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1">Formas de Pagamento</h4>
                        <p class="card-description mb-0">
                            Gerencie as formas de pagamento aceitas pelo estabelecimento
                        </p>
                    </div>
                </div>
                <?php if (session()->has('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-info-circle"></i> Informação!</strong> <?= session('info'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('erro')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-circle"></i> Erro!</strong> <?= session('erro'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Informações -->
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Informações Importantes:</h6>
                    <ul class="mb-0">
                        <li>Ative ou desative as formas de pagamento que seu estabelecimento aceita</li>
                        <li>As alterações são salvas automaticamente ao clicar em "Salvar Configurações"</li>
                        <li>Formas de pagamento inativas não aparecerão para os clientes</li>
                        <li>É recomendado ter pelo menos uma forma de pagamento ativa</li>
                    </ul>
                </div>

                <!-- Formulário -->
                <form action="<?= site_url('admin/formas-pagamento/atualizar') ?>" method="POST" id="form-pagamento">
                    <?= csrf_field() ?>

                    <div class="row">
                        <?php if (!empty($formasPagamento)): ?>
                            <?php foreach ($formasPagamento as $forma): ?>
                                <div class="col-md-6 col-lg-3">
                                    <div class="payment-card <?= $forma->ativo ? '' : 'inactive' ?>" data-id="<?= $forma->id ?>">
                                        <!-- Badge de status -->
                                        <span class="status-badge <?= $forma->ativo ? 'active' : 'inactive' ?>">
                                            <?= $forma->ativo ? 'Ativo' : 'Inativo' ?>
                                        </span>

                                        <!-- Ícone -->
                                        <div class="text-center">
                                            <i class="<?= esc($forma->icone) ?> payment-icon"></i>
                                        </div>

                                        <!-- Nome -->
                                        <div class="text-center payment-name">
                                            <?= esc($forma->nome) ?>
                                        </div>

                                        <!-- Switch -->
                                        <div class="text-center mt-3">
                                            <label class="switch">
                                                <input type="checkbox" 
                                                       name="forma_<?= $forma->id ?>" 
                                                       value="1" 
                                                       <?= $forma->ativo ? 'checked' : '' ?>
                                                       class="payment-toggle"
                                                       data-id="<?= $forma->id ?>">
                                                <span class="slider"></span>
                                            </label>
                                        </div>

                                        <!-- Descrição -->
                                        <div class="text-center mt-2">
                                            <small class="text-muted">
                                                <?php
                                                switch ($forma->slug) {
                                                    case 'dinheiro':
                                                        echo 'Pagamento em espécie';
                                                        break;
                                                    case 'debito':
                                                        echo 'Cartão de débito';
                                                        break;
                                                    case 'credito':
                                                        echo 'Cartão de crédito';
                                                        break;
                                                    case 'pix':
                                                        echo 'Transferência instantânea';
                                                        break;
                                                    default:
                                                        echo 'Forma de pagamento';
                                                }
                                                ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-warning text-center">
                                    <h5><i class="fas fa-exclamation-triangle"></i> Nenhuma forma de pagamento encontrada</h5>
                                    <p class="mb-0">Execute o script SQL para criar as formas de pagamento padrão.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Botão de salvar -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success btn-lg" id="btn-salvar">
                                <i class="fas fa-save"></i> Salvar Configurações
                            </button>
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
<script>
    $(document).ready(function() {
        console.log('=== FORMAS DE PAGAMENTO CARREGADA ===');

        // Atualiza o visual do card quando o switch muda
        $('.payment-toggle').on('change', function() {
            const $card = $(this).closest('.payment-card');
            const $badge = $card.find('.status-badge');
            const isActive = $(this).is(':checked');

            console.log('Toggle alterado - ID:', $(this).data('id'), 'Ativo:', isActive);

            // Atualiza classes do card
            if (isActive) {
                $card.removeClass('inactive');
                $badge.removeClass('inactive').addClass('active').text('Ativo');
            } else {
                $card.addClass('inactive');
                $badge.removeClass('active').addClass('inactive').text('Inativo');
            }

            // Animação
            $card.addClass('saving');
            setTimeout(function() {
                $card.removeClass('saving');
            }, 500);
        });

        // Validação antes de enviar
        $('#form-pagamento').on('submit', function(e) {
            const ativos = $('.payment-toggle:checked').length;

            if (ativos === 0) {
                e.preventDefault();
                alert('⚠️ Atenção!\n\nVocê deve ter pelo menos uma forma de pagamento ativa.');
                return false;
            }

            // Feedback visual
            $('#btn-salvar')
                .prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i> Salvando...');

            console.log('Formulário enviado com', ativos, 'formas de pagamento ativas');
        });

        // Contador de formas ativas
        function atualizarContador() {
            const ativos = $('.payment-toggle:checked').length;
            const total = $('.payment-toggle').length;
            console.log('Formas ativas:', ativos, '/', total);
        }

        $('.payment-toggle').on('change', atualizarContador);
        atualizarContador();
    });
</script>
<?php echo $this->endSection(); ?>
