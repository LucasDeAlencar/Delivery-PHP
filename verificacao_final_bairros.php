<?php

require_once 'vendor/autoload.php';

// Inicializar o CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "🔍 VERIFICAÇÃO FINAL - Sistema de Bairros\n";
echo "==========================================\n\n";

$erros = [];
$sucessos = [];

try {
    // 1. Verificar se a tabela existe
    echo "1. Verificando tabela no banco de dados...\n";
    $db = \Config\Database::connect();
    
    if ($db->tableExists('bairros')) {
        $sucessos[] = "✅ Tabela 'bairros' existe no banco";
        
        // Verificar estrutura
        $fields = $db->getFieldData('bairros');
        $camposEsperados = ['id', 'nome', 'slug', 'cidade', 'valor_entrega', 'ativo', 'criado_em', 'atualizado_em', 'deletado_em'];
        $camposEncontrados = array_column($fields, 'name');
        
        foreach ($camposEsperados as $campo) {
            if (in_array($campo, $camposEncontrados)) {
                $sucessos[] = "✅ Campo '$campo' existe";
            } else {
                $erros[] = "❌ Campo '$campo' não encontrado";
            }
        }
    } else {
        $erros[] = "❌ Tabela 'bairros' não existe";
    }
    
    // 2. Verificar arquivos do sistema
    echo "\n2. Verificando arquivos do sistema...\n";
    
    $arquivos = [
        'Controller' => 'app/Controllers/Admin/Bairros.php',
        'Model' => 'app/Models/BairroModel.php',
        'Entity' => 'app/Entities/Bairro.php',
        'Migration' => 'app/Database/Migrations/2025-01-29-205726_CriaTabelaBairros.php',
        'View Index' => 'app/Views/Admin/Bairros/index.php',
        'View Criar' => 'app/Views/Admin/Bairros/criar.php',
        'View Editar' => 'app/Views/Admin/Bairros/editar.php',
        'View Show' => 'app/Views/Admin/Bairros/show.php',
        'View Excluir' => 'app/Views/Admin/Bairros/excluir.php'
    ];
    
    foreach ($arquivos as $tipo => $arquivo) {
        if (file_exists($arquivo)) {
            $sucessos[] = "✅ $tipo existe ($arquivo)";
        } else {
            $erros[] = "❌ $tipo não encontrado ($arquivo)";
        }
    }
    
    // 3. Verificar se as classes podem ser carregadas
    echo "\n3. Verificando carregamento das classes...\n";
    
    try {
        $controller = new \App\Controllers\Admin\Bairros();
        $sucessos[] = "✅ Controller Bairros carregado";
    } catch (Exception $e) {
        $erros[] = "❌ Erro ao carregar Controller: " . $e->getMessage();
    }
    
    try {
        $model = new \App\Models\BairroModel();
        $sucessos[] = "✅ Model BairroModel carregado";
    } catch (Exception $e) {
        $erros[] = "❌ Erro ao carregar Model: " . $e->getMessage();
    }
    
    try {
        $entity = new \App\Entities\Bairro();
        $sucessos[] = "✅ Entity Bairro carregada";
    } catch (Exception $e) {
        $erros[] = "❌ Erro ao carregar Entity: " . $e->getMessage();
    }
    
    // 4. Testar validação
    echo "\n4. Testando validação...\n";
    
    $dadosTeste = [
        'nome' => 'Centro Teste',
        'cidade' => 'Contagem',
        'valor_entrega' => 5.50,
        'ativo' => 1
    ];
    
    try {
        $model = new \App\Models\BairroModel();
        if ($model->validate($dadosTeste)) {
            $sucessos[] = "✅ Validação funcionando corretamente";
        } else {
            $erros[] = "❌ Falha na validação: " . implode(', ', $model->errors());
        }
    } catch (Exception $e) {
        $erros[] = "❌ Erro ao testar validação: " . $e->getMessage();
    }
    
    // 5. Verificar rotas
    echo "\n5. Verificando rotas...\n";
    
    $routes = \Config\Services::routes();
    $routes->loadRoutes();
    $collection = $routes->getRoutes();
    
    $rotasEsperadas = [
        'admin/bairros',
        'admin/bairros/criar',
        'admin/bairros/cadastrar',
        'admin/bairros/editar/([0-9]+)',
        'admin/bairros/atualizar/([0-9]+)',
        'admin/bairros/excluir/([0-9]+)',
        'admin/bairros/deletar/([0-9]+)',
        'admin/bairros/show/([0-9]+)'
    ];
    
    foreach ($rotasEsperadas as $rota) {
        $encontrada = false;
        foreach ($collection as $route => $handler) {
            if (strpos($route, str_replace('([0-9]+)', '(:num)', $rota)) !== false) {
                $encontrada = true;
                break;
            }
        }
        
        if ($encontrada) {
            $sucessos[] = "✅ Rota '$rota' configurada";
        } else {
            $erros[] = "❌ Rota '$rota' não encontrada";
        }
    }
    
} catch (Exception $e) {
    $erros[] = "❌ Erro geral: " . $e->getMessage();
}

// Exibir resultados
echo "\n" . str_repeat("=", 50) . "\n";
echo "RESULTADOS DA VERIFICAÇÃO\n";
echo str_repeat("=", 50) . "\n\n";

if (!empty($sucessos)) {
    echo "✅ SUCESSOS:\n";
    foreach ($sucessos as $sucesso) {
        echo "   $sucesso\n";
    }
    echo "\n";
}

if (!empty($erros)) {
    echo "❌ PROBLEMAS ENCONTRADOS:\n";
    foreach ($erros as $erro) {
        echo "   $erro\n";
    }
    echo "\n";
} else {
    echo "🎉 PARABÉNS! Nenhum problema encontrado!\n\n";
}

// Resumo
$totalSucessos = count($sucessos);
$totalErros = count($erros);
$totalVerificacoes = $totalSucessos + $totalErros;

echo "📊 RESUMO:\n";
echo "   Total de verificações: $totalVerificacoes\n";
echo "   Sucessos: $totalSucessos\n";
echo "   Problemas: $totalErros\n";

if ($totalErros == 0) {
    echo "\n🚀 Sistema de Bairros está 100% funcional!\n";
    echo "   Acesse: /admin/bairros\n";
} else {
    echo "\n⚠️  Corrija os problemas antes de usar o sistema.\n";
}

echo "\n🏁 Verificação concluída.\n";