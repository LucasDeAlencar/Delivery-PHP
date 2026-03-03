// Função global para abrir o popup do carrinho
function abrirCarrinhoPopup() {
    if (typeof window.Carrinho !== 'undefined') {
        window.Carrinho.atualizarPopup();
    }
    
    const popup = document.getElementById('carrinho-popup');
    if (popup) {
        popup.style.display = 'flex';
    }
}

// Função global para fechar o popup do carrinho
function fecharCarrinhoPopup() {
    const popup = document.getElementById('carrinho-popup');
    if (popup) {
        popup.style.display = 'none';
    }
}

// Função global para calcular total (mantida para compatibilidade)
function calcularTotal() {
    if (typeof window.Carrinho !== 'undefined') {
        window.Carrinho.atualizarPopup();
    }
}

// Função global para finalizar compra
function finalizarCompra() {
    if (typeof window.Carrinho !== 'undefined') {
        window.Carrinho.finalizar();
        fecharCarrinhoPopup();
    }
}
