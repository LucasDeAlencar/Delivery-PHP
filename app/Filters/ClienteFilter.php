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

        // Modo 3: home pública — deixa a home carregar, bloqueia ações com JSON
        $db = \Config\Database::connect();
        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        if ((int)($dadosCorp->modo_cadastro ?? 1) === 3) {
            $uri = trim($request->getUri()->getPath(), '/');
            $base = trim(parse_url(config('App')->baseURL, PHP_URL_PATH) ?? '', '/');
            $relativo = $base ? ltrim(substr($uri, strlen($base)), '/') : $uri;
            if ($relativo === '' || $relativo === 'api/status-expediente') {
                return;
            }
            return service('response')
                ->setJSON(['success' => false, 'requer_login' => true, 'message' => 'Faça login para continuar'])
                ->setStatusCode(401);
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
