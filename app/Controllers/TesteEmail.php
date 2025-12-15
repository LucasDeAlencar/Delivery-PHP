<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TesteEmail extends Controller
{
    public function index()
    {
        echo "<h2>Teste de Email via CodeIgniter</h2>";
        
        try {
            $emailService = \Config\Services::email();
            
            $emailService->setFrom('lucas130333@gmail.com', 'Teste');
            $emailService->setTo('lucas130333@gmail.com');
            $emailService->setSubject('Teste CodeIgniter - ' . date('H:i:s'));
            
            $corpo = "Teste de Email\nEnviado em " . date('d/m/Y H:i:s');
            $emailService->setMessage($corpo);
            
            if ($emailService->send()) {
                echo "<p style='color: green;'>✅ Email enviado com sucesso!</p>";
            } else {
                echo "<p style='color: red;'>❌ Erro ao enviar:</p>";
                echo "<pre>" . $emailService->printDebugger(['headers']) . "</pre>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Exceção: " . $e->getMessage() . "</p>";
        }
    }
}
