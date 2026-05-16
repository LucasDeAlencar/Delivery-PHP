<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if ((int)session()->get('usuario_id') > 0) {
            return; // admin logado
        }

        if ($request->isAJAX()) {
            return service('response')
                ->setJSON(['success' => false, 'message' => 'Sessão expirada. Faça login novamente.'])
                ->setStatusCode(401);
        }

        return redirect()->to(site_url('login'))->with('info', 'Por favor realize o login primeiramente');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
