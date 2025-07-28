<?php

// Script para corrigir o problema do reset_hash que não pode ser NULL

echo "Iniciando correção do campo reset_hash...\n";

try {
    // Conecta diretamente ao banco usando as configurações do .env
    $host = 'localhost';
    $database = 'food';
    $username = 'root';
    $password = 'Legnu.131807';
    
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado ao banco de dados com sucesso!\n";
    
    // Primeiro, vamos verificar a estrutura atual
    echo "\n=== ESTRUTURA ATUAL DOS CAMPOS DE HASH ===\n";
    $stmt = $pdo->query("DESCRIBE usuarios");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], ['ativacao_hash', 'reset_hash'])) {
            echo sprintf("%-15s %-15s %-10s %-10s %-15s\n", 
                $column['Field'], 
                $column['Type'], 
                $column['Null'], 
                $column['Key'], 
                $column['Default']
            );
        }
    }
    
    // Verifica registros problemáticos
    echo "\n=== VERIFICANDO REGISTROS PROBLEMÁTICOS ===\n";
    
    // Verifica registros com reset_hash vazio
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE reset_hash = ''");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Registros com reset_hash vazio: " . $result['count'] . "\n";
    
    // Verifica registros com ativacao_hash vazio
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE ativacao_hash = ''");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Registros com ativacao_hash vazio: " . $result['count'] . "\n";
    
    // CORREÇÃO 1: Atualizar registros com reset_hash vazio para NULL
    echo "\n=== CORRIGINDO REGISTROS COM RESET_HASH VAZIO ===\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE reset_hash = ''");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] > 0) {
        echo "Atualizando {$result['count']} registros com reset_hash vazio para NULL...\n";
        $pdo->exec("UPDATE usuarios SET reset_hash = NULL WHERE reset_hash = ''");
        echo "Registros atualizados com sucesso!\n";
    } else {
        echo "Nenhum registro com reset_hash vazio encontrado.\n";
    }
    
    // CORREÇÃO 2: Atualizar registros com ativacao_hash vazio
    echo "\n=== CORRIGINDO REGISTROS COM ATIVACAO_HASH VAZIO ===\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE ativacao_hash = ''");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] > 0) {
        echo "Gerando hashes únicos para {$result['count']} registros...\n";
        
        $stmt = $pdo->query("SELECT id FROM usuarios WHERE ativacao_hash = ''");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($usuarios as $usuario) {
            $novoHash = bin2hex(random_bytes(32));
            $updateStmt = $pdo->prepare("UPDATE usuarios SET ativacao_hash = ? WHERE id = ?");
            $updateStmt->execute([$novoHash, $usuario['id']]);
            echo "Usuário ID {$usuario['id']}: novo ativacao_hash gerado\n";
        }
    } else {
        echo "Nenhum registro com ativacao_hash vazio encontrado.\n";
    }
    
    // CORREÇÃO 3: Modificar estrutura da tabela para permitir NULL
    echo "\n=== MODIFICANDO ESTRUTURA DA TABELA ===\n";
    
    try {
        // Remove a constraint UNIQUE temporariamente se existir
        echo "Removendo constraints UNIQUE temporariamente...\n";
        
        // Verifica se existem índices únicos
        $stmt = $pdo->query("SHOW INDEX FROM usuarios WHERE Key_name LIKE '%ativacao_hash%' OR Key_name LIKE '%reset_hash%'");
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $indexesToRecreate = [];
        foreach ($indexes as $index) {
            if ($index['Non_unique'] == 0) { // É um índice único
                $indexesToRecreate[] = $index['Key_name'];
                echo "Removendo índice único: {$index['Key_name']}\n";
                $pdo->exec("ALTER TABLE usuarios DROP INDEX {$index['Key_name']}");
            }
        }
        
        // Modifica as colunas para permitir NULL
        echo "Modificando colunas para permitir NULL...\n";
        $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN ativacao_hash VARCHAR(255) NULL DEFAULT NULL");
        $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN reset_hash VARCHAR(255) NULL DEFAULT NULL");
        
        // Recria os índices únicos
        foreach ($indexesToRecreate as $indexName) {
            if (strpos($indexName, 'ativacao') !== false) {
                echo "Recriando índice único para ativacao_hash...\n";
                $pdo->exec("ALTER TABLE usuarios ADD UNIQUE KEY ativacao_hash_unique (ativacao_hash)");
            } elseif (strpos($indexName, 'reset') !== false) {
                echo "Recriando índice único para reset_hash...\n";
                $pdo->exec("ALTER TABLE usuarios ADD UNIQUE KEY reset_hash_unique (reset_hash)");
            }
        }
        
        echo "Estrutura da tabela modificada com sucesso!\n";
        
    } catch (Exception $e) {
        echo "Erro ao modificar estrutura: " . $e->getMessage() . "\n";
        echo "Tentando abordagem alternativa...\n";
        
        // Abordagem alternativa: modificar diretamente
        $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN ativacao_hash VARCHAR(255) NULL DEFAULT NULL");
        $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN reset_hash VARCHAR(255) NULL DEFAULT NULL");
        echo "Estrutura modificada com abordagem alternativa!\n";
    }
    
    // VERIFICAÇÃO FINAL
    echo "\n=== VERIFICAÇÃO FINAL ===\n";
    
    // Verifica a nova estrutura
    $stmt = $pdo->query("DESCRIBE usuarios");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], ['ativacao_hash', 'reset_hash'])) {
            echo sprintf("%-15s %-15s %-10s %-10s %-15s\n", 
                $column['Field'], 
                $column['Type'], 
                $column['Null'], 
                $column['Key'], 
                $column['Default']
            );
        }
    }
    
    // Verifica registros problemáticos
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE reset_hash = ''");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nRegistros com reset_hash vazio após correção: " . $result['count'] . "\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE ativacao_hash = ''");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Registros com ativacao_hash vazio após correção: " . $result['count'] . "\n";
    
    echo "\n✅ CORREÇÃO CONCLUÍDA COM SUCESSO!\n";
    echo "Agora você pode criar novos usuários sem problemas de NULL.\n";
    
} catch (Exception $e) {
    echo "❌ ERRO durante a correção: " . $e->getMessage() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
}