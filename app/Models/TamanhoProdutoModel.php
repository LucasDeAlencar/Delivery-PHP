<?php

namespace App\Models;

use CodeIgniter\Model;

class TamanhoProdutoModel extends Model
{
    protected $table = 'produtos_tamanhos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'produto_id',
        'nome',
        'preco',
        'ativo',
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';

    /**
     * Busca tamanhos ativos de um produto
     */
    public function buscaPorProduto($produtoId)
    {
        return $this->where('produto_id', $produtoId)
                    ->where('ativo', 1)
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Salva (substitui) todos os tamanhos de um produto
     * $tamanhos = [['nome' => 'Pequeno', 'preco' => 10.00], ...]
     */
    public function salvarTamanhosDoProduto($produtoId, array $tamanhos)
    {
        // Remove tamanhos anteriores
        $this->where('produto_id', $produtoId)->delete();

        foreach ($tamanhos as $t) {
            $nome  = trim($t['nome'] ?? '');
            $preco = floatval(str_replace(',', '.', $t['preco'] ?? 0));
            if ($nome === '' || $preco <= 0) {
                continue;
            }
            $this->insert([
                'produto_id' => $produtoId,
                'nome'       => $nome,
                'preco'      => $preco,
                'ativo'      => 1,
            ]);
        }
    }
}
