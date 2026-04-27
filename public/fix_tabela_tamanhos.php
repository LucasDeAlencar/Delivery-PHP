<?php
// Acesse via: http://localhost:8080/fix_tabela_tamanhos.php
$pdo = new PDO("mysql:host=localhost;dbname=food;charset=utf8mb4", "root", "Legnu.131807");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "<pre>";

// 1. Recria produtos_tamanhos com coluna 'nome'
$cols = array_column($pdo->query("SHOW COLUMNS FROM produtos_tamanhos")->fetchAll(PDO::FETCH_ASSOC), 'Field');
if (!in_array('nome', $cols)) {
    $pdo->exec("DROP TABLE IF EXISTS produtos_tamanhos");
    $pdo->exec("CREATE TABLE produtos_tamanhos (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        produto_id INT UNSIGNED NOT NULL,
        nome VARCHAR(64) NOT NULL,
        preco DECIMAL(10,2) NOT NULL,
        ativo TINYINT(1) NOT NULL DEFAULT 1,
        criado_em DATETIME NULL DEFAULT NULL,
        atualizado_em DATETIME NULL DEFAULT NULL,
        PRIMARY KEY (id),
        KEY produto_id (produto_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ produtos_tamanhos recriada com coluna 'nome'\n";
} else {
    echo "- produtos_tamanhos já tem coluna 'nome'\n";
}

// 2. com_tamanho em produtos
$cols = array_column($pdo->query("SHOW COLUMNS FROM produtos")->fetchAll(PDO::FETCH_ASSOC), 'Field');
if (!in_array('com_tamanho', $cols)) {
    $pdo->exec("ALTER TABLE produtos ADD COLUMN com_tamanho TINYINT(1) NOT NULL DEFAULT 0 AFTER max_extras");
    echo "✓ com_tamanho adicionado em produtos\n";
} else {
    echo "- com_tamanho já existe em produtos\n";
}

// 3. tamanho_nome e tamanho_preco em pedidos_itens
$cols = array_column($pdo->query("SHOW COLUMNS FROM pedidos_itens")->fetchAll(PDO::FETCH_ASSOC), 'Field');
if (!in_array('tamanho_nome', $cols)) {
    $pdo->exec("ALTER TABLE pedidos_itens ADD COLUMN tamanho_nome VARCHAR(64) NULL DEFAULT NULL AFTER observacoes");
    echo "✓ tamanho_nome adicionado em pedidos_itens\n";
} else {
    echo "- tamanho_nome já existe em pedidos_itens\n";
}
if (!in_array('tamanho_preco', $cols)) {
    $pdo->exec("ALTER TABLE pedidos_itens ADD COLUMN tamanho_preco DECIMAL(10,2) NULL DEFAULT NULL AFTER tamanho_nome");
    echo "✓ tamanho_preco adicionado em pedidos_itens\n";
} else {
    echo "- tamanho_preco já existe em pedidos_itens\n";
}

echo "\nPronto! Apague este arquivo após usar.\n</pre>";
