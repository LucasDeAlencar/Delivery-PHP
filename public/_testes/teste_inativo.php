<?php
/**
 * Script de teste para verificar a lógica de inativação de pedidos
 */

// Definir fuso horário de Brasília
date_default_timezone_set('America/Sao_Paulo');

// Configuração do banco
$host = 'localhost';
$dbname = 'food';
$user = 'root';
$pass = 'Legnu.131807';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

echo "<h1>Teste de Inativação de Pedidos</h1>";
echo "<style>body{font-family:Arial;padding:20px;background:#1a1a1a;color:#fff;} table{border-collapse:collapse;width:100%;margin:10px 0;} th,td{border:1px solid #444;padding:8px;text-align:left;} th{background:#333;} .pendente{color:#ffc107;} .confirmado{color:#17a2b8;} .inativo{color:#888;} .cancelado{color:#dc3545;} .entregue{color:#28a745;} a.btn{display:inline-block;padding:10px 20px;margin:5px;text-decoration:none;border-radius:5px;}</style>";

// Constantes - 1 hora para inativar
$TEMPO_INATIVO = 60; // minutos

$agora = date('Y-m-d H:i:s');
$limiteInativo = date('Y-m-d H:i:s', strtotime("-{$TEMPO_INATIVO} minutes"));

echo "<h2>Configurações</h2>";
echo "<p><strong>Agora:</strong> {$agora}</p>";
echo "<p><strong>Limite para Inativar (1 hora):</strong> {$limiteInativo}</p>";

// Buscar pedidos que deveriam ser inativos (NULL ou pendente, criados há mais de 1 hora)
echo "<h2>Pedidos que deveriam virar Inativos</h2>";
$stmt = $pdo->prepare("SELECT id, codigo, status, criado_em, atualizado_em, deletado_em 
                       FROM pedidos 
                       WHERE (status IS NULL OR status = '' OR status = 'pendente')
                       AND criado_em < ? 
                       AND deletado_em IS NULL
                       AND status != 'inativo'");
$stmt->execute([$limiteInativo]);
$paraInativar = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($paraInativar)) {
    echo "<p style='color:#888;'>Nenhum pedido encontrado que deveria ser inativo.</p>";
} else {
    echo "<table><tr><th>ID</th><th>Código</th><th>Status</th><th>Criado Em</th><th>Atualizado Em</th></tr>";
    foreach ($paraInativar as $p) {
        echo "<tr class='pendente'><td>{$p['id']}</td><td>{$p['codigo']}</td><td>'" . ($p['status'] ?? 'NULL') . "'</td><td>{$p['criado_em']}</td><td>{$p['atualizado_em']}</td></tr>";
    }
    echo "</table>";
}

// Listar todos os pedidos
echo "<h2>Todos os Pedidos (últimos 20)</h2>";
$stmt = $pdo->query("SELECT id, codigo, status, criado_em, atualizado_em, deletado_em, inativo_em FROM pedidos ORDER BY id DESC LIMIT 20");
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table><tr><th>ID</th><th>Código</th><th>Status</th><th>Criado Em</th><th>Atualizado Em</th><th>Deletado Em</th><th>Inativo Em</th></tr>";
foreach ($todos as $t) {
    $class = strtolower(trim($t['status'] ?? 'pendente'));
    echo "<tr class='{$class}'><td>{$t['id']}</td><td>{$t['codigo']}</td><td>{$t['status']}</td><td>{$t['criado_em']}</td><td>{$t['atualizado_em']}</td><td>{$t['deletado_em']}</td><td>{$t['inativo_em']}</td></tr>";
}
echo "</table>";

// Forçar inativação
if (isset($_GET['forcar'])) {
    echo "<h2>Forçando Inativação...</h2>";
    
    // Query simples - pedidos NULL ou pendente criados há mais de 1 hora
    $sql = "UPDATE pedidos 
            SET status = 'inativo', inativo_em = NOW() 
            WHERE (status IS NULL OR status = '' OR status = 'pendente')
            AND criado_em < DATE_SUB(NOW(), INTERVAL {$TEMPO_INATIVO} MINUTE)
            AND deletado_em IS NULL
            AND status != 'inativo'";
    echo "<p style='color:#888;font-size:12px;'>SQL: {$sql}</p>";
    $affected = $pdo->exec($sql);
    echo "<p style='color:" . ($affected > 0 ? '#28a745' : '#ffc107') . ";'><strong>Total alterados: {$affected}</strong></p>";
}

// Forçar inativação de um pedido específico
if (isset($_GET['inativar_id'])) {
    $id = (int)$_GET['inativar_id'];
    echo "<h2>Inativando pedido #{$id}...</h2>";
    $sql = "UPDATE pedidos SET status = 'inativo', inativo_em = '{$agora}' WHERE id = {$id}";
    echo "<p style='color:#888;font-size:12px;'>SQL: {$sql}</p>";
    $affected = $pdo->exec($sql);
    echo "<p style='color:" . ($affected > 0 ? '#28a745' : '#dc3545') . ";'>Resultado: {$affected} linha(s) alterada(s)</p>";
}

// Criar pedido de teste
if (isset($_GET['criar_teste'])) {
    $minutos = (int)($_GET['minutos'] ?? 35);
    $criadoEm = date('Y-m-d H:i:s', strtotime("-{$minutos} minutes"));
    $codigo = 'PED-TESTE-' . time();
    
    try {
        $stmt = $pdo->prepare("INSERT INTO pedidos (codigo, nome_cliente, telefone_cliente, endereco_entrega, forma_pagamento, valor_produtos, valor_entrega, valor_total, status, criado_em, atualizado_em) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendente', ?, ?)");
        $stmt->execute([$codigo, 'Cliente Teste', '11999999999', 'Rua Teste, 123', 'dinheiro', 50.00, 0.00, 50.00, $criadoEm, $criadoEm]);
    } catch (PDOException $e) {
        echo "<p style='color:#dc3545;'>Erro ao criar pedido: " . $e->getMessage() . "</p>";
    }
    
    echo "<p style='color:#28a745;'>Pedido de teste criado: {$codigo} com criado_em = {$criadoEm} ({$minutos} minutos atrás)</p>";
}

// Mostrar estrutura da tabela
echo "<h2>Estrutura do campo STATUS</h2>";
$stmt = $pdo->query("SHOW COLUMNS FROM pedidos WHERE Field = 'status'");
$col = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<pre>" . print_r($col, true) . "</pre>";

// Verificar valores únicos de status
echo "<h2>Valores únicos de STATUS no banco</h2>";
$stmt = $pdo->query("SELECT DISTINCT status, HEX(status) as hex_status FROM pedidos");
$statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<table><tr><th>Status</th><th>HEX</th></tr>";
foreach ($statuses as $s) {
    echo "<tr><td>'{$s['status']}'</td><td>{$s['hex_status']}</td></tr>";
}
echo "</table>";

echo "<h2>Ações</h2>";
echo "<a href='teste_inativo.php?forcar=1' class='btn' style='background:#f8b531;color:#000;'>Forçar Inativação (Automática)</a>";
echo "<a href='teste_inativo.php?criar_teste=1&minutos=65' class='btn' style='background:#dc3545;color:#fff;'>Criar Pedido (65 min atrás - deve inativar)</a>";
echo "<a href='teste_inativo.php?criar_teste=1&minutos=30' class='btn' style='background:#17a2b8;color:#fff;'>Criar Pedido (30 min atrás)</a>";
echo "<a href='teste_inativo.php?criar_teste=1&minutos=5' class='btn' style='background:#28a745;color:#fff;'>Criar Pedido (5 min atrás)</a>";
echo "<a href='teste_inativo.php' class='btn' style='background:#6c757d;color:#fff;'>Recarregar</a>";

// Botões para inativar pedidos específicos
echo "<h3>Inativar Pedido Específico</h3>";
foreach ($todos as $t) {
    if (strtolower(trim($t['status'])) !== 'inativo') {
        echo "<a href='teste_inativo.php?inativar_id={$t['id']}' class='btn' style='background:#b22222;color:#fff;margin:2px;'>Inativar #{$t['id']}</a>";
    }
}
