<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ConfiguracaoApi extends ResourceController
{
    public function precoMinimo()
    {
        $db = \Config\Database::connect();
        $dados = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        
        $precoMinimo = $dados ? $dados->preco_minimo_compra : 0;
        
        return $this->respond([
            'success' => true,
            'preco_minimo' => (float) $precoMinimo
        ]);
    }
}
