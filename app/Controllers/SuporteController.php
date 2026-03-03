<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SuporteController extends BaseController
{
    public function criar()
    {
        $json = $this->request->getJSON(true);
        
        if (!$json || !isset($json['pedido_id']) || !isset($json['razao'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dados incompletos'
            ]);
        }

        $db = \Config\Database::connect();
        
        $nomeClienteDb = 'Cliente';
        $telefoneClienteDb = '';
        
        if (!empty($json['codigo_pedido'])) {
            $pedido = $db->query("SELECT nome_cliente, telefone_cliente FROM pedidos WHERE codigo = ?", [$json['codigo_pedido']])->getRow();
            if ($pedido) {
                $nomeClienteDb = $pedido->nome_cliente ?: 'Cliente';
                $telefoneClienteDb = $pedido->telefone_cliente ?: '';
            }
        }
        
        $data = [
            'pedido_id' => $json['pedido_id'],
            'codigo_pedido' => $json['codigo_pedido'],
            'cliente_nome' => $nomeClienteDb,
            'cliente_telefone' => $json['cliente_telefone'] ?: $telefoneClienteDb,
            'razao' => $json['razao'],
            'status' => 'pendente'
        ];

        try {
            $db->table('suporte_pedidos')->insert($data);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Suporte criado com sucesso'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao criar suporte: ' . $e->getMessage()
            ]);
        }
    }

    public function listar()
    {
        $db = \Config\Database::connect();
        
        $suportes = $db->table('suporte_pedidos')
            ->orderBy('criado_em', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'suportes' => $suportes
        ]);
    }

    public function atualizar($id)
    {
        $status = $this->request->getPost('status');
        
        if (!$status) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status não informado'
            ]);
        }

        $db = \Config\Database::connect();
        
        try {
            $db->table('suporte_pedidos')
                ->where('id', $id)
                ->update(['status' => $status]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status atualizado'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao atualizar: ' . $e->getMessage()
            ]);
        }
    }

    public function deletar($id)
    {
        $db = \Config\Database::connect();
        
        try {
            $db->table('suporte_pedidos')
                ->where('id', $id)
                ->delete();
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Suporte removido'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao deletar: ' . $e->getMessage()
            ]);
        }
    }

    public function resolverTodos()
    {
        $json = $this->request->getJSON(true);
        
        if (!isset($json['codigo_pedido'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Código do pedido não informado'
            ]);
        }

        $db = \Config\Database::connect();
        
        try {
            $db->table('suporte_pedidos')
                ->where('codigo_pedido', $json['codigo_pedido'])
                ->where('status', 'pendente')
                ->update(['status' => 'resolvido']);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Todos os suportes foram resolvidos'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao resolver: ' . $e->getMessage()
            ]);
        }
    }

    public function infoPedido($pedidoId)
    {
        $db = \Config\Database::connect();
        
        $pedido = $db->query("SELECT p.*, b.nome as bairro_nome FROM pedidos p LEFT JOIN bairros b ON p.bairro_id = b.id WHERE p.id = ?", [$pedidoId])->getRow();
        
        if ($pedido) {
            $itens = $db->query("SELECT pi.*, prod.nome as produto_nome FROM pedidos_itens pi LEFT JOIN produtos prod ON pi.produto_id = prod.id WHERE pi.pedido_id = ?", [$pedidoId])->getResult();
            
            foreach ($itens as &$item) {
                $extras = $db->query("SELECT * FROM pedidos_itens_extras WHERE pedido_item_id = ?", [$item->id])->getResult();
                $item->extras = $extras;
            }
            
            return $this->response->setJSON([
                'success' => true,
                'pedido' => $pedido,
                'itens' => $itens
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Pedido não encontrado'
        ]);
    }
}
