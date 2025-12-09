<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Entities\Medida;

class Medidas extends BaseController {
    
    private $medidaModel;
    
    public function __construct() {
        $this->medidaModel = new \App\Models\MedidaModel();
    }

    public function index() {
        
        $data = [
            'titulo' => 'Listando as medidas de produtos',
            'medidas' => $this->medidaModel->withDeleted(true)->paginate(10),
            'pager' => $this->medidaModel->pager,
        ];
        
        return view('Admin/Medidas/index', $data);
    }
    
    public function show($id) {
        // Busca a medida específica pelo ID
        $medida = $this->medidaModel->withDeleted(true)->find($id);

        // Verifica se a medida existe
        if (!$medida) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Medida não encontrada');
        }

        $data = [
            'titulo' => 'Detalhes da Medida',
            'medida' => $medida
        ];

        return view('Admin/Medidas/show', $data);
    }
    
    public function criar() {

        $medida = new \App\Entities\Medida();

        $data = [
            'titulo' => "Criando nova medida",
            'medida' => $medida,
        ];

        return view('Admin/Medidas/criar', $data);
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
            $medidaExistente = $this->medidaModel->where('nome', $post['nome'])->first();
            if ($medidaExistente) {
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
            $camposPermitidos = ['nome', 'ativo', 'descricao'];

            foreach ($camposPermitidos as $campo) {
                if (isset($post[$campo])) {
                    $dadosPermitidos[$campo] = $post[$campo];
                }
            }

            // Usa o model para inserir (aproveitando validações e callbacks)
            if ($this->medidaModel->insert($dadosPermitidos)) {
                $insertId = $this->medidaModel->getInsertID();

                // Busca a medida criada
                $medida = $this->medidaModel->find($insertId);
                $nomeMedida = $medida ? $medida->nome : 'Medida';

                return redirect()->to(site_url("admin/medidas/show/" . $insertId))
                                ->with('sucesso', "Medida {$nomeMedida} cadastrada com sucesso");
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->medidaModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        }

        // Se não for POST, redireciona
        return redirect()->back();
    }

    public function editar($id) {
        // Busca a medida específica pelo ID
        $medida = $this->medidaModel->find($id);

        // Verifica se a medida existe
        if (!$medida) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Medida não encontrada');
        }

        $data = [
            'titulo' => 'Editando a medida',
            'medida' => $medida
        ];

        return view('Admin/Medidas/editar.php', $data);
    }

    public function atualizar($id = null) {
        if ($this->request->getMethod() !== 'post') {
            $medida = $this->medidaModel->find($id);

            if (!$medida) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Medida não encontrada');
            }

            $post = $this->request->getPost();

            // Remove o token CSRF dos dados antes de processar
            unset($post[csrf_token()]);

            // Trata checkboxes que podem não estar presentes no POST
            $post['ativo'] = isset($post['ativo']) ? 1 : 0;

            // Filtra apenas os campos permitidos
            $dadosPermitidos = [];
            $camposPermitidos = ['nome', 'ativo', 'descricao'];

            foreach ($camposPermitidos as $campo) {
                if (isset($post[$campo])) {
                    $dadosPermitidos[$campo] = $post[$campo];
                }
            }

            $medida->fill($dadosPermitidos);

            if (!$medida->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            if ($this->medidaModel->save($medida)) {

                return redirect()->to(site_url("admin/medidas/show/$medida->id"))
                                ->with('sucesso', "Medida $medida->nome atualizada com sucesso");
            } else {

                return redirect()->back()
                                ->with('errors_model', $this->medidaModel->errors())
                                ->with('atencao', "Por favor verifique os erros abaixo")
                                ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }

    public function excluir($id) {
        // Busca a medida específica pelo ID
        $medida = $this->medidaModel->find($id);

        // Verifica se a medida existe
        if (!$medida) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Medida não encontrada');
        }

        $data = [
            'titulo' => 'Confirmar Exclusão da Medida',
            'medida' => $medida
        ];

        return view('Admin/Medidas/excluir.php', $data);
    }

    public function deletar($id = null) {
        if ($this->request->getMethod() !== 'post') {
            $medida = $this->medidaModel->find($id);

            if (!$medida) {
                return redirect()->back()->with('erro', 'Medida não encontrada');
            }

            // Usa soft delete
            if ($this->medidaModel->delete($id)) {
                return redirect()->to(site_url('admin/medidas'))
                                ->with('sucesso', "Medida $medida->nome excluída com sucesso");
            } else {
                return redirect()->back()->with('erro', 'Não foi possível excluir a medida.');
            }
        } else {
            return redirect()->back();
        }
    }

    public function desfazerExclusao($id = null) {
        $medida = $this->medidaModel->withDeleted(true)->find($id);
        if (!$medida) {
            return redirect()->back()->with('erro', 'Medida não encontrada.');
        }
        if ($medida->deletado_em === null) {
            return redirect()->back()->with('info', 'Apenas medidas excluídas podem ser recuperadas.');
        }

        // Restaura a medida
        $db = \Config\Database::connect();
        $result = $db->table('medidas')
                ->where('id', $id)
                ->update(['deletado_em' => null]);

        if ($result) {
            return redirect()->to(site_url('admin/medidas/show/' . $id))
                            ->with('sucesso', 'Medida restaurada com sucesso.');
        } else {
            return redirect()->back()->with('erro', 'Não foi possível restaurar a medida.');
        }
    }
}
