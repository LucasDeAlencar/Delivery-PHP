<!-- Remover CSS órfão -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Encontrar e remover qualquer texto CSS órfão na página
    const walker = document.createTreeWalker(
        document.body,
        NodeFilter.SHOW_TEXT,
        null,
        false
    );
    
    const nodesToRemove = [];
    let node;
    
    while (node = walker.nextNode()) {
        if (node.textContent.includes('.carrinho-icon-container') || 
            node.textContent.includes('@media') ||
            node.textContent.includes('cursor: pointer')) {
            nodesToRemove.push(node);
        }
    }
    
    nodesToRemove.forEach(node => {
        node.parentNode.removeChild(node);
    });
});
</script>
