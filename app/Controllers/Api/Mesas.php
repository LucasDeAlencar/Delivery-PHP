<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class Mesas extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $config = $db->table('configuracao_mesas')->where('id', 1)->get()->getRow();
        
        if (!$config || $config->sistema_ativo != 1) {
            return $this->response->setJSON([
                'sucesso' => false,
                'mensagem' => 'Sistema de mesas desativado'
            ]);
        }

        // Sistema de chamada ativo: cliente só vê balcão, admin gerencia as mesas
        if (!empty($config->sistema_chamada) && $config->sistema_chamada == 1) {
            return $this->response->setJSON([
                'sucesso' => true,
                'balcao' => ['id' => 'balcao', 'nome' => 'Balcão (Retirada)', 'ocupado' => false],
                'mesas' => []
            ]);
        }
        
        $mesas = $db->table('mesas')
            ->where('ativo', 1)
            ->where('mostrar_no_carrinho', 1)
            ->orderBy('numero', 'ASC')
            ->get()
            ->getResult();
        
        return $this->response->setJSON([
            'sucesso' => true,
            'mostrar_capacidade_carrinho' => (int)($config->mostrar_capacidade_carrinho ?? 1),
            'balcao' => [
                'id' => 'balcao',
                'nome' => 'Balcão (Retirada)',
                'ocupado' => false
            ],
            'mesas' => $mesas
        ]);
    }

    public function ocupar()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $json = $this->request->getJSON();
        $db = \Config\Database::connect();

        if ($json->local === 'balcao') {
            return $this->response->setJSON(['sucesso' => true]);
        }

        try {
            $mesaId = $json->mesa_id;
            $pedidoId = $json->pedido_id;

            $db->table('mesas')->where('id', $mesaId)->update([
                'ocupado' => 1,
                'pedido_id' => $pedidoId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON(['sucesso' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['sucesso' => false, 'erro' => $e->getMessage()]);
        }
    }
}