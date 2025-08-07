<?php

require_once 'vendor/autoload.php';

// Configurar o ambiente
putenv('CI_ENVIRONMENT=development');

// Inicializar o CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "=== DEBUG DOS FILTROS DE CATEGORIA ===\n\n";

try {
    // 1. Verificar dados no banco
    echo "1. VERIFICANDO DADOS NO BANCO:\n";
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
    
    echo "   Categorias ativas: " . count($categorias) . "\n";
    echo "   Produtos ativos: " . count($produtos) . "\n\n";
    
    if (empty($categorias)) {
        echo "❌ PROBLEMA: Nenhuma categoria ativa encontrada!\n";
        echo "   Executando seeder para criar dados...\n";
        $seeder = \Config\Database::seeder();
        $seeder->call('ProdutoSeeder');
        
        // Recarregar dados
        $categorias = $categoriaModel->where('ativo', true)->orderBy('nome', 'ASC')->findAll();
        $produtos = $produtoModel->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug')
                                ->join('categorias', 'categorias.id = produtos.categoria_id')
                                ->where('produtos.ativo', true)
                                ->where('categorias.ativo', true)
                                ->orderBy('categorias.nome', 'ASC')
                                ->orderBy('produtos.nome', 'ASC')
                                ->findAll();
        
        echo "   Após seeder - Categorias: " . count($categorias) . ", Produtos: " . count($produtos) . "\n\n";
    }
    
    // 2. Verificar estrutura das categorias
    echo "2. ESTRUTURA DAS CATEGORIAS:\n";
    foreach ($categorias as $categoria) {
        echo "   - ID: {$categoria->id}, Nome: '{$categoria->nome}', Slug: '{$categoria->slug}', Ativo: " . ($categoria->ativo ? 'SIM' : 'NÃO') . "\n";
    }
    echo "\n";
    
    // 3. Verificar produtos por categoria
    echo "3. PRODUTOS POR CATEGORIA:\n";
    $produtosPorCategoria = [];
    foreach ($produtos as $produto) {
        $produtosPorCategoria[$produto->categoria_slug][] = $produto;
        echo "   - Produto: '{$produto->nome}' -> Categoria: '{$produto->categoria_nome}' (slug: {$produto->categoria_slug})\n";
    }
    echo "\n";
    
    // 4. Resumo por categoria
    echo "4. RESUMO POR CATEGORIA:\n";
    foreach ($categorias as $categoria) {
        $count = isset($produtosPorCategoria[$categoria->slug]) ? count($produtosPorCategoria[$categoria->slug]) : 0;
        echo "   - {$categoria->nome} ({$categoria->slug}): {$count} produtos\n";
    }
    echo "\n";
    
    // 5. Verificar HTML gerado
    echo "5. VERIFICANDO HTML GERADO:\n";
    $homeController = new \App\Controllers\Home();
    $html = $homeController->index();
    
    // Verificar se os botões estão sendo gerados
    echo "   Verificando botões de filtro:\n";
    if (strpos($html, 'data-filter="all"') !== false) {
        echo "   ✅ Botão 'Todos' encontrado\n";
    } else {
        echo "   ❌ Botão 'Todos' NÃO encontrado\n";
    }
    
    foreach ($categorias as $categoria) {
        if (strpos($html, 'data-filter="' . $categoria->slug . '"') !== false) {
            echo "   ✅ Botão '{$categoria->nome}' encontrado\n";
        } else {
            echo "   ❌ Botão '{$categoria->nome}' NÃO encontrado\n";
        }
    }
    
    // Verificar se as divs de filtro estão sendo geradas
    echo "\n   Verificando divs de filtro:\n";
    if (strpos($html, 'filtr-item filter all') !== false) {
        echo "   ✅ Div 'all' encontrada\n";
    } else {
        echo "   ❌ Div 'all' NÃO encontrada\n";
    }
    
    foreach ($categorias as $categoria) {
        if (strpos($html, 'filtr-item filter ' . $categoria->slug) !== false) {
            echo "   ✅ Div '{$categoria->slug}' encontrada\n";
        } else {
            echo "   ❌ Div '{$categoria->slug}' NÃO encontrada\n";
        }
    }
    
    // 6. Verificar JavaScript
    echo "\n6. VERIFICANDO JAVASCRIPT:\n";
    if (strpos($html, 'filter-button') !== false) {
        echo "   ✅ Classe 'filter-button' encontrada no HTML\n";
    } else {
        echo "   ❌ Classe 'filter-button' NÃO encontrada no HTML\n";
    }
    
    if (strpos($html, 'data-filter') !== false) {
        echo "   ✅ Atributo 'data-filter' encontrado no HTML\n";
    } else {
        echo "   ❌ Atributo 'data-filter' NÃO encontrado no HTML\n";
    }
    
    if (strpos($html, '$(document).ready') !== false) {
        echo "   ✅ jQuery document.ready encontrado\n";
    } else {
        echo "   ❌ jQuery document.ready NÃO encontrado\n";
    }
    
    // 7. Criar arquivo de teste simples
    echo "\n7. CRIANDO ARQUIVO DE TESTE:\n";
    
    $teste = "<!DOCTYPE html>
<html lang=\"pt-br\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Teste - Filtros de Categoria</title>
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
        .item.active .filter-button {
            background: #e0a800;
            color: #fff;
            transform: translateY(-2px);
        }
        .filtr-item {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            display: none;
        }
        .filtr-item.show {
            display: block;
        }
        .product-card {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            background: #f8f9fa;
        }
        .debug {
            background: #f0f0f0;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class=\"container mt-5\">
        <h1 class=\"text-center mb-4\">Teste - Filtros de Categoria</h1>
        
        <div class=\"debug\">
            <strong>Debug Info:</strong><br>
            Total de categorias: " . count($categorias) . "<br>
            Total de produtos: " . count($produtos) . "<br>
            Produtos por categoria: " . json_encode(array_map('count', $produtosPorCategoria)) . "
        </div>
        
        <!-- Filtros -->
        <div class=\"menu_filter text-center mb-4\">
            <ul class=\"list-unstyled list-inline d-inline-block\">
                <li class=\"item active\">
                    <a href=\"javascript:;\" class=\"filter-button\" data-filter=\"all\">Todos (" . count($produtos) . ")</a>
                </li>";
    
    foreach ($categorias as $categoria) {
        $count = isset($produtosPorCategoria[$categoria->slug]) ? count($produtosPorCategoria[$categoria->slug]) : 0;
        $teste .= "\n                <li class=\"item\">
                    <a href=\"javascript:;\" class=\"filter-button\" data-filter=\"{$categoria->slug}\">{$categoria->nome} ({$count})</a>
                </li>";
    }
    
    $teste .= "\n            </ul>
        </div>
        
        <!-- Produtos -->
        <div id=\"menu_items\">
            <!-- Todos os produtos -->
            <div class=\"filtr-item filter all show\">
                <h3>Todos os Produtos</h3>
                <div class=\"row\">";
    
    foreach ($produtos as $produto) {
        $preco = $produto->preco ? 'R$ ' . number_format($produto->preco, 2, ',', '.') : 'Sem preço';
        $teste .= "\n                    <div class=\"col-md-4\">
                        <div class=\"product-card\">
                            <h5>{$produto->nome}</h5>
                            <p class=\"text-muted\">{$produto->ingredientes}</p>
                            <p><strong>{$preco}</strong></p>
                            <small class=\"badge bg-secondary\">{$produto->categoria_nome}</small>
                        </div>
                    </div>";
    }
    
    $teste .= "\n                </div>
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
        
        $teste .= "\n            
            <!-- Categoria: {$categoriaNome} -->
            <div class=\"filtr-item filter {$slug}\">
                <h3>{$categoriaNome}</h3>
                <div class=\"row\">";
        
        foreach ($prods as $prod) {
            $preco = $prod->preco ? 'R$ ' . number_format($prod->preco, 2, ',', '.') : 'Sem preço';
            $teste .= "\n                    <div class=\"col-md-4\">
                        <div class=\"product-card\">
                            <h5>{$prod->nome}</h5>
                            <p class=\"text-muted\">{$prod->ingredientes}</p>
                            <p><strong>{$preco}</strong></p>
                        </div>
                    </div>";
        }
        
        $teste .= "\n                </div>
            </div>";
    }
    
    $teste .= "\n        </div>
        
        <div class=\"debug mt-4\">
            <strong>Log de eventos:</strong><br>
            <div id=\"log\"></div>
        </div>
    </div>
    
    <script>
    function log(message) {
        console.log(message);
        $('#log').append(message + '<br>');
    }
    
    $(document).ready(function() {
        log('✅ jQuery carregado');
        log('✅ Document ready executado');
        log('Botões encontrados: ' + $('.filter-button').length);
        log('Items encontrados: ' + $('.filtr-item').length);
        
        // Verificar estrutura inicial
        $('.filtr-item').each(function(index) {
            var classes = $(this).attr('class');
            var visible = $(this).is(':visible') || $(this).hasClass('show');
            log('Item ' + index + ': ' + classes + ' (visível: ' + visible + ')');
        });
        
        $('.filter-button').click(function(e) {
            e.preventDefault();
            
            log('🔄 Filtro clicado!');
            
            // Remove active de todos
            $('.filter-button').parent().removeClass('active');
            $(this).parent().addClass('active');
            
            // Pega o filtro
            var filterValue = $(this).data('filter');
            log('Filtro selecionado: ' + filterValue);
            
            // Esconde todos
            $('.filtr-item').removeClass('show').hide();
            
            // Mostra o filtrado
            if (filterValue === 'all') {
                $('.filtr-item.filter.all').addClass('show').show();
                log('✅ Mostrando todos os produtos');
            } else {
                $('.filtr-item.filter.' + filterValue).addClass('show').show();
                log('✅ Mostrando categoria: ' + filterValue);
            }
            
            var visibleItems = $('.filtr-item:visible').length;
            log('Items visíveis após filtro: ' + visibleItems);
        });
        
        log('✅ Filtros configurados!');
    });
    </script>
</body>
</html>";
    
    file_put_contents('teste_filtros.html', $teste);
    echo "   ✅ Arquivo 'teste_filtros.html' criado\n";
    
    // 8. Verificar possíveis problemas
    echo "\n8. POSSÍVEIS PROBLEMAS IDENTIFICADOS:\n";
    
    $problemas = [];
    
    if (count($categorias) == 0) {
        $problemas[] = "Nenhuma categoria ativa no banco de dados";
    }
    
    if (count($produtos) == 0) {
        $problemas[] = "Nenhum produto ativo no banco de dados";
    }
    
    // Verificar se há produtos sem categoria
    $produtosSemCategoria = $produtoModel->where('categoria_id IS NULL OR categoria_id = 0')->where('ativo', true)->countAllResults();
    if ($produtosSemCategoria > 0) {
        $problemas[] = "{$produtosSemCategoria} produtos ativos sem categoria definida";
    }
    
    // Verificar se há categorias sem produtos
    $categoriasSemProdutos = [];
    foreach ($categorias as $categoria) {
        if (!isset($produtosPorCategoria[$categoria->slug]) || count($produtosPorCategoria[$categoria->slug]) == 0) {
            $categoriasSemProdutos[] = $categoria->nome;
        }
    }
    if (!empty($categoriasSemProdutos)) {
        $problemas[] = "Categorias sem produtos: " . implode(', ', $categoriasSemProdutos);
    }
    
    if (empty($problemas)) {
        echo "   ✅ Nenhum problema óbvio identificado nos dados\n";
        echo "   💡 O problema pode estar no JavaScript ou CSS\n";
        echo "   💡 Verifique o console do navegador para erros\n";
        echo "   💡 Teste o arquivo 'teste_filtros.html' para verificar se o JavaScript funciona\n";
    } else {
        foreach ($problemas as $problema) {
            echo "   ❌ {$problema}\n";
        }
    }
    
    echo "\n✅ DEBUG CONCLUÍDO!\n";
    echo "\nPróximos passos:\n";
    echo "1. Abra 'teste_filtros.html' no navegador\n";
    echo "2. Verifique se os filtros funcionam no arquivo de teste\n";
    echo "3. Se funcionarem, o problema está na integração com o template\n";
    echo "4. Se não funcionarem, o problema está no JavaScript\n";
    echo "5. Verifique o console do navegador para erros JavaScript\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== DEBUG FINALIZADO ===\n";