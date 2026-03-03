<?php
// Teste simples da funcionalidade de associação
require_once '../vendor/autoload.php';

// Configuração básica do CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'codeigniter4' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);
define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('WRITEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR);

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/teste';

$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();

echo "<h2>Teste de Associação de Extras por Categoria</h2>";

// Parâmetros de teste via GET
$extraIds = $_GET['extras'] ?? [3, 4]; // IDs dos extras
$categoriaId = $_GET['categoria'] ?? 3; // ID da categoria

if (!is_array($extraIds)) {
    $extraIds = explode(',', $extraIds);
}

echo "<p><strong>Parâmetros:</strong></p>";
echo "<p>Extras IDs: " . implode(', ', $extraIds) . "</p>";
echo "<p>Categoria ID: {$categoriaId}</p>";

// Busca dados
$categoria = $db->query("SELECT * FROM categorias WHERE id = {$categoriaId}")->getRow();
$produtos = $db->query("SELECT * FROM produtos WHERE categoria_id = {$categoriaId} AND ativo = 1")->getResult();

echo "<h3>Categoria: {$categoria->nome}</h3>";
echo "<p>Produtos encontrados: " . count($produtos) . "</p>";

// Testa a associação
$extraModel = new \App\Models\ExtraModel();
$totalSucessos = 0;
$extrasNomes = [];

foreach ($extraIds as $extraId) {
    $extra = $db->query("SELECT * FROM extras WHERE id = {$extraId}")->getRow();
    if ($extra) {
        $extrasNomes[] = $extra->nome;
        echo "<p>Testando extra: {$extra->nome} (ID: {$extraId})</p>";
        
        $sucessos = $extraModel->associarPorCategoria($extraId, $categoriaId);
        $totalSucessos += $sucessos;
        
        echo "<p>✅ {$sucessos} produtos associados ao extra '{$extra->nome}'</p>";
    }
}

echo "<h3>Resultado Final:</h3>";
echo "<p><strong>{$totalSucessos} associações criadas</strong></p>";
echo "<p>Extras: " . implode(', ', $extrasNomes) . "</p>";

// Mostra as associações criadas
echo "<h3>Associações na Base de Dados:</h3>";
$associacoes = $db->query("
    SELECT pe.*, p.nome as produto_nome, e.nome as extra_nome 
    FROM produtos_extras pe 
    JOIN produtos p ON p.id = pe.produto_id 
    JOIN extras e ON e.id = pe.extra_id 
    WHERE p.categoria_id = {$categoriaId}
    ORDER BY e.nome, p.nome
")->getResult();

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Produto</th><th>Extra</th></tr>";
foreach ($associacoes as $assoc) {
    echo "<tr><td>{$assoc->produto_nome}</td><td>{$assoc->extra_nome}</td></tr>";
}
echo "</table>";

echo "<hr>";
echo "<p><a href='?extras=3,4&categoria=4'>Testar com Pizzas (categoria 4)</a></p>";
echo "<p><a href='?extras=5,6&categoria=3'>Testar outros extras com Hambúrguer</a></p>";
?>
