<?php
// Teste simples do método enviarCodigo
echo "<h2>Teste do enviarCodigo</h2>";

echo '<form id="testeForm">';
echo '<input type="email" id="email" placeholder="Digite seu email" required>';
echo '<button type="button" onclick="testarEnvio()">Testar Envio</button>';
echo '</form>';

echo '<div id="resultado"></div>';

echo '<script>
function testarEnvio() {
    const email = document.getElementById("email").value;
    
    fetch("/login/enviarCodigo", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            email: email,
            "csrf_test_name": "test_token"
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("resultado").innerHTML = "<pre>" + JSON.stringify(data, null, 2) + "</pre>";
    })
    .catch(error => {
        document.getElementById("resultado").innerHTML = "Erro: " + error;
    });
}
</script>';
?>
