<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BairroModel;

class BairroController extends BaseController
{
    public function taxa()
    {
        $request = $this->request;
        
        if (!$request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acesso negado']);
        }
        
        $bairro = $request->getPost('bairro');
        $cidade = $request->getPost('cidade');
        
        if (!$bairro || !$cidade) {
            return $this->response->setJSON(['taxa' => 0]);
        }
        
        $bairroModel = new BairroModel();
        
        $resultado = $bairroModel->where('nome', $bairro)
                                 ->where('cidade', $cidade)
                                 ->where('ativo', 1)
                                 ->first();
        
        $taxa = $resultado ? (float)$resultado['taxa'] : 0;
        
        return $this->response->setJSON(['taxa' => $taxa]);
    }
}
