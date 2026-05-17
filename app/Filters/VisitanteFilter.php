<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class VisitanteFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (isset($_COOKIE[config('Session')->cookieName])) {
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

        // Modo 3: /login é substituído pelo popup — redireciona para home
        $db = \Config\Database::connect();
        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        if ((int)($dadosCorp->modo_cadastro ?? 1) === 3) {
            return redirect()->to(site_url('/'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
