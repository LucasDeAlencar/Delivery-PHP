<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Contato extends Controller
{
    public function enviar()
    {
        $nome = $this->request->getPost('nome');
        $emailDestino = $this->request->getPost('email');
        $telefone = $this->request->getPost('telefone');
        $mensagem = $this->request->getPost('mensagem');
        
        if (empty($nome) || empty($emailDestino) || empty($telefone) || empty($mensagem)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
        }
        
        if (!filter_var($emailDestino, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email inválido']);
        }
        
        // Salvar email em arquivo (simulação para desenvolvimento)
        $emailData = [
            'data' => date('d/m/Y H:i:s'),
            'de' => 'lucas130333@gmail.com',
            'para' => $emailDestino,
            'assunto' => 'Confirmação de contato - No Kapricho Pizzaria',
            'nome' => $nome,
            'telefone' => $telefone,
            'mensagem' => $mensagem
        ];
        
        $logFile = WRITEPATH . 'logs/emails_enviados.txt';
        $logContent = "\n=== EMAIL ENVIADO ===\n";
        $logContent .= "Data: " . $emailData['data'] . "\n";
        $logContent .= "De: " . $emailData['de'] . "\n";
        $logContent .= "Para: " . $emailData['para'] . "\n";
        $logContent .= "Assunto: " . $emailData['assunto'] . "\n";
        $logContent .= "Nome: " . $emailData['nome'] . "\n";
        $logContent .= "Telefone: " . $emailData['telefone'] . "\n";
        $logContent .= "Mensagem: " . $emailData['mensagem'] . "\n";
        $logContent .= "==================\n";
        
        file_put_contents($logFile, $logContent, FILE_APPEND | LOCK_EX);
        
        return $this->response->setJSON(['success' => true, 'message' => 'Mensagem registrada! (Email salvo em logs para desenvolvimento)']);
    }
}
