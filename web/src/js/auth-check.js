/**
 * Sistema de verificação de autenticação simples
 */
window.AuthCheck = {
    init() {
        // Só executa se não estiver na página de login ou admin
        const path = window.location.pathname;
        if (path === '/login' || path.includes('/login') || path.includes('/admin')) {
            return;
        }

        this.verificarAutenticacao();
    },

    verificarAutenticacao() {
        const emailSalvo = localStorage.getItem('cliente_email');
        
        if (!emailSalvo) {
            // Não tem email salvo - redirecionar para login
            this.redirecionarParaLogin();
        }
        // Se tem email salvo, deixa continuar
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
