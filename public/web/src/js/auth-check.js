/**
 * Utilitários de autenticação do lado cliente.
 * A proteção de acesso à home é feita pelo servidor (ClienteFilter).
 */
window.AuthCheck = {
    salvarCliente(email) {
        localStorage.setItem('cliente_email', email);
    },

    logout() {
        localStorage.removeItem('cliente_email');
        window.location.href = '/login';
    }
};
