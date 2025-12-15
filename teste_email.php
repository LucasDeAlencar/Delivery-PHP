<?php

require_once 'vendor/autoload.php';

// Configurar CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

try {
    $email = \Config\Services::email();
    
    $email->setTo('destinatario@email.com'); // ALTERE AQUI
    $email->setSubject('Confirmação de Configuração - Sistema de Delivery');
    
    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #28a745; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px; }
            .success { color: #28a745; font-weight: bold; }
            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🚀 Sistema de Delivery</h1>
            </div>
            <div class="content">
                <h2 class="success">✅ Configuração de Email Validada</h2>
                <p>Parabéns! O sistema de envio de emails foi configurado com sucesso.</p>
                <p><strong>Detalhes da configuração:</strong></p>
                <ul>
                    <li>Servidor SMTP: Gmail</li>
                    <li>Protocolo: TLS/587</li>
                    <li>Status: Operacional</li>
                </ul>
                <p>O sistema está pronto para enviar notificações de pedidos, confirmações e atualizações aos clientes.</p>
            </div>
            <div class="footer">
                <p>Sistema de Delivery - Teste de Configuração</p>
            </div>
        </div>
    </body>
    </html>';
    
    $email->setMessage($message);
    
    if ($email->send()) {
        echo "✅ Email enviado com sucesso!\n";
    } else {
        echo "❌ Erro ao enviar email:\n";
        echo $email->printDebugger(['headers']);
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
