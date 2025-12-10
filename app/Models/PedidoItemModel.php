<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoItemModel extends Model {

    protected $table = 'pedidos_itens';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'pedido_id',
        'produto_id',
        'produto_nome',
        'quantidade',
        'preco_unitario',
        'preco_total',
        'observacoes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';

    // Validation
    protected $validationRules = [
        'pedido_id' => 'required|integer',
        'produto_id' => 'permit_empty|integer',
        'produto_nome' => 'required',
        'quantidade' => 'required|integer|greater_than[0]',
        'preco_unitario' => 'required|decimal',
        'preco_total' => 'required|decimal',
    ];

    protected $validationMessages = [
        'quantidade' => [
            'required' => 'A quantidade é obrigatória.',
            'greater_than' => 'A quantidade deve ser maior que zero.',
        ],
    ];

    /**
     * Busca itens de um pedido específico com extras
     */
    public function buscaItensDoPedido($pedidoId) {
        $itens = $this->select('pedidos_itens.*, produtos.imagem as produto_imagem')
                    ->join('produtos', 'produtos.id = pedidos_itens.produto_id', 'left')
                    ->where('pedidos_itens.pedido_id', $pedidoId)
                    ->findAll();
        
        // Carregar extras de cada item
        $extraModel = new PedidoItemExtraModel();
        foreach ($itens as &$item) {
            $item->extras = $extraModel->getExtrasByItemId($item->id);
        }
        
        return $itens;
    }

    /**
     * Busca itens com informações completas do produto
     */
    public function buscaItensComProduto($pedidoId) {
        return $this->select('pedidos_itens.*, produtos.nome as produto_nome_atual, produtos.imagem, produtos.ativo as produto_ativo')
                    ->join('produtos', 'produtos.id = pedidos_itens.produto_id', 'left')
                    ->where('pedidos_itens.pedido_id', $pedidoId)
                    ->findAll();
    }

    /**
     * Calcula o total de itens de um pedido
     */
    public function calcularTotalItens($pedidoId) {
        $resultado = $this->selectSum('preco_total')
                          ->where('pedido_id', $pedidoId)
                          ->first();
        
        return $resultado->preco_total ?? 0;
    }

    /**
     * Remove todos os itens de um pedido
     */
    public function removerItensDoPedido($pedidoId) {
        return $this->where('pedido_id', $pedidoId)->delete();
    }
}
