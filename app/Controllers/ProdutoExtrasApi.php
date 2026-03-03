<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ProdutoExtrasApi extends BaseController
{
    private $produtoModel;
    private $extraModel;
    private $produtoExtraModel;

    public function __construct()
    {
        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoExtraModel = new \App\Models\ProdutoExtraModel();
    }

    /**
     * Retorna os extras de um produto específico
     * GET /api/produto-extras/{produto_id}
     */
    public function getExtrasProduto($produtoId = null)
    {
        if (!$produtoId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID do produto não fornecido'
            ]);
        }

        // Busca o produto
        $produto = $this->produtoModel->find($produtoId);
        if (!$produto) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Produto não encontrado'
            ]);
        }

        // Busca os extras associados ao produto
        $produtosExtras = $this->produtoExtraModel
            ->select('extras.id, extras.nome, extras.descricao, extras.preco, extras.multitude')
            ->join('extras', 'extras.id = produtos_extras.extra_id')
            ->where('produtos_extras.produto_id', $produtoId)
            ->where('extras.ativo', 1)
            ->findAll();

        // Formata os dados
        $extras = [];
        foreach ($produtosExtras as $extra) {
            $extras[] = [
                'id' => $extra->id,
                'nome' => $extra->nome,
                'descricao' => $extra->descricao ?? '',
                'preco' => floatval($extra->preco ?? 0),
                'preco_formatado' => 'R$ ' . number_format($extra->preco ?? 0, 2, ',', '.'),
                'multitude' => intval($extra->multitude ?? 0)
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'produto_id' => $produtoId,
            'obrigatorio_extras' => intval($produto->obrigatorio_extras ?? 0),
            'max_extras' => intval($produto->max_extras ?? 0),
            'extras' => $extras,
            'total_extras' => count($extras)
        ]);
    }
}
