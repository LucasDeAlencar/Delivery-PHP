<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo - Pedido <?= esc($pedido->codigo) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.5;
            padding: 30px;
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            color: #000;
        }
        
        .container {
            background: white;
            padding: 30px;
            border: 2px solid #000;
            max-width: 700px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #000;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header-info {
            font-size: 12px;
        }

        .pedido-codigo {
            text-align: center;
            margin: 12px 0;
            padding: 10px;
            border: 2px solid #000;
        }

        .pedido-codigo-numero {
            font-size: 20px;
            font-weight: 700;
        }

        .pedido-data {
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border: 1px solid #000;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .section {
            margin: 12px 0;
            padding: 10px 0;
            border-bottom: 1px dashed #ccc;
        }

        .section:last-of-type {
            border-bottom: none;
        }

        .label {
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .content {
            font-size: 14px;
            margin-left: 3px;
        }

        .item {
            margin: 6px 0;
            padding: 6px 0;
            border-bottom: 1px dotted #ddd;
        }

        .item-linha {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .item-nome {
            font-weight: 600;
            font-size: 14px;
            flex: 1;
        }

        .item-obs {
            font-size: 11px;
            color: #666;
            margin-left: 15px;
        }

        .total-section {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 2px solid #000;
        }

        .total-linha {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin: 5px 0;
        }

        .total-final {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid #000;
        }

        .total-final .total-linha {
            font-size: 18px;
            font-weight: 700;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #000;
            font-size: 12px;
        }

        .footer-destaque {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabeçalho -->
        <div class="header">
            <div class="logo"><?= !empty($dadosCorporativos['nome_fantasia']) ? esc($dadosCorporativos['nome_fantasia']) : 'Space Burger Dog Do Paulista' ?></div>
            <div class="header-info">
                <?= !empty($dadosCorporativos['telefone']) ? esc($dadosCorporativos['telefone']) : 'Tel: (00) 00000-0000' ?>
                <?= !empty($dadosCorporativos['endereco']) ? '<br>' . esc($dadosCorporativos['endereco']) : '' ?>
            </div>
        </div>

        <!-- Código do Pedido -->
        <div class="pedido-codigo">
            <div class="pedido-codigo-numero">#<?= esc($pedido->codigo) ?></div>
            <div class="pedido-data"><?= date('d/m/Y H:i', strtotime($pedido->criado_em)) ?></div>
            <span class="status-badge"><?= strtoupper(str_replace('_', ' ', $pedido->status)) ?></span>
        </div>

        <!-- Cliente -->
        <div class="section">
            <div class="label">CLIENTE</div>
            <div class="content">
                <?= esc($pedido->nome_cliente) ?><br>
                Tel: <?= esc($pedido->telefone_cliente) ?>
            </div>
        </div>

        <!-- Endereço -->
        <div class="section">
            <div class="label"><?= $pedido->tipo_entrega === 'retirada' ? 'RETIRADA' : 'ENDEREÇO' ?></div>
            <div class="content">
                <?= $pedido->tipo_entrega === 'retirada' ? 'Retirada na loja' : esc($pedido->endereco_entrega) ?>
                <?php if (!empty($pedido->complemento)): ?>
                    - <?= esc($pedido->complemento) ?>
                <?php endif; ?>
                <?php if (!empty($bairroNome)): ?>
                    <br>Bairro: <?= esc($bairroNome) ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Itens -->
        <div class="section">
            <div class="label">ITENS</div>
            <?php foreach ($itens as $item): ?>
                <div class="item">
                    <div class="item-linha">
                        <span class="item-nome"><?= $item['quantidade'] ?>x <?= esc($item['produto_nome']) ?></span>
                        <span>R$ <?= number_format($item['preco_total'], 2, ',', '.') ?></span>
                    </div>
                    <?php if (!empty($extrasItens[$item['id']])): ?>
                        <?php foreach ($extrasItens[$item['id']] as $extra): ?>
                            <div class="item-obs">
                                + <?= esc($extra['extra_nome']) ?><?= $extra['quantidade'] > 1 ? ' x' . $extra['quantidade'] : '' ?>
                                <?php if ($extra['extra_preco'] > 0): ?>
                                    (+R$ <?= number_format($extra['extra_preco'] * $extra['quantidade'], 2, ',', '.') ?>)
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (!empty($item['observacoes'])): ?>
                        <div class="item-obs">* <?= esc($item['observacoes']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Observações do Pedido -->
        <?php if (!empty($pedido->observacoes)): ?>
            <div class="section">
                <div class="label">OBSERVAÇÕES</div>
                <div class="content"><?= nl2br(esc($pedido->observacoes)) ?></div>
            </div>
        <?php endif; ?>

        <!-- Sachês -->
        <?php if (!empty($saches)): ?>
            <div class="section">
                <div class="label">SACHÊS</div>
                <?php foreach ($saches as $s): ?>
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin:3px 0;">
                        <span><?= $s['quantidade'] ?>x <?= esc($s['sache_nome']) ?><?= $s['quantidade_paga'] > 0 ? ' (' . $s['quantidade_gratuita'] . ' grátis + ' . $s['quantidade_paga'] . ' pago' . ($s['quantidade_paga'] > 1 ? 's' : '') . ')' : ' (grátis)' ?></span>
                        <span><?= $s['preco_total'] > 0 ? 'R$ ' . number_format($s['preco_total'], 2, ',', '.') : '—' ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Total -->
        <div class="total-section">
            <div class="total-linha">
                <span>Subtotal:</span>
                <span>R$ <?= number_format($pedido->valor_produtos, 2, ',', '.') ?></span>
            </div>
            <div class="total-linha">
                <span>Entrega:</span>
                <span>R$ <?= number_format($pedido->valor_entrega, 2, ',', '.') ?></span>
            </div>
            <div class="total-final">
                <div class="total-linha">
                    <span>TOTAL:</span>
                    <span>R$ <?= number_format($pedido->valor_total, 2, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Pagamento -->
        <div class="section">
            <div class="label">PAGAMENTO</div>
            <div class="content">
                <?= esc(ucfirst($pedido->forma_pagamento)) ?>
                <?php if (strtolower($pedido->forma_pagamento) === 'dinheiro' && $pedido->troco_para > 0): ?>
                    <br>Troco p/ R$ <?= number_format($pedido->troco_para, 2, ',', '.') ?>
                    = R$ <?= number_format($pedido->troco_para - $pedido->valor_total, 2, ',', '.') ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="footer">
            <div class="footer-destaque">Obrigado pela preferência!</div>
            <div><?= !empty($dadosCorporativos['nome_fantasia']) ? esc($dadosCorporativos['nome_fantasia']) : 'Space Burger Dog Do Paulista' ?> - Volte sempre!</div>
        </div>
    </div>
</body>
</html>
