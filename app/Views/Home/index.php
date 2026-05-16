<?php echo $this->extend('layout/principal_web'); ?>

<?php echo $this->section('titulo'); ?>
<?php echo $titulo; ?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('estilos'); ?>
<link rel="stylesheet" href="<?= site_url('web/src/css/menu-simple.css'); ?>">
<link rel="stylesheet" href="<?= site_url('assets/css/produto-extras.css'); ?>">
<link rel="stylesheet" href="<?= site_url('assets/css/produto-modal.css?v=' . (@filemtime(FCPATH . 'assets/css/produto-modal.css') ?: '1')); ?>">
<style>
    #modalCompra #quantidade { color: #ffc135 !important; }
    #modalCompra .close { color: #fff !important; opacity: .8 !important; }
    #modalCompra .close:hover { opacity: 1 !important; }
</style>
<?php echo $this->endSection(); ?>

<?php echo $this->section('menu_dinamico'); ?>

<?= $this->include('Home/menu_produtos') ?>

<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>
<script src="<?= site_url('assets/js/extras-sistema.js'); ?>"></script>
<script src="<?= site_url('web/src/js/auth-check.js') ?>"></script>
<script>
const estaAberto     = <?= json_encode($estaAberto ?? false) ?>;
const expedienteHoje = <?= json_encode($expedienteHoje ?? null) ?>;

$(document).ready(function () {
    // ---- Filtro de categorias ----
    $(document).on('click', '.cat-btn', function () {
        $('.cat-btn').removeClass('active');
        $(this).addClass('active');
        const filtro = $(this).data('filter');

        if (filtro === 'all') {
            $('.secao-categoria').show();
        } else {
            $('.secao-categoria').each(function () {
                $(this).toggle($(this).data('categoria') === filtro);
            });
        }
    });

    // ---- Abrir modal ao clicar no card ----
    $(document).on('click', '.card-produto:not(.produto-inativo)', function () {
        const $card = $(this);
        // Dispara o mesmo evento que o sistema existente espera
        $card.trigger('click.produto');
        // Fallback: se o sistema de modal já escuta data-produto-id, apenas propaga
    });

    // ---- Botão + abre modal ----
    $(document).on('click', '.btn-adicionar', function (e) {
        e.stopPropagation();
        $(this).closest('.card-produto').trigger('click');
    });

    // ---- Popup fechado ----
    if (!estaAberto) {
        $('.card-produto').css({ opacity: '0.5', 'pointer-events': 'none', cursor: 'not-allowed' });

        let mensagem = 'Desculpe, estamos fechados no momento.';
        if (expedienteHoje && expedienteHoje.situacao == 0) {
            mensagem = `Desculpe, estamos fechados hoje (${expedienteHoje.dia_descricao}).`;
        } else if (expedienteHoje) {
            mensagem = `Estamos fechados no momento. Horário de hoje: ${expedienteHoje.abertura.substring(0,5)} às ${expedienteHoje.fechamento.substring(0,5)}.`;
        }

        const overlay = document.createElement('div');
        overlay.id = 'popup-fechado';
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.75);z-index:9998;display:flex;align-items:center;justify-content:center;padding:0 16px 56px;box-sizing:border-box;';
        overlay.innerHTML = `
            <div style="background:#1a1a1a;border:2px solid #0055ff;border-radius:15px;max-width:360px;width:100%;">
                <div style="border-bottom:1px solid #333;padding:12px;text-align:center;">
                    <h5 style="font-family:'Poppins',sans-serif;font-weight:600;font-size:1rem;color:#f8b531;margin:0;">
                        <i class="fas fa-clock" style="margin-right:6px;"></i>Delivery Fechado
                    </h5>
                </div>
                <div style="padding:18px;text-align:center;">
                    <i class="fas fa-store-slash" style="color:#0055ff;font-size:2rem;margin-bottom:12px;display:block;"></i>
                    <p style="color:#fff;line-height:1.4;margin-bottom:8px;font-size:.9rem;">${mensagem}</p>
                    <p style="color:#aaa;margin-bottom:0;font-size:.78rem;">Você pode ver o cardápio, mas não aceitamos pedidos agora.</p>
                </div>
                <div style="border-top:1px solid #333;padding:10px;text-align:center;">
                    <button id="btn-entendi-fechado" style="background:#f8b531;color:#000;border:none;border-radius:6px;padding:8px 30px;font-weight:bold;cursor:pointer;font-size:.9rem;">Entendi</button>
                </div>
            </div>`;
        document.body.appendChild(overlay);
        document.getElementById('btn-entendi-fechado').addEventListener('click', () => overlay.remove());
    }

    // ---- Polling: recarrega se status aberto/fechado mudar ----
    setInterval(function () {
        $.getJSON('<?= site_url('api/status-expediente') ?>', function (data) {
            if (data.aberto !== estaAberto) {
                location.reload();
            }
        });
    }, 60000); // verifica a cada 1 minuto
});
</script>
<?php echo $this->endSection(); ?>
