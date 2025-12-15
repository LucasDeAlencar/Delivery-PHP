<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333; text-align: center;">Pedido Confirmado!</h2>
        
        <p>Olá <strong><?= $nome_cliente ?></strong>,</p>
        
        <p>Seu pedido foi confirmado com sucesso e já está sendo preparado.</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #333;">Detalhes do Pedido</h3>
            <p><strong>Número:</strong> #<?= $id_pedido ?></p>
            <p><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>
            <p><strong>Forma de Pagamento:</strong> <?= $forma_pagamento ?></p>
        </div>
        
        <p>Obrigado pela preferência!</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #666; text-align: center;">
            Este é um email automático, não responda.
        </p>
    </div>
</body>
</html>
