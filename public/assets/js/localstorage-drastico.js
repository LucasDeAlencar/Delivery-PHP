/**
 * SISTEMA DRÁSTICO DE LOCALSTORAGE
 * Força salvamento e carregamento
 */

window.LocalStorageDrastico = {
    chave: 'carrinho_delivery_drastico',
    
    salvar: function(dados) {
        try {
            // Múltiplas tentativas de salvamento
            const json = JSON.stringify(dados);
            
            // Tentativa 1: localStorage normal
            localStorage.setItem(this.chave, json);
            
            // Tentativa 2: sessionStorage como backup
            sessionStorage.setItem(this.chave, json);
            
            // Tentativa 3: cookie como backup
            document.cookie = `${this.chave}=${encodeURIComponent(json)}; path=/; max-age=86400`;
            
            
            // Verifica se salvou
            const verificacao = this.carregar();
            if (verificacao && verificacao.length > 0) {
                return true;
            } else {
                console.error('❌ ERRO: Dados não foram salvos');
                return false;
            }
        } catch (e) {
            console.error('❌ ERRO ao salvar:', e);
            return false;
        }
    },
    
    carregar: function() {
        try {
            // Tenta carregar do localStorage
            let dados = localStorage.getItem(this.chave);
            
            // Se não encontrou, tenta sessionStorage
            if (!dados) {
                dados = sessionStorage.getItem(this.chave);
            }
            
            // Se não encontrou, tenta cookie
            if (!dados) {
                const cookies = document.cookie.split(';');
                for (let cookie of cookies) {
                    const [nome, valor] = cookie.trim().split('=');
                    if (nome === this.chave) {
                        dados = decodeURIComponent(valor);
                        break;
                    }
                }
            }
            
            if (dados) {
                const itens = JSON.parse(dados);
                return itens;
            } else {
                return [];
            }
        } catch (e) {
            console.error('❌ ERRO ao carregar:', e);
            return [];
        }
    },
    
    limpar: function() {
        localStorage.removeItem(this.chave);
        sessionStorage.removeItem(this.chave);
        document.cookie = `${this.chave}=; path=/; max-age=0`;
    }
};

// Substitui os métodos do carrinho
if (window.Carrinho) {
    window.Carrinho.sincronizarLocalStorage = function() {
        window.LocalStorageDrastico.salvar(this.itens);
    };
    
    window.Carrinho.carregarDoLocalStorage = function() {
        this.itens = window.LocalStorageDrastico.carregar();
    };
    
}
