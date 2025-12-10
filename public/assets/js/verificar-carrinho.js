/**
 * Script de Verificação do Sistema de Carrinho
 * Execute este script no console do navegador para verificar se tudo está funcionando
 */

(function() {
    console.log('🔍 Iniciando verificação do sistema de carrinho...\n');
    
    const verificacoes = {
        total: 0,
        sucesso: 0,
        falhas: []
    };
    
    function verificar(nome, condicao, mensagemErro) {
        verificacoes.total++;
        if (condicao) {
            console.log(`✅ ${nome}`);
            verificacoes.sucesso++;
            return true;
        } else {
            console.error(`❌ ${nome}: ${mensagemErro}`);
            verificacoes.falhas.push({ nome, mensagemErro });
            return false;
        }
    }
    
    console.log('📦 Verificando dependências...\n');
    
    // Verificar jQuery
    verificar(
        'jQuery carregado',
        typeof jQuery !== 'undefined',
        'jQuery não encontrado. Verifique se está incluído no HTML.'
    );
    
    // Verificar Bootstrap
    verificar(
        'Bootstrap carregado',
        typeof $.fn.modal !== 'undefined',
        'Bootstrap não encontrado. Verifique se está incluído no HTML.'
    );
    
    console.log('\n🛒 Verificando objeto Carrinho...\n');
    
    // Verificar objeto Carrinho
    verificar(
        'Objeto window.Carrinho existe',
        typeof window.Carrinho !== 'undefined',
        'Objeto Carrinho não encontrado. Verifique se carrinho-modal.js está carregado.'
    );
    
    if (window.Carrinho) {
        // Verificar métodos do Carrinho
        const metodos = [
            'init',
            'adicionar',
            'remover',
            'atualizarQuantidade',
            'editarObservacoes',
            'limpar',
            'finalizar',
            'atualizarModal',
            'atualizarBadge',
            'abrirModal'
        ];
        
        metodos.forEach(metodo => {
            verificar(
                `Método Carrinho.${metodo}()`,
                typeof window.Carrinho[metodo] === 'function',
                `Método ${metodo} não encontrado no objeto Carrinho.`
            );
        });
        
        // Verificar propriedade itens
        verificar(
            'Propriedade Carrinho.itens',
            Array.isArray(window.Carrinho.itens),
            'Propriedade itens não é um array.'
        );
    }
    
    console.log('\n🎨 Verificando elementos do DOM...\n');
    
    // Verificar elementos do DOM
    verificar(
        'Badge do carrinho (#carrinho-badge)',
        document.getElementById('carrinho-badge') !== null,
        'Elemento #carrinho-badge não encontrado no DOM.'
    );
    
    verificar(
        'Container do badge (#carrinho-badge-container)',
        document.getElementById('carrinho-badge-container') !== null,
        'Elemento #carrinho-badge-container não encontrado no DOM.'
    );
    
    verificar(
        'Modal do carrinho (#modalCarrinho)',
        document.getElementById('modalCarrinho') !== null,
        'Elemento #modalCarrinho não encontrado no DOM.'
    );
    
    verificar(
        'Body do modal (#modal-carrinho-body)',
        document.getElementById('modal-carrinho-body') !== null,
        'Elemento #modal-carrinho-body não encontrado no DOM.'
    );
    
    verificar(
        'Total do modal (#modal-carrinho-total)',
        document.getElementById('modal-carrinho-total') !== null,
        'Elemento #modal-carrinho-total não encontrado no DOM.'
    );
    
    verificar(
        'Botão finalizar (#btn-finalizar-pedido)',
        document.getElementById('btn-finalizar-pedido') !== null,
        'Elemento #btn-finalizar-pedido não encontrado no DOM.'
    );
    
    verificar(
        'Botão limpar (#btn-limpar-carrinho)',
        document.getElementById('btn-limpar-carrinho') !== null,
        'Elemento #btn-limpar-carrinho não encontrado no DOM.'
    );
    
    console.log('\n💾 Verificando localStorage...\n');
    
    // Verificar localStorage
    verificar(
        'localStorage disponível',
        typeof Storage !== 'undefined',
        'localStorage não está disponível neste navegador.'
    );
    
    if (typeof Storage !== 'undefined') {
        try {
            localStorage.setItem('teste_carrinho', 'ok');
            const teste = localStorage.getItem('teste_carrinho');
            localStorage.removeItem('teste_carrinho');
            
            verificar(
                'localStorage funcional',
                teste === 'ok',
                'localStorage não está funcionando corretamente.'
            );
        } catch (e) {
            verificar(
                'localStorage funcional',
                false,
                `Erro ao acessar localStorage: ${e.message}`
            );
        }
        
        // Verificar dados do carrinho
        const dadosCarrinho = localStorage.getItem('carrinho_modal');
        if (dadosCarrinho) {
            try {
                const itens = JSON.parse(dadosCarrinho);
                verificar(
                    'Dados do carrinho válidos',
                    Array.isArray(itens),
                    'Dados do carrinho não são um array válido.'
                );
                
                console.log(`\n📊 Carrinho atual: ${itens.length} item(ns)`);
                if (itens.length > 0) {
                    console.log('Itens:', itens);
                }
            } catch (e) {
                verificar(
                    'Dados do carrinho válidos',
                    false,
                    `Erro ao parsear dados: ${e.message}`
                );
            }
        } else {
            console.log('ℹ️ Carrinho vazio (nenhum dado salvo ainda)');
        }
    }
    
    console.log('\n📱 Verificando CSS...\n');
    
    // Verificar se o CSS foi carregado
    const testElement = document.createElement('div');
    testElement.id = 'carrinho-badge';
    testElement.style.display = 'none';
    document.body.appendChild(testElement);
    
    const styles = window.getComputedStyle(testElement);
    const hasStyles = styles.position === 'absolute';
    
    verificar(
        'CSS do carrinho carregado',
        hasStyles,
        'CSS do carrinho não parece estar carregado. Verifique carrinho-modal.css.'
    );
    
    document.body.removeChild(testElement);
    
    // Resumo final
    console.log('\n' + '='.repeat(60));
    console.log('📊 RESUMO DA VERIFICAÇÃO');
    console.log('='.repeat(60));
    console.log(`Total de verificações: ${verificacoes.total}`);
    console.log(`✅ Sucessos: ${verificacoes.sucesso}`);
    console.log(`❌ Falhas: ${verificacoes.falhas.length}`);
    
    if (verificacoes.falhas.length === 0) {
        console.log('\n🎉 TUDO OK! O sistema de carrinho está funcionando perfeitamente!');
        console.log('\n💡 Teste adicionar um produto ao carrinho para verificar o funcionamento completo.');
    } else {
        console.log('\n⚠️ ATENÇÃO! Foram encontrados problemas:');
        verificacoes.falhas.forEach((falha, index) => {
            console.log(`\n${index + 1}. ${falha.nome}`);
            console.log(`   → ${falha.mensagemErro}`);
        });
        console.log('\n🔧 Corrija os problemas acima para garantir o funcionamento correto.');
    }
    
    console.log('\n' + '='.repeat(60));
    
    // Retornar resultado
    return {
        sucesso: verificacoes.falhas.length === 0,
        total: verificacoes.total,
        aprovadas: verificacoes.sucesso,
        falhas: verificacoes.falhas
    };
})();
