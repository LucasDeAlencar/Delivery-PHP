<?php

require_once 'vendor/autoload.php';

// Carregar o framework
$app = \Config\Services::codeigniter();
$app->initialize();

// Obter a instância do banco de dados
$db = \Config\Database::connect();

try {
    // Verificar se a tabela bairros existe
    if ($db->tableExists('bairros')) {
        echo "✅ A tabela 'bairros' já existe no banco de dados.\n";
        
        // Verificar a estrutura da tabela
        $fields = $db->getFieldData('bairros');
        echo "\n📋 Estrutura da tabela 'bairros':\n";
        foreach ($fields as $field) {
            echo "- {$field->name} ({$field->type})\n";
        }
    } else {
        echo "❌ A tabela 'bairros' NÃO existe no banco de dados.\n";
        echo "🔧 Executando migration para criar a tabela...\n";
        
        // Executar a migration
        $migrate = \Config\Services::migrations();
        $migrate->setNamespace('App');
        
        try {
            $migrate->latest();
            echo "✅ Migration executada com sucesso!\n";
        } catch (Exception $e) {
            echo "❌ Erro ao executar migration: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erro ao verificar tabela: " . $e->getMessage() . "\n";
}