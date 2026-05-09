<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BairroModel;

class BairrosApi extends BaseController
{
    public function index()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acesso negado']);
        }

        $bairroModel = new BairroModel();
        $bairros = $bairroModel->where('ativo', 1)
                               ->where('deletado_em IS NULL')
                               ->orderBy('nome', 'ASC')
                               ->findAll();

        // Formatar para o frontend
        $data = array_map(function($b) {
            return [
                'id' => $b['id'],
                'nome' => $b['nome'],
                'valor_entrega' => (float)($b['valor_entrega'] ?? 0)
            ];
        }, $bairros);

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }
}
