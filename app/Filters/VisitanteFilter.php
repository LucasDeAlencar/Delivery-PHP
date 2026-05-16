<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class VisitanteFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Não processa se a sessão acabou de ser destruída (sem cookie ou sessão vazia)
        if (!isset($_COOKIE[config('Session')->cookieName])) {
            return;
        }

        $session   = session();
        $clienteId = (int)$session->get('cliente_id');
        $usuarioId = (int)$session->get('usuario_id');

        if ($clienteId > 0) {
            return redirect()->to(site_url('/'));
        }

        if ($usuarioId > 0) {
            return redirect()->to(site_url($session->get('usuario_is_admin') ? 'admin/home' : 'admin/pedidos'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
