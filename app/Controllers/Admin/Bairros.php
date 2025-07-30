<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Entities\Bairro;

class Bairros extends BaseController {
    
    private $bairroModel;
    
    public function __construct() {
        $this->bairroModel = new \App\Models\BairroModel();
    }

    public function index() {
        $data = [
            'titulo'  => "Listando os bairros atendidos",
            'bairros' => $this->bairroModel->withDeleted(true)->paginate(10),
            'pager' => $this->bairroModel->pager,
        ];
        
        return view('Admin/Bairros/index', $data);
    }

    public function criar() {
        $bairro = new Bairro();
        
        $data = [
            'titulo' => 'Criando novo bairro',
            'bairro' => $bairro,
        ];
        
        return view('Admin/Bairros/criar', $data);
    }

    public function cadastrar() {
        if (!$this->request->isAJAX()) {
            $checkToken = $this->validaToken();
            if (!$checkToken) {
                return redirect()->back()->with('erro', 'Token inválido');
            }
        }

        // Filtrar apenas os campos permitidos (CEP não é salvo no banco)
        $dadosPost = $this->request->getPost();
        $dadosLimpos = [];
        
        // Campos permitidos
        $camposPermitidos = ['nome', 'cidade', 'valor_entrega', 'ativo'];
        
        foreach ($camposPermitidos as $campo) {
            if (isset($dadosPost[$campo])) {
                $dadosLimpos[$campo] = $dadosPost[$campo];
            }
        }
        
        // Garantir que ativo seja 0 se não foi enviado (checkbox desmarcado)
        if (!isset($dadosLimpos['ativo'])) {
            $dadosLimpos['ativo'] = 0;
        }
        
        // Converter valor de entrega do formato brasileiro para decimal
        if (isset($dadosLimpos['valor_entrega'])) {
            $dadosLimpos['valor_entrega'] = $this->converterValorParaDecimal($dadosLimpos['valor_entrega']);
        }

        $bairro = new Bairro($dadosLimpos);

        if ($this->bairroModel->save($bairro)) {
            return redirect()->to(site_url("admin/bairros/show/" . $this->bairroModel->getInsertID()))
                           ->with('sucesso', 'Bairro criado com sucesso!');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('errors_model', $this->bairroModel->errors())
                           ->with('atencao', 'Por favor, verifique os erros abaixo');
        }
    }

    public function show($id = null) {
        $bairro = $this->buscaBairroOu404($id);
        
        $data = [
            'titulo' => "Detalhando o bairro " . esc($bairro->nome),
            'bairro' => $bairro,
        ];
        
        return view('Admin/Bairros/show', $data);
    }

    public function editar($id = null) {
        $bairro = $this->buscaBairroOu404($id);
        
        $data = [
            'titulo' => "Editando o bairro " . esc($bairro->nome),
            'bairro' => $bairro,
        ];
        
        return view('Admin/Bairros/editar', $data);
    }

    public function atualizar($id = null) {
        if (!$this->request->isAJAX()) {
            $checkToken = $this->validaToken();
            if (!$checkToken) {
                return redirect()->back()->with('erro', 'Token inválido');
            }
        }

        $bairro = $this->buscaBairroOu404($id);
        
        // Filtrar apenas os campos permitidos (CEP não é salvo no banco)
        $dadosPost = $this->request->getPost();
        $dadosLimpos = [];
        
        // Campos permitidos
        $camposPermitidos = ['nome', 'cidade', 'valor_entrega', 'ativo'];
        
        foreach ($camposPermitidos as $campo) {
            if (isset($dadosPost[$campo])) {
                $dadosLimpos[$campo] = $dadosPost[$campo];
            }
        }
        
        // Garantir que ativo seja 0 se não foi enviado (checkbox desmarcado)
        if (!isset($dadosLimpos['ativo'])) {
            $dadosLimpos['ativo'] = 0;
        }
        
        // Converter valor de entrega do formato brasileiro para decimal
        if (isset($dadosLimpos['valor_entrega'])) {
            $dadosLimpos['valor_entrega'] = $this->converterValorParaDecimal($dadosLimpos['valor_entrega']);
        }
        
        $bairro->fill($dadosLimpos);

        if (!$bairro->hasChanged()) {
            return redirect()->back()->with('info', 'Não há dados para serem atualizados');
        }

        if ($this->bairroModel->save($bairro)) {
            return redirect()->to(site_url("admin/bairros/show/" . $bairro->id))
                           ->with('sucesso', 'Bairro atualizado com sucesso!');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('errors_model', $this->bairroModel->errors())
                           ->with('atencao', 'Por favor, verifique os erros abaixo');
        }
    }

    public function excluir($id = null) {
        $bairro = $this->buscaBairroOu404($id);
        
        if ($bairro->deletado_em != null) {
            return redirect()->back()->with('info', 'Este bairro já foi excluído anteriormente');
        }
        
        $data = [
            'titulo' => "Excluindo o bairro " . esc($bairro->nome),
            'bairro' => $bairro,
        ];
        
        return view('Admin/Bairros/excluir', $data);
    }

    public function deletar($id = null) {
        if (!$this->request->isAJAX()) {
            $checkToken = $this->validaToken();
            if (!$checkToken) {
                return redirect()->back()->with('erro', 'Token inválido');
            }
        }

        $bairro = $this->buscaBairroOu404($id);
        
        if ($bairro->deletado_em != null) {
            return redirect()->back()->with('info', 'Este bairro já foi excluído anteriormente');
        }

        if ($this->bairroModel->delete($id)) {
            return redirect()->to(site_url('admin/bairros'))
                           ->with('sucesso', 'Bairro excluído com sucesso!');
        } else {
            return redirect()->back()
                           ->with('errors_model', $this->bairroModel->errors())
                           ->with('atencao', 'Não foi possível excluir o bairro. Verifique se não há dependências.');
        }
    }

    public function desfazerExclusao($id = null) {
        if (!$this->request->isAJAX()) {
            $checkToken = $this->validaToken();
            if (!$checkToken) {
                return redirect()->back()->with('erro', 'Token inválido');
            }
        }

        $bairro = $this->buscaBairroOu404($id);
        
        if ($bairro->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas bairros excluídos podem ser restaurados');
        }

        if ($this->bairroModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', 'Exclusão desfeita com sucesso!');
        } else {
            return redirect()->back()
                           ->with('errors_model', $this->bairroModel->errors())
                           ->with('atencao', 'Não foi possível desfazer a exclusão. Tente novamente.');
        }
    }

    private function buscaBairroOu404(int $id = null) {
        if (!$id || !is_numeric($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Bairro $id não encontrado");
        }

        $bairro = $this->bairroModel
                      ->withDeleted(true)
                      ->where('id', $id)
                      ->first();

        if (!$bairro) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Bairro $id não encontrado");
        }

        return $bairro;
    }

    private function validaToken() {
        $token = $this->request->getPost(csrf_token());
        if (!$token || !hash_equals(csrf_hash(), $token)) {
            return false;
        }
        return true;
    }
    
    private function converterValorParaDecimal($valor) {
        // Remove pontos (separadores de milhares) e substitui vírgula por ponto
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
        
        // Garante que seja um número válido
        return is_numeric($valor) ? (float) $valor : 0;
    }
}