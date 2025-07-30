<?php

require_once 'vendor/autoload.php';

// Inicializar o CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "🔍 Debug do problema de cadastro de bairros...\n\n";

try {
    // Simular dados do POST como se viessem do formulário
    $dadosSimulados = [
        'csrf_test_name' => 'token_csrf_aqui', // Este é o problema!
        'nome' => 'Centro',
        'cep' => '32010-000', // Este campo não deve ir para o banco
        'cidade' => 'Contagem',
        'valor_entrega' => '5,50',
        'ativo' => '1'
    ];
    
    echo "📋 Dados simulados do POST:\n";
    foreach ($dadosSimulados as $campo => $valor) {
        echo "- $campo: $valor\n";
    }
    
    echo "\n🔧 Filtrando apenas campos permitidos...\n";
    
    // Campos permitidos (igual ao controller)
    $camposPermitidos = ['nome', 'cidade', 'valor_entrega', 'ativo'];
    $dadosLimpos = [];
    
    foreach ($camposPermitidos as $campo) {
        if (isset($dadosSimulados[$campo])) {
            $dadosLimpos[$campo] = $dadosSimulados[$campo];
        }
    }
    
    // Garantir que ativo seja 0 se não foi enviado
    if (!isset($dadosLimpos['ativo'])) {
        $dadosLimpos['ativo'] = 0;
    }
    
    // Converter valor de entrega
    if (isset($dadosLimpos['valor_entrega'])) {
        $valor = $dadosLimpos['valor_entrega'];
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
        $dadosLimpos['valor_entrega'] = is_numeric($valor) ? (float) $valor : 0;
    }
    
    echo "✅ Dados filtrados e limpos:\n";
    foreach ($dadosLimpos as $campo => $valor) {
        echo "- $campo: $valor (" . gettype($valor) . ")\n";
    }
    
    echo "\n🧪 Testando criação da Entity...\n";
    
    // Criar entity
    $bairro = new \App\Entities\Bairro($dadosLimpos);
    
    echo "✅ Entity criada com sucesso!\n";
    echo "📋 Dados na entity:\n";
    echo "- Nome: " . ($bairro->nome ?? 'null') . "\n";
    echo "- Cidade: " . ($bairro->cidade ?? 'null') . "\n";
    echo "- Valor entrega: " . ($bairro->valor_entrega ?? 'null') . "\n";
    echo "- Ativo: " . ($bairro->ativo ?? 'null') . "\n";
    
    echo "\n🧪 Testando validação...\n";
    
    $bairroModel = new \App\Models\BairroModel();
    
    if ($bairroModel->validate($dadosLimpos)) {
        echo "✅ Validação passou!\n";
        
        echo "\n🧪 Testando inserção no banco...\n";
        
        // Verificar se já existe
        $existente = $bairroModel->where('nome', $dadosLimpos['nome'])->first();
        if ($existente) {
            echo "ℹ️ Bairro já existe, pulando inserção.\n";
        } else {
            if ($bairroModel->save($bairro)) {
                echo "✅ Inserção realizada com sucesso!\n";
                echo "🆔 ID gerado: " . $bairroModel->getInsertID() . "\n";
            } else {
                echo "❌ Erro na inserção:\n";
                foreach ($bairroModel->errors() as $error) {
                    echo "- $error\n";
                }
            }
        }
        
    } else {
        echo "❌ Falha na validação:\n";
        foreach ($bairroModel->errors() as $error) {
            echo "- $error\n";
        }
    }
    
    echo "\n🔍 Verificando estrutura da tabela bairros...\n";
    
    $db = \Config\Database::connect();
    if ($db->tableExists('bairros')) {
        $fields = $db->getFieldData('bairros');
        echo "📋 Campos da tabela:\n";
        foreach ($fields as $field) {
            echo "- {$field->name} ({$field->type}";
            if (isset($field->max_length)) {
                echo ", max: {$field->max_length}";
            }
            echo ")\n";
        }
    } else {
        echo "❌ Tabela 'bairros' não existe!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro durante debug: " . $e->getMessage() . "\n";
    echo "📍 Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")\n";
    echo "📋 Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🏁 Debug concluído.\n";