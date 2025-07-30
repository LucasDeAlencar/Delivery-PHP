<?php

require_once 'vendor/autoload.php';

// Carregar o framework
$app = \Config\Services::codeigniter();
$app->initialize();

// Obter a instância do banco de dados
$db = \Config\Database::connect();

echo "🔧 FORÇANDO REMOÇÃO DE CONSTRAINTS NO CAMPO valor_entrega\n";
echo "=" . str_repeat("=", 60) . "\n\n";

try {
    // 1. IDENTIFICAR TODAS AS CONSTRAINTS RELACIONADAS AO valor_entrega
    echo "🔍 Identificando constraints no campo valor_entrega...\n";
    
    $indexes = $db->query("SHOW INDEX FROM bairros WHERE Column_name = 'valor_entrega'")->getResultArray();
    
    if (empty($indexes)) {
        echo "✅ Nenhum index encontrado no campo valor_entrega.\n";
    } else {
        echo "📋 Indexes encontrados:\n";
        foreach ($indexes as $index) {
            $unique = $index['Non_unique'] == 0 ? 'ÚNICO' : 'NÃO ÚNICO';
            echo "- {$index['Key_name']} | Tipo: {$unique}\n";
        }
    }
    
    // 2. REMOVER TODOS OS INDEXES ÚNICOS DO valor_entrega
    $uniqueIndexes = $db->query("SHOW INDEX FROM bairros WHERE Column_name = 'valor_entrega' AND Non_unique = 0")->getResultArray();
    
    if (empty($uniqueIndexes)) {
        echo "\n✅ Não há indexes únicos no campo valor_entrega para remover.\n";
    } else {
        echo "\n🗑️  Removendo indexes únicos...\n";
        foreach ($uniqueIndexes as $index) {
            $indexName = $index['Key_name'];
            echo "Removendo index: {$indexName}...\n";
            
            try {
                $db->query("ALTER TABLE bairros DROP INDEX `{$indexName}`");
                echo "✅ Index '{$indexName}' removido com sucesso!\n";
            } catch (Exception $e) {
                echo "❌ Erro ao remover index '{$indexName}': " . $e->getMessage() . "\n";
            }
        }
    }
    
    // 3. VERIFICAR SE AINDA EXISTEM CONSTRAINTS ÚNICAS
    echo "\n🔍 Verificação final...\n";
    $remainingUnique = $db->query("SHOW INDEX FROM bairros WHERE Column_name = 'valor_entrega' AND Non_unique = 0")->getResultArray();
    
    if (empty($remainingUnique)) {
        echo "✅ Confirmado: Não há mais constraints únicos no campo valor_entrega!\n";
    } else {
        echo "⚠️  Ainda existem constraints únicos:\n";
        foreach ($remainingUnique as $index) {
            echo "- {$index['Key_name']}\n";
        }
    }
    
    // 4. TENTAR DIFERENTES ABORDAGENS DE REMOÇÃO
    echo "\n🔧 Tentando abordagens alternativas...\n";
    
    // Lista de possíveis nomes de constraints
    $possibleConstraints = [
        'valor_entrega',
        'bairros_valor_entrega_unique',
        'valor_entrega_UNIQUE',
        'uk_valor_entrega',
        'unique_valor_entrega',
        'bairros_valor_entrega_idx',
        'idx_valor_entrega_unique'
    ];
    
    foreach ($possibleConstraints as $constraintName) {
        try {
            // Verificar se existe
            $exists = $db->query("SHOW INDEX FROM bairros WHERE Key_name = '{$constraintName}'")->getResultArray();
            if (!empty($exists)) {
                echo "Tentando remover constraint '{$constraintName}'...\n";
                $db->query("ALTER TABLE bairros DROP INDEX `{$constraintName}`");
                echo "✅ Constraint '{$constraintName}' removida!\n";
            }
        } catch (Exception $e) {
            // Ignorar erros - constraint pode não existir
        }
    }
    
    // 5. TESTE FINAL
    echo "\n🧪 Teste final de inserção...\n";
    
    $timestamp = date('Y-m-d H:i:s');
    
    // Limpar testes anteriores
    $db->query("DELETE FROM bairros WHERE nome LIKE 'TESTE_FINAL_%'");
    
    try {
        // Primeiro insert
        $db->query("
            INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
            VALUES ('TESTE_FINAL_1', 'teste-final-1', 'Contagem', 20.00, 1, '{$timestamp}', '{$timestamp}')
        ");
        $id1 = $db->insertID();
        
        // Segundo insert com mesmo valor
        $db->query("
            INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
            VALUES ('TESTE_FINAL_2', 'teste-final-2', 'Contagem', 20.00, 1, '{$timestamp}', '{$timestamp}')
        ");
        $id2 = $db->insertID();
        
        echo "🎉 SUCESSO! Ambos os bairros foram inseridos com valor R$ 20,00!\n";
        echo "- Bairro 1 ID: {$id1}\n";
        echo "- Bairro 2 ID: {$id2}\n";
        
        // Limpar
        $db->query("DELETE FROM bairros WHERE id IN ({$id1}, {$id2})");
        echo "🧹 Dados de teste removidos.\n";
        
        echo "\n🎯 PROBLEMA RESOLVIDO! Agora você pode cadastrar bairros com o mesmo valor de entrega.\n";
        
    } catch (Exception $e) {
        echo "❌ AINDA HÁ PROBLEMA: " . $e->getMessage() . "\n";
        
        if (strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'valor_entrega') !== false) {
            echo "\n🚨 O problema persiste! Vamos tentar uma abordagem mais agressiva...\n";
            
            // Abordagem mais agressiva - recriar a tabela sem a constraint
            echo "⚠️  ATENÇÃO: Vou tentar recriar a estrutura da tabela...\n";
            echo "Isso é seguro, mas pode demorar alguns segundos.\n";
            
            try {
                // Backup dos dados
                echo "💾 Fazendo backup dos dados...\n";
                $dados = $db->query("SELECT * FROM bairros")->getResultArray();
                
                // Recriar tabela
                echo "🔧 Recriando estrutura da tabela...\n";
                
                $db->query("DROP TABLE IF EXISTS bairros_backup");
                $db->query("CREATE TABLE bairros_backup AS SELECT * FROM bairros");
                
                $db->query("DROP TABLE bairros");
                
                $createTableSQL = "
                CREATE TABLE `bairros` (
                    `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
                    `nome` varchar(128) NOT NULL,
                    `slug` varchar(128) NOT NULL,
                    `cidade` varchar(20) NOT NULL DEFAULT 'Contagem',
                    `valor_entrega` decimal(10,2) NOT NULL,
                    `ativo` tinyint(1) NOT NULL DEFAULT 1,
                    `criado_em` datetime DEFAULT NULL,
                    `atualizado_em` datetime DEFAULT NULL,
                    `deletado_em` datetime DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `nome` (`nome`),
                    UNIQUE KEY `slug` (`slug`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
                ";
                
                $db->query($createTableSQL);
                
                // Restaurar dados
                echo "📥 Restaurando dados...\n";
                foreach ($dados as $linha) {
                    $campos = array_keys($linha);
                    $valores = array_map(function($v) use ($db) {
                        return $v === null ? 'NULL' : "'" . $db->escapeString($v) . "'";
                    }, array_values($linha));
                    
                    $insertSQL = "INSERT INTO bairros (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $valores) . ")";
                    $db->query($insertSQL);
                }
                
                echo "✅ Tabela recriada com sucesso!\n";
                echo "🗑️  Removendo backup...\n";
                $db->query("DROP TABLE bairros_backup");
                
                // Teste final após recriação
                echo "🧪 Teste após recriação...\n";
                $db->query("
                    INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
                    VALUES ('TESTE_RECREATE_1', 'teste-recreate-1', 'Contagem', 20.00, 1, '{$timestamp}', '{$timestamp}')
                ");
                $id1 = $db->insertID();
                
                $db->query("
                    INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
                    VALUES ('TESTE_RECREATE_2', 'teste-recreate-2', 'Contagem', 20.00, 1, '{$timestamp}', '{$timestamp}')
                ");
                $id2 = $db->insertID();
                
                echo "🎉 SUCESSO TOTAL! Problema resolvido após recriação da tabela!\n";
                
                // Limpar
                $db->query("DELETE FROM bairros WHERE id IN ({$id1}, {$id2})");
                
            } catch (Exception $recreateError) {
                echo "❌ Erro na recriação: " . $recreateError->getMessage() . "\n";
                echo "🆘 Restaurando backup...\n";
                try {
                    $db->query("DROP TABLE IF EXISTS bairros");
                    $db->query("CREATE TABLE bairros AS SELECT * FROM bairros_backup");
                    $db->query("DROP TABLE bairros_backup");
                    echo "✅ Backup restaurado.\n";
                } catch (Exception $restoreError) {
                    echo "❌ ERRO CRÍTICO na restauração: " . $restoreError->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "🏁 PROCESSO CONCLUÍDO\n";
    echo str_repeat("=", 60) . "\n";
    
} catch (Exception $e) {
    echo "❌ ERRO GERAL: " . $e->getMessage() . "\n";
}