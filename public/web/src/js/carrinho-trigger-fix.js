$(document).ready(function() {
    // Evento para abrir carrinho ao clicar no ícone da navbar
    $(document).on('click', '.carrinho-navbar a, .fa-shopping-cart', function(e) {
        e.preventDefault();
        
        // Remover popup existente
        $('#carrinho-popup').remove();
        
        // Criar popup simples
        const popup = `
            <div id="carrinho-popup" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                <div style="background: #1a1a1a; width: 90%; max-width: 500px; border-radius: 15px; padding: 20px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #f8b531; margin: 0;">Meu Carrinho</h3>
                        <button onclick="$('#carrinho-popup').remove()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer;">&times;</button>
                    </div>
                    
                    <div id="carrinho-lista">
                        <p>Carregando...</p>
                    </div>
                    
                    <div style="margin: 20px 0;">
                        <h4 style="color: #f8b531;">Forma de Recebimento:</h4>
                        <label style="display: block; margin: 10px 0; cursor: pointer;">
                            <input type="radio" name="tipoEntrega" value="retirada" style="margin-right: 10px;">
                            Retirada na Loja
                        </label>
                        <label style="display: block; margin: 10px 0; cursor: pointer;">
                            <input type="radio" name="tipoEntrega" value="entrega" style="margin-right: 10px;">
                            Entrega
                        </label>
                    </div>
                    
                    <div style="border-top: 2px solid #f8b531; padding-top: 15px; margin-top: 20px;">
                        <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                            <span>Subtotal:</span>
                            <span id="subtotal">R$ 0,00</span>
                        </div>
                        <div id="taxa-entrega-linha" style="display: none; justify-content: space-between; margin: 5px 0;">
                            <span>Taxa de Entrega:</span>
                            <span id="taxa-entrega">R$ 0,00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin: 10px 0; font-weight: bold; font-size: 18px; color: #f8b531; border-top: 1px solid #333; padding-top: 10px;">
                            <span>Total:</span>
                            <span id="total-final">R$ 0,00</span>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button onclick="$('#carrinho-popup').remove()" style="flex: 1; padding: 12px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer;">Cancelar</button>
                        <button id="btn-finalizar" style="flex: 1; padding: 12px; background: #f8b531; color: black; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Finalizar Compra</button>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(popup);
        
        // Carregar itens
        setTimeout(function() {
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
            let html = '';
            let subtotal = 0;
            
            if (carrinho.length === 0) {
                html = '<p style="text-align: center; color: #666; padding: 20px;">Seu carrinho está vazio</p>';
            } else {
                carrinho.forEach(item => {
                    subtotal += item.preco * item.quantidade;
                    html += `
                        <div style="border-bottom: 1px solid #333; padding: 15px 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h6 style="margin: 0; color: #f8b531;">${item.nome}</h6>
                                    <small style="color: #ccc;">Qtd: ${item.quantidade} | Preço: R$ ${item.preco.toFixed(2).replace('.', ',')}</small>
                                </div>
                                <strong style="color: white;">R$ ${(item.preco * item.quantidade).toFixed(2).replace('.', ',')}</strong>
                            </div>
                        </div>
                    `;
                });
            }
            
            $('#carrinho-lista').html(html);
            $('#subtotal').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
            $('#total-final').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
        }, 100);
    });
});
