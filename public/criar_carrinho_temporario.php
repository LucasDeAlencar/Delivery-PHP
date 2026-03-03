<?php
// Criar tabela carrinho_temporario diretamente
try {
    $pdo = new PDO("mysql:host=localhost;dbname=food;charset=utf8", 'root', 'Legnu.131807');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE TABLE IF NOT EXISTS `carrinho_temporario` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `session_id` varchar(128) NOT NULL,
        `produto_id` int(11) unsigned NOT NULL,
        `produto_nome` varchar(255) NOT NULL,
        `produto_imagem` varchar(255) DEFAULT NULL,
        `quantidade` int(11) unsigned DEFAULT 1,
        `preco_unitario` decimal(10,2) NOT NULL,
        `preco_total` decimal(10,2) NOT NULL,
        `observacoes` text DEFAULT NULL,
        `criado_em` datetime NOT NULL,
        `atualizado_em` datetime NOT NULL,
        PRIMARY KEY (`id`),
        KEY `session_id` (`session_id`),
        KEY `produto_id` (`produto_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $pdo->exec($sql);
    echo "✅ Tabela 'carrinho_temporario' criada com sucesso!";
    
} catch (PDOException $e) {
    echo "❌ Erro ao criar tabela: " . $e->getMessage();
}
?>
