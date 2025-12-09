<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Entities\Usuario;

class Usuarios extends BaseController {

    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index() {
        // Busca todos os usuários do banco de dados
        $usuarios = $this->usuarioModel->withDeleted(true)->findAll();

        $data = [
            'titulo' => 'Relatório de Usuários',
            'usuarios' => $usuarios
        ];

        session()->remove('sucesso');
//        session()->set('sucesso', "Olá Lucio");

        return view('Admin/Usuarios/index.php', $data);
    }

    public function show($id) {
        // Busca o usuário específico pelo ID
        $usuario = $this->usuarioModel->find($id);

        // Verifica se o usuário existe
        if (!$usuario) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Usuário não encontrado');
        }

        $data = [
            'titulo' => 'Detalhes do Usuário',
            'usuario' => $usuario
        ];

        return view('Admin/Usuarios/show.php', $data);
    }

    public function editar($id) {
        // Busca o usuário específico pelo ID
        $usuario = $this->usuarioModel->find($id);

        // Verifica se o usuário existe
        if (!$usuario) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Usuário não encontrado');
        }

        $data = [
            'titulo' => 'Editando o Usuário',
            'usuario' => $usuario
        ];

        return view('Admin/Usuarios/editar.php', $data);
    }

    public function atualizar($id = null) {
        if ($this->request->getMethod() !== 'post') {
            $usuario = $this->usuarioModel->find($id);

            if (!$usuario) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Usuário não encontrado');
            }

            $post = $this->request->getPost();

            // Remove o token CSRF dos dados antes de processar
            unset($post[csrf_token()]);

            // Configura validação para atualização (permite manter email e CPF do próprio usuário)
            $this->usuarioModel->configurarValidacaoParaAtualizacao($id);

            if (empty($post['password'])) {
                $this->usuarioModel->desabilitaValidacaoSenha();
                unset($post['password']);
                unset($post['password_confirm']);
            } else {
                // Se uma nova senha foi fornecida, gera o hash
                $post['password_hash'] = password_hash($post['password'], PASSWORD_DEFAULT);
                unset($post['password']);
                unset($post['password_confirm']);
            }

            // Trata checkboxes que podem não estar presentes no POST
            $post['ativo'] = isset($post['ativo']) ? 1 : 0;
            $post['is_admin'] = isset($post['is_admin']) ? 1 : 0;

            // Filtra apenas os campos permitidos
            $dadosPermitidos = [];
            $camposPermitidos = ['nome', 'email', 'cpf', 'telefone', 'ativo', 'is_admin', 'password_hash'];
            
            foreach ($camposPermitidos as $campo) {
                if (isset($post[$campo])) {
                    $dadosPermitidos[$campo] = $post[$campo];
                }
            }

            $usuario->fill($dadosPermitidos);

            if (!$usuario->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            if ($this->usuarioModel->protect(false)->save($usuario)) {

                return redirect()->to(site_url("admin/usuarios/show/$usuario->id"))
                                ->with('sucesso', "Usuário $usuario->nome atualizado com sucesso");
            } else {

                return redirect()->back()
                                ->with('errors_model', $this->usuarioModel->errors())
                                ->with('atencao', "Por favor verifique os erros abaixo")
                                ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }

    public function criar() {

        $usuario = new Usuario();

        $data = [
            'titulo' => "Criando novo usuário",
            'usuario' => $usuario,
        ];

        return view('Admin/Usuarios/criar', $data);
    }

    public function excluir($id) {
        // Busca o usuário específico pelo ID
        $usuario = $this->usuarioModel->find($id);

        // Verifica se o usuário existe
        if (!$usuario) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Usuário não encontrado');
        } else if ($usuario->is_admin) {
            return redirect()->back()->with('info', 'Não foi possivel excluir um usuário <b>Administrador</b>');
        }

        $data = [
            'titulo' => 'Confirmar Exclusão do Usuário',
            'usuario' => $usuario
        ];

        return view('Admin/Usuarios/excluir.php', $data);
    }

    public function deletar($id = null) {
        if ($this->request->getMethod() !== 'post') {
            $usuario = $this->usuarioModel->find($id);

            if (!$usuario) {
                return redirect()->back()->with('erro', 'Usuário não encontrado');
            }

            if ($usuario->is_admin) {
                return redirect()->back()->with('info', 'Não é possível excluir um usuário <b>Administrador</b>');
            }

            // Usa soft delete diretamente no banco
            $db = \Config\Database::connect();
            $result = $db->table('usuarios')
                         ->where('id', $id)
                         ->update(['deletado_em' => date('Y-m-d H:i:s')]);

            if ($result) {
                return redirect()->to(site_url('admin/usuarios'))
                                ->with('sucesso', "Usuário $usuario->nome excluído com sucesso");
            } else {
                return redirect()->back()->with('erro', 'Não foi possível excluir o usuário.');
            }
        } else {
            return redirect()->back();
        }
    }

    public function cadastrar() {
        // Verifica se é uma requisição POST
        if ($this->request->getMethod() !== 'post') {
            $post = $this->request->getPost();

            // Remove o token CSRF dos dados antes de processar
            unset($post[csrf_token()]);

            // Trata checkboxes que podem não estar presentes no POST
            $post['ativo'] = isset($post['ativo']) ? 1 : 0;
            $post['is_admin'] = isset($post['is_admin']) ? 1 : 0;

            // Validação manual simples
            $errors = [];
            
            if (empty($post['nome'])) {
                $errors['nome'] = 'O campo Nome é obrigatório';
            }
            
            if (empty($post['email']) || !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'O campo E-mail é obrigatório e deve ser válido';
            }
            
            if (empty($post['telefone'])) {
                $errors['telefone'] = 'O campo Telefone é obrigatório';
            }
            
            if (empty($post['password']) || strlen($post['password']) < 6) {
                $errors['password'] = 'O campo Senha é obrigatório e deve ter pelo menos 6 caracteres';
            }
            
            if ($post['password'] !== $post['password_confirm']) {
                $errors['password_confirm'] = 'As senhas não coincidem';
            }
            
            // Verifica se email já existe
            $usuarioExistente = $this->usuarioModel->where('email', $post['email'])->first();
            if ($usuarioExistente) {
                $errors['email'] = 'Este e-mail já está em uso';
            }
            
            // Se houver erros, retorna
            if (!empty($errors)) {
                return redirect()->back()
                                ->with('errors_model', $errors)
                                ->with('atencao', "Por favor verifique os erros abaixo")
                                ->withInput();
            }

            // Prepara dados para inserção
            $dadosUsuario = [
                'nome' => $post['nome'],
                'email' => $post['email'],
                'cpf' => $post['cpf'] ?? null,
                'telefone' => $post['telefone'],
                'ativo' => $post['ativo'],
                'is_admin' => $post['is_admin'],
                'password_hash' => password_hash($post['password'], PASSWORD_DEFAULT),
                'ativacao_hash' => bin2hex(random_bytes(32)), // Gera hash único para ativação
                'reset_hash' => password_hash($post['password'], PASSWORD_DEFAULT) // Inicializa como null
            ];

            // Insere diretamente no banco usando query builder
            $db = \Config\Database::connect();
            
            if ($db->table('usuarios')->insert($dadosUsuario)) {
                $insertId = $db->insertID();
                
                // Busca o usuário criado
                $usuario = $this->usuarioModel->find($insertId);
                $nomeUsuario = $usuario ? $usuario->nome : 'Usuário';

                return redirect()->to(site_url("admin/usuarios/show/" . $insertId))
                                ->with('sucesso', "Usuário {$nomeUsuario} cadastrado com sucesso");
            } else {
                return redirect()->back()
                                ->with('erro', 'Erro ao cadastrar usuário')
                                ->withInput();
            }
        }

        // Se não for POST, redireciona
        return redirect()->back();
    }

    public function desfazerExclusao($id = null) {
        $usuario = $this->usuarioModel->withDeleted(true)->find($id);
        if (!$usuario) {
            return redirect()->back()->with('erro', 'Usuário não encontrado.');
        }
        if ($usuario->deletado_em === null) {
            return redirect()->back()->with('info', 'Apenas usuários excluídos podem ser recuperados.');
        }
        if ($this->usuarioModel->desfazerExclusao($id)) {
            return redirect()->to(site_url('admin/usuarios/show/' . $id))
                            ->with('sucesso', 'Usuário restaurado com sucesso.');
        } else {
            return redirect()->back()->with('erro', 'Não foi possível restaurar o usuário.');
        }
    }
}
