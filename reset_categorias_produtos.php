<?php

require_once 'vendor/autoload.php';

// Configurar o ambiente
putenv('CI_ENVIRONMENT=production');

// Inicializar o CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "=== RESET DE CATEGORIAS E PRODUTOS ===\n\n";

try {
    $db = \Config\Database::connect();
    
    // Perguntar se o usuário quer realmente resetar
    echo "ATENÇÃO: Este script irá remover TODOS os produtos e categorias existentes!\n";
    echo "Tem certeza que deseja continuar? (digite 'SIM' para confirmar): ";
    $handle = fopen("php://stdin", "r");
    $confirmacao = trim(fgets($handle));
    fclose($handle);
    
    if ($confirmacao !== 'SIM') {
        echo "Operação cancelada.\n";
        exit;
    }
    
    echo "\n1. Removendo produtos existentes...\n";
    $db->query("DELETE FROM produtos");
    echo "✓ Produtos removidos.\n";
    
    echo "\n2. Removendo categorias existentes...\n";
    $db->query("DELETE FROM categorias");
    echo "✓ Categorias removidas.\n";
    
    echo "\n3. Resetando auto_increment...\n";
    $db->query("ALTER TABLE produtos AUTO_INCREMENT = 1");
    $db->query("ALTER TABLE categorias AUTO_INCREMENT = 1");
    echo "✓ Auto_increment resetado.\n";
    
    echo "\n4. Executando seeder para recriar dados...\n";
    $seeder = \Config\Database::seeder();
    $seeder->call('ProdutoSeeder');
    
    echo "\n5. Verificando resultado final...\n";
    
    // Verificar categorias criadas
    $query = $db->query("SELECT * FROM categorias WHERE ativo = 1 ORDER BY nome");
    $categorias = $query->getResult();
    
    echo "Categorias criadas:\n";
    foreach ($categorias as $cat) {
        echo "- " . $cat->nome . " (slug: " . $cat->slug . ")\n";
    }
    
    // Verificar produtos por categoria
    $query = $db->query("
        SELECT c.nome as categoria, COUNT(p.id) as total 
        FROM categorias c 
        LEFT JOIN produtos p ON c.id = p.categoria_id AND p.ativo = 1 
        WHERE c.ativo = 1 
        GROUP BY c.id, c.nome 
        ORDER BY c.nome
    ");
    $produtosPorCategoria = $query->getResult();
    
    echo "\nProdutos por categoria:\n";
    foreach ($produtosPorCategoria as $item) {
        echo "- " . $item->categoria . ": " . $item->total . " produtos\n";
    }
    
    echo "\n✓ Reset concluído com sucesso!\n";
    echo "Agora você pode acessar o site e ver as categorias dinâmicas funcionando.\n";
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}

echo "\n=== RESET CONCLUÍDO ===\n";