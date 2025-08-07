<?php

require_once 'vendor/autoload.php';

// Configurar o ambiente
putenv('CI_ENVIRONMENT=development');

// Inicializar o CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "=== TESTE FINAL DOS FILTROS ===\n\n";

try {
    // 1. Garantir que temos dados
    echo "1. Garantindo dados no banco...\n";
    $seeder = \Config\Database::seeder();
    $seeder->call('ProdutoSeeder');
    
    // 2. Testar models
    echo "\n2. Testando models...\n";
    $categoriaModel = new \App\Models\CategoriaModel();
    $produtoModel = new \App\Models\ProdutoModel();
    
    $categorias = $categoriaModel->where('ativo', true)->orderBy('nome', 'ASC')->findAll();
    $produtos = $produtoModel->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug')
                            ->join('categorias', 'categorias.id = produtos.categoria_id')
                            ->where('produtos.ativo', true)
                            ->where('categorias.ativo', true)
                            ->orderBy('categorias.nome', 'ASC')
                            ->orderBy('produtos.nome', 'ASC')
                            ->findAll();
    
    echo "✅ Categorias: " . count($categorias) . "\n";
    echo "✅ Produtos: " . count($produtos) . "\n";
    
    // 3. Mostrar estrutura esperada
    echo "\n3. Estrutura esperada dos filtros:\n";
    echo "Botão 'Todos' -> mostra div.filtr-item.filter.all\n";
    
    $produtosPorCategoria = [];
    foreach ($produtos as $produto) {
        $produtosPorCategoria[$produto->categoria_slug][] = $produto;
    }
    
    foreach ($categorias as $categoria) {
        $count = isset($produtosPorCategoria[$categoria->slug]) ? count($produtosPorCategoria[$categoria->slug]) : 0;
        echo "Botão '{$categoria->nome}' (data-filter='{$categoria->slug}') -> mostra div.filtr-item.filter.{$categoria->slug} ({$count} produtos)\n";
    }
    
    // 4. Testar controller
    echo "\n4. Testando controller...\n";
    $homeController = new \App\Controllers\Home();
    $resultado = $homeController->index();
    
    if (strpos($resultado, 'Debug - Dados recebidos na view') !== false) {
        echo "✅ Debug ativo na view\n";
    }
    
    if (strpos($resultado, 'menu_filter') !== false) {
        echo "✅ Seção de filtros presente\n";
    }
    
    // Verificar se todas as categorias estão na view
    $categoriasEncontradas = 0;
    foreach ($categorias as $categoria) {
        if (strpos($resultado, $categoria->nome) !== false && strpos($resultado, $categoria->slug) !== false) {
            $categoriasEncontradas++;
            echo "✅ Categoria '{$categoria->nome}' (slug: {$categoria->slug}) encontrada\n";
        } else {
            echo "❌ Categoria '{$categoria->nome}' (slug: {$categoria->slug}) NÃO encontrada\n";
        }
    }
    
    echo "\nResumo: {$categoriasEncontradas}/" . count($categorias) . " categorias na view\n";
    
    // 5. Criar arquivo de demonstração
    echo "\n5. Criando demonstração...\n";
    
    $demo = "<!DOCTYPE html>
<html lang=\"pt-br\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Demo - Filtros de Categoria</title>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
    <script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>
    <style>
        .filter-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background: #ffc107;
            color: #000;
            text-decoration: none;
            border-radius: 25px;
            border: 2px solid #ffc107;
            transition: all 0.3s;
        }
        .filter-button:hover,
        .filter-button.active {
            background: #e0a800;
            color: #fff;
            transform: translateY(-2px);
        }
        .filtr-item {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .product-card {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class=\"container mt-5\">
        <h1 class=\"text-center mb-4\">Demonstração - Filtros de Categoria</h1>
        
        <!-- Filtros -->
        <div class=\"menu_filter text-center mb-4\">
            <a href=\"#\" class=\"filter-button active\" data-filter=\"all\">Todos (" . count($produtos) . ")</a>";
    
    foreach ($categorias as $categoria) {
        $count = isset($produtosPorCategoria[$categoria->slug]) ? count($produtosPorCategoria[$categoria->slug]) : 0;
        $demo .= "\n            <a href=\"#\" class=\"filter-button\" data-filter=\"{$categoria->slug}\">{$categoria->nome} ({$count})</a>";
    }
    
    $demo .= "\n        </div>
        
        <!-- Produtos -->
        <div id=\"menu_items\">
            <!-- Todos os produtos -->
            <div class=\"filtr-item filter all\" style=\"display: block;\">
                <h3>Todos os Produtos</h3>
                <div class=\"row\">";
    
    foreach ($produtos as $produto) {
        $preco = $produto->preco ? 'R$ ' . number_format($produto->preco, 2, ',', '.') : 'Sem preço';
        $demo .= "\n                    <div class=\"col-md-4\">
                        <div class=\"product-card\">
                            <h5>{$produto->nome}</h5>
                            <p class=\"text-muted\">{$produto->ingredientes}</p>
                            <p><strong>{$preco}</strong></p>
                            <small class=\"badge bg-secondary\">{$produto->categoria_nome}</small>
                        </div>
                    </div>";
    }
    
    $demo .= "\n                </div>
            </div>";
    
    // Produtos por categoria
    foreach ($produtosPorCategoria as $slug => $prods) {
        $categoriaNome = '';
        foreach ($categorias as $cat) {
            if ($cat->slug === $slug) {
                $categoriaNome = $cat->nome;
                break;
            }
        }
        
        $demo .= "\n            
            <!-- Categoria: {$categoriaNome} -->
            <div class=\"filtr-item filter {$slug}\" style=\"display: none;\">
                <h3>{$categoriaNome}</h3>
                <div class=\"row\">";
        
        foreach ($prods as $prod) {
            $preco = $prod->preco ? 'R$ ' . number_format($prod->preco, 2, ',', '.') : 'Sem preço';
            $demo .= "\n                    <div class=\"col-md-4\">
                        <div class=\"product-card\">
                            <h5>{$prod->nome}</h5>
                            <p class=\"text-muted\">{$prod->ingredientes}</p>
                            <p><strong>{$preco}</strong></p>
                        </div>
                    </div>";
        }
        
        $demo .= "\n                </div>
            </div>";
    }
    
    $demo .= "\n        </div>
        
        <!-- Debug Info -->
        <div class=\"mt-5 p-3 bg-light\">
            <h4>Debug Info</h4>
            <p><strong>Total de categorias:</strong> " . count($categorias) . "</p>
            <p><strong>Total de produtos:</strong> " . count($produtos) . "</p>
            <p><strong>Produtos por categoria:</strong></p>
            <ul>";
    
    foreach ($produtosPorCategoria as $slug => $prods) {
        $demo .= "\n                <li>{$slug}: " . count($prods) . " produtos</li>";
    }
    
    $demo .= "\n            </ul>
        </div>
    </div>
    
    <script>
    $(document).ready(function() {
        console.log('Demo iniciada');
        console.log('Botões encontrados:', $('.filter-button').length);
        console.log('Items encontrados:', $('.filtr-item').length);
        
        $('.filter-button').click(function(e) {
            e.preventDefault();
            
            // Remove active de todos
            $('.filter-button').removeClass('active');
            $(this).addClass('active');
            
            // Pega o filtro
            var filterValue = $(this).data('filter');
            console.log('Filtro selecionado:', filterValue);
            
            // Esconde todos
            $('.filtr-item').hide();
            
            // Mostra o filtrado
            if (filterValue === 'all') {
                $('.filtr-item.filter.all').show();
            } else {
                $('.filtr-item.filter.' + filterValue).show();
            }
            
            console.log('Items visíveis:', $('.filtr-item:visible').length);
        });
        
        console.log('Filtros configurados!');
    });
    </script>
</body>
</html>";
    
    file_put_contents('demo_filtros.html', $demo);
    echo "✅ Arquivo 'demo_filtros.html' criado\n";
    
    echo "\n✅ TESTE CONCLUÍDO!\n";
    echo "\nPara testar:\n";
    echo "1. Abra 'demo_filtros.html' no navegador\n";
    echo "2. Acesse o site em " . site_url() . "\n";
    echo "3. Verifique o console do navegador para logs de debug\n";
    
    echo "\nCategorias disponíveis:\n";
    foreach ($categorias as $categoria) {
        $count = isset($produtosPorCategoria[$categoria->slug]) ? count($produtosPorCategoria[$categoria->slug]) : 0;
        echo "- {$categoria->nome} ({$categoria->slug}): {$count} produtos\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
}

echo "\n=== TESTE FINALIZADO ===\n";