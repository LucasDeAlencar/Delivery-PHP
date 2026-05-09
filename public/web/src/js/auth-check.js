/**
 * Sistema de verificação de autenticação simples
 */
window.AuthCheck = {
    init() {
        // Só executa se não estiver na página de login ou admin
        const path = window.location.pathname;
        if (path.includes('/login') || path.includes('/admin')) {
            return;
        }

        this.verificarAutenticacao();
    },

    verificarAutenticacao() {
        // Se já estiver logado segundo variável global, ok
        if (window.clienteLogado && window.clienteLogado.logado) {
            return;
        }

        // Caso contrário, redireciona para login
        this.redirecionarParaLogin();
    },

    redirecionarParaLogin() {
        window.location.href = '/login';
    },

    salvarCliente(email) {
        localStorage.setItem('cliente_email', email);
    },

    logout() {
        localStorage.removeItem('cliente_email');
        window.location.href = '/login';
    }
};

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.AuthCheck.init();
});
