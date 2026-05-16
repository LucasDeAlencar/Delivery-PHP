<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ClienteFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if ($session->get('cliente_id') || $session->get('usuario_id')) {
            return; // autenticado
        }

        // Destrói qualquer sessão antiga/corrompida antes de redirecionar,
        // evitando que o VisitanteFilter leia dados obsoletos e crie loop.
        if (session_status() === PHP_SESSION_ACTIVE) {
            $session->destroy();
        }

        return redirect()->to(site_url('login'))->with('info', 'Faça login para continuar');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
