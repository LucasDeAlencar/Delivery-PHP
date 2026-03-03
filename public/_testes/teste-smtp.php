<?php

echo "<h2>Teste de Capacidade SMTP do Servidor Local</h2>";

// Teste 1: Verificar se pode abrir socket SMTP
echo "<h3>1. Teste de Socket SMTP</h3>";
$socket = @fsockopen('smtp.gmail.com', 587, $errno, $errstr, 10);
if ($socket) {
    echo "<p style='color: green;'>✅ Socket SMTP aberto com sucesso</p>";
    
    // Ler resposta inicial
    $response = fgets($socket, 515);
    echo "<p>Resposta inicial: " . htmlspecialchars($response) . "</p>";
    
    // Tentar EHLO
    fwrite($socket, "EHLO localhost\r\n");
    $response = fgets($socket, 515);
    echo "<p>Resposta EHLO: " . htmlspecialchars($response) . "</p>";
    
    fclose($socket);
} else {
    echo "<p style='color: red;'>❌ Erro ao abrir socket: $errstr ($errno)</p>";
}

// Teste 2: Verificar configurações PHP
echo "<h3>2. Configurações PHP</h3>";
echo "<p>allow_url_fopen: " . (ini_get('allow_url_fopen') ? '✅ Habilitado' : '❌ Desabilitado') . "</p>";
echo "<p>default_socket_timeout: " . ini_get('default_socket_timeout') . " segundos</p>";
echo "<p>user_agent: " . ini_get('user_agent') . "</p>";

// Teste 3: Verificar se pode fazer conexão TLS
echo "<h3>3. Teste de Conexão TLS</h3>";
$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
]);

$socket = @stream_socket_client('tls://smtp.gmail.com:587', $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
if ($socket) {
    echo "<p style='color: green;'>✅ Conexão TLS estabelecida</p>";
    fclose($socket);
} else {
    echo "<p style='color: red;'>❌ Erro TLS: $errstr ($errno)</p>";
}

// Teste 4: Verificar firewall/proxy
echo "<h3>4. Teste de Conectividade Externa</h3>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.google.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "<p style='color: green;'>✅ Conectividade externa OK</p>";
} else {
    echo "<p style='color: red;'>❌ Problema de conectividade externa</p>";
}

?>
