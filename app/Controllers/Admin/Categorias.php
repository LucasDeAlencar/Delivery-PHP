<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;

class Categorias extends BaseController {

    private $categoriaModel;

    public function __construct() {

        $this->categoriaModel = new \App\Models\CategoriaModel();
    }

    public function index() {

        $data = [
            'titulo' => 'Listando as categorias',
            'categorias' => $this->categoriaModel->withDeleted(true)->paginate(10),
            'pager' => $this->categoriaModel->pager,
        ];

        return view('Admin/Categorias/index', $data);
    }

    public function show($id) {
        // Busca a categoria específica pelo ID
        $categoria = $this->categoriaModel->withDeleted(true)->find($id);

        // Verifica se a categoria existe
        if (!$categoria) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Categoria não encontrada');
        }

        $data = [
            'titulo' => 'Detalhes da Categoria',
            'categoria' => $categoria
        ];

        return view('Admin/Categorias/show', $data);
    }

    public function editar($id) {
        // Busca a categoria específica pelo ID
        $categoria = $this->categoriaModel->find($id);

        // Verifica se a categoria existe
        if (!$categoria) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Categoria não encontrada');
        }

        $data = [
            'titulo' => 'Editando a categoria',
            'categoria' => $categoria
        ];

        return view('Admin/Categorias/editar.php', $data);
    }

    public function atualizar($id = null) {
        if ($this->request->getMethod() !== 'post') {
            $categoria = $this->categoriaModel->find($id);

            if (!$categoria) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Categoria não encontrada');
            }

            $post = $this->request->getPost();

            // Remove o token CSRF dos dados antes de processar
            unset($post[csrf_token()]);

            // Trata checkboxes que podem não estar presentes no POST
            $post['ativo'] = isset($post['ativo']) ? 1 : 0;

            // Filtra apenas os campos permitidos
            $dadosPermitidos = [];
            $camposPermitidos = ['nome', 'slug', 'ativo'];

            foreach ($camposPermitidos as $campo) {
                if (isset($post[$campo])) {
                    $dadosPermitidos[$campo] = $post[$campo];
                }
            }

            $categoria->fill($dadosPermitidos);

            if (!$categoria->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            if ($this->categoriaModel->save($categoria)) {

                return redirect()->to(site_url("admin/categorias/show/$categoria->id"))
                                ->with('sucesso', "Categoria $categoria->nome atualizada com sucesso");
            } else {

                return redirect()->back()
                                ->with('errors_model', $this->categoriaModel->errors())
                                ->with('atencao', "Por favor verifique os erros abaixo")
                                ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }

    public function criar() {

        $categoria = new \App\Entities\Categoria();

        $data = [
            'titulo' => "Criando nova categoria",
            'categoria' => $categoria,
        ];

        return view('Admin/Categorias/criar', $data);
    }

    public function cadastrar() {
        // Verifica se é uma requisição POST
        if ($this->request->getMethod() !== 'post') {
            $post = $this->request->getPost();

            // Remove o token CSRF dos dados antes de processar
            unset($post[csrf_token()]);

            // Trata checkboxes que podem não estar presentes no POST
            $post['ativo'] = isset($post['ativo']) ? 1 : 0;

            $errors = [];

            // Verifica se nome já existe
            $categoriaExistente = $this->categoriaModel->where('nome', $post['nome'])->first();
            if ($categoriaExistente) {
                $errors['nome'] = 'Este nome já está em uso';
            }

            // Se houver erros, retorna
            if (!empty($errors)) {
                return redirect()->back()
                                ->with('errors_model', $errors)
                                ->with('atencao', "Por favor verifique os erros abaixo")
                                ->withInput();
            }

            // Filtra apenas os campos permitidos
            $dadosPermitidos = [];
            $camposPermitidos = ['nome', 'slug', 'ativo'];

            foreach ($camposPermitidos as $campo) {
                if (isset($post[$campo])) {
                    $dadosPermitidos[$campo] = $post[$campo];
                }
            }

            // Usa o model para inserir (aproveitando validações e callbacks)
            if ($this->categoriaModel->insert($dadosPermitidos)) {
                $insertId = $this->categoriaModel->getInsertID();

                // Busca a categoria criada
                $categoria = $this->categoriaModel->find($insertId);
                $nomeCategoria = $categoria ? $categoria->nome : 'Categoria';

                return redirect()->to(site_url("admin/categorias/show/" . $insertId))
                                ->with('sucesso', "Categoria {$nomeCategoria} cadastrada com sucesso");
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->categoriaModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        }

        // Se não for POST, redireciona
        return redirect()->back();
    }

    public function excluir($id) {
        // Busca a categoria específica pelo ID
        $categoria = $this->categoriaModel->find($id);

        // Verifica se a categoria existe
        if (!$categoria) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Categoria não encontrada');
        }

        $data = [
            'titulo' => 'Confirmar Exclusão da Categoria',
            'categoria' => $categoria
        ];

        return view('Admin/Categorias/excluir.php', $data);
    }

    public function deletar($id = null) {
        if ($this->request->getMethod() !== 'post') {
            $categoria = $this->categoriaModel->find($id);

            if (!$categoria) {
                return redirect()->back()->with('erro', 'Categoria não encontrada');
            }

            // Usa soft delete
            if ($this->categoriaModel->delete($id)) {
                return redirect()->to(site_url('admin/categorias'))
                                ->with('sucesso', "Categoria $categoria->nome excluída com sucesso");
            } else {
                return redirect()->back()->with('erro', 'Não foi possível excluir a categoria.');
            }
        } else {
            return redirect()->back();
        }
    }

    public function desfazerExclusao($id = null) {
        $categoria = $this->categoriaModel->withDeleted(true)->find($id);
        if (!$categoria) {
            return redirect()->back()->with('erro', 'Categoria não encontrada.');
        }
        if ($categoria->deletado_em === null) {
            return redirect()->back()->with('info', 'Apenas categorias excluídas podem ser recuperadas.');
        }
        
        // Restaura a categoria
        $db = \Config\Database::connect();
        $result = $db->table('categorias')
                     ->where('id', $id)
                     ->update(['deletado_em' => null]);
        
        if ($result) {
            return redirect()->to(site_url('admin/categorias/show/' . $id))
                            ->with('sucesso', 'Categoria restaurada com sucesso.');
        } else {
            return redirect()->back()->with('erro', 'Não foi possível restaurar a categoria.');
        }
    }
}
