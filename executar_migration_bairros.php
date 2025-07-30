<?php

// Script para executar migration dos bairros
echo "🔧 Executando migration para criar tabela bairros...\n";

// Executar o comando spark migrate
$output = [];
$return_var = 0;

exec('php spark migrate 2>&1', $output, $return_var);

echo "📋 Saída do comando:\n";
foreach ($output as $line) {
    echo $line . "\n";
}

if ($return_var === 0) {
    echo "\n✅ Migration executada com sucesso!\n";
} else {
    echo "\n❌ Erro ao executar migration. Código de retorno: $return_var\n";
}

echo "\n🔍 Verificando se a tabela foi criada...\n";

// Verificar se a tabela foi criada
try {
    require_once 'vendor/autoload.php';
    
    // Inicializar o CodeIgniter
    $app = \Config\Services::codeigniter();
    $app->initialize();
    
    $db = \Config\Database::connect();
    
    if ($db->tableExists('bairros')) {
        echo "✅ Tabela 'bairros' criada com sucesso!\n";
        
        // Mostrar estrutura
        $fields = $db->getFieldData('bairros');
        echo "\n📋 Estrutura da tabela:\n";
        foreach ($fields as $field) {
            echo "- {$field->name} ({$field->type})\n";
        }
    } else {
        echo "❌ Tabela 'bairros' não foi encontrada.\n";
    }
} catch (Exception $e) {
    echo "❌ Erro ao verificar tabela: " . $e->getMessage() . "\n";
}