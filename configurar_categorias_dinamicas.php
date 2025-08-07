<?php

require_once 'vendor/autoload.php';

// Configurar o ambiente
putenv('CI_ENVIRONMENT=production');

// Inicializar o CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "=== CONFIGURAÇÃO DE CATEGORIAS DINÂMICAS ===\n\n";

try {
    $db = \Config\Database::connect();
    
    echo "1. Verificando estado atual...\n";
    
    // Verificar categorias
    $query = $db->query("SELECT COUNT(*) as total FROM categorias WHERE ativo = 1");
    $totalCategorias = $query->getRow()->total;
    
    // Verificar produtos
    $query = $db->query("SELECT COUNT(*) as total FROM produtos WHERE ativo = 1");
    $totalProdutos = $query->getRow()->total;
    
    echo "- Categorias ativas: " . $totalCategorias . "\n";
    echo "- Produtos ativos: " . $totalProdutos . "\n";
    
    if ($totalCategorias == 0 || $totalProdutos == 0) {
        echo "\n2. Executando seeder para criar dados...\n";
        $seeder = \Config\Database::seeder();
        $seeder->call('ProdutoSeeder');
        
        // Verificar novamente
        $query = $db->query("SELECT COUNT(*) as total FROM categorias WHERE ativo = 1");
        $totalCategorias = $query->getRow()->total;
        
        $query = $db->query("SELECT COUNT(*) as total FROM produtos WHERE ativo = 1");
        $totalProdutos = $query->getRow()->total;
        
        echo "Após seeder:\n";
        echo "- Categorias ativas: " . $totalCategorias . "\n";
        echo "- Produtos ativos: " . $totalProdutos . "\n";
    } else {
        echo "✓ Dados já existem, pulando seeder.\n";
    }
    
    echo "\n3. Testando carregamento de categorias...\n";
    $categoriaModel = new \App\Models\CategoriaModel();
    $categorias = $categoriaModel->where('ativo', true)->orderBy('nome', 'ASC')->findAll();
    
    if (!empty($categorias)) {
        echo "✓ Categorias carregadas com sucesso:\n";
        foreach ($categorias as $categoria) {
            echo "  - " . $categoria->nome . " (slug: " . $categoria->slug . ")\n";
        }
    } else {
        echo "✗ Nenhuma categoria foi carregada!\n";
        throw new Exception("Falha ao carregar categorias");
    }
    
    echo "\n4. Testando carregamento de produtos...\n";
    $produtoModel = new \App\Models\ProdutoModel();
    $produtos = $produtoModel->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug')
                            ->join('categorias', 'categorias.id = produtos.categoria_id')
                            ->where('produtos.ativo', true)
                            ->where('categorias.ativo', true)
                            ->orderBy('categorias.nome', 'ASC')
                            ->orderBy('produtos.nome', 'ASC')
                            ->findAll();
    
    if (!empty($produtos)) {
        echo "✓ Produtos carregados com sucesso:\n";
        
        // Agrupar por categoria para mostrar
        $produtosPorCategoria = [];
        foreach ($produtos as $produto) {
            $produtosPorCategoria[$produto->categoria_nome][] = $produto;
        }
        
        foreach ($produtosPorCategoria as $categoriaNome => $produtosCategoria) {
            echo "  " . $categoriaNome . " (" . count($produtosCategoria) . " produtos):\n";
            foreach ($produtosCategoria as $produto) {
                $preco = $produto->preco ? 'R$ ' . number_format($produto->preco, 2, ',', '.') : 'Sem preço';
                echo "    - " . $produto->nome . " - " . $preco . "\n";
            }
        }
    } else {
        echo "✗ Nenhum produto foi carregado!\n";
        throw new Exception("Falha ao carregar produtos");
    }
    
    echo "\n5. Testando Controller Home...\n";
    $homeController = new \App\Controllers\Home();
    $resultado = $homeController->index();
    
    if (is_string($resultado) && strlen($resultado) > 0) {
        echo "✓ Controller Home funcionando corretamente\n";
        echo "  - View renderizada com " . strlen($resultado) . " caracteres\n";
        
        // Verificar se as categorias estão na view
        $categoriasNaView = 0;
        foreach ($categorias as $categoria) {
            if (strpos($resultado, $categoria->nome) !== false) {
                $categoriasNaView++;
            }
        }
        echo "  - " . $categoriasNaView . "/" . count($categorias) . " categorias encontradas na view\n";
        
        if ($categoriasNaView == count($categorias)) {
            echo "✓ Todas as categorias estão sendo exibidas corretamente!\n";
        } else {
            echo "⚠ Algumas categorias podem não estar sendo exibidas\n";
        }
        
    } else {
        echo "✗ Problema no Controller Home\n";
        throw new Exception("Controller não retornou view válida");
    }
    
    echo "\n6. Verificações finais...\n";
    
    // Verificar se o ambiente está correto
    echo "- Ambiente: " . ENVIRONMENT . "\n";
    
    // Verificar se as rotas estão funcionando
    echo "- URL base: " . site_url() . "\n";
    
    echo "\n✅ CONFIGURAÇÃO CONCLUÍDA COM SUCESSO!\n";
    echo "\nO sistema de categorias dinâmicas está funcionando corretamente.\n";
    echo "Agora você pode acessar o site e ver as categorias sendo carregadas dinamicamente do banco de dados.\n";
    echo "\nCategorias disponíveis:\n";
    foreach ($categorias as $categoria) {
        echo "- " . $categoria->nome . "\n";
    }
    
} catch (Exception $e) {
    echo "\n✗ ERRO: " . $e->getMessage() . "\n";
    echo "Verifique os logs para mais detalhes.\n";
}

echo "\n=== CONFIGURAÇÃO FINALIZADA ===\n";