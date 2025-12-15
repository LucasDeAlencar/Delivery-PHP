<?php

if (!function_exists('enviarEmailPedido')) {
    function enviarEmailPedido($dadosPedido)
    {
        $email = \Config\Services::email();
        
        $email->setTo($dadosPedido['email_cliente']);
        $email->setSubject('Confirmação do Pedido #' . $dadosPedido['id_pedido']);
        
        $mensagem = view('emails/pedido_confirmado', $dadosPedido);
        $email->setMessage($mensagem);
        
        return $email->send();
    }
}

if (!function_exists('enviarEmailStatusPedido')) {
    function enviarEmailStatusPedido($dadosPedido, $novoStatus)
    {
        $email = \Config\Services::email();
        
        $email->setTo($dadosPedido['email_cliente']);
        $email->setSubject('Atualização do Pedido #' . $dadosPedido['id_pedido']);
        
        $dados = array_merge($dadosPedido, ['novo_status' => $novoStatus]);
        $mensagem = view('emails/status_pedido', $dados);
        $email->setMessage($mensagem);
        
        return $email->send();
    }
}
