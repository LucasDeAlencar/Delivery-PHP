<?php

require_once 'vendor/autoload.php';

// Configurar o ambiente
putenv('CI_ENVIRONMENT=development');

// Inicializar o CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "=== DIAGNÓSTICO SIMPLES DOS FILTROS ===\n\n";

try {
    // 1. Verificar dados básicos
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
    
    echo "DADOS NO BANCO:\n";
    echo "- Categorias ativas: " . count($categorias) . "\n";
    echo "- Produtos ativos: " . count($produtos) . "\n\n";
    
    if (empty($categorias) || empty($produtos)) {
        echo "❌ PROBLEMA: Dados insuficientes no banco!\n";
        echo "Executando seeder para criar dados de teste...\n\n";
        
        $seeder = \Config\Database::seeder();
        $seeder->call('ProdutoSeeder');
        
        // Recarregar
        $categorias = $categoriaModel->where('ativo', true)->orderBy('nome', 'ASC')->findAll();
        $produtos = $produtoModel->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug')
                                ->join('categorias', 'categorias.id = produtos.categoria_id')
                                ->where('produtos.ativo', true)
                                ->where('categorias.ativo', true)
                                ->orderBy('categorias.nome', 'ASC')
                                ->orderBy('produtos.nome', 'ASC')
                                ->findAll();
        
        echo "Após seeder:\n";
        echo "- Categorias ativas: " . count($categorias) . "\n";
        echo "- Produtos ativos: " . count($produtos) . "\n\n";
    }
    
    // 2. Mostrar estrutura esperada
    echo "ESTRUTURA ESPERADA:\n\n";
    
    echo "Botões de filtro:\n";
    echo "- Botão 'Todos' com data-filter='all'\n";
    foreach ($categorias as $categoria) {
        echo "- Botão '{$categoria->nome}' com data-filter='{$categoria->slug}'\n";
    }
    echo "\n";
    
    echo "Divs de conteúdo:\n";
    echo "- Div com classes 'filtr-item filter all' (visível por padrão)\n";
    foreach ($categorias as $categoria) {
        echo "- Div com classes 'filtr-item filter {$categoria->slug}' (oculta por padrão)\n";
    }
    echo "\n";
    
    // 3. Agrupar produtos por categoria
    $produtosPorCategoria = [];
    foreach ($produtos as $produto) {
        $produtosPorCategoria[$produto->categoria_slug][] = $produto;
    }
    
    echo "PRODUTOS POR CATEGORIA:\n";
    foreach ($categorias as $categoria) {
        $count = isset($produtosPorCategoria[$categoria->slug]) ? count($produtosPorCategoria[$categoria->slug]) : 0;
        echo "- {$categoria->nome} ({$categoria->slug}): {$count} produtos\n";
    }
    echo "\n";
    
    // 4. Verificar se há problemas óbvios
    echo "VERIFICAÇÃO DE PROBLEMAS:\n";
    
    $problemas = [];
    
    // Verificar slugs duplicados
    $slugs = array_column($categorias, 'slug');
    if (count($slugs) !== count(array_unique($slugs))) {
        $problemas[] = "Slugs de categoria duplicados encontrados";
    }
    
    // Verificar produtos sem categoria
    $produtosSemCategoria = $produtoModel->where('categoria_id IS NULL OR categoria_id = 0')->where('ativo', true)->countAllResults();
    if ($produtosSemCategoria > 0) {
        $problemas[] = "{$produtosSemCategoria} produtos ativos sem categoria";
    }
    
    // Verificar categorias vazias
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
        echo "✅ Nenhum problema óbvio encontrado nos dados\n";
    } else {
        foreach ($problemas as $problema) {
            echo "⚠️ {$problema}\n";
        }
    }
    echo "\n";
    
    // 5. Criar arquivo HTML de teste mínimo
    echo "CRIANDO TESTE MÍNIMO:\n";
    
    $testeMinimo = "<!DOCTYPE html>
<html>
<head>
    <title>Teste Mínimo - Filtros</title>
    <script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>
    <style>
        .filter-button { 
            padding: 10px 20px; 
            margin: 5px; 
            background: #ffc107; 
            color: #000; 
            text-decoration: none; 
            border-radius: 5px; 
            display: inline-block;
            cursor: pointer;
        }
        .filter-button.active { 
            background: #e0a800; 
            color: #fff; 
        }
        .filtr-item { 
            margin: 20px 0; 
            padding: 20px; 
            border: 1px solid #ddd; 
            display: none; 
        }
        .filtr-item.show { 
            display: block; 
        }
        .product { 
            padding: 10px; 
            margin: 5px 0; 
            background: #f5f5f5; 
            border-radius: 3px; 
        }
        .log { 
            background: #000; 
            color: #0f0; 
            padding: 10px; 
            font-family: monospace; 
            font-size: 12px; 
            height: 200px; 
            overflow-y: auto; 
        }
    </style>
</head>
<body>
    <h1>Teste Mínimo - Filtros de Categoria</h1>
    
    <div class=\"log\" id=\"log\"></div>
    
    <h2>Filtros:</h2>
    <div>
        <a href=\"#\" class=\"filter-button active\" data-filter=\"all\">Todos (" . count($produtos) . ")</a>";
    
    foreach ($categorias as $categoria) {
        $count = isset($produtosPorCategoria[$categoria->slug]) ? count($produtosPorCategoria[$categoria->slug]) : 0;
        $testeMinimo .= "\n        <a href=\"#\" class=\"filter-button\" data-filter=\"{$categoria->slug}\">{$categoria->nome} ({$count})</a>";
    }
    
    $testeMinimo .= "\n    </div>
    
    <h2>Produtos:</h2>
    
    <!-- Todos os produtos -->
    <div class=\"filtr-item filter all show\" data-category=\"all\">
        <h3>Todos os Produtos</h3>";
    
    foreach ($produtos as $produto) {
        $testeMinimo .= "\n        <div class=\"product\">{$produto->nome} - {$produto->categoria_nome}</div>";
    }
    
    $testeMinimo .= "\n    </div>";
    
    // Produtos por categoria
    foreach ($produtosPorCategoria as $slug => $prods) {
        $categoriaNome = '';
        foreach ($categorias as $cat) {
            if ($cat->slug === $slug) {
                $categoriaNome = $cat->nome;
                break;
            }
        }
        
        $testeMinimo .= "\n    
    <!-- Categoria: {$categoriaNome} -->
    <div class=\"filtr-item filter {$slug}\" data-category=\"{$slug}\">
        <h3>{$categoriaNome}</h3>";
        
        foreach ($prods as $prod) {
            $testeMinimo .= "\n        <div class=\"product\">{$prod->nome}</div>";
        }
        
        $testeMinimo .= "\n    </div>";
    }
    
    $testeMinimo .= "\n    
    <script>
    function log(msg) {
        var time = new Date().toLocaleTimeString();
        $('#log').append('[' + time + '] ' + msg + '\\n');
        $('#log').scrollTop($('#log')[0].scrollHeight);
        console.log(msg);
    }
    
    $(document).ready(function() {
        log('✅ Sistema iniciado');
        log('Botões: ' + $('.filter-button').length);
        log('Items: ' + $('.filtr-item').length);
        
        $('.filter-button').click(function(e) {
            e.preventDefault();
            
            var filtro = $(this).data('filter');
            log('🔄 Filtro clicado: ' + filtro);
            
            // Atualizar botões
            $('.filter-button').removeClass('active');
            $(this).addClass('active');
            
            // Ocultar todos
            $('.filtr-item').removeClass('show').hide();
            
            // Mostrar filtrados
            if (filtro === 'all') {
                $('.filtr-item[data-category=\"all\"]').addClass('show').show();
            } else {
                $('.filtr-item[data-category=\"' + filtro + '\"]').addClass('show').show();
            }
            
            var visiveis = $('.filtr-item:visible').length;
            log('✅ Items visíveis: ' + visiveis);
        });
        
        log('✅ Filtros configurados');
    });
    </script>
</body>
</html>";
    
    file_put_contents('teste_minimo.html', $testeMinimo);
    echo "✅ Arquivo 'teste_minimo.html' criado\n\n";
    
    echo "INSTRUÇÕES:\n";
    echo "1. Abra 'teste_minimo.html' no navegador\n";
    echo "2. Teste se os filtros funcionam\n";
    echo "3. Verifique o log na tela e no console\n";
    echo "4. Se funcionar aqui, o problema está na integração\n";
    echo "5. Se não funcionar, há problema no JavaScript básico\n\n";
    
    echo "POSSÍVEIS CAUSAS DO PROBLEMA:\n";
    echo "- Conflito com outros scripts JavaScript\n";
    echo "- CSS sobrescrevendo display: none/block\n";
    echo "- Seletores jQuery não encontrando elementos\n";
    echo "- Eventos não sendo vinculados corretamente\n";
    echo "- Problemas de timing (DOM não carregado)\n\n";
    
    echo "✅ DIAGNÓSTICO CONCLUÍDO!\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
}

echo "\n=== FIM DO DIAGNÓSTICO ===\n";