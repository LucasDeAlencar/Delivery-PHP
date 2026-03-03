<?php
// Teste direto da lógica de busca de bairro

$db = \Config\Database::connect();

$email = 'bimdasbalas@gmail.com';

// Buscar cliente
$cliente = $db->table('clientes')
    ->where('email', $email)
    ->get()
    ->getRowArray();

echo "=== CLIENTE ===\n";
echo "Email: " . $email . "\n";
echo "Bairro: " . ($cliente['Bairro'] ?? 'NULL') . "\n";
echo "Cidade: " . ($cliente['Cidade'] ?? 'NULL') . "\n\n";

if ($cliente) {
    // Primeira busca: específica
    echo "=== BUSCA ESPECÍFICA ===\n";
    $bairroEspecifico = $db->table('bairros')
        ->where('nome', $cliente['Bairro'])
        ->where('cidade', $cliente['Cidade'])
        ->where('ativo', 1)
        ->where('deletado_em IS NULL')
        ->get()
        ->getRowArray();
    
    if ($bairroEspecifico) {
        echo "✅ Encontrado: " . $bairroEspecifico['nome'] . ", " . $bairroEspecifico['cidade'] . " - R$ " . $bairroEspecifico['valor_entrega'] . "\n";
    } else {
        echo "❌ Não encontrado: " . $cliente['Bairro'] . ", " . $cliente['Cidade'] . "\n";
    }
    
    // Segunda busca: universal
    echo "\n=== BUSCA UNIVERSAL ===\n";
    $bairroUniversal = $db->table('bairros')
        ->where('nome', '*')
        ->where('cidade', $cliente['Cidade'])
        ->where('ativo', 1)
        ->where('deletado_em IS NULL')
        ->get()
        ->getRowArray();
    
    if ($bairroUniversal) {
        echo "✅ Encontrado: " . $bairroUniversal['nome'] . ", " . $bairroUniversal['cidade'] . " - R$ " . $bairroUniversal['valor_entrega'] . "\n";
    } else {
        echo "❌ Não encontrado: *, " . $cliente['Cidade'] . "\n";
    }
    
    // Resultado final
    echo "\n=== RESULTADO FINAL ===\n";
    $bairroFinal = $bairroEspecifico ?: $bairroUniversal;
    
    if ($bairroFinal) {
        echo "✅ ENTREGA DISPONÍVEL\n";
        echo "Bairro usado: " . $bairroFinal['nome'] . "\n";
        echo "Valor: R$ " . $bairroFinal['valor_entrega'] . "\n";
    } else {
        echo "❌ ENTREGA NÃO DISPONÍVEL\n";
    }
}
?>
