<?php

/**
 * Script para desabilitar HTTPS
 * Execute este arquivo para desativar o HTTPS no projeto
 */

echo "=== Desabilitando HTTPS ===\n\n";

// Função para atualizar arquivo
function updateFile($file, $search, $replace) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $newContent = str_replace($search, $replace, $content);
        file_put_contents($file, $newContent);
        return true;
    }
    return false;
}

// 1. Atualizar .env
echo "1. Atualizando arquivo .env...\n";
if (updateFile('.env', 
    "app.baseURL = 'https://localhost:8080'\napp.forceGlobalSecureRequests = true",
    "app.baseURL = 'http://localhost:8080'\napp.forceGlobalSecureRequests = false"
)) {
    echo "   ✓ .env atualizado\n";
} else {
    echo "   ✗ Erro ao atualizar .env\n";
}

// 2. Atualizar App.php
echo "2. Atualizando arquivo App.php...\n";
if (updateFile('app/Config/App.php',
    "public string \$baseURL = 'https://localhost:8080/';",
    "public string \$baseURL = 'http://localhost:8080/';"
) && updateFile('app/Config/App.php',
    "public bool \$forceGlobalSecureRequests = true;",
    "public bool \$forceGlobalSecureRequests = false;"
)) {
    echo "   ✓ App.php atualizado\n";
} else {
    echo "   ✗ Erro ao atualizar App.php\n";
}

// 3. Desabilitar filtro HTTPS
echo "3. Desabilitando filtro HTTPS...\n";
if (updateFile('app/Config/Filters.php',
    "            'forcehttps', // Force Global Secure Requests",
    "            // 'forcehttps', // Force Global Secure Requests (disabled for development)"
)) {
    echo "   ✓ Filtro HTTPS desabilitado\n";
} else {
    echo "   ✗ Erro ao desabilitar filtro HTTPS\n";
}

// 4. Remover redirecionamento .htaccess
echo "4. Removendo redirecionamento HTTPS do .htaccess...\n";
$htaccessContent = file_get_contents('public/.htaccess');
if (strpos($htaccessContent, 'Force HTTPS redirect') !== false) {
    $newHtaccess = str_replace(
        "\n\t# Force HTTPS redirect\n\tRewriteCond %{HTTPS} off\n\tRewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n",
        "\n",
        $htaccessContent
    );
    file_put_contents('public/.htaccess', $newHtaccess);
    echo "   ✓ Redirecionamento HTTPS removido\n";
} else {
    echo "   ✓ Redirecionamento HTTPS já estava removido\n";
}

// 5. Atualizar configuração HTTPSSecurity
echo "5. Atualizando configuração HTTPSSecurity...\n";
if (updateFile('app/Config/HTTPSSecurity.php',
    'public bool \$forceHTTPS = true;',
    'public bool \$forceHTTPS = false;'
)) {
    echo "   ✓ HTTPSSecurity desabilitado\n";
} else {
    echo "   ✓ HTTPSSecurity já estava desabilitado\n";
}

echo "\n=== HTTPS Desabilitado ===\n";
echo "\nAgora você pode acessar: http://localhost:8080\n";