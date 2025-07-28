<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'Login::novo');
$routes->post('login/criar', 'Login::criar');
$routes->get('login/logout', 'Login::logout');
$routes->get('login/mostraMensagemLogout', 'Login::mostraMensagemLogout');

// Rota específica removida - agora usa a rota do grupo admin

// Rotas da área administrativa
$routes->group('admin', function($routes) {
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
    $routes->get('categorias/(:num)', 'Admin\Categorias::show/$1');
    
    // Rotas de produtos
    $routes->get('produtos', 'Admin\Produtos::index');
    $routes->get('produtos/criar', 'Admin\Produtos::criar');
    $routes->post('produtos/cadastrar', 'Admin\Produtos::cadastrar');
    $routes->get('produtos/editar/(:num)', 'Admin\Produtos::editar/$1');
    $routes->post('produtos/atualizar/(:num)', 'Admin\Produtos::atualizar/$1');
    $routes->get('produtos/extras/(:num)', 'Admin\Produtos::extras/$1');
    $routes->post('produtos/salvar-extras/(:num)', 'Admin\Produtos::salvarExtras/$1');
    $routes->get('produtos/especificacoes/(:num)', 'Admin\Produtos::especificacoes/$1');
    $routes->post('produtos/salvar-especificacoes/(:num)', 'Admin\Produtos::salvarEspecificacoes/$1');
    $routes->get('produtos/excluir/(:num)', 'Admin\Produtos::excluir/$1');
    $routes->post('produtos/deletar/(:num)', 'Admin\Produtos::deletar/$1');
    $routes->post('produtos/desfazer-exclusao/(:num)', 'Admin\Produtos::desfazerExclusao/$1');
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
    $routes->get('extras/(:num)', 'Admin\Extras::show/$1');
});