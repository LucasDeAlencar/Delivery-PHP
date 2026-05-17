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

    /* ── Popup de login modo 3 ── */
    #overlay-login-modo3 {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.82);
        z-index: 10000;
        display: none;
        align-items: center; justify-content: center;
        padding: 0 16px 56px; box-sizing: border-box;
    }
    #overlay-login-modo3.ativo { display: flex; }
    #overlay-login-modo3 .popup-box {
        background: #1a1a1a; border: 2px solid #0055ff;
        border-radius: 15px; max-width: 360px; width: 100%;
    }
    #overlay-login-modo3 .popup-header {
        border-bottom: 1px solid #333; padding: 12px;
        display: flex; align-items: center; justify-content: space-between;
    }
    #overlay-login-modo3 .popup-header h5 {
        font-family: 'Poppins', sans-serif; font-weight: 600;
        font-size: 1rem; color: #f8b531; margin: 0;
    }
    #overlay-login-modo3 .popup-fechar {
        background: none; border: none; color: #aaa; font-size: 1.3rem;
        cursor: pointer; line-height: 1; padding: 0 4px;
    }
    #overlay-login-modo3 .popup-fechar:hover { color: #fff; }
    #overlay-login-modo3 .popup-venda { text-align: center; margin-bottom: 14px; }
    #overlay-login-modo3 .popup-venda strong { color: #f8b531; font-size: .95rem; display: block; margin-bottom: 3px; }
    #overlay-login-modo3 .popup-venda span { color: #bbb; font-size: .8rem; }
    #overlay-login-modo3 .popup-body { padding: 20px; }
    #overlay-login-modo3 .popup-body p { color: #ccc; font-size: .88rem; margin-bottom: 14px; text-align: center; }
    #overlay-login-modo3 .form-group { margin-bottom: 12px; }
    #overlay-login-modo3 .form-group label { color: #aaa; font-size: .8rem; display: block; margin-bottom: 4px; }
    #overlay-login-modo3 .form-group input {
        width: 100%; padding: 9px 12px; border-radius: 8px;
        border: 1px solid #444; background: #2d2d2d; color: #fff;
        font-size: .9rem; box-sizing: border-box;
    }
    #overlay-login-modo3 .form-group input:focus { outline: none; border-color: #0055ff; }
    #overlay-login-modo3 .btn-entrar {
        width: 100%; padding: 10px; background: #0055ff; color: #fff;
        border: none; border-radius: 8px; font-weight: 600;
        font-size: .95rem; cursor: pointer; margin-top: 4px;
    }
    #overlay-login-modo3 .btn-entrar:hover { background: #0044cc; }
    #overlay-login-modo3 .msg-erro { color: #ff6b6b; font-size: .82rem; text-align: center; margin-top: 8px; display: none; }
    #overlay-login-modo3 .msg-sucesso { color: #4caf50; font-size: .85rem; text-align: center; margin-top: 8px; display: none; }
</style>
<?php echo $this->endSection(); ?>

<?php echo $this->section('menu_dinamico'); ?>
<?= $this->include('Home/menu_produtos') ?>

<?php if (($modoCadastro ?? 1) === 3 && !session()->get('cliente_id')): ?>
<div id="overlay-login-modo3">
    <div class="popup-box">
        <div class="popup-header">
            <h5><i class="fas fa-shopping-bag" style="margin-right:6px;"></i>Quase lá!</h5>
            <button class="popup-fechar" id="btn-popup-fechar" title="Fechar">&times;</button>
        </div>
        <div class="popup-body">
            <div class="popup-venda">
                <strong>🛒 Para continuar, identifique-se</strong>
                <span>Cadastre-se gratuitamente e aproveite nosso cardápio completo.</span>
            </div>
            <div class="form-group">
                <label>Seu nome</label>
                <input type="text" id="popup-nome" placeholder="Como podemos te chamar?" maxlength="80" autocomplete="name">
            </div>
            <div class="form-group">
                <label>Celular</label>
                <input type="tel" id="popup-celular" placeholder="(00) 00000-0000" maxlength="15" autocomplete="tel">
            </div>
            <button class="btn-entrar" id="btn-popup-entrar">
                <i class="fas fa-sign-in-alt" style="margin-right:6px;"></i>Entrar / Cadastrar
            </button>
            <div class="msg-erro" id="popup-erro"></div>
            <div class="msg-sucesso" id="popup-sucesso"></div>
        </div>
    </div>
</div>
<?php endif; ?>
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

    // ---- Popup de login modo 3 ----
    if (window.modoCadastro === 3 && window.clienteLogado && !window.clienteLogado.logado) {

        $('#btn-popup-fechar').on('click', function () {
            $('#overlay-login-modo3').removeClass('ativo');
        });

        $('#overlay-login-modo3').on('click', function (e) {
            if (!$(e.target).closest('.popup-box').length) {
                $(this).removeClass('ativo');
            }
        });

        // Máscara de celular
        $('#popup-celular').on('input', function () {
            let v = $(this).val().replace(/\D/g, '').substring(0, 11);
            if (v.length > 6)      v = '(' + v.substring(0,2) + ') ' + v.substring(2,7) + '-' + v.substring(7);
            else if (v.length > 2) v = '(' + v.substring(0,2) + ') ' + v.substring(2);
            else if (v.length > 0) v = '(' + v;
            $(this).val(v);
        });

        $('#btn-popup-entrar').on('click', function () {
            const celular = $('#popup-celular').val().trim();
            const nome    = $('#popup-nome').val().trim();
            const $btn    = $(this);

            $('#popup-erro').hide();
            $('#popup-sucesso').hide();

            if (!nome) {
                $('#popup-erro').text('Informe seu nome.').show();
                return;
            }
            if (celular.replace(/\D/g,'').length < 10) {
                $('#popup-erro').text('Informe um celular válido.').show();
                return;
            }

            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Aguarde...');

            $.ajax({
                url: '<?= site_url('login/ajaxLogin') ?>',
                method: 'POST',
                contentType: 'application/json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                data: JSON.stringify({ celular, nome }),
                success: function (res) {
                    if (res.sucesso) {
                        $('#popup-sucesso').text(res.message).show();
                        setTimeout(() => location.reload(), 800);
                    } else {
                        $('#popup-erro').text(res.message || 'Erro ao fazer login.').show();
                        $btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt" style="margin-right:6px;"></i>Entrar / Cadastrar');
                    }
                },
                error: function () {
                    $('#popup-erro').text('Erro de conexão. Tente novamente.').show();
                    $btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt" style="margin-right:6px;"></i>Entrar / Cadastrar');
                }
            });
        });

        $('#popup-celular, #popup-nome').on('keydown', function (e) {
            if (e.key === 'Enter') $('#btn-popup-entrar').trigger('click');
        });
    }

    // ---- Popup de estabelecimento fechado ----
    if (!estaAberto) {
        let mensagem = 'Desculpe, estamos fechados no momento.';
        if (expedienteHoje && expedienteHoje.situacao == 0) {
            mensagem = `Desculpe, estamos fechados hoje (${expedienteHoje.dia_descricao}).`;
        } else if (expedienteHoje) {
            mensagem = `Estamos fechados no momento. Horário de hoje: ${expedienteHoje.abertura.substring(0,5)} às ${expedienteHoje.fechamento.substring(0,5)}${expedienteHoje.vira_dia == 1 ? ' (+madrugada)' : ''}.`;
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
            if (data.aberto !== estaAberto) location.reload();
        });
    }, 60000);

});
</script>
<?php echo $this->endSection(); ?>
