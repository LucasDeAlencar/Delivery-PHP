<?php
// Teste simples de POST para processar-associacao
echo "<h2>Teste de POST para processar-associacao</h2>";

echo '<form method="POST" action="/admin/extras/processar-associacao">';
echo '<input type="hidden" name="extra_id[]" value="1">';
echo '<input type="hidden" name="categoria_id" value="1">';
echo '<input type="hidden" name="csrf_test_name" value="test_token">';
echo '<button type="submit">Testar POST</button>';
echo '</form>';
?>
