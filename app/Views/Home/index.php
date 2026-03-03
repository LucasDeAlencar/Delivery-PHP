t<?php echo $this->extend('layout/principal_web'); ?>

<!-- Seção do Título -->
<?php echo $this->section('titulo'); ?>
<?php echo $titulo; ?>
<?php echo $this->endSection(); ?>

<!-- Seção de Estilos Personalizados -->
<?php echo $this->section('estilos'); ?>
<link rel="stylesheet" href="<?= site_url('web/src/css/menu-simple.css'); ?>">
<link rel="stylesheet" href="<?= site_url('web/src/css/modal-dark.css'); ?>">
<link rel="stylesheet" href="<?= site_url('assets/css/produto-extras.css'); ?>">
<style>
    /* Força cor preta no campo quantidade */
    #modalCompra #quantidade,
    #modalCompra input#quantidade {
        color: #000000 !important;
    }
    #modalCompra #quantidade:focus,
    #modalCompra #quantidade:active {
        color: #000000 !important;
    }

    /* Força cor preta no X de fechar */
    #modalCompra .close,
    #modalCompra .modal-header .close {
        color: #000000 !important;
        opacity: 0.8 !important;
    }
    #modalCompra .close:hover,
    #modalCompra .modal-header .close:hover {
        color: #000000 !important;
        opacity: 1 !important;
    }

    /* Responsividade Mobile */
    @media (max-width: 768px) {
        #modalCompra .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        #modalCompra .modal-content {
            max-height: 85vh;
            border-radius: 20px 20px 0 0 !important;
            display: flex !important;
            flex-direction: column !important;
        }

        #modalCompra .modal-header {
            flex-shrink: 0 !important;
            position: sticky;
            top: 0;
            background: #1a1a1a;
            z-index: 1;
        }

        #modalCompra .modal-body {
            flex: 1 1 auto !important;
            max-height: none !important;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }

        #modalCompra .modal-footer {
            flex-shrink: 0 !important;
            position: sticky;
            bottom: 0;
            background: #1a1a1a;
        }

        /* Modal de Extras - Estilo bottom sheet em mobile */
        #modalExtras .modal-dialog {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        #modalExtras .modal-content {
            max-height: 80vh;
            border-radius: 20px 20px 0 0 !important;
            background: #1a1a1a;
        }

        #modalExtras .modal-body {
            max-height: calc(80vh - 120px) !important;
            overflow-y: auto !important;
            background: #1a1a1a;
        }
        
        #modalExtras .modal-header {
            background: #1a1a1a;
        }
        
        #modalExtras .modal-footer {
            background: #1a1a1a;
        }
    }
    
    @media (max-width: 480px) {
        #modalCompra .modal-content {
            max-height: 90vh;
        }
        
        #modalExtras .modal-content {
            max-height: 85vh;
        }
        
        #modalExtras .modal-body {
            max-height: calc(85vh - 110px) !important;
        }
    }

    /* Input number em extras - prevenir teclado sobrepor botões */
    .extra-number {
        font-size: 16px !important;
    }

    /* Modal Fechado - Responsivo */
    #modalFechado .modal-dialog {
        max-width: 400px;
        margin: 1.75rem auto;
    }

    @media (max-width: 576px) {
        #modalFechado .modal-dialog {
            max-width: 90% !important;
            width: 90% !important;
            height: auto !important; 
            min-height: auto !important;
            margin: 20vh auto !important; /* Centraliza verticalmente no topo */
            display: flex;
            align-items: center;
        }

        #modalFechado .modal-content {
            height: auto !important;
            border-radius: 15px !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
        }

        #modalFechado .modal-header {
            padding: 12px 15px !important;
            flex-shrink: 0 !important;
        }

        #modalFechado .modal-title {
            font-size: 0.95rem !important;
        }

        #modalFechado .modal-body {
            padding: 20px 15px !important;
        }

        #modalFechado .fa-store-slash {
            font-size: 2rem !important;
        }

        #modalFechado .modal-body p:first-of-type {
            font-size: 0.85rem !important;
            margin-bottom: 8px !important;
        }

        #modalFechado .text-muted {
            font-size: 0.75rem !important;
        }

        #modalFechado .modal-footer {
            padding: 10px 15px !important;
            flex-shrink: 0 !important;
        }

        #modalFechado .btn {
            padding: 8px 20px !important;
            font-size: 0.85rem !important;
        }
    }
</style>
<?php echo $this->endSection(); ?>

<!-- Seção de Conteúdo Dinâmico do Menu -->
<?php echo $this->section('menu_dinamico'); ?>
<?= $this->include('Home/menu_produtos') ?>
<?php echo $this->endSection(); ?>

<!-- Seção de Scripts Personalizados -->
<?php echo $this->section('scripts'); ?>
<!-- Sistema de Extras -->
<script src="<?= site_url('assets/js/extras-sistema.js'); ?>"></script>

<script>
// Verificar expediente ao carregar página
    const estaAberto = <?= json_encode($estaAberto ?? false) ?>;
    const expedienteHoje = <?= json_encode($expedienteHoje ?? null) ?>;

    $(document).ready(function () {
        if (!estaAberto) {
            // Bloquear cliques nos produtos
            $('[data-produto-id]').css({
                'opacity': '0.5',
                'pointer-events': 'none',
                'cursor': 'not-allowed'
            });

            // Mostrar aviso
            let mensagem = 'Desculpe, estamos fechados no momento.';
            if (expedienteHoje && expedienteHoje.situacao == 0) {
                mensagem = `Desculpe, estamos fechados hoje (${expedienteHoje.dia_descricao}).`;
            } else if (expedienteHoje) {
                mensagem = `Desculpe, estamos fechados no momento. Horário de funcionamento hoje: ${expedienteHoje.abertura.substring(0, 5)} às ${expedienteHoje.fechamento.substring(0, 5)}.`;
            }

            // Modal de aviso
            const modalHtml = `
    <div class="modal fade" id="modalFechado" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #1a1a1a; border: 2px solid #f8b531; border-radius: 15px;">
                <div class="modal-header" style="border-bottom: 1px solid #333; padding: 12px;">
                    <h5 class="modal-title text-warning" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1rem; width: 100%; text-align: center; margin: 0;">
                        <i class="fas fa-clock mr-2"></i>Restaurante Fechado
                    </h5>
                </div>
                <div class="modal-body text-center" style="padding: 20px;">
                    <i class="fas fa-store-slash" style="color: #f8b531; font-size: 2.2rem; margin-bottom: 15px; display: block;"></i>
                    <p class="text-light" style="line-height: 1.4; margin-bottom: 10px; font-size: 0.95rem;">${mensagem}</p>
                    <p class="text-muted" style="margin-bottom: 0; font-size: 0.8rem;">Você pode ver o cardápio, mas não aceitamos pedidos agora.</p>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #333; justify-content: center; padding: 12px;">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="padding: 8px 30px; font-weight: bold;">
                        Entendi
                    </button>
                </div>
            </div>
        </div>
    </div>
`;

            $('body').append(modalHtml);
            $('#modalFechado').modal('show');
        }
    });
</script>
<?php echo $this->endSection(); ?>