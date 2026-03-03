// Configuração global do jQuery para incluir credenciais em todas as requisições AJAX
$(document).ready(function() {
    // Configurar jQuery para sempre enviar cookies/credenciais nas requisições AJAX
    $.ajaxSetup({
        xhrFields: {
            withCredentials: true
        }
    });
});
