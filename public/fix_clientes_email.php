<?php
// SCRIPT TEMPORÁRIO - APAGAR APÓS USO
// Acesse: seusite.com/fix_clientes_email.php

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

require_once '../vendor/autoload.php';
$app = require_once '../app/Config/Boot/production.php';

$db = \Config\Database::connect();
$erros = [];
$ok = [];

// 1. Tornar email nullable
try {
    $db->query("ALTER TABLE clientes MODIFY COLUMN email VARCHAR(100) NULL DEFAULT NULL");
    $ok[] = "email agora é nullable";
} catch (\Exception $e) {
    $erros[] = "email nullable: " . $e->getMessage();
}

// 2. Remover unique key de email
try {
    $db->query("ALTER TABLE clientes DROP INDEX email");
    $ok[] = "unique key de email removida";
} catch (\Exception $e) {
    $erros[] = "drop index email: " . $e->getMessage();
}

// 3. Adicionar modo_cadastro se não existir
try {
    $colunas = $db->getFieldNames('clientes');
    if (!in_array('modo_cadastro', $colunas)) {
        $db->query("ALTER TABLE clientes ADD COLUMN modo_cadastro TINYINT(1) NOT NULL DEFAULT 1 AFTER complemento");
        $ok[] = "coluna modo_cadastro adicionada";
    } else {
        $ok[] = "coluna modo_cadastro já existe";
    }
} catch (\Exception $e) {
    $erros[] = "modo_cadastro: " . $e->getMessage();
}

echo "<pre>";
echo "=== RESULTADOS ===\n\n";
foreach ($ok as $msg) echo "✅ $msg\n";
foreach ($erros as $msg) echo "⚠️  $msg\n";
echo "\n=== APAGUE ESTE ARQUIVO APÓS USO ===\n";
echo "</pre>";
