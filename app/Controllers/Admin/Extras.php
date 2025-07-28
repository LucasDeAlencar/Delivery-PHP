<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Entities\Extra;

class Extras extends BaseController {

    private $extraModel;

    public function __construct() {
        $this->extraModel = new \App\Models\ExtraModel();
    }

    public function index() {

        $data = [
            'titulo' => 'Listando os extras de produtos',
            'extras' => $this->extraModel->withDeleted(true)->findAll(),
        ];

        return view('Admin/Extras/index', $data);
    }

    public function show($id) {
        // Busca a extra específica pelo ID
        $extra = $this->extraModel->withDeleted(true)->find($id);

        // Verifica se a extra existe
        if (!$extra) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Extra não encontrada');
        }

        $data = [
            'titulo' => 'Detalhes da Extra',
            'extra' => $extra
        ];

        return view('Admin/Extras/show', $data);
    }

    public function criar() {

        $extra = new \App\Entities\Extra();

        $data = [
            'titulo' => "Criando nova extra",
            'extra' => $extra,
        ];

        return view('Admin/Extras/criar', $data);
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
            $extraExistente = $this->extraModel->where('nome', $post['nome'])->first();
            if ($extraExistente) {
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
            $camposPermitidos = ['nome', 'slug', 'ativo', 'preco', 'descricao'];

            foreach ($camposPermitidos as $campo) {
                if (isset($post[$campo])) {
                    $dadosPermitidos[$campo] = $post[$campo];
                }
            }

            // Usa o model para inserir (aproveitando validações e callbacks)
            if ($this->extraModel->insert($dadosPermitidos)) {
                $insertId = $this->extraModel->getInsertID();

                // Busca a extra criada
                $extra = $this->extraModel->find($insertId);
                $nomeExtra = $extra ? $extra->nome : 'Extra';

                return redirect()->to(site_url("admin/extras/show/" . $insertId))
                                ->with('sucesso', "Extra {$nomeExtra} cadastrada com sucesso");
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->extraModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        }

        // Se não for POST, redireciona
        return redirect()->back();
    }

    public function editar($id) {
        // Busca a extra específica pelo ID
        $extra = $this->extraModel->find($id);

        // Verifica se a extra existe
        if (!$extra) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Extra não encontrada');
        }

        $data = [
            'titulo' => 'Editando a extra',
            'extra' => $extra
        ];

        return view('Admin/Extras/editar.php', $data);
    }

    public function atualizar($id = null) {
        if ($this->request->getMethod() !== 'post') {
            $extra = $this->extraModel->find($id);

            if (!$extra) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Extra não encontrada');
            }

            $post = $this->request->getPost();

            // Remove o token CSRF dos dados antes de processar
            unset($post[csrf_token()]);

            // Trata checkboxes que podem não estar presentes no POST
            $post['ativo'] = isset($post['ativo']) ? 1 : 0;

            // Filtra apenas os campos permitidos
            $dadosPermitidos = [];
            $camposPermitidos = ['nome', 'slug', 'ativo', 'preco', 'descricao'];

            foreach ($camposPermitidos as $campo) {
                if (isset($post[$campo])) {
                    $dadosPermitidos[$campo] = $post[$campo];
                }
            }

            $extra->fill($dadosPermitidos);

            if (!$extra->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            if ($this->extraModel->save($extra)) {

                return redirect()->to(site_url("admin/extras/show/$extra->id"))
                                ->with('sucesso', "Extra $extra->nome atualizada com sucesso");
            } else {

                return redirect()->back()
                                ->with('errors_model', $this->extraModel->errors())
                                ->with('atencao', "Por favor verifique os erros abaixo")
                                ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }

    public function excluir($id) {
        // Busca a extra específica pelo ID
        $extra = $this->extraModel->find($id);

        // Verifica se a extra existe
        if (!$extra) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Extra não encontrada');
        }

        $data = [
            'titulo' => 'Confirmar Exclusão da Extra',
            'extra' => $extra
        ];

        return view('Admin/Extras/excluir.php', $data);
    }

    public function deletar($id = null) {
        if ($this->request->getMethod() !== 'post') {
            $extra = $this->extraModel->find($id);

            if (!$extra) {
                return redirect()->back()->with('erro', 'Extra não encontrada');
            }

            // Usa soft delete
            if ($this->extraModel->delete($id)) {
                return redirect()->to(site_url('admin/extras'))
                                ->with('sucesso', "Extra $extra->nome excluída com sucesso");
            } else {
                return redirect()->back()->with('erro', 'Não foi possível excluir a extra.');
            }
        } else {
            return redirect()->back();
        }
    }

    public function desfazerExclusao($id = null) {
        $extra = $this->extraModel->withDeleted(true)->find($id);
        if (!$extra) {
            return redirect()->back()->with('erro', 'Extra não encontrada.');
        }
        if ($extra->deletado_em === null) {
            return redirect()->back()->with('info', 'Apenas extras excluídas podem ser recuperadas.');
        }

        // Restaura a extra
        $db = \Config\Database::connect();
        $result = $db->table('extras')
                ->where('id', $id)
                ->update(['deletado_em' => null]);

        if ($result) {
            return redirect()->to(site_url('admin/extras/show/' . $id))
                            ->with('sucesso', 'Extra restaurada com sucesso.');
        } else {
            return redirect()->back()->with('erro', 'Não foi possível restaurar a extra.');
        }
    }
}
