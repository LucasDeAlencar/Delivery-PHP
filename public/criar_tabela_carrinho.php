<?php
// Cria a tabela carrinho_temporario
require_once '../vendor/autoload.php';

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'codeigniter4' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);
define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('WRITEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR);

$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();

echo "<h2>Criando Tabela carrinho_temporario</h2>";

$sql = "
CREATE TABLE IF NOT EXISTS `carrinho_temporario` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

try {
    $db->query($sql);
    echo "<p style='color: green;'>✅ Tabela 'carrinho_temporario' criada com sucesso!</p>";
    
    // Verifica se a tabela foi criada
    $tables = $db->query("SHOW TABLES LIKE 'carrinho_temporario'")->getResult();
    if (count($tables) > 0) {
        echo "<p>✅ Tabela confirmada no banco de dados</p>";
        
        // Mostra a estrutura da tabela
        $structure = $db->query("DESCRIBE carrinho_temporario")->getResult();
        echo "<h3>Estrutura da Tabela:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($structure as $field) {
            echo "<tr>";
            echo "<td>{$field->Field}</td>";
            echo "<td>{$field->Type}</td>";
            echo "<td>{$field->Null}</td>";
            echo "<td>{$field->Key}</td>";
            echo "<td>{$field->Default}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='teste_associacao.php'>Voltar ao teste de associação</a></p>";
?>
