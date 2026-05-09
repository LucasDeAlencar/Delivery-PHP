<?php echo $this->extend('Admin/layout/principal'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('estilos'); ?>
<style>
    .form-group { margin-bottom: 1rem; }
    .switch { position:relative; display:inline-block; width:46px; height:24px; }
    .switch input { opacity:0; width:0; height:0; }
    .slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:#ccc; border-radius:24px; transition:.3s; }
    .slider:before { position:absolute; content:""; height:18px; width:18px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.3s; }
    input:checked + .slider { background:#28a745; }
    input:checked + .slider:before { transform:translateX(22px); }
</style>
<?php echo $this->endSection(); ?>

<?php echo $this->section('conteudos'); ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-1">Dados Corporativos</h4>
                        <p class="card-description mb-0">
                            Informações da empresa exibidas no site
                        </p>
                    </div>
                </div>

                <form id="formDadosCorporativos">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="endereco">Endereço</label>
                                <input type="text" 
                                       id="endereco" 
                                       class="form-control" 
                                       placeholder="Rua, Avenida, etc."
                                       value="<?= esc($dados->endereco ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cep">CEP</label>
                                <input type="text" 
                                       id="cep" 
                                       class="form-control" 
                                       placeholder="00000-000"
                                       value="<?= esc($dados->cep ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero">Telefone</label>
                                <input type="text" 
                                       id="numero" 
                                       class="form-control" 
                                       placeholder="(00) 00000-0000"
                                       value="<?= esc($dados->numero ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="whatsapp">WhatsApp</label>
                                <input type="text" 
                                       id="whatsapp" 
                                       class="form-control" 
                                       placeholder="(00) 00000-0000"
                                       value="<?= esc($dados->whatsapp ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" 
                                       id="email" 
                                       class="form-control" 
                                       placeholder="contato@empresa.com"
                                       value="<?= esc($dados->email ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instagram">Instagram</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">@</span>
                                    </div>
                                    <input type="text" 
                                           id="instagram" 
                                           class="form-control" 
                                           placeholder="nokapricho"
                                           value="<?= esc($dados->instagram ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="facebook">Facebook</label>
                                <input type="text" 
                                       id="facebook" 
                                       class="form-control" 
                                       placeholder="nokapricho"
                                       value="<?= esc($dados->facebook ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="preco_minimo_compra">Preço Mínimo de Compra (R$)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" 
                                           id="preco_minimo_compra" 
                                           class="form-control money" 
                                           placeholder="0,00"
                                           value="<?= isset($dados->preco_minimo_compra) ? number_format($dados->preco_minimo_compra, 2, ',', '.') : '' ?>">
                                </div>
                                <small class="form-text text-muted">Valor mínimo para finalizar pedido</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="entrega_ate">Entrega em até (minutos)</label>
                                <div class="input-group">
                                    <input type="number" 
                                           id="entrega_ate" 
                                           class="form-control" 
                                           placeholder="Ex: 45"
                                           min="0"
                                           value="<?= esc($dados->entrega_ate ?? '') ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">min</span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Tempo estimado de entrega exibido ao cliente após finalizar pedido</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Negociação de Entrega</label>
                                <div class="d-flex align-items-center mt-1" style="gap:12px;">
                                    <label class="switch mb-0">
                                        <input type="checkbox" id="negociacao_entrega" <?= !empty($dados->negociacao_entrega) ? 'checked' : '' ?>>
                                        <span class="slider round"></span>
                                    </label>
                                    <small class="text-muted">Quando ativo, permite pedidos fora da área de cobertura com taxa negociada diretamente com o cliente</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Modo de Cadastro do Cliente</label>
                                <small class="form-text text-muted d-block mb-2">Define como os clientes se identificam para realizar pedidos</small>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="card border <?= ($dados->modo_cadastro ?? 1) == 1 ? 'border-primary' : 'border-secondary' ?>" id="card-modo-1" style="cursor:pointer;" onclick="selecionarModo(1)">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <input type="radio" name="modo_cadastro" id="modo_1" value="1" <?= ($dados->modo_cadastro ?? 1) == 1 ? 'checked' : '' ?> style="margin-right:8px;">
                                                    <strong>Modo 1 — Completo com verificação</strong>
                                                </div>
                                                <small class="text-muted">Cadastro com e-mail verificado por código, CEP, bairro, logradouro e número. Igual ao fluxo atual.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="card border <?= ($dados->modo_cadastro ?? 1) == 2 ? 'border-primary' : 'border-secondary' ?>" id="card-modo-2" style="cursor:pointer;" onclick="selecionarModo(2)">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <input type="radio" name="modo_cadastro" id="modo_2" value="2" <?= ($dados->modo_cadastro ?? 1) == 2 ? 'checked' : '' ?> style="margin-right:8px;">
                                                    <strong>Modo 2 — Cadastro simplificado</strong>
                                                </div>
                                                <small class="text-muted">Sem verificação de e-mail. Exige apenas nome, celular, cidade, bairro, logradouro e número.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="card border <?= ($dados->modo_cadastro ?? 1) == 3 ? 'border-primary' : 'border-secondary' ?>" id="card-modo-3" style="cursor:pointer;" onclick="selecionarModo(3)">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <input type="radio" name="modo_cadastro" id="modo_3" value="3" <?= ($dados->modo_cadastro ?? 1) == 3 ? 'checked' : '' ?> style="margin-right:8px;">
                                                    <strong>Modo 3 — Compra simplória</strong>
                                                </div>
                                                <small class="text-muted">Popup na home exige apenas nome e celular. No carrinho, cliente escolhe bairro/cidade da área de cobertura.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-success" id="btnSalvar">
                            <i class="fas fa-save mr-2"></i>Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>

<script>
function selecionarModo(modo) {
    document.getElementById('modo_' + modo).checked = true;
    [1,2,3].forEach(function(m) {
        var card = document.getElementById('card-modo-' + m);
        card.classList.remove('border-primary');
        card.classList.add('border-secondary');
    });
    var sel = document.getElementById('card-modo-' + modo);
    sel.classList.remove('border-secondary');
    sel.classList.add('border-primary');
}

$(document).ready(function() {
    // Máscara para telefone
    $('#numero').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            if (value.length < 14) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            }
        }
        $(this).val(value);
    });

    // Máscara para WhatsApp
    $('#whatsapp').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            if (value.length < 14) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            }
        }
        $(this).val(value);
    });

    // Máscara para CEP
    $('#cep').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 8) {
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
        }
        $(this).val(value);
    });

    // Salvar dados
    $('#btnSalvar').click(function() {
        const dados = {
            endereco: $('#endereco').val(),
            cep: $('#cep').val().replace(/\D/g, ''),
            numero: $('#numero').val(),
            whatsapp: $('#whatsapp').val().replace(/\D/g, ''),
            email: $('#email').val(),
            instagram: $('#instagram').val(),
            facebook: $('#facebook').val(),
            preco_minimo_compra: $('#preco_minimo_compra').val().replace(/\./g, '').replace(',', '.'),
            entrega_ate: $('#entrega_ate').val(),
            negociacao_entrega: $('#negociacao_entrega').is(':checked') ? 1 : 0,
            modo_cadastro: $('input[name="modo_cadastro"]:checked').val() || 1
        };

        $.ajax({
            url: '<?= site_url('admin/dados-corporativos/atualizar') ?>',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(dados),
            beforeSend: function() {
                $('#btnSalvar').prop('disabled', true).text('Salvando...');
            },
            success: function(response) {
                $('#btnSalvar').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Salvar Alterações');
                
                if (response.sucesso) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Dados atualizados com sucesso!');
                    } else {
                        alert('Dados atualizados com sucesso!');
                    }
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Erro: ' + response.msg);
                    } else {
                        alert('Erro: ' + response.msg);
                    }
                }
            },
            error: function() {
                $('#btnSalvar').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Salvar Alterações');
                
                if (typeof toastr !== 'undefined') {
                    toastr.error('Erro ao salvar dados');
                } else {
                    alert('Erro ao salvar dados');
                }
            }
        });
    });
});
</script>

<?php echo $this->endSection(); ?>
