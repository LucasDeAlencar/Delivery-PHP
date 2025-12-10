<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class Registrar extends BaseController {

    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
        helper(['form']);
    }

    /**
     * Exibe o formulário de registro
     */
    public function index() {
        $data = [
            'titulo' => 'Criar Nova Conta'
        ];

        return view('Registrar/novo', $data);
    }

    /**
     * Processa o registro do usuário
     */
    public function criar() {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->back()->with('atencao', 'Método não permitido');
        }

        // Capturar dados do formulário
        $dados = [
            'nome' => $this->request->getPost('nome'),
            'email' => $this->request->getPost('email'),
            'cpf' => $this->request->getPost('cpf'),
            'telefone' => $this->request->getPost('telefone'),
            'password' => $this->request->getPost('password'),
            'password_confirm' => $this->request->getPost('password_confirm'),
            'is_admin' => false, // Usuários públicos não são admin por padrão
            'ativo' => true // Usuários são ativados automaticamente
        ];

        // Validar dados
        if (!$this->usuarioModel->insert($dados)) {
            return redirect()->back()
                            ->withInput()
                            ->with('atencao', 'Erro ao criar conta. Verifique os dados informados.')
                            ->with('erros_model', $this->usuarioModel->errors());
        }

        // Buscar o usuário recém-criado para fazer login automático
        $usuario = $this->usuarioModel->where('email', $dados['email'])->first();

        if ($usuario) {
            // Fazer login automático
            $autenticacao = service('autenticacao');

            if ($autenticacao->login($dados['email'], $dados['password'])) {
                return redirect()->to(site_url('/'))
                                ->with('sucesso', "Bem-vindo(a), {$usuario->nome}! Sua conta foi criada com sucesso.");
            }
        }

        // Se chegou até aqui, conta foi criada mas login automático falhou
        return redirect()->to(site_url('login'))
                        ->with('sucesso', 'Conta criada com sucesso! Faça login para continuar.');
    }
}
