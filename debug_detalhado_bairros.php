<?php

require_once 'vendor/autoload.php';

// Carregar o framework
$app = \Config\Services::codeigniter();
$app->initialize();

// Obter a instância do banco de dados
$db = \Config\Database::connect();

echo "🔍 DIAGNÓSTICO DETALHADO DO PROBLEMA valor_entrega\n";
echo "=" . str_repeat("=", 70) . "\n\n";

try {
    // 1. VERIFICAR ESTRUTURA COMPLETA DA TABELA
    echo "📋 ETAPA 1: Estrutura completa da tabela bairros\n";
    echo "-" . str_repeat("-", 50) . "\n";
    
    $createTable = $db->query("SHOW CREATE TABLE bairros")->getRowArray();
    echo "Estrutura da tabela:\n";
    echo $createTable['Create Table'] . "\n\n";
    
    // 2. VERIFICAR TODOS OS INDEXES
    echo "🔑 ETAPA 2: Todos os indexes da tabela\n";
    echo "-" . str_repeat("-", 50) . "\n";
    
    $indexes = $db->query("SHOW INDEX FROM bairros")->getResultArray();
    foreach ($indexes as $index) {
        $unique = $index['Non_unique'] == 0 ? 'ÚNICO' : 'NÃO ÚNICO';
        echo "- {$index['Key_name']} | Coluna: {$index['Column_name']} | Tipo: {$unique}\n";
    }
    echo "\n";
    
    // 3. VERIFICAR CONSTRAINTS ESPECÍFICAS
    echo "🔒 ETAPA 3: Constraints específicas\n";
    echo "-" . str_repeat("-", 50) . "\n";
    
    $constraints = $db->query("
        SELECT 
            CONSTRAINT_NAME, 
            COLUMN_NAME,
            CONSTRAINT_TYPE
        FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
        JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu 
            ON tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
        WHERE tc.TABLE_SCHEMA = DATABASE() 
        AND tc.TABLE_NAME = 'bairros'
        ORDER BY CONSTRAINT_TYPE, CONSTRAINT_NAME
    ")->getResultArray();
    
    foreach ($constraints as $constraint) {
        echo "- {$constraint['CONSTRAINT_TYPE']}: {$constraint['CONSTRAINT_NAME']} na coluna {$constraint['COLUMN_NAME']}\n";
    }
    echo "\n";
    
    // 4. VERIFICAR DADOS EXISTENTES
    echo "📊 ETAPA 4: Dados existentes na tabela\n";
    echo "-" . str_repeat("-", 50) . "\n";
    
    $totalBairros = $db->query("SELECT COUNT(*) as total FROM bairros")->getRowArray();
    echo "Total de bairros: {$totalBairros['total']}\n";
    
    $bairrosAtivos = $db->query("SELECT COUNT(*) as total FROM bairros WHERE deletado_em IS NULL")->getRowArray();
    echo "Bairros ativos: {$bairrosAtivos['total']}\n";
    
    // Verificar valores de entrega únicos
    $valoresUnicos = $db->query("
        SELECT 
            valor_entrega, 
            COUNT(*) as quantidade,
            GROUP_CONCAT(nome SEPARATOR ', ') as bairros
        FROM bairros 
        WHERE deletado_em IS NULL 
        GROUP BY valor_entrega 
        ORDER BY valor_entrega
    ")->getResultArray();
    
    echo "\nValores de entrega por quantidade de bairros:\n";
    foreach ($valoresUnicos as $valor) {
        $status = $valor['quantidade'] > 1 ? '⚠️  DUPLICADO' : '✅ ÚNICO';
        echo "- R$ {$valor['valor_entrega']}: {$valor['quantidade']} bairro(s) {$status}\n";
        if ($valor['quantidade'] > 1) {
            echo "  Bairros: {$valor['bairros']}\n";
        }
    }
    echo "\n";
    
    // 5. VERIFICAR ESPECIFICAMENTE O VALOR 20.00
    echo "🎯 ETAPA 5: Análise específica do valor R$ 20,00\n";
    echo "-" . str_repeat("-", 50) . "\n";
    
    $bairros20 = $db->query("
        SELECT id, nome, cidade, valor_entrega, ativo, deletado_em 
        FROM bairros 
        WHERE valor_entrega = 20.00
        ORDER BY id
    ")->getResultArray();
    
    if (empty($bairros20)) {
        echo "✅ Nenhum bairro encontrado com valor R$ 20,00\n";
    } else {
        echo "Bairros com valor R$ 20,00:\n";
        foreach ($bairros20 as $bairro) {
            $status = $bairro['deletado_em'] ? 'EXCLUÍDO' : 'ATIVO';
            echo "- ID: {$bairro['id']} | {$bairro['nome']} | {$bairro['cidade']} | Status: {$status}\n";
        }
    }
    echo "\n";
    
    // 6. TESTAR INSERÇÃO REAL
    echo "🧪 ETAPA 6: Teste de inserção\n";
    echo "-" . str_repeat("-", 50) . "\n";
    
    // Limpar testes anteriores
    $db->query("DELETE FROM bairros WHERE nome LIKE 'TESTE_DEBUG_%'");
    
    $timestamp = date('Y-m-d H:i:s');
    
    // Primeiro teste
    echo "Tentando inserir primeiro bairro de teste...\n";
    try {
        $db->query("
            INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
            VALUES ('TESTE_DEBUG_1', 'teste-debug-1', 'Contagem', 20.00, 1, '{$timestamp}', '{$timestamp}')
        ");
        $id1 = $db->insertID();
        echo "✅ Primeiro bairro inserido com ID: {$id1}\n";
        
        // Segundo teste
        echo "Tentando inserir segundo bairro de teste com mesmo valor...\n";
        $db->query("
            INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
            VALUES ('TESTE_DEBUG_2', 'teste-debug-2', 'Contagem', 20.00, 1, '{$timestamp}', '{$timestamp}')
        ");
        $id2 = $db->insertID();
        echo "✅ Segundo bairro inserido com ID: {$id2}\n";
        
        echo "🎉 SUCESSO! Ambos os bairros foram inseridos!\n";
        
        // Limpar
        $db->query("DELETE FROM bairros WHERE id IN ({$id1}, {$id2})");
        echo "🧹 Dados de teste removidos.\n";
        
    } catch (Exception $e) {
        echo "❌ ERRO na inserção: " . $e->getMessage() . "\n";
        echo "🔍 Este é exatamente o erro que você está enfrentando!\n";
        
        // Verificar se é problema de constraint
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "\n🎯 PROBLEMA IDENTIFICADO: Constraint única ainda existe!\n";
            
            // Tentar identificar a constraint problemática
            $problematicIndexes = $db->query("
                SHOW INDEX FROM bairros 
                WHERE Column_name = 'valor_entrega' AND Non_unique = 0
            ")->getResultArray();
            
            if (!empty($problematicIndexes)) {
                echo "Constraint problemática encontrada:\n";
                foreach ($problematicIndexes as $index) {
                    echo "- Nome: {$index['Key_name']}\n";
                    echo "  Comando para remover: ALTER TABLE bairros DROP INDEX `{$index['Key_name']}`;\n";
                }
            }
        }
    }
    
    // 7. VERIFICAR USANDO O MODEL DO CODEIGNITER
    echo "\n🔧 ETAPA 7: Teste usando BairroModel\n";
    echo "-" . str_repeat("-", 50) . "\n";
    
    try {
        $bairroModel = new \App\Models\BairroModel();
        
        $dadosTest = [
            'nome' => 'TESTE_MODEL_DEBUG',
            'cidade' => 'Contagem',
            'valor_entrega' => 20.00,
            'ativo' => 1
        ];
        
        $bairro = new \App\Entities\Bairro($dadosTest);
        
        echo "Tentando salvar usando BairroModel...\n";
        if ($bairroModel->save($bairro)) {
            $id = $bairroModel->getInsertID();
            echo "✅ Bairro salvo com sucesso! ID: {$id}\n";
            
            // Limpar
            $bairroModel->delete($id, true); // hard delete
            echo "🧹 Bairro de teste removido.\n";
        } else {
            echo "❌ Erro ao salvar usando BairroModel:\n";
            $errors = $bairroModel->errors();
            foreach ($errors as $field => $error) {
                echo "- {$field}: {$error}\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ ERRO usando BairroModel: " . $e->getMessage() . "\n";
        echo "🎯 Este é o mesmo erro do seu controller!\n";
    }
    
    echo "\n" . str_repeat("=", 70) . "\n";
    echo "🏁 DIAGNÓSTICO CONCLUÍDO\n";
    echo str_repeat("=", 70) . "\n";
    
} catch (Exception $e) {
    echo "❌ ERRO GERAL: " . $e->getMessage() . "\n";
}