<?php
// Dentro de um Controller no CodeIgniter
$emailService = \Config\Services::email();

$emailService->setFrom('lucas130333@gmail.com', 'Teste CI4');
$emailService->setTo('nookapricho@gmail.com');
$emailService->setSubject('Teste Final SMTP CI4');
$emailService->setMessage('Verificação de quebra de linha correta.');

if ($emailService->send()) {
    echo "Sucesso!";
} else {
    echo $emailService->printDebugger();
}
