<?php

/**
 * Script para habilitar HTTPS
 * Execute este arquivo para ativar o HTTPS no projeto
 */

echo "=== Habilitando HTTPS ===\n\n";

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
    "app.baseURL = 'http://localhost:8080'\napp.forceGlobalSecureRequests = false",
    "app.baseURL = 'https://localhost:8080'\napp.forceGlobalSecureRequests = true"
)) {
    echo "   ✓ .env atualizado\n";
} else {
    echo "   ✗ Erro ao atualizar .env\n";
}

// 2. Atualizar App.php
echo "2. Atualizando arquivo App.php...\n";
if (updateFile('app/Config/App.php',
    "public string \$baseURL = 'http://localhost:8080/';",
    "public string \$baseURL = 'https://localhost:8080/';"
) && updateFile('app/Config/App.php',
    "public bool \$forceGlobalSecureRequests = false;",
    "public bool \$forceGlobalSecureRequests = true;"
)) {
    echo "   ✓ App.php atualizado\n";
} else {
    echo "   ✗ Erro ao atualizar App.php\n";
}

// 3. Habilitar filtro HTTPS
echo "3. Habilitando filtro HTTPS...\n";
if (updateFile('app/Config/Filters.php',
    "            // 'forcehttps', // Force Global Secure Requests (disabled for development)",
    "            'forcehttps', // Force Global Secure Requests"
)) {
    echo "   ✓ Filtro HTTPS habilitado\n";
} else {
    echo "   ✗ Erro ao habilitar filtro HTTPS\n";
}

// 4. Adicionar redirecionamento .htaccess
echo "4. Adicionando redirecionamento HTTPS no .htaccess...\n";
$htaccessContent = file_get_contents('public/.htaccess');
if (strpos($htaccessContent, 'Force HTTPS redirect') === false) {
    $newHtaccess = str_replace(
        "RewriteEngine On\n\n\t# If you installed",
        "RewriteEngine On\n\n\t# Force HTTPS redirect\n\tRewriteCond %{HTTPS} off\n\tRewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n\n\t# If you installed",
        $htaccessContent
    );
    file_put_contents('public/.htaccess', $newHtaccess);
    echo "   ✓ Redirecionamento HTTPS adicionado\n";
} else {
    echo "   ✓ Redirecionamento HTTPS já existe\n";
}

// 5. Atualizar configuração HTTPSSecurity
echo "5. Atualizando configuração HTTPSSecurity...\n";
if (updateFile('app/Config/HTTPSSecurity.php',
    'public bool \$forceHTTPS = true;',
    'public bool \$forceHTTPS = true;'
)) {
    echo "   ✓ HTTPSSecurity configurado\n";
} else {
    echo "   ✓ HTTPSSecurity já estava configurado\n";
}

echo "\n=== HTTPS Habilitado ===\n";
echo "\nIMPORTANTE:\n";
echo "- Certifique-se de ter um certificado SSL configurado\n";
echo "- Para desenvolvimento local, use: mkcert localhost\n";
echo "- Ou configure um proxy reverso com SSL\n";
echo "- Teste acessando: https://localhost:8080\n";