<?php
// Teste de URL de imagem
// REMOVER APÓS USO!

// Simular CodeIgniter base_url
function base_url($uri = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . '://' . $host . '/' . ltrim($uri, '/');
}

$imagem = '1771854261_adb3cdcba2981a2bbaf9.jpg';

echo "<h2>Teste de URLs de Imagem</h2>";

echo "<h3>1. URL gerada por base_url():</h3>";
$url1 = base_url('uploads/produtos/' . $imagem);
echo "<p><strong>URL:</strong> <a href='$url1' target='_blank'>$url1</a></p>";
echo "<img src='$url1' style='max-width: 200px; border: 2px solid red;' onerror=\"this.style.display='none'; this.nextElementSibling.style.display='block';\">";
echo "<p style='display:none; color: red;'>❌ Imagem não carregou</p>";

echo "<h3>2. URL direta com /public/:</h3>";
$url2 = base_url('public/uploads/produtos/' . $imagem);
echo "<p><strong>URL:</strong> <a href='$url2' target='_blank'>$url2</a></p>";
echo "<img src='$url2' style='max-width: 200px; border: 2px solid green;' onerror=\"this.style.display='none'; this.nextElementSibling.style.display='block';\">";
echo "<p style='display:none; color: red;'>❌ Imagem não carregou</p>";

echo "<h3>3. Caminho do arquivo no servidor:</h3>";
$caminho = __DIR__ . '/public/uploads/produtos/' . $imagem;
echo "<p><strong>Caminho:</strong> $caminho</p>";
echo "<p><strong>Existe?</strong> " . (file_exists($caminho) ? '✅ SIM' : '❌ NÃO') . "</p>";

if (file_exists($caminho)) {
    echo "<p><strong>Tamanho:</strong> " . number_format(filesize($caminho) / 1024, 2) . " KB</p>";
}

echo "<h3>4. Estrutura de diretórios:</h3>";
echo "<pre>";
echo "Diretório atual: " . __DIR__ . "\n";
echo "Existe /public/? " . (is_dir(__DIR__ . '/public') ? 'SIM' : 'NÃO') . "\n";
echo "Existe /public/uploads/? " . (is_dir(__DIR__ . '/public/uploads') ? 'SIM' : 'NÃO') . "\n";
echo "Existe /public/uploads/produtos/? " . (is_dir(__DIR__ . '/public/uploads/produtos') ? 'SIM' : 'NÃO') . "\n";
echo "</pre>";
?>
