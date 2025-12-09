<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verifica se o usuário está logado
        $autenticacao = service('autenticacao');
        
        if (!$autenticacao->estaLogado()) {
            // Se for AJAX, retorna JSON
            if ($request->isAJAX()) {
                return service('response')
                    ->setJSON(['success' => false, 'message' => 'Sessão expirada'])
                    ->setStatusCode(401);
            }
            return redirect()->to(site_url('login'))->with('atencao', 'Você precisa estar logado para acessar esta área.');
        }
        
        // Pega o usuário logado
        $usuario = $autenticacao->pegaUsuarioLogado();
        
        // Verifica se é admin
        if ($usuario->is_admin != 1) {
            // Se for AJAX, retorna JSON
            if ($request->isAJAX()) {
                return service('response')
                    ->setJSON(['success' => false, 'message' => 'Sem permissão'])
                    ->setStatusCode(403);
            }
            // Redireciona para pedidos (única área permitida)
            return redirect()->to(site_url('admin/pedidos'))->with('atencao', 'Você não tem permissão para acessar esta área. Apenas administradores podem acessar.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Não precisa fazer nada após a requisição
    }
}
