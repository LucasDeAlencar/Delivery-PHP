<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class EmailController extends Controller
{
    public function enviarEmail()
    {
        try {
            $email = \Config\Services::email();
            
            // ALTERE O EMAIL AQUI PARA TESTAR
            $email->setTo('seu-email@gmail.com');
            $email->setSubject('Teste de Email - Delivery System');
            $email->setMessage('<h1>Email de Teste</h1><p>Este é um email de teste do sistema de delivery.</p>');
            
            if ($email->send()) {
                return '✅ Email enviado com sucesso!';
            } else {
                return '❌ Erro ao enviar email: ' . $email->printDebugger(['headers']);
            }
        } catch (\Exception $e) {
            return '❌ Erro: ' . $e->getMessage();
        }
    }
    
    public function enviarEmailPedido($dadosPedido = null)
    {
        if (!$dadosPedido) {
            // Dados de exemplo para teste
            $dadosPedido = [
                'email_cliente' => 'cliente@email.com',
                'nome_cliente' => 'João Silva',
                'id_pedido' => '123',
                'total' => 45.90,
                'forma_pagamento' => 'Cartão de Crédito'
            ];
        }
        
        try {
            $email = \Config\Services::email();
            
            $email->setTo($dadosPedido['email_cliente']);
            $email->setSubject('Confirmação do Pedido #' . $dadosPedido['id_pedido']);
            
            $mensagem = view('emails/pedido_confirmado', $dadosPedido);
            $email->setMessage($mensagem);
            
            return $email->send();
        } catch (\Exception $e) {
            log_message('error', 'Erro ao enviar email: ' . $e->getMessage());
            return false;
        }
    }
}
