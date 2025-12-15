<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Atualização do Pedido</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333; text-align: center;">Atualização do Pedido</h2>
        
        <p>Olá <strong><?= $nome_cliente ?></strong>,</p>
        
        <p>Seu pedido #<?= $id_pedido ?> foi atualizado.</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #333;">Status Atual</h3>
            <p style="font-size: 18px; color: #007bff;"><strong><?= $novo_status ?></strong></p>
        </div>
        
        <p>Obrigado pela preferência!</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #666; text-align: center;">
            Este é um email automático, não responda.
        </p>
    </div>
</body>
</html>
