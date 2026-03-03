<?php
// Script temporário para verificar estrutura de uploads
// REMOVER APÓS USO!

$uploadDir = __DIR__ . '/uploads/produtos/';

echo "<h2>Verificação de Uploads</h2>";
echo "<p><strong>Diretório:</strong> $uploadDir</p>";

if (!is_dir($uploadDir)) {
    echo "<p style='color: red;'>❌ Diretório não existe!</p>";
    echo "<p>Criando diretório...</p>";
    if (mkdir($uploadDir, 0755, true)) {
        echo "<p style='color: green;'>✅ Diretório criado!</p>";
    } else {
        echo "<p style='color: red;'>❌ Erro ao criar diretório</p>";
    }
} else {
    echo "<p style='color: green;'>✅ Diretório existe</p>";
    
    $arquivos = scandir($uploadDir);
    $total = count($arquivos) - 2; // Remove . e ..
    
    echo "<p><strong>Total de imagens:</strong> $total</p>";
    
    if ($total > 0) {
        echo "<h3>Primeiras 10 imagens:</h3><ul>";
        $count = 0;
        foreach ($arquivos as $arquivo) {
            if ($arquivo != '.' && $arquivo != '..' && $count < 10) {
                $tamanho = filesize($uploadDir . $arquivo);
                echo "<li>$arquivo (" . number_format($tamanho / 1024, 2) . " KB)</li>";
                $count++;
            }
        }
        echo "</ul>";
    }
}

echo "<hr>";
echo "<p><strong>Permissões do diretório:</strong> " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "</p>";
echo "<p><strong>Caminho completo:</strong> " . realpath($uploadDir) . "</p>";
?>
