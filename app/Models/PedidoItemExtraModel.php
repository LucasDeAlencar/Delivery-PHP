<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoItemExtraModel extends Model
{
    protected $table = 'pedidos_itens_extras';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'pedido_item_id',
        'extra_id',
        'extra_nome',
        'extra_preco',
        'quantidade'
    ];

    protected $validationRules = [
        'pedido_item_id' => 'required|integer',
        'extra_id' => 'permit_empty|integer',
        'extra_nome' => 'required|max_length[120]',
        'extra_preco' => 'required|decimal',
        'quantidade' => 'required|integer|greater_than[0]'
    ];

    protected $validationMessages = [
        'pedido_item_id' => [
            'required' => 'O ID do item do pedido é obrigatório'
        ],
        'extra_id' => [
            'required' => 'O ID do extra é obrigatório'
        ],
        'extra_nome' => [
            'required' => 'O nome do extra é obrigatório'
        ],
        'extra_preco' => [
            'required' => 'O preço do extra é obrigatório'
        ],
        'quantidade' => [
            'required' => 'A quantidade é obrigatória',
            'greater_than' => 'A quantidade deve ser maior que zero'
        ]
    ];

    /**
     * Busca os extras de um item do pedido
     */
    public function getExtrasByItemId($itemId)
    {
        return $this->where('pedido_item_id', $itemId)->findAll();
    }

    /**
     * Salva múltiplos extras de um item
     */
    public function salvarExtrasItem($itemId, $extras)
    {
        if (empty($extras)) {
            return true;
        }

        $dados = [];
        foreach ($extras as $extra) {
            $dados[] = [
                'pedido_item_id' => $itemId,
                'extra_id' => $extra['id'],
                'extra_nome' => $extra['nome'],
                'extra_preco' => $extra['preco'],
                'quantidade' => $extra['quantidade'] ?? 1
            ];
        }

        return $this->insertBatch($dados);
    }
}
