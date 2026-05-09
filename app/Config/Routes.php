<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'Login::index');
$routes->get('login/entrar', 'Login::novo');
$routes->post('login/criar', 'Login::criar');
$routes->get('login/cadastrar', 'Login::cadastrar');
$routes->post('login/cadastrar', 'Login::cadastrar');
$routes->post('login/verificarTelefoneAdmin', 'Login::verificarTelefoneAdmin');
$routes->post('login/verificarEmail', 'Login::verificarEmail');
$routes->post('login/verificarTelefone', 'Login::verificarTelefone');
$routes->post('login/verificarNomeCelular', 'Login::verificarNomeCelular');
$routes->post('login/enviarCodigo', 'Login::enviarCodigo');
$routes->post('login/verificarCodigo', 'Login::verificarCodigo');
$routes->post('login/buscar_cep', 'Login::buscar_cep');
$routes->get('login/logout', 'Login::logout');
// $routes->get('teste-auth', 'Login::testarAutenticacao'); // Rota de teste desabilitada

// Teste de entrega
$routes->get('teste-entrega', 'TesteEntrega::index');

// Rotas do Carrinho (apenas novas rotas implementadas)
$routes->get('carrinho', 'CarrinhoController::index', ['as' => 'carrinho.index']);
$routes->post('carrinho/adicionar', 'CarrinhoController::adicionar', ['as' => 'carrinho.adicionar']);
$routes->get('carrinho/listar', 'CarrinhoController::listar', ['as' => 'carrinho.listar']);
$routes->post('carrinho/remover/(:num)', 'CarrinhoController::remover/$1', ['as' => 'carrinho.remover']);
$routes->post('carrinho/limpar', 'CarrinhoController::limpar', ['as' => 'carrinho.limpar']);

// Taxa de entrega
$routes->post('taxa-entrega-email', 'TaxaEntregaController::calcularPorEmail');
$routes->post('taxa-entrega-sessao', 'TaxaEntregaController::calcularPorSessao');

// Finalizar pedido
$routes->post('finalizar-pedido', 'FinalizarPedidoController::processar');
$routes->get('acompanhar-pedido/(:any)', 'FinalizarPedidoController::acompanhar/$1');
$routes->post('cancelar-pedido/(:any)', 'FinalizarPedidoController::cancelar/$1');
$routes->get('recibo/(:any)', 'FinalizarPedidoController::recibo/$1');

// Retirada
$routes->get('carrinho/lista', 'CarrinhoController::lista');
$routes->get('carrinho/verSessao', 'CarrinhoController::verCarrinhoSessao');

// API do Carrinho (REST)
    $routes->group('api', function ($routes) {
        $routes->get('carrinho', 'CarrinhoApi::index');
        $routes->post('carrinho', 'CarrinhoApi::create');
        $routes->put('carrinho/(:num)', 'CarrinhoApi::update/$1');
        $routes->delete('carrinho/(:num)', 'CarrinhoApi::delete/$1');
        $routes->delete('carrinho', 'CarrinhoApi::limpar');
        $routes->patch('carrinho/(:num)/observacoes', 'CarrinhoApi::atualizarObservacoes/$1');
        
        // Formas de pagamento
        $routes->get('formas-pagamento', 'FormasPagamentoApi::index');
        
        // Bairros
        $routes->get('bairros', 'BairrosApi::index');
        
        // Extras de Produtos
        $routes->get('produto-extras/(:num)', 'ProdutoExtrasApi::getExtrasProduto/$1');
        
        // Taxa de Entrega
        $routes->post('taxa-entrega', 'TaxaEntregaApi::calcular');
        $routes->post('taxa-entrega-cliente', 'TaxaEntregaClienteApi::calcular');
        $routes->post('bairro/taxa', 'Api\BairroController::taxa');
        $routes->post('verificar-entrega', 'EntregaApi::verificarEntrega');
        
        // Pedidos
        $routes->post('finalizar-pedido', 'PedidoApi::finalizarPedido');
        $routes->post('status-pedido', 'PedidoApi::statusPedido');
        $routes->get('usuario-atual', 'PedidoApi::usuarioAtual');
        
        // Validação de Produtos
        $routes->post('produtos/validar', 'ProdutosApi::validar');
        
        // Configuração de Entrega
        $routes->post('configuracao-entrega', 'EntregaApi::configuracaoEntrega');
        $routes->get('configuracao/preco-minimo', 'ConfiguracaoApi::precoMinimo');
        $routes->post('saches/disponiveis', 'SachesApi::disponiveis');
        $routes->post('carrinho-cliente', 'EntregaApi::carrinhoCliente');
        
        // Mesas
        $routes->get('mesas', 'Api\Mesas::index');
        $routes->post('mesas/ocupar', 'Api\Mesas::ocupar');
    });

// Rotas de Pedidos (público)
$routes->post('pedidos/criar', 'Pedidos::criar');

// Rota de contato
$routes->post('contato/enviar', 'Contato::enviar');

// Rota de teste de email
$routes->get('teste-email', 'EmailController::enviarEmail');

// Rotas de teste do carrinho
$routes->get('teste-carrinho', 'TesteCarrinho::index');
$routes->post('teste-carrinho/validar', 'TesteCarrinho::validarApi');

// Rotas de registro de usuários públicos
$routes->get('registrar', 'Registrar::index');
$routes->post('registrar/criar', 'Registrar::criar');
$routes->post('registrar/criarSemVerificacao', 'Registrar::criarSemVerificacao');
$routes->post('registrar/cadastroRapido', 'Registrar::cadastroRapido');
$routes->post('registrar/enviarCodigo', 'Registrar::enviarCodigo');
$routes->post('registrar/verificarCodigo', 'Registrar::verificarCodigo');
$routes->post('registrar/verificarSessao', 'Registrar::verificarSessao');
$routes->post('registrar/buscar_cep', 'Registrar::buscar_cep');
$routes->get('registrar/bairros_cidade', 'Registrar::bairros_cidade');

// Alias para /registar (grafia alternativa)
$routes->get('registar', 'Registrar::index');
$routes->post('registar/criar', 'Registrar::criar');
$routes->post('registar/criarSemVerificacao', 'Registrar::criarSemVerificacao');
$routes->post('registar/cadastroRapido', 'Registrar::cadastroRapido');
$routes->post('registar/enviarCodigo', 'Registrar::enviarCodigo');
$routes->post('registar/verificarCodigo', 'Registrar::verificarCodigo');
$routes->post('registar/verificarSessao', 'Registrar::verificarSessao');
$routes->post('registar/buscar_cep', 'Registrar::buscar_cep');

// Rotas do cliente
$routes->post('cliente/dados', 'Cliente::dados');
$routes->get('cliente/dados_sessao', 'Cliente::dados_sessao');
$routes->post('cliente/atualizar_endereco', 'Cliente::atualizar_endereco');
$routes->get('cliente/endereco_atual', 'Cliente::endereco_atual');
$routes->get('cliente/logout', 'Cliente::logout');
$routes->post('cliente/atualizar', 'Cliente::atualizar');
$routes->post('cliente/atualizar', 'Cliente::atualizar');
$routes->post('api/cliente/telefone', 'Cliente::telefone');

// Rota para servir imagens de produtos
$routes->get('imagem/produto/(:any)', 'ImagemProduto::servir/$1');

// Rota específica removida - agora usa a rota do grupo admin
// Rotas da área administrativa
$routes->group('admin', function ($routes) {
    $routes->get('home', 'Admin\Home::index');

    // Rotas de usuários
    $routes->get('usuarios', 'Admin\Usuarios::index');
    $routes->get('usuarios/criar', 'Admin\Usuarios::criar');
    $routes->post('usuarios/cadastrar', 'Admin\Usuarios::cadastrar');
    $routes->get('usuarios/editar/(:num)', 'Admin\Usuarios::editar/$1');
    $routes->post('usuarios/atualizar/(:num)', 'Admin\Usuarios::atualizar/$1');
    $routes->get('usuarios/excluir/(:num)', 'Admin\Usuarios::excluir/$1');
    $routes->post('usuarios/deletar/(:num)', 'Admin\Usuarios::deletar/$1');
    $routes->post('usuarios/desfazer-exclusao/(:num)', 'Admin\Usuarios::desfazerExclusao/$1');
    $routes->post('usuarios/deletar-definitivamente/(:num)', 'Admin\Usuarios::deletarDefinitivamente/$1');
    $routes->get('usuarios/(:num)', 'Admin\Usuarios::show/$1');

    // Rotas de categorias
    $routes->get('categorias', 'Admin\Categorias::index');
    $routes->get('categorias/criar', 'Admin\Categorias::criar');
    $routes->post('categorias/cadastrar', 'Admin\Categorias::cadastrar');
    $routes->get('categorias/editar/(:num)', 'Admin\Categorias::editar/$1');
    $routes->post('categorias/atualizar/(:num)', 'Admin\Categorias::atualizar/$1');
    $routes->get('categorias/excluir/(:num)', 'Admin\Categorias::excluir/$1');
    $routes->post('categorias/deletar/(:num)', 'Admin\Categorias::deletar/$1');
    $routes->post('categorias/desfazer-exclusao/(:num)', 'Admin\Categorias::desfazerExclusao/$1');
    $routes->post('categorias/deletar-definitivamente/(:num)', 'Admin\Categorias::deletarDefinitivamente/$1');
    $routes->post('categorias/atualizarOrdem', 'Admin\Categorias::atualizarOrdem');
    $routes->get('categorias/(:num)', 'Admin\Categorias::show/$1');

    // Rotas de produtos
    $routes->get('produtos', 'Admin\Produtos::index');
    $routes->get('produtos/criar', 'Admin\Produtos::criar');
    $routes->post('produtos/cadastrar', 'Admin\Produtos::cadastrar');
    $routes->get('produtos/editar/(:num)', 'Admin\Produtos::editar/$1');
    $routes->post('produtos/atualizar/(:num)', 'Admin\Produtos::atualizar/$1');
    $routes->get('produtos/extras/(:num)', 'Admin\Produtos::extras/$1');
    $routes->post('produtos/salvar-extras/(:num)', 'Admin\Produtos::salvarExtras/$1');
    $routes->post('produtos/adicionar-extra/(:num)/(:num)', 'Admin\Produtos::adicionarExtra/$1/$2');
    $routes->post('produtos/remover-extra/(:num)/(:num)', 'Admin\Produtos::removerExtra/$1/$2');
    $routes->get('produtos/excluir/(:num)', 'Admin\Produtos::excluir/$1');
    $routes->post('produtos/deletar/(:num)', 'Admin\Produtos::deletar/$1');
    $routes->post('produtos/toggle-ativo', 'Admin\Produtos::toggleAtivo');
    $routes->post('produtos/acao-coletiva', 'Admin\Produtos::acaoColetiva');
    $routes->get('produtos/(:num)', 'Admin\Produtos::show/$1');

    // Rotas de extras
    $routes->get('extras', 'Admin\Extras::index');
    $routes->get('extras/criar', 'Admin\Extras::criar');
    $routes->post('extras/cadastrar', 'Admin\Extras::cadastrar');
    $routes->get('extras/editar/(:num)', 'Admin\Extras::editar/$1');
    $routes->post('extras/atualizar/(:num)', 'Admin\Extras::atualizar/$1');
    $routes->get('extras/excluir/(:num)', 'Admin\Extras::excluir/$1');
    $routes->post('extras/deletar/(:num)', 'Admin\Extras::deletar/$1');
    $routes->post('extras/desfazer-exclusao/(:num)', 'Admin\Extras::desfazerExclusao/$1');
    $routes->post('extras/deletar-definitivamente/(:num)', 'Admin\Extras::deletarDefinitivamente/$1');
    $routes->get('extras/associar-categoria', 'Admin\Extras::associarCategoria');
    $routes->post('extras/processar-associacao', 'Admin\Extras::processarAssociacao');
    $routes->get('extras/processar-associacao', 'Admin\Extras::processarAssociacao');
    $routes->get('extras/(:num)', 'Admin\Extras::show/$1');

    // Rotas de medidas
//    $routes->get('medidas', 'Admin\Medidas::index');
//    $routes->get('medidas/criar', 'Admin\Medidas::criar');
//    $routes->post('medidas/cadastrar', 'Admin\Medidas::cadastrar');
//    $routes->get('medidas/editar/(:num)', 'Admin\Medidas::editar/$1');
//    $routes->post('medidas/atualizar/(:num)', 'Admin\Medidas::atualizar/$1');
//    $routes->get('medidas/excluir/(:num)', 'Admin\Medidas::excluir/$1');
//    $routes->post('medidas/deletar/(:num)', 'Admin\Medidas::deletar/$1');
//    $routes->post('medidas/desfazer-exclusao/(:num)', 'Admin\Medidas::desfazerExclusao/$1');
//    $routes->get('medidas/(:num)', 'Admin\Medidas::show/$1');

    // Rotas de entregadores
    $routes->get('entregadores', 'Admin\Entregadores::index');
    $routes->get('entregadores/criar', 'Admin\Entregadores::criar');
    $routes->post('entregadores/cadastrar', 'Admin\Entregadores::cadastrar');
    $routes->get('entregadores/editar/(:num)', 'Admin\Entregadores::editar/$1');
    $routes->post('entregadores/atualizar/(:num)', 'Admin\Entregadores::atualizar/$1');
    $routes->get('entregadores/excluir/(:num)', 'Admin\Entregadores::excluir/$1');
    $routes->post('entregadores/deletar/(:num)', 'Admin\Entregadores::deletar/$1');
    $routes->post('entregadores/desfazer-exclusao/(:num)', 'Admin\Entregadores::desfazerExclusao/$1');
    $routes->get('entregadores/show/(:num)', 'Admin\Entregadores::show/$1');
    $routes->get('entregadores/(:num)', 'Admin\Entregadores::show/$1');

    // Rotas de FormasPagamento
    $routes->get('formas', 'Admin\FormasPagamento::index');
    $routes->get('formas/criar', 'Admin\FormasPagamento::criar');
    $routes->post('formas/cadastrar', 'Admin\FormasPagamento::cadastrar');
    $routes->get('formas/editar/(:num)', 'Admin\FormasPagamento::editar/$1');
    $routes->post('formas/atualizar/(:num)', 'Admin\FormasPagamento::atualizar/$1');
    $routes->get('formas/excluir/(:num)', 'Admin\FormasPagamento::excluir/$1');
    $routes->post('formas/deletar/(:num)', 'Admin\FormasPagamento::deletar/$1');
    $routes->post('formas/desfazer-exclusao/(:num)', 'Admin\FormasPagamento::desfazerExclusao/$1');
    $routes->get('formas/show/(:num)', 'Admin\FormasPagamento::show/$1');
    $routes->get('formas/(:num)', 'Admin\FormasPagamento::show/$1');

    // Rotas de bairros
    $routes->get('bairros', 'Admin\Bairros::index');
    $routes->post('bairros/salvarModoCobranca', 'Admin\Bairros::salvarModoCobranca');
    $routes->post('bairros/salvarConfiguracao', 'Admin\Bairros::salvarConfiguracao');
    $routes->post('bairros/desativar-todos', 'Admin\Bairros::desativarTodos');
    $routes->post('bairros/ativar-todos', 'Admin\Bairros::ativarTodos');
    $routes->get('bairros/criar', 'Admin\Bairros::criar');
    $routes->post('bairros/cadastrar', 'Admin\Bairros::cadastrar');
    $routes->get('bairros/editar/(:num)', 'Admin\Bairros::editar/$1');
    $routes->post('bairros/atualizar/(:num)', 'Admin\Bairros::atualizar/$1');
    $routes->get('bairros/excluir/(:num)', 'Admin\Bairros::excluir/$1');
    $routes->post('bairros/deletar/(:num)', 'Admin\Bairros::deletar/$1');
    $routes->post('bairros/desfazer-exclusao/(:num)', 'Admin\Bairros::desfazerExclusao/$1');
    $routes->post('bairros/deletar-definitivamente/(:num)', 'Admin\Bairros::deletarDefinitivamente/$1');
    $routes->get('bairros/show/(:num)', 'Admin\Bairros::show/$1');
    $routes->get('bairros/(:num)', 'Admin\Bairros::show/$1');

    // Rotas de Expediente
    $routes->get('expedientes', 'Admin\Expedientes::expedientes');
    $routes->post('expedientes', 'Admin\Expedientes::expedientes');

    // Rotas de Formas de Pagamento
    $routes->get('formas-pagamento', 'Admin\FormasPagamento::index');
    $routes->post('formas-pagamento/atualizar', 'Admin\FormasPagamento::atualizar');

    // Rotas de Pedidos
    $routes->get('pedidos', 'Admin\Pedidos::index');
    $routes->get('pedidos/(:num)', 'Admin\Pedidos::show/$1');
    $routes->post('pedidos/atualizar-status', 'Admin\Pedidos::atualizarStatus');
    $routes->post('pedidos/atualizar-taxa-entrega', 'Admin\Pedidos::atualizarTaxaEntrega');
    $routes->post('pedidos/filtrar-status', 'Admin\Pedidos::filtrarPorStatus');
    $routes->post('pedidos/alterar-mesa', 'Admin\Pedidos::alterarMesa');
    $routes->get('pedidos/cancelar/(:num)', 'Admin\Pedidos::cancelar/$1');
    $routes->get('pedidos/excluir/(:num)', 'Admin\Pedidos::excluir/$1');
    $routes->post('pedidos/deletar/(:num)', 'Admin\Pedidos::deletar/$1');
    $routes->get('pedidos/imprimir/(:num)', 'Admin\Pedidos::imprimir/$1');
    $routes->get('pedidos/csv', 'Admin\Pedidos::exportarCsv');
    $routes->post('pedidos/limpar', 'Admin\Pedidos::limparPedidos');
    
    $routes->get('pedidos/verificar-novos/(:num)', 'Admin\Pedidos::verificarNovos/$1');
    
    // Venda Específica
    $routes->get('venda-especifica', 'Admin\VendaEspecifica::index');
    $routes->post('venda-especifica/criar', 'Admin\VendaEspecifica::criar');
    $routes->post('venda-especifica/abrir-comanda', 'Admin\VendaEspecifica::abrirComanda');
    $routes->get('venda-especifica/comandas-abertas', 'Admin\VendaEspecifica::listarComandasAbertas');
    $routes->get('venda-especifica/itens-comanda/(:num)', 'Admin\VendaEspecifica::buscarItensComanda/$1');
    $routes->post('venda-especifica/adicionar-item-comanda', 'Admin\VendaEspecifica::adicionarItemComanda');
    $routes->post('venda-especifica/remover-item-comanda', 'Admin\VendaEspecifica::removerItemComanda');
    $routes->post('venda-especifica/alterar-qtd-item-comanda', 'Admin\VendaEspecifica::alterarQtdItemComanda');
    $routes->post('venda-especifica/atualizar-extras-item', 'Admin\VendaEspecifica::atualizarExtrasItem');
    $routes->post('venda-especifica/taxa-bairro', 'Admin\VendaEspecifica::taxaBairro');
    $routes->get('venda-especifica/produtos', 'Admin\VendaEspecifica::listarProdutos');
    $routes->get('venda-especifica/clientes', 'Admin\VendaEspecifica::buscarClientes');
    $routes->get('venda-especifica/todos-clientes', 'Admin\VendaEspecifica::listarClientes');
    $routes->get('venda-especifica/bairros', 'Admin\VendaEspecifica::listarBairros');
    $routes->get('venda-especifica/mesas', 'Admin\VendaEspecifica::listarMesas');
    $routes->get('venda-especifica/produto-extras/(:num)', 'Admin\VendaEspecifica::buscarExtrasProduto/$1');
    $routes->post('venda-especifica/criar-cliente', 'Admin\VendaEspecifica::criarCliente');
    
    // Rotas de Dados Corporativos
    $routes->get('dados-corporativos', 'Admin\DadosCorporativos::index');
    $routes->post('dados-corporativos/atualizar', 'Admin\DadosCorporativos::atualizar');

    // Sachês
    $routes->get('saches', 'Admin\Saches::index');
    $routes->post('saches/salvar', 'Admin\Saches::salvar');
    $routes->post('saches/toggle/(:num)', 'Admin\Saches::toggleAtivo/$1');
    $routes->post('saches/excluir/(:num)', 'Admin\Saches::excluir/$1');
    $routes->get('saches/get/(:num)', 'Admin\Saches::get/$1');
    $routes->post('saches/salvarGrupo', 'Admin\Saches::salvarGrupo');
    $routes->post('saches/excluirGrupo', 'Admin\Saches::excluirGrupo');
    $routes->post('saches/reordenar', 'Admin\Saches::reordenar');
    
    // Rotas de Mesas
    $routes->get('mesas', 'Admin\Mesas::index');
    $routes->post('mesas/atualizarConfig', 'Admin\Mesas::atualizarConfig');
    $routes->post('mesas/criar', 'Admin\Mesas::criar');
    $routes->post('mesas/criarSerie', 'Admin\Mesas::criarSerie');
    $routes->post('mesas/atualizar', 'Admin\Mesas::atualizar');
    $routes->post('mesas/excluir', 'Admin\Mesas::excluir');
    $routes->post('mesas/liberar', 'Admin\Mesas::liberar');
    $routes->post('mesas/ocupar', 'Admin\Mesas::ocupar');
    $routes->get('mesas/status', 'Admin\Mesas::status');
});

// Rotas de Suporte (API)
$routes->post('suporte/criar', 'SuporteController::criar');
$routes->get('suporte/listar', 'SuporteController::listar');
$routes->post('suporte/atualizar/(:num)', 'SuporteController::atualizar/$1');
$routes->post('suporte/deletar/(:num)', 'SuporteController::deletar/$1');
$routes->post('suporte/resolver-todos', 'SuporteController::resolverTodos');
$routes->get('suporte/info-pedido/(:num)', 'SuporteController::infoPedido/$1');
