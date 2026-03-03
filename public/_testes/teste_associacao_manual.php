<?php
// Teste de associação de categoria sem CodeIgniter
echo "<h2>Teste de Associação de Categoria</h2>";

// Configuração do banco (usando as mesmas configurações do .env)
$host = 'localhost';
$database = 'food';
$username = 'root';
$password = 'Legnu.131807';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão com banco OK<br><br>";
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}

// Simular dados que viriam do POST
echo "<h3>Verificando dados disponíveis:</h3>";

// Listar categorias disponíveis
$stmt = $pdo->prepare("SELECT id, nome, ativo FROM categorias ORDER BY id");
$stmt->execute();
$todas_categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Categorias no banco:<br>";
foreach ($todas_categorias as $cat) {
    $status = $cat['ativo'] ? '✅' : '❌';
    echo "$status ID: {$cat['id']} - {$cat['nome']} (ativo: {$cat['ativo']})<br>";
}

// Listar extras disponíveis
$stmt = $pdo->prepare("SELECT id, nome, ativo FROM extras ORDER BY id");
$stmt->execute();
$todos_extras = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<br>Extras no banco:<br>";
foreach ($todos_extras as $ext) {
    $status = $ext['ativo'] ? '✅' : '❌';
    echo "$status ID: {$ext['id']} - {$ext['nome']} (ativo: {$ext['ativo']})<br>";
}

// Usar a primeira categoria ativa encontrada
$categoria_ativa = null;
foreach ($todas_categorias as $cat) {
    if ($cat['ativo']) {
        $categoria_ativa = $cat;
        break;
    }
}

if (!$categoria_ativa) {
    echo "<br>❌ Nenhuma categoria ativa encontrada!";
    exit;
}

// Usar os primeiros extras ativos encontrados
$extras_ativos = array_filter($todos_extras, function($ext) { return $ext['ativo']; });
$extras_ids = array_slice(array_column($extras_ativos, 'id'), 0, 3);

$categoria_id = $categoria_ativa['id'];

echo "<br><h3>Dados do teste (ajustados):</h3>";
echo "Categoria ID: $categoria_id ({$categoria_ativa['nome']})<br>";
echo "Extras IDs: " . implode(', ', $extras_ids) . "<br><br>";

// 1. Verificar se a categoria existe
$stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = ? AND ativo = 1");
$stmt->execute([$categoria_id]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categoria) {
    echo "❌ Categoria não encontrada ou inativa<br>";
    exit;
}
echo "✅ Categoria encontrada: " . $categoria['nome'] . "<br>";

// 2. Verificar se os extras existem
$placeholders = str_repeat('?,', count($extras_ids) - 1) . '?';
$stmt = $pdo->prepare("SELECT id, nome FROM extras WHERE id IN ($placeholders) AND ativo = 1");
$stmt->execute($extras_ids);
$extras = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "✅ Extras encontrados: ";
foreach ($extras as $extra) {
    echo $extra['nome'] . " ";
}
echo "<br>";

// 3. Buscar produtos da categoria
$stmt = $pdo->prepare("SELECT id, nome FROM produtos WHERE categoria_id = ? AND ativo = 1");
$stmt->execute([$categoria_id]);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($produtos)) {
    echo "❌ Nenhum produto ativo encontrado na categoria<br>";
    exit;
}

echo "✅ Produtos da categoria: ";
foreach ($produtos as $produto) {
    echo $produto['nome'] . " ";
}
echo "<br><br>";

// 4. Remover associações existentes dos produtos desta categoria
echo "<h3>Removendo associações existentes...</h3>";
$produto_ids = array_column($produtos, 'id');
$placeholders = str_repeat('?,', count($produto_ids) - 1) . '?';
$stmt = $pdo->prepare("DELETE FROM produtos_extras WHERE produto_id IN ($placeholders)");
$result = $stmt->execute($produto_ids);
echo "✅ Associações antigas removidas<br>";

// 5. Criar novas associações
echo "<h3>Criando novas associações...</h3>";
$stmt = $pdo->prepare("INSERT INTO produtos_extras (produto_id, extra_id) VALUES (?, ?)");

$associacoes_criadas = 0;
foreach ($produtos as $produto) {
    foreach ($extras as $extra) {
        $stmt->execute([$produto['id'], $extra['id']]);
        $associacoes_criadas++;
        echo "✅ {$produto['nome']} → {$extra['nome']}<br>";
    }
}

echo "<br><strong>Total de associações criadas: $associacoes_criadas</strong><br>";

// 6. Verificar resultado final
echo "<h3>Verificação final:</h3>";
$stmt = $pdo->prepare("
    SELECT p.nome as produto, e.nome as extra 
    FROM produtos_extras pe
    JOIN produtos p ON p.id = pe.produto_id
    JOIN extras e ON e.id = pe.extra_id
    WHERE p.categoria_id = ?
    ORDER BY p.nome, e.nome
");
$stmt->execute([$categoria_id]);
$verificacao = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($verificacao as $assoc) {
    echo "✅ {$assoc['produto']} → {$assoc['extra']}<br>";
}

echo "<br><a href='javascript:history.back()'>Voltar</a>";
?>
