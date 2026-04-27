<?php
// Test through web flow
require 'index.php';

use Config\Database;
use App\Models\ProdutoModel;
use App\Models\TamanhoProdutoModel;
use App\Models\TamanhoModel;

echo "=== Web Flow Test ===\n\n";

try {
    $db = Database::connect();
    
    // Clean up any previous test data
    $db->table('produtos_tamanhos')->where('produto_id >', 1000)->delete();
    $db->table('produtos')->where('id >', 1000)->delete();
    
    $produtoModel = new ProdutoModel();
    $tamanhoProdutoModel = new TamanhoProdutoModel();
    
    // Create test product
    echo "1. Creating test product with sizes...\n";
    $produtoId = $produtoModel->criarProduto([
        'nome' => 'Pizza Teste Web ' . time(),
        'categoria_id' => 1,
        'ingredientes' => 'Teste de integração',
        'preco' => 0,
        'ativo' => 1,
        'com_tamanho' => 1
    ]);
    
    if (!$produtoId) {
        echo "   ✗ Failed: " . json_encode($produtoModel->errors()) . "\n";
        exit(1);
    }
    echo "   ✓ Product created: ID $produtoId\n";
    
    // Add sizes
    echo "\n2. Adding sizes...\n";
    $sizes = [
        ['tamanho_id' => 2, 'preco' => 25.00],
        ['tamanho_id' => 3, 'preco' => 35.00],
        ['tamanho_id' => 4, 'preco' => 45.00]
    ];
    
    foreach ($sizes as $size) {
        $id = $tamanhoProdutoModel->insert([
            'produto_id' => $produtoId,
            'tamanho_id' => $size['tamanho_id'],
            'preco' => $size['preco'],
            'ativo' => 1
        ]);
        echo "   ✓ Size added: ID $id\n";
    }
    
    // Verify sizes
    echo "\n3. Verifying sizes...\n";
    $sizesFound = $produtoModel->getTamanhosProduto($produtoId);
    if (count($sizesFound) === 3) {
        echo "   ✓ Found " . count($sizesFound) . " sizes\n";
        foreach ($sizesFound as $s) {
            echo "     - {$s->tamanho_nome}: R$ {$s->preco}\n";
        }
    } else {
        echo "   ✗ Expected 3 sizes, found " . count($sizesFound) . "\n";
        exit(1);
    }
    
    // Verify product has sizes
    echo "\n4. Checking product has sizes...\n";
    $hasSizes = $produtoModel->produtoTemTamanhos($produtoId);
    if ($hasSizes) {
        echo "   ✓ Product has sizes\n";
    } else {
        echo "   ✗ Product should have sizes\n";
        exit(1);
    }
    
    // Test cart API integration
    echo "\n5. Testing cart API price retrieval...\n";
    $sizePrice = $tamanhoProdutoModel->buscaPorProdutoETamanho($produtoId, 3);
    if ($sizePrice && $sizePrice->preco == 35.00) {
        echo "   ✓ Size price correct: R$ {$sizePrice->preco}\n";
    } else {
        echo "   ✗ Size price incorrect\n";
        exit(1);
    }
    
    // Verify product data
    echo "\n6. Verifying product data...\n";
    $produto = $produtoModel->find($produtoId);
    if ($produto->com_tamanho == 1) {
        echo "   ✓ Product marked as having sizes\n";
    } else {
        echo "   ✗ Product should be marked as having sizes\n";
        exit(1);
    }
    
    echo "\n=== ALL TESTS PASSED ===\n";
    echo "\nProduct ID: $produtoId\n";
    
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
