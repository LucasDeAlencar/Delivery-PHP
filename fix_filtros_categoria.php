<?php

require_once 'vendor/autoload.php';

// Configurar o ambiente
putenv('CI_ENVIRONMENT=development');

// Inicializar o CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "=== CORREÇÃO DOS FILTROS DE CATEGORIA ===\n\n";

try {
    // 1. Verificar e garantir dados
    echo "1. Verificando dados no banco...\n";
    $categoriaModel = new \App\Models\CategoriaModel();
    $produtoModel = new \App\Models\ProdutoModel();
    
    $categorias = $categoriaModel->where('ativo', true)->orderBy('nome', 'ASC')->findAll();
    $produtos = $produtoModel->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug')
                            ->join('categorias', 'categorias.id = produtos.categoria_id')
                            ->where('produtos.ativo', true)
                            ->where('categorias.ativo', true)
                            ->orderBy('categorias.nome', 'ASC')
                            ->orderBy('produtos.nome', 'ASC')
                            ->findAll();
    
    if (empty($categorias) || empty($produtos)) {
        echo "   Dados insuficientes. Executando seeder...\n";
        $seeder = \Config\Database::seeder();
        $seeder->call('ProdutoSeeder');
        
        // Recarregar dados
        $categorias = $categoriaModel->where('ativo', true)->orderBy('nome', 'ASC')->findAll();
        $produtos = $produtoModel->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug')
                                ->join('categorias', 'categorias.id = produtos.categoria_id')
                                ->where('produtos.ativo', true)
                                ->where('categorias.ativo', true)
                                ->orderBy('categorias.nome', 'ASC')
                                ->orderBy('produtos.nome', 'ASC')
                                ->findAll();
    }
    
    echo "   ✅ Categorias: " . count($categorias) . "\n";
    echo "   ✅ Produtos: " . count($produtos) . "\n\n";
    
    // 2. Criar versão corrigida do menu_produtos.php
    echo "2. Criando versão corrigida do menu_produtos.php...\n";
    
    $menuCorrigido = '<!-- Filtros do Menu -->
<div class="menu_filter text-center">
    <ul class="list-unstyled list-inline d-inline-block">
        <li class="item active">
            <a href="javascript:;" class="filter-button" data-filter="all">Todos</a>
        </li>
        <?php if (!empty($categorias)): ?>
            <?php foreach ($categorias as $categoria): ?>
                <li class="item">
                    <a href="javascript:;" class="filter-button" data-filter="<?= esc($categoria->slug) ?>">
                        <?= esc($categoria->nome) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="item">
                <span class="text-muted">Nenhuma categoria encontrada</span>
            </li>
        <?php endif; ?>
    </ul>
    
    <!-- Debug info (apenas em desenvolvimento) -->
    <?php if (ENVIRONMENT === \'development\'): ?>
        <div class="mt-2 small text-muted">
            Debug: <?= count($categorias ?? []) ?> categorias carregadas
            <?php if (!empty($categorias)): ?>
                (<?= implode(\', \', array_map(function($cat) { return $cat->nome; }, $categorias)) ?>)
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Items do Menu -->
<div id="menu_items">
    <?php if (!empty($produtos)): ?>
        <?php 
        // Agrupar produtos por categoria
        $produtosPorCategoria = [];
        foreach ($produtos as $produto) {
            $produtosPorCategoria[$produto->categoria_slug][] = $produto;
        }
        ?>
        
        <!-- Todos os produtos (visível por padrão) -->
        <div class="filtr-item filter all" data-category="all">
            <div class="row">
                <?php foreach ($produtos as $produto): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="block ftco-animate">
                            <div class="content">
                                <div class="filter_item_img">
                                    <i class="icon-search"></i>
                                    <?php if (!empty($produto->imagem) && file_exists(FCPATH . \'uploads/produtos/\' . $produto->imagem)): ?>
                                        <img src="<?= site_url(\'uploads/produtos/\' . $produto->imagem) ?>" alt="<?= esc($produto->nome) ?>" />
                                    <?php else: ?>
                                        <img src="<?= site_url(\'web/src/images/pizza-1.jpg\') ?>" alt="<?= esc($produto->nome) ?>" />
                                    <?php endif; ?>
                                </div>
                                <div class="info">
                                    <div class="name"><?= esc($produto->nome) ?></div>
                                    <div class="short"><?= esc($produto->ingredientes) ?></div>
                                    <div class="category-badge">
                                        <small class="text-muted"><?= esc($produto->categoria_nome) ?></small>
                                    </div>
                                    <?php if (!empty($produto->preco)): ?>
                                        <div class="price mt-3">
                                            <span class="h5 text-primary">R$ <?= number_format($produto->preco, 2, \',\', \'.\') ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Produtos por categoria específica -->
        <?php foreach ($produtosPorCategoria as $categoriaSlug => $produtosCategoria): ?>
            <div class="filtr-item filter <?= esc($categoriaSlug) ?>" data-category="<?= esc($categoriaSlug) ?>" style="display: none;">
                <div class="row">
                    <?php foreach ($produtosCategoria as $produto): ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="block ftco-animate">
                                <div class="content">
                                    <div class="filter_item_img">
                                        <i class="icon-search"></i>
                                        <?php if (!empty($produto->imagem) && file_exists(FCPATH . \'uploads/produtos/\' . $produto->imagem)): ?>
                                            <img src="<?= site_url(\'uploads/produtos/\' . $produto->imagem) ?>" alt="<?= esc($produto->nome) ?>" />
                                        <?php else: ?>
                                            <img src="<?= site_url(\'web/src/images/pizza-1.jpg\') ?>" alt="<?= esc($produto->nome) ?>" />
                                        <?php endif; ?>
                                    </div>
                                    <div class="info">
                                        <div class="name"><?= esc($produto->nome) ?></div>
                                        <div class="short"><?= esc($produto->ingredientes) ?></div>
                                        <div class="category-badge">
                                            <small class="text-muted"><?= esc($produto->categoria_nome) ?></small>
                                        </div>
                                        <?php if (!empty($produto->preco)): ?>
                                            <div class="price mt-3">
                                                <span class="h5 text-primary">R$ <?= number_format($produto->preco, 2, \',\', \'.\') ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <!-- Debug: Mostrar estrutura dos dados -->
        <?php if (ENVIRONMENT === \'development\'): ?>
            <div class="mt-4 p-3 bg-light small">
                <strong>Debug - Estrutura dos dados:</strong><br>
                Total de produtos: <?= count($produtos) ?><br>
                Categorias com produtos:
                <?php foreach ($produtosPorCategoria as $slug => $prods): ?>
                    <br>- <?= $slug ?>: <?= count($prods) ?> produtos
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Mensagem quando não há produtos -->
        <div class="filtr-item filter all" data-category="all">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <h4>Nenhum produto encontrado</h4>
                        <p>Não há produtos cadastrados no momento. Volte em breve para conferir nossas deliciosas opções!</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Debug: Mostrar dados recebidos -->
<?php if (ENVIRONMENT === \'development\'): ?>
    <div class="mt-4 p-3 bg-secondary text-white small">
        <strong>Debug - Dados recebidos na view:</strong><br>
        Categorias: <?= isset($categorias) ? count($categorias) : \'NÃO DEFINIDO\' ?><br>
        Produtos: <?= isset($produtos) ? count($produtos) : \'NÃO DEFINIDO\' ?><br>
        <?php if (isset($categorias) && !empty($categorias)): ?>
            Lista de categorias:
            <?php foreach ($categorias as $cat): ?>
                <br>- <?= $cat->nome ?> (<?= $cat->slug ?>)
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>';
    
    // Fazer backup do arquivo original
    $arquivoOriginal = 'app/Views/Home/menu_produtos.php';
    $arquivoBackup = 'app/Views/Home/menu_produtos_backup_' . date('Y-m-d_H-i-s') . '.php';
    
    if (file_exists($arquivoOriginal)) {
        copy($arquivoOriginal, $arquivoBackup);
        echo "   ✅ Backup criado: {$arquivoBackup}\n";
    }
    
    file_put_contents($arquivoOriginal, $menuCorrigido);
    echo "   ✅ Arquivo menu_produtos.php atualizado\n\n";
    
    // 3. Criar versão corrigida do index.php
    echo "3. Criando versão corrigida do index.php...\n";
    
    $indexCorrigido = '<?php echo $this->extend(\'layout/principal_web\'); ?>

<!-- Seção do Título -->
<?php echo $this->section(\'titulo\'); ?>
<?php echo $titulo; ?>
<?php echo $this->endSection(); ?>

<!-- Seção de Estilos Personalizados -->
<?php echo $this->section(\'estilos\'); ?>
<link rel="stylesheet" href="<?= site_url(\'web/src/css/menu-dinamico.css\'); ?>">
<?php echo $this->endSection(); ?>

<!-- Seção de Conteúdo Dinâmico do Menu -->
<?php echo $this->section(\'menu_dinamico\'); ?>
<?= $this->include(\'Home/menu_produtos\') ?>
<?php echo $this->endSection(); ?>

<!-- Seção de Scripts Personalizados -->
<?php echo $this->section(\'scripts\'); ?>
<script>
// Script para filtros dinâmicos com animações - VERSÃO CORRIGIDA
$(document).ready(function() {
    console.log(\'🚀 Inicializando filtros de categoria (versão corrigida)...\');
    
    // Debug: Verificar elementos
    var botoesFiltro = $(\'.filter-button\');
    var itensFiltro = $(\'.filtr-item\');
    
    console.log(\'Botões de filtro encontrados:\', botoesFiltro.length);
    console.log(\'Items de filtro encontrados:\', itensFiltro.length);
    
    // Listar todos os botões e seus data-filter
    botoesFiltro.each(function(index) {
        var filtro = $(this).data(\'filter\');
        console.log(\'Botão\', index + 1, \'- data-filter:\', filtro, \'- texto:\', $(this).text().trim());
    });
    
    // Listar todos os items e suas classes
    itensFiltro.each(function(index) {
        var classes = $(this).attr(\'class\');
        var categoria = $(this).data(\'category\');
        var visivel = $(this).is(\':visible\');
        console.log(\'Item\', index + 1, \'- classes:\', classes, \'- categoria:\', categoria, \'- visível:\', visivel);
    });
    
    // Função para mostrar/ocultar items
    function filtrarItems(filtroSelecionado) {
        console.log(\'🔄 Aplicando filtro:\', filtroSelecionado);
        
        // Ocultar todos os items primeiro
        itensFiltro.hide().removeClass(\'active\');
        
        // Mostrar items baseado no filtro
        if (filtroSelecionado === \'all\') {
            var itemTodos = $(\'.filtr-item.filter.all, .filtr-item[data-category="all"]\');
            itemTodos.show().addClass(\'active\');
            console.log(\'✅ Mostrando todos os produtos (\' + itemTodos.length + \' items)\');
        } else {
            var itemCategoria = $(\'.filtr-item.filter.\' + filtroSelecionado + \', .filtr-item[data-category="\' + filtroSelecionado + \'"]\');
            itemCategoria.show().addClass(\'active\');
            console.log(\'✅ Mostrando categoria "\' + filtroSelecionado + \'" (\' + itemCategoria.length + \' items)\');
        }
        
        // Debug: Verificar quantos items estão visíveis
        var itensVisiveis = $(\'.filtr-item:visible\');
        console.log(\'📊 Items visíveis após filtro:\', itensVisiveis.length);
        
        if (itensVisiveis.length === 0) {
            console.warn(\'⚠️ Nenhum item visível após aplicar o filtro!\');
        }
    }
    
    // Event handler para os botões de filtro
    botoesFiltro.on(\'click\', function(e) {
        e.preventDefault();
        
        console.log(\'🖱️ Filtro clicado!\');
        
        // Remove active class from all buttons
        $(\'.filter-button\').parent().removeClass(\'active\');
        
        // Add active class to clicked button
        $(this).parent().addClass(\'active\');
        
        // Get filter value
        var filterValue = $(this).data(\'filter\');
        console.log(\'Filtro selecionado:\', filterValue);
        
        // Aplicar filtro
        filtrarItems(filterValue);
    });
    
    // Garantir que "Todos" esteja selecionado inicialmente
    console.log(\'🎯 Configurando estado inicial...\');
    filtrarItems(\'all\');
    
    // Efeito hover nos items
    $(\'.filtr-item .block\').hover(
        function() {
            $(this).find(\'.filter_item_img i\').addClass(\'animated pulse\');
        },
        function() {
            $(this).find(\'.filter_item_img i\').removeClass(\'animated pulse\');
        }
    );
    
    console.log(\'✅ Filtros inicializados com sucesso!\');
    
    // Debug adicional após 2 segundos
    setTimeout(function() {
        console.log(\'🔍 Debug após 2 segundos:\');
        console.log(\'- Botões ativos:\', $(\'.filter-button\').parent(\'.active\').length);
        console.log(\'- Items visíveis:\', $(\'.filtr-item:visible\').length);
        console.log(\'- Items com classe active:\', $(\'.filtr-item.active\').length);
    }, 2000);
});
</script>
<?php echo $this->endSection(); ?>';
    
    // Fazer backup do arquivo original
    $arquivoIndexOriginal = 'app/Views/Home/index.php';
    $arquivoIndexBackup = 'app/Views/Home/index_backup_' . date('Y-m-d_H-i-s') . '.php';
    
    if (file_exists($arquivoIndexOriginal)) {
        copy($arquivoIndexOriginal, $arquivoIndexBackup);
        echo "   ✅ Backup criado: {$arquivoIndexBackup}\n";
    }
    
    file_put_contents($arquivoIndexOriginal, $indexCorrigido);
    echo "   ✅ Arquivo index.php atualizado\n\n";
    
    // 4. Atualizar CSS para garantir funcionamento
    echo "4. Atualizando CSS...\n";
    
    $cssCorrigido = '/* Menu Dinâmico - Integração com Template - VERSÃO CORRIGIDA */

/* Filtros do Menu */
.menu_filter {
    margin-bottom: 50px;
}

.menu_filter ul {
    margin: 0;
    padding: 0;
}

.menu_filter ul li {
    display: inline-block;
    margin: 0 10px;
}

.menu_filter ul li a {
    display: block;
    padding: 12px 25px;
    background: transparent;
    border: 2px solid #ffc107;
    color: #ffc107;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 500;
    transition: all 0.3s ease;
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 1px;
    cursor: pointer;
}

.menu_filter ul li a:hover,
.menu_filter ul li.active a {
    background: #ffc107;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
    text-decoration: none;
}

/* Items do Menu */
.filtr-item {
    margin-bottom: 30px;
    display: none; /* Oculto por padrão */
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.5s ease;
}

.filtr-item.active {
    display: block !important;
    opacity: 1;
    transform: translateY(0);
}

/* Garantir que o item "all" seja visível por padrão */
.filtr-item.filter.all {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.filtr-item .block {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.filtr-item .block:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.filtr-item .content {
    position: relative;
}

.filter_item_img {
    position: relative;
    overflow: hidden;
    height: 250px;
}

.filter_item_img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.filter_item_img:hover img {
    transform: scale(1.1);
}

.filter_item_img i {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 24px;
    color: #fff;
    background: rgba(255, 193, 7, 0.9);
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
    opacity: 0;
    transition: all 0.3s ease;
}

.filter_item_img:hover i {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1.1);
}

.filtr-item .info {
    padding: 25px;
}

.filtr-item .info .name {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    font-family: \'Poppins\', sans-serif;
}

.filtr-item .info .short {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 15px;
}

.category-badge {
    margin-top: 10px;
}

.category-badge small {
    background: #f8f9fa;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 12px;
    color: #666;
    border: 1px solid #e9ecef;
}

/* Responsividade */
@media (max-width: 768px) {
    .menu_filter ul li {
        display: block;
        margin: 5px 0;
        text-align: center;
    }
    
    .menu_filter ul li a {
        display: inline-block;
        margin: 5px;
    }
    
    .filter_item_img {
        height: 200px;
    }
    
    .filtr-item .info {
        padding: 20px;
    }
    
    .filtr-item .info .name {
        font-size: 18px;
    }
}

/* Estilo para quando não há produtos */
.alert {
    border-radius: 10px;
    border: none;
    padding: 30px;
    text-align: center;
}

.alert-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1976d2;
}

.alert h4 {
    color: #1565c0;
    margin-bottom: 15px;
}

/* Integração com o estilo do template */
.ftco-section .menu_filter {
    margin-bottom: 60px;
}

.ftco-section .filtr-item .block {
    border: none;
    background: #fff;
}

.ftco-section .filtr-item .info .name {
    color: #000;
    font-family: \'Poppins\', sans-serif;
}

/* Efeito de loading */
.menu-loading {
    text-align: center;
    padding: 50px;
}

.menu-loading::after {
    content: \'\';
    display: inline-block;
    width: 40px;
    height: 40px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #ffc107;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Botões de ação (se necessário) */
.btn-menu {
    background: #ffc107;
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.btn-menu:hover {
    background: #e0a800;
    color: #fff;
    transform: translateY(-1px);
}

/* Debug styles */
.debug {
    background: #f0f0f0;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    font-family: monospace;
    font-size: 12px;
    border-left: 4px solid #ffc107;
}';
    
    $arquivoCssOriginal = 'public/web/src/css/menu-dinamico.css';
    $arquivoCssBackup = 'public/web/src/css/menu-dinamico_backup_' . date('Y-m-d_H-i-s') . '.css';
    
    if (file_exists($arquivoCssOriginal)) {
        copy($arquivoCssOriginal, $arquivoCssBackup);
        echo "   ✅ Backup CSS criado: {$arquivoCssBackup}\n";
    }
    
    file_put_contents($arquivoCssOriginal, $cssCorrigido);
    echo "   ✅ Arquivo CSS atualizado\n\n";
    
    // 5. Criar página de teste
    echo "5. Criando página de teste...\n";
    
    $paginaTeste = "<!DOCTYPE html>
<html lang=\"pt-br\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Teste - Filtros de Categoria (CORRIGIDO)</title>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
    <script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>
    <style>
        .filter-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background: #ffc107;
            color: #000;
            text-decoration: none;
            border-radius: 25px;
            border: 2px solid #ffc107;
            transition: all 0.3s;
            cursor: pointer;
        }
        .filter-button:hover,
        .item.active .filter-button {
            background: #e0a800;
            color: #fff;
            transform: translateY(-2px);
            text-decoration: none;
        }
        .filtr-item {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            display: none;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }
        .filtr-item.active {
            display: block !important;
            opacity: 1;
            transform: translateY(0);
        }
        .filtr-item.filter.all {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        .product-card {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            background: #f8f9fa;
        }
        .debug {
            background: #f0f0f0;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            border-left: 4px solid #ffc107;
        }
        .log {
            background: #000;
            color: #0f0;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class=\"container mt-5\">
        <h1 class=\"text-center mb-4\">Teste - Filtros de Categoria (VERSÃO CORRIGIDA)</h1>
        
        <div class=\"debug\">
            <strong>Status:</strong> ✅ Versão corrigida dos filtros<br>
            <strong>Total de categorias:</strong> " . count($categorias) . "<br>
            <strong>Total de produtos:</strong> " . count($produtos) . "<br>
            <strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "
        </div>
        
        <!-- Filtros -->
        <div class=\"menu_filter text-center mb-4\">
            <ul class=\"list-unstyled list-inline d-inline-block\">
                <li class=\"item active\">
                    <a href=\"javascript:;\" class=\"filter-button\" data-filter=\"all\">Todos (" . count($produtos) . ")</a>
                </li>";
    
    $produtosPorCategoria = [];
    foreach ($produtos as $produto) {
        $produtosPorCategoria[$produto->categoria_slug][] = $produto;
    }
    
    foreach ($categorias as $categoria) {
        $count = isset($produtosPorCategoria[$categoria->slug]) ? count($produtosPorCategoria[$categoria->slug]) : 0;
        $paginaTeste .= "\n                <li class=\"item\">
                    <a href=\"javascript:;\" class=\"filter-button\" data-filter=\"{$categoria->slug}\">{$categoria->nome} ({$count})</a>
                </li>";
    }
    
    $paginaTeste .= "\n            </ul>
        </div>
        
        <!-- Log de eventos -->
        <div class=\"log mb-4\" id=\"log\">
            <div>🚀 Sistema de log iniciado...</div>
        </div>
        
        <!-- Produtos -->
        <div id=\"menu_items\">
            <!-- Todos os produtos -->
            <div class=\"filtr-item filter all\" data-category=\"all\">
                <h3>Todos os Produtos</h3>
                <div class=\"row\">";
    
    foreach ($produtos as $produto) {
        $preco = $produto->preco ? 'R$ ' . number_format($produto->preco, 2, ',', '.') : 'Sem preço';
        $paginaTeste .= "\n                    <div class=\"col-md-4\">
                        <div class=\"product-card\">
                            <h5>{$produto->nome}</h5>
                            <p class=\"text-muted\">{$produto->ingredientes}</p>
                            <p><strong>{$preco}</strong></p>
                            <small class=\"badge bg-secondary\">{$produto->categoria_nome}</small>
                        </div>
                    </div>";
    }
    
    $paginaTeste .= "\n                </div>
            </div>";
    
    // Produtos por categoria
    foreach ($produtosPorCategoria as $slug => $prods) {
        $categoriaNome = '';
        foreach ($categorias as $cat) {
            if ($cat->slug === $slug) {
                $categoriaNome = $cat->nome;
                break;
            }
        }
        
        $paginaTeste .= "\n            
            <!-- Categoria: {$categoriaNome} -->
            <div class=\"filtr-item filter {$slug}\" data-category=\"{$slug}\">
                <h3>{$categoriaNome}</h3>
                <div class=\"row\">";
        
        foreach ($prods as $prod) {
            $preco = $prod->preco ? 'R$ ' . number_format($prod->preco, 2, ',', '.') : 'Sem preço';
            $paginaTeste .= "\n                    <div class=\"col-md-4\">
                        <div class=\"product-card\">
                            <h5>{$prod->nome}</h5>
                            <p class=\"text-muted\">{$prod->ingredientes}</p>
                            <p><strong>{$preco}</strong></p>
                        </div>
                    </div>";
        }
        
        $paginaTeste .= "\n                </div>
            </div>";
    }
    
    $paginaTeste .= "\n        </div>
    </div>
    
    <script>
    function log(message) {
        var timestamp = new Date().toLocaleTimeString();
        var logEntry = '<div>[' + timestamp + '] ' + message + '</div>';
        $('#log').append(logEntry);
        $('#log').scrollTop($('#log')[0].scrollHeight);
        console.log(message);
    }
    
    $(document).ready(function() {
        log('✅ jQuery carregado e document ready executado');
        
        var botoesFiltro = $('.filter-button');
        var itensFiltro = $('.filtr-item');
        
        log('🔍 Elementos encontrados:');
        log('   - Botões de filtro: ' + botoesFiltro.length);
        log('   - Items de filtro: ' + itensFiltro.length);
        
        // Listar botões
        botoesFiltro.each(function(index) {
            var filtro = $(this).data('filter');
            var texto = $(this).text().trim();
            log('   - Botão ' + (index + 1) + ': \"' + texto + '\" (data-filter=\"' + filtro + '\")');
        });
        
        // Listar items
        itensFiltro.each(function(index) {
            var categoria = $(this).data('category');
            var classes = $(this).attr('class');
            var visivel = $(this).is(':visible');
            log('   - Item ' + (index + 1) + ': categoria=\"' + categoria + '\" visível=' + visivel);
        });
        
        // Função para filtrar
        function filtrarItems(filtroSelecionado) {
            log('🔄 Aplicando filtro: \"' + filtroSelecionado + '\"');
            
            // Ocultar todos
            itensFiltro.removeClass('active').hide();
            
            // Mostrar filtrados
            var itemsParaMostrar;
            if (filtroSelecionado === 'all') {
                itemsParaMostrar = $('.filtr-item.filter.all, .filtr-item[data-category=\"all\"]');
            } else {
                itemsParaMostrar = $('.filtr-item.filter.' + filtroSelecionado + ', .filtr-item[data-category=\"' + filtroSelecionado + '\"]');
            }
            
            itemsParaMostrar.addClass('active').show();
            
            log('✅ Resultado: ' + itemsParaMostrar.length + ' items mostrados');
            
            if (itemsParaMostrar.length === 0) {
                log('⚠️ ATENÇÃO: Nenhum item foi mostrado!');
            }
        }
        
        // Event handler
        botoesFiltro.on('click', function(e) {
            e.preventDefault();
            
            var filtro = $(this).data('filter');
            var texto = $(this).text().trim();
            
            log('🖱️ Clique no botão: \"' + texto + '\" (filtro=\"' + filtro + '\")');
            
            // Atualizar botões ativos
            $('.filter-button').parent().removeClass('active');
            $(this).parent().addClass('active');
            
            // Aplicar filtro
            filtrarItems(filtro);
        });
        
        // Estado inicial
        log('🎯 Configurando estado inicial (mostrar todos)');
        filtrarItems('all');
        
        log('✅ Sistema de filtros inicializado com sucesso!');
        
        // Teste automático após 3 segundos
        setTimeout(function() {
            log('🤖 Iniciando teste automático...');
            var botoes = $('.filter-button');
            var indice = 0;
            
            function testarProximoBotao() {
                if (indice < botoes.length) {
                    var botao = botoes.eq(indice);
                    var filtro = botao.data('filter');
                    log('🧪 Testando filtro: \"' + filtro + '\"');
                    botao.click();
                    indice++;
                    setTimeout(testarProximoBotao, 1500);
                } else {
                    log('✅ Teste automático concluído!');
                    // Voltar para \"Todos\"
                    $('.filter-button[data-filter=\"all\"]').click();
                }
            }
            
            testarProximoBotao();
        }, 3000);
    });
    </script>
</body>
</html>";
    
    file_put_contents('teste_filtros_corrigido.html', $paginaTeste);
    echo "   ✅ Página de teste criada: teste_filtros_corrigido.html\n\n";
    
    echo "✅ CORREÇÃO CONCLUÍDA!\n\n";
    echo "ARQUIVOS MODIFICADOS:\n";
    echo "- app/Views/Home/menu_produtos.php (backup: {$arquivoBackup})\n";
    echo "- app/Views/Home/index.php (backup: {$arquivoIndexBackup})\n";
    echo "- public/web/src/css/menu-dinamico.css (backup: {$arquivoCssBackup})\n\n";
    echo "ARQUIVOS DE TESTE CRIADOS:\n";
    echo "- teste_filtros_corrigido.html\n\n";
    echo "PRÓXIMOS PASSOS:\n";
    echo "1. Abra 'teste_filtros_corrigido.html' no navegador para testar\n";
    echo "2. Acesse o site principal em " . site_url() . "\n";
    echo "3. Verifique o console do navegador para logs detalhados\n";
    echo "4. Se ainda houver problemas, verifique se há conflitos JavaScript\n\n";
    echo "PRINCIPAIS CORREÇÕES APLICADAS:\n";
    echo "- ✅ Adicionado atributo data-category aos items\n";
    echo "- ✅ Melhorado o seletor CSS para mostrar/ocultar items\n";
    echo "- ✅ Adicionado logs detalhados no JavaScript\n";
    echo "- ✅ Corrigido o CSS para garantir visibilidade correta\n";
    echo "- ✅ Adicionado fallback para seletores\n";
    echo "- ✅ Melhorado o tratamento de eventos\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== CORREÇÃO FINALIZADA ===\n";