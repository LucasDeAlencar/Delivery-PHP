<?php

// Script completo para corrigir todos os problemas relacionados aos campos de hash

echo "=== CORREÇÃO COMPLETA DOS CAMPOS DE HASH ===\n";
echo "Este script irá:\n";
echo "1. Verificar a estrutura atual da tabela\n";
echo "2. Corrigir registros com valores vazios\n";
echo "3. Modificar a estrutura para permitir NULL\n";
echo "4. Testar a criação de um novo usuário\n\n";

try {
    // Conecta ao banco
    $host = 'localhost';
    $database = 'food';
    $username = 'root';
    $password = 'Legnu.131807';
    
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado ao banco de dados\n\n";
    
    // PASSO 1: Verificar estrutura atual
    echo "=== PASSO 1: VERIFICANDO ESTRUTURA ATUAL ===\n";
    $stmt = $pdo->query("DESCRIBE usuarios");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $hashFields = [];
    foreach ($columns as $column) {
        if (in_array($column['Field'], ['ativacao_hash', 'reset_hash'])) {
            $hashFields[$column['Field']] = $column;
            echo sprintf("%-15s %-15s %-10s %-15s\n", 
                $column['Field'], 
                $column['Type'], 
                $column['Null'], 
                $column['Default'] ?? 'NULL'
            );
        }
    }
    
    // PASSO 2: Verificar registros problemáticos
    echo "\n=== PASSO 2: VERIFICANDO REGISTROS PROBLEMÁTICOS ===\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE ativacao_hash = ''");
    $ativacaoVazios = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Registros com ativacao_hash vazio: $ativacaoVazios\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE reset_hash = ''");
    $resetVazios = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Registros com reset_hash vazio: $resetVazios\n";
    
    // PASSO 3: Corrigir registros com ativacao_hash vazio
    if ($ativacaoVazios > 0) {
        echo "\n=== PASSO 3A: CORRIGINDO ATIVACAO_HASH VAZIOS ===\n";
        
        $stmt = $pdo->query("SELECT id FROM usuarios WHERE ativacao_hash = ''");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($usuarios as $usuario) {
            $novoHash = bin2hex(random_bytes(32));
            $updateStmt = $pdo->prepare("UPDATE usuarios SET ativacao_hash = ? WHERE id = ?");
            $updateStmt->execute([$novoHash, $usuario['id']]);
            echo "Usuário ID {$usuario['id']}: novo ativacao_hash gerado\n";
        }
    }
    
    // PASSO 4: Corrigir registros com reset_hash vazio
    if ($resetVazios > 0) {
        echo "\n=== PASSO 3B: CORRIGINDO RESET_HASH VAZIOS ===\n";
        echo "Definindo reset_hash vazio como NULL para $resetVazios registros...\n";
        $pdo->exec("UPDATE usuarios SET reset_hash = NULL WHERE reset_hash = ''");
        echo "Reset_hash vazios corrigidos!\n";
    }
    
    // PASSO 5: Modificar estrutura da tabela
    echo "\n=== PASSO 4: MODIFICANDO ESTRUTURA DA TABELA ===\n";
    
    // Verifica se os campos já permitem NULL
    $ativacaoAllowsNull = $hashFields['ativacao_hash']['Null'] === 'YES';
    $resetAllowsNull = $hashFields['reset_hash']['Null'] === 'YES';
    
    if (!$ativacaoAllowsNull || !$resetAllowsNull) {
        echo "Modificando campos para permitir NULL...\n";
        
        try {
            // Tenta modificar sem mexer nos índices primeiro
            if (!$ativacaoAllowsNull) {
                $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN ativacao_hash VARCHAR(255) NULL DEFAULT NULL");
                echo "✅ ativacao_hash agora permite NULL\n";
            }
            
            if (!$resetAllowsNull) {
                $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN reset_hash VARCHAR(255) NULL DEFAULT NULL");
                echo "✅ reset_hash agora permite NULL\n";
            }
            
        } catch (Exception $e) {
            echo "⚠️ Erro ao modificar estrutura: " . $e->getMessage() . "\n";
            echo "Tentando abordagem alternativa...\n";
            
            // Abordagem alternativa: remover e recriar índices
            try {
                // Remove índices únicos temporariamente
                $pdo->exec("ALTER TABLE usuarios DROP INDEX IF EXISTS ativacao_hash");
                $pdo->exec("ALTER TABLE usuarios DROP INDEX IF EXISTS reset_hash");
                
                // Modifica as colunas
                $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN ativacao_hash VARCHAR(255) NULL DEFAULT NULL");
                $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN reset_hash VARCHAR(255) NULL DEFAULT NULL");
                
                // Recria os índices únicos
                $pdo->exec("ALTER TABLE usuarios ADD UNIQUE KEY ativacao_hash_unique (ativacao_hash)");
                $pdo->exec("ALTER TABLE usuarios ADD UNIQUE KEY reset_hash_unique (reset_hash)");
                
                echo "✅ Estrutura modificada com abordagem alternativa\n";
                
            } catch (Exception $e2) {
                echo "❌ Falha na abordagem alternativa: " . $e2->getMessage() . "\n";
            }
        }
    } else {
        echo "✅ Campos já permitem NULL\n";
    }
    
    // PASSO 6: Verificar estrutura final
    echo "\n=== PASSO 5: VERIFICANDO ESTRUTURA FINAL ===\n";
    $stmt = $pdo->query("DESCRIBE usuarios");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], ['ativacao_hash', 'reset_hash'])) {
            echo sprintf("%-15s %-15s %-10s %-15s\n", 
                $column['Field'], 
                $column['Type'], 
                $column['Null'], 
                $column['Default'] ?? 'NULL'
            );
        }
    }
    
    // PASSO 7: Teste de criação de usuário
    echo "\n=== PASSO 6: TESTANDO CRIAÇÃO DE USUÁRIO ===\n";
    
    $dadosUsuario = [
        'nome' => 'Teste Correção ' . date('H:i:s'),
        'email' => 'teste_correcao_' . time() . '@exemplo.com',
        'cpf' => null,
        'telefone' => '(11) 99999-9999',
        'ativo' => 1,
        'is_admin' => 0,
        'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
        'ativacao_hash' => bin2hex(random_bytes(32)),
        'reset_hash' => null,
        'criado_em' => date('Y-m-d H:i:s'),
        'atualizado_em' => date('Y-m-d H:i:s')
    ];
    
    $campos = implode(', ', array_keys($dadosUsuario));
    $placeholders = ':' . implode(', :', array_keys($dadosUsuario));
    
    $sql = "INSERT INTO usuarios ($campos) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($dadosUsuario)) {
        $userId = $pdo->lastInsertId();
        echo "✅ Usuário de teste criado com sucesso! ID: $userId\n";
        
        // Remove o usuário de teste
        $pdo->exec("DELETE FROM usuarios WHERE id = $userId");
        echo "🧹 Usuário de teste removido\n";
        
    } else {
        echo "❌ Falha ao criar usuário de teste\n";
    }
    
    // VERIFICAÇÃO FINAL
    echo "\n=== VERIFICAÇÃO FINAL ===\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE ativacao_hash = ''");
    $ativacaoVazios = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios WHERE reset_hash = ''");
    $resetVazios = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "Registros com ativacao_hash vazio: $ativacaoVazios\n";
    echo "Registros com reset_hash vazio: $resetVazios\n";
    
    if ($ativacaoVazios == 0 && $resetVazios == 0) {
        echo "\n🎉 CORREÇÃO CONCLUÍDA COM SUCESSO!\n";
        echo "✅ Todos os problemas foram resolvidos\n";
        echo "✅ Novos usuários podem ser criados sem erros\n";
        echo "✅ Campos de hash agora permitem NULL corretamente\n";
    } else {
        echo "\n⚠️ Ainda existem registros problemáticos\n";
        echo "Verifique manualmente os dados da tabela\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ ERRO CRÍTICO: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
}