<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoEspecificacaoModel extends Model {

    protected $table = 'produtos_especificacoes';
    protected $returnType = 'object';
    protected $allowedFields = ['produto_id', 'medida_id', 'preco', 'customizavel'];
    protected $validationRules = [
        'medida_id' => 'required|integer',
        'preco' => 'required|greater_than[0]',
    ];
    protected $validationMessages = [
        'medida_id' => [
            'required' => 'O campo Medida é obrigatório',
        ],
    ];

    /**
     * @descrição retorna as especificações do produto em questão
     * @uso Admin/Produtos/especificacoes
     * @uso int $produto_id
     * @param int $quantidade_paginacao
     * @return array objetos
     */
    public function buscaEspecificacoesDoProduto(int $produto_id = null) {
        return $this->select('medidas.nome AS medida, produtos_especificacoes.*')
                        ->join('medidas', 'medidas.id = produtos_especificacoes.medida_id')
                        ->join('produtos', 'produtos.id = produtos_especificacoes.produto_id')
                        ->where('produtos_especificacoes.produto_id', $produto_id)
                        ->findAll();
    }
}
