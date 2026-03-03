<?php
echo "PHP funcionando!<br>";

// Teste 1: Autoload
try {
    require_once '../vendor/autoload.php';
    echo "Autoload OK<br>";
} catch (Exception $e) {
    echo "Erro no autoload: " . $e->getMessage() . "<br>";
    exit;
}

// Teste 2: CodeIgniter
try {
    $app = \Config\Services::codeigniter();
    echo "CodeIgniter Services OK<br>";
} catch (Exception $e) {
    echo "Erro no CodeIgniter Services: " . $e->getMessage() . "<br>";
    exit;
}

// Teste 3: Initialize
try {
    $app->initialize();
    echo "CodeIgniter Initialize OK<br>";
} catch (Exception $e) {
    echo "Erro no Initialize: " . $e->getMessage() . "<br>";
    exit;
}

echo "Tudo funcionando!";
?>
