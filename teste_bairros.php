<?php

require_once 'vendor/autoload.php';

// Inicializar o CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "🧪 Testando funcionalidade de bairros...\n\n";

try {
    // Verificar se a tabela existe
    $db = \Config\Database::connect();
    
    if (!$db->tableExists('bairros')) {
        echo "❌ Tabela 'bairros' não existe. Execute a migration primeiro.\n";
        exit(1);
    }
    
    echo "✅ Tabela 'bairros' encontrada.\n";
    
    // Testar o modelo
    $bairroModel = new \App\Models\BairroModel();
    
    echo "✅ Modelo BairroModel carregado com sucesso.\n";
    
    // Testar dados de exemplo
    $dadosTeste = [
        'nome' => 'Centro',
        'cidade' => 'Contagem',
        'valor_entrega' => 5.50,
        'ativo' => 1
    ];
    
    echo "🔍 Testando validação com dados: " . json_encode($dadosTeste) . "\n";
    
    // Criar entity
    $bairro = new \App\Entities\Bairro($dadosTeste);
    
    echo "✅ Entity Bairro criada com sucesso.\n";
    
    // Testar validação
    if ($bairroModel->validate($dadosTeste)) {
        echo "✅ Validação passou com sucesso.\n";
        
        // Verificar se já existe um bairro com esse nome
        $existente = $bairroModel->where('nome', $dadosTeste['nome'])->first();
        
        if ($existente) {
            echo "ℹ️ Bairro 'Centro' já existe no banco. ID: {$existente->id}\n";
        } else {
            echo "🔄 Tentando inserir bairro de teste...\n";
            
            if ($bairroModel->save($bairro)) {
                $id = $bairroModel->getInsertID();
                echo "✅ Bairro inserido com sucesso! ID: $id\n";
                
                // Buscar o bairro inserido
                $bairroInserido = $bairroModel->find($id);
                echo "📋 Dados do bairro inserido:\n";
                echo "- ID: {$bairroInserido->id}\n";
                echo "- Nome: {$bairroInserido->nome}\n";
                echo "- Slug: {$bairroInserido->slug}\n";
                echo "- Cidade: {$bairroInserido->cidade}\n";
                echo "- Valor entrega: R$ " . number_format($bairroInserido->valor_entrega, 2, ',', '.') . "\n";
                echo "- Ativo: " . ($bairroInserido->ativo ? 'Sim' : 'Não') . "\n";
                
            } else {
                echo "❌ Erro ao inserir bairro:\n";
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
    
    // Listar todos os bairros
    echo "\n📋 Listando todos os bairros:\n";
    $bairros = $bairroModel->findAll();
    
    if (empty($bairros)) {
        echo "ℹ️ Nenhum bairro encontrado.\n";
    } else {
        foreach ($bairros as $b) {
            echo "- ID: {$b->id} | Nome: {$b->nome} | Cidade: {$b->cidade} | Valor: R$ " . number_format($b->valor_entrega, 2, ',', '.') . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erro durante o teste: " . $e->getMessage() . "\n";
    echo "📍 Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")\n";
}

echo "\n🏁 Teste concluído.\n";