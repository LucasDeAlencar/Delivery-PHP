<?php
// Arquivo de teste para debug da associação de extras por categoria
try {
    require_once '../vendor/autoload.php';
    
    // Bootstrap CodeIgniter 4
    $app = \Config\Services::codeigniter();
    $app->initialize();
    
    // Conecta ao banco
    $db = \Config\Database::connect();
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
    exit;
}

echo "<h2>Debug - Associação de Extras por Categoria</h2>";

// Lista categorias
echo "<h3>Categorias Ativas:</h3>";
$categorias = $db->table('categorias')->where('ativo', 1)->get()->getResult();
foreach ($categorias as $categoria) {
    echo "ID: {$categoria->id} - Nome: {$categoria->nome}<br>";
}

// Lista extras
echo "<h3>Extras Ativos:</h3>";
$extras = $db->table('extras')->where('ativo', 1)->get()->getResult();
foreach ($extras as $extra) {
    echo "ID: {$extra->id} - Nome: {$extra->nome}<br>";
}

// Lista produtos por categoria
echo "<h3>Produtos por Categoria:</h3>";
foreach ($categorias as $categoria) {
    $produtos = $db->table('produtos')
                  ->where('categoria_id', $categoria->id)
                  ->where('ativo', 1)
                  ->get()
                  ->getResult();
    
    echo "<strong>Categoria: {$categoria->nome} ({$categoria->id})</strong><br>";
    if (empty($produtos)) {
        echo "- Nenhum produto ativo encontrado<br>";
    } else {
        foreach ($produtos as $produto) {
            echo "- Produto ID: {$produto->id} - Nome: {$produto->nome}<br>";
        }
    }
    echo "<br>";
}

// Verifica associações existentes
echo "<h3>Associações Existentes (produtos_extras):</h3>";
$associacoes = $db->table('produtos_extras pe')
                 ->select('pe.*, p.nome as produto_nome, e.nome as extra_nome')
                 ->join('produtos p', 'p.id = pe.produto_id')
                 ->join('extras e', 'e.id = pe.extra_id')
                 ->get()
                 ->getResult();

if (empty($associacoes)) {
    echo "Nenhuma associação encontrada.<br>";
} else {
    foreach ($associacoes as $assoc) {
        echo "Produto: {$assoc->produto_nome} -> Extra: {$assoc->extra_nome}<br>";
    }
}

echo "<br><a href='javascript:history.back()'>Voltar</a>";
?>
