<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ProdutosApi extends ResourceController {

    use ResponseTrait;

    protected $produtoModel;
    protected $format = 'json';

    public function __construct() {
        $this->produtoModel = new \App\Models\ProdutoModel();
    }

    /**
     * Valida se uma lista de produtos ainda existe e está ativa
     * POST /api/produtos/validar
     */
    public function validar() {
        $input = $this->request->getJSON(true);
        
        if (!isset($input['produto_ids']) || !is_array($input['produto_ids'])) {
            return $this->fail('Lista de IDs de produtos é obrigatória', 400);
        }
        
        $produtoIds = array_map('intval', $input['produto_ids']);
        $produtoIds = array_filter($produtoIds, function($id) {
            return $id > 0;
        });
        
        if (empty($produtoIds)) {
            return $this->fail('Nenhum ID de produto válido fornecido', 400);
        }
        
        // Busca produtos ativos
        $produtosAtivos = $this->produtoModel
            ->whereIn('id', $produtoIds)
            ->where('ativo', true)
            ->findAll();
        
        $produtosValidosIds = array_map(function($produto) {
            return (int) $produto->id;
        }, $produtosAtivos);
        
        $produtosInvalidosIds = array_diff($produtoIds, $produtosValidosIds);
        
        return $this->respond([
            'success' => true,
            'message' => 'Validação concluída',
            'produtos_validos' => $produtosValidosIds,
            'produtos_invalidos' => array_values($produtosInvalidosIds),
            'total_validos' => count($produtosValidosIds),
            'total_invalidos' => count($produtosInvalidosIds)
        ]);
    }
}
