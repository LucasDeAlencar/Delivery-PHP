<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController {

    public function __construct() {
        helper(['form']);
    }

    public function novo() {
        $data = [
            'titulo' => "Realize o login "
        ];

        return view('Login/novo', $data);
    }

    public function criar() {

        // Verificar se é uma requisição POST
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->back()->with('atencao', 'Método não permitido');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validação básica
        if (empty($email) || empty($password)) {
            return redirect()->back()->with('atencao', 'Por favor, preencha todos os campos');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('atencao', 'Por favor, digite um e-mail válido');
        }

        $autenticacao = service('autenticacao');

        if ($autenticacao->login($email, $password)) {

            $usuario = $autenticacao->pegaUsuarioLogado();

            // Verificar se o usuário está ativo
            if (!$usuario->ativo) {
                $autenticacao->logout();
                return redirect()->back()->with('atencao', 'Sua conta está desativada. Entre em contato com o suporte.');
            }

            // Redirecionar baseado no tipo de usuário
            if ($usuario->is_admin) {
                return redirect()->to(site_url('admin/home'))->with('sucesso', "Olá {$usuario->nome}, que bom que está de volta!");
            } else {
                return redirect()->to(site_url('/'))->with('sucesso', "Bem-vindo(a), {$usuario->nome}!");
            }
        }

        return redirect()->back()->with('atencao', 'E-mail ou senha incorretos');
    }

    /**
     * Realiza o logout do usuário
     */
    public function logout() {
        $autenticacao = service('autenticacao');
        $usuario = $autenticacao->pegaUsuarioLogado();

        $nomeUsuario = $usuario ? $usuario->nome : 'Usuário';

        $autenticacao->logout();

        return redirect()->to(site_url('login'))->with('info', "Até logo, {$nomeUsuario}! Esperamos ver você novamente.");
    }
}
