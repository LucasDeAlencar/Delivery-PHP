<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido <?= esc($pedido->codigo) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            line-height: 1.3;
            padding: 15px;
            max-width: 80mm;
            margin: 0 auto;
            background: #fff;
            color: #000;
        }
        
        .container {
            background: white;
            padding: 10px;
            border: 1px solid #000;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #000;
        }

        .logo {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .header-info {
            font-size: 9px;
        }

        .pedido-codigo {
            text-align: center;
            margin: 8px 0;
            padding: 6px;
            border: 1px solid #000;
        }

        .pedido-codigo-numero {
            font-size: 14px;
            font-weight: 700;
        }

        .pedido-data {
            font-size: 9px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #000;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .section {
            margin: 8px 0;
            padding: 6px 0;
            border-bottom: 1px dashed #ccc;
        }

        .section:last-of-type {
            border-bottom: none;
        }

        .label {
            font-weight: 700;
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .content {
            font-size: 10px;
            margin-left: 2px;
        }

        .item {
            margin: 4px 0;
            padding: 4px 0;
            border-bottom: 1px dotted #ddd;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-linha {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .item-nome {
            font-weight: 600;
            font-size: 10px;
        }

        .item-obs {
            font-size: 9px;
            margin-left: 4px;
            font-style: italic;
            color: #000;
        }

        .tamanho-info {
            font-weight: 600;
            color: #000;
        }

        .total-section {
            margin-top: 8px;
            padding: 6px;
            border: 1px solid #000;
        }

        .total-linha {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            font-size: 10px;
        }

        .total-final {
            font-size: 14px;
            font-weight: 700;
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px solid #000;
        }

        .footer {
            text-align: center;
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px dashed #000;
            font-size: 9px;
        }

        .footer-destaque {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        /* Botões para tela */
        .no-print {
            text-align: center;
            margin-top: 15px;
        }

        .btn {
            padding: 8px 20px;
            font-size: 12px;
            cursor: pointer;
            border: 2px solid #000;
            background: white;
            color: #000;
            font-weight: 600;
            margin: 0 5px;
            transition: all 0.3s;
        }

        .btn:hover {
            background: #000;
            color: white;
        }

        .btn-primary {
            background: #000;
            color: white;
        }

        /* Impressão */
        @media print {
            body {
                padding: 0;
                background: white !important;
                font-size: 9px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            * {
                color: #000 !important;
                background: transparent !important;
                border-color: #000 !important;
                text-shadow: none !important;
                box-shadow: none !important;
            }

            .container {
                box-shadow: none;
                padding: 5px;
                border: 1px solid #000;
            }

            .no-print,
            .btn {
                display: none !important;
            }

            .section {
                break-inside: avoid;
            }

            .item-obs {
                color: #000 !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabeçalho -->
        <div class="header">
            <div class="logo">Space Burger Dog Do Paulista</div>
            <div class="header-info">Tel: (11) 9999-9999</div>
        </div>

        <!-- Código do Pedido -->
        <div class="pedido-codigo">
            <div class="pedido-codigo-numero">#<?= esc($pedido->codigo) ?></div>
            <div class="pedido-data"><?= date('d/m/Y H:i', strtotime($pedido->criado_em)) ?></div>
            <span class="status-badge"><?= strtoupper(str_replace('_', ' ', $pedido->status)) ?></span>
        </div>

        <!-- Mesa -->
        <?php if (!empty($pedido->mesa_id)): ?>
        <div class="section">
            <div class="label">MESA</div>
            <div class="content">
                <?= esc($pedido->mesa_numero ?? $pedido->mesa_id) ?>
            </div>
        </div>
        <?php endif; ?>

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
            <div class="label">ENDEREÇO</div>
            <div class="content">
                <?= esc($pedido->endereco_entrega) ?>
                <?php if (!empty($pedido->complemento)): ?>
                    - <?= esc($pedido->complemento) ?>
                <?php endif; ?>
                <?php if (!empty($pedido->bairro_nome)): ?>
                    <br>Bairro: <?= esc($pedido->bairro_nome) ?>
                <?php endif; ?>
            </div>
        </div>

         <!-- Itens -->
         <div class="section">
             <div class="label">ITENS</div>
             <?php foreach ($itens as $item): ?>
                 <div class="item">
                     <div class="item-linha">
                         <span class="item-nome"><?= $item->quantidade ?>x <?= esc($item->produto_nome) ?></span>
                         <span>R$ <?= number_format($item->preco_total, 2, ',', '.') ?></span>
                     </div>
                     <?php if (!empty($item->tamanho_nome)): ?>
                         <div class="item-obs" style="font-weight: 600;">
                             <i class="fas fa-ruler-combined"></i> <?= esc($item->tamanho_nome) ?>
                             <?php if (!empty($item->tamanho_preco) && $item->tamanho_preco > 0): ?>
                                 - R$ <?= number_format($item->tamanho_preco, 2, ',', '.') ?>
                             <?php endif; ?>
                         </div>
                     <?php endif; ?>
                     <?php if (!empty($item->extras)): ?>
                         <?php foreach ($item->extras as $extra): ?>
                             <div class="item-obs">
                                 + <?= esc($extra->extra_nome) ?><?= $extra->quantidade > 1 ? ' x' . $extra->quantidade : '' ?>
                                 <?php if ($extra->extra_preco > 0): ?>
                                     (+R$ <?= number_format($extra->extra_preco * $extra->quantidade, 2, ',', '.') ?>)
                                 <?php endif; ?>
                             </div>
                         <?php endforeach; ?>
                     <?php endif; ?>
                     <?php if (!empty($item->observacoes)): ?>
                         <div class="item-obs">* <?= esc($item->observacoes) ?></div>
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
            <div class="item-linha">
                <span><?= esc($s->sache_nome) ?> x<?= $s->quantidade ?>
                    <?php if ($s->quantidade_gratuita > 0): ?>(<?= $s->quantidade_gratuita ?> grátis)<?php endif; ?>
                </span>
                <span><?= $s->preco_total > 0 ? 'R$ ' . number_format($s->preco_total, 2, ',', '.') : 'Grátis' ?></span>
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
            <?php
            $valorSaches = 0;
            if (!empty($saches)) { foreach ($saches as $s) { $valorSaches += $s->preco_total; } }
            if ($valorSaches > 0): ?>
            <div class="total-linha">
                <span>Sachês:</span>
                <span>R$ <?= number_format($valorSaches, 2, ',', '.') ?></span>
            </div>
            <?php endif; ?>
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
                    <br>Troco: R$ <?= number_format(max(0, $pedido->troco_para - $pedido->valor_total), 2, ',', '.') ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="footer">
            <div class="footer-destaque">Obrigado pela preferência!</div>
            <div>Space Burger Dog Do Paulista - Volte sempre!</div>
        </div>

        <!-- Botões -->
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-primary">Imprimir</button>
            <button onclick="window.close()" class="btn">Fechar</button>
        </div>
    </div>
</body>
</html>
