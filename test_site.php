<?php

/**
 * Script para testar se o site está funcionando
 */

echo "=== Testando Conectividade do Site ===\n\n";

// Função para testar URL
function testURL($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'success' => $response !== false && empty($error),
        'http_code' => $httpCode,
        'error' => $error,
        'response_length' => strlen($response)
    ];
}

// Testar HTTP
echo "1. Testando HTTP (http://localhost:8080)...\n";
$httpResult = testURL('http://localhost:8080');
if ($httpResult['success']) {
    echo "   ✓ HTTP funcionando - Código: {$httpResult['http_code']}\n";
    echo "   ✓ Resposta recebida: {$httpResult['response_length']} bytes\n";
} else {
    echo "   ✗ HTTP falhou - Erro: {$httpResult['error']}\n";
}

echo "\n";

// Testar HTTPS
echo "2. Testando HTTPS (https://localhost:8080)...\n";
$httpsResult = testURL('https://localhost:8080');
if ($httpsResult['success']) {
    echo "   ✓ HTTPS funcionando - Código: {$httpsResult['http_code']}\n";
    echo "   ✓ Resposta recebida: {$httpsResult['response_length']} bytes\n";
} else {
    echo "   ✗ HTTPS falhou - Erro: {$httpsResult['error']}\n";
}

echo "\n";

// Verificar configurações atuais
echo "3. Configurações atuais:\n";
$envContent = file_get_contents('.env');
if (strpos($envContent, 'https://') !== false) {
    echo "   • Base URL: HTTPS\n";
} else {
    echo "   • Base URL: HTTP\n";
}

if (strpos($envContent, 'forceGlobalSecureRequests = true') !== false) {
    echo "   • Force HTTPS: Habilitado\n";
} else {
    echo "   • Force HTTPS: Desabilitado\n";
}

echo "\n=== Recomendações ===\n";

if (!$httpResult['success'] && !$httpsResult['success']) {
    echo "❌ Nenhuma conexão funcionando!\n";
    echo "Verifique se o servidor está rodando:\n";
    echo "   php spark serve --host=localhost --port=8080\n";
} elseif ($httpResult['success'] && !$httpsResult['success']) {
    echo "✅ HTTP funcionando, HTTPS não disponível\n";
    echo "Para usar apenas HTTP, execute: php disable_https.php\n";
    echo "Para configurar HTTPS, você precisa de um certificado SSL\n";
} elseif (!$httpResult['success'] && $httpsResult['success']) {
    echo "✅ HTTPS funcionando, HTTP redirecionando\n";
    echo "Configuração SSL está correta!\n";
} else {
    echo "✅ Ambos HTTP e HTTPS funcionando\n";
    echo "Você pode escolher qual usar modificando as configurações\n";
}

echo "\n";