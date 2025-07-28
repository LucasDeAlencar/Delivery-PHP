<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Password extends BaseController {

    private $usuarioModel;

    public function __construct() {

        $this->usuarioModel = new \App\Models\UsuarioModel();
    }

    public function esqueci() {
        $data = [
            'titulo' => 'Esqueci a minha senha',
        ];

        return view('Password/esqueci', $data);
    }

    public function processaEsqueci() {
        if ($this->request->getMethod() !== 'post') {

            $usuario = $this->usuarioModel->buscaUsuarioPorEmail($this->request->getPost('email'));

            if ($usuario === null || !$usuario->ativo) {
                return redirect()->to(site_url('password/esqueci'))
                                ->with('atencao', 'Não encontramos uma conta válida com esse email')
                                ->withInput();
            }

            $usuario->iniciaPasswordReset();

            $this->usuarioModel->save($usuario);

            $this->enviaEmailRedeFinicaoSenha($usuario);

            return redirect()->to(site_url('login'))->with('sucesso', 'E-mail de redefinição de senha enviado para sua caixa de entrada');
        } else {
            /* Não é POST */
            return redirect()->back();
        }
    }

    public function reset($token = null) {

        if ($token === null) {
            return redirect()->to(site_url('password/esqueci'))->with('atencao', 'Link invalido ou expirado');
        }

        $usuario = $this->usuarioModel->buscaUsuarioParaResetarSenha($token);

        if ($usuario != null) {
            $data = [
                'titulo' => 'Redefina a sua senha',
                'token' => $token,
            ];

            return view('Password/reset', $data);
        } else {
            return redirect()->to(site_url('password/esqueci'))->with('atencao', 'Link inválido ou expirado');
        }
    }

    public function enviaEmailRedeFinicaoSenha($usuario) {
        $email = service('email');

        $email->setFrom('your@example.com', "Your Name");
        $email->setTo('eef25e43be@emaily.pro');
//        $email->setCC('another@another-example.com');
//        $email->setBCC('them@their-example.com');

        $mensage = view('Password/reset_email', ['token' => $usuario->reset_token]);

        $email->setSubject('Redefinição de senha');
        $email->setMessage($mensage);

        $email->send();
    }

    public function processareset($token = null) {

        if ($token === null) {
            return redirect()->to(site_url('password/esqueci'))->with('atencao', 'Link invalido ou expirado');
        }

        $usuario = $this->usuarioModel->buscaUsuarioParaResetarSenha($token);

        if ($usuario != null) {

            /**
             * setando as colunas 'reset_hash' e 'reset_expires_em' como null ao invocar o método abaixo
             * que foi na Entidade User
             */
            $usuario->fill($this->request->getPost());

//            Atualizamos novamente o usúario com os novos valores definidos acima
            $this->usuarioModel->save($usuario);
            
            if ($this->usuarioModel->save($usuario)) {
                return redirect()->to(site_url("login"))->with('sucesso', 'Nova senha cadastrada com sucesso');
            } else {
                return redirect()->to(site_url("password/reset/$token"))
                                ->with('errors_model', $this->usuarioModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        } else {
            return redirect()->to(site_url('password/esqueci'))->with('atencao', 'Link inválido ou expirado');
        }
    }
}
