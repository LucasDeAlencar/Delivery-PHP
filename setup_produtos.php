<?php

require_once 'vendor/autoload.php';

// Configurar o ambiente
putenv('CI_ENVIRONMENT=production');

// Inicializar o CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "=== SETUP DE PRODUTOS ===\n\n";

// 1. Executar migration para adicionar campo preço
echo "1. Executando migration para adicionar campo preço...\n";
try {
    $migrate = \Config\Services::migrations();
    $migrate->latest();
    echo "✓ Migration executada com sucesso!\n\n";
} catch (Exception $e) {
    echo "✗ Erro ao executar migration: " . $e->getMessage() . "\n\n";
}

// 2. Verificar estrutura da tabela
echo "2. Verificando estrutura da tabela produtos...\n";
try {
    $db = \Config\Database::connect();
    $query = $db->query("DESCRIBE produtos");
    $fields = $query->getResult();
    
    $temPreco = false;
    foreach ($fields as $field) {
        if ($field->Field === 'preco') {
            $temPreco = true;
            break;
        }
    }
    
    if ($temPreco) {
        echo "✓ Campo 'preco' encontrado na tabela produtos!\n\n";
    } else {
        echo "✗ Campo 'preco' NÃO encontrado na tabela produtos!\n\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erro ao verificar estrutura: " . $e->getMessage() . "\n\n";
}

// 3. Executar seeder para adicionar produtos de exemplo
echo "3. Executando seeder para adicionar produtos de exemplo...\n";
try {
    $seeder = \Config\Database::seeder();
    $seeder->call('ProdutoSeeder');
    echo "✓ Seeder executado com sucesso!\n\n";
} catch (Exception $e) {
    echo "✗ Erro ao executar seeder: " . $e->getMessage() . "\n\n";
}

// 4. Verificar produtos inseridos
echo "4. Verificando produtos inseridos...\n";
try {
    $db = \Config\Database::connect();
    $query = $db->query("SELECT COUNT(*) as total FROM produtos");
    $result = $query->getRow();
    
    echo "✓ Total de produtos na base: " . $result->total . "\n";
    
    if ($result->total > 0) {
        $query = $db->query("SELECT p.nome, p.preco, c.nome as categoria FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id LIMIT 5");
        $produtos = $query->getResult();
        
        echo "\nPrimeiros produtos:\n";
        echo "-------------------\n";
        foreach ($produtos as $produto) {
            $preco = $produto->preco ? 'R$ ' . number_format($produto->preco, 2, ',', '.') : 'Sem preço';
            echo "- " . $produto->nome . " (" . $produto->categoria . ") - " . $preco . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Erro ao verificar produtos: " . $e->getMessage() . "\n";
}

echo "\n=== SETUP CONCLUÍDO ===\n";