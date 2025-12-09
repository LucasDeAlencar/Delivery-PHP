<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class CarrinhoApi extends ResourceController {

    use ResponseTrait;

    protected $db;
    protected $format = 'json';

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    /**
     * Retorna todos os itens do carrinho da sessão atual
     * GET /api/carrinho
     */
    public function index() {
        $sessionID = session_id();

        $itens = $this->db->table('carrinho_temporario')
                ->where('session_id', $sessionID)
                ->orderBy('criado_em', 'ASC')
                ->get()
                ->getResultArray();

        $totalItens = array_sum(array_column($itens, 'quantidade'));
        $valorTotal = array_sum(array_column($itens, 'preco_total'));

        return $this->respond([
                    'success' => true,
                    'message' => 'Carrinho carregado com sucesso',
                    'data' => [
                        'itens' => $itens,
                        'total_itens' => $totalItens,
                        'valor_total' => $valorTotal
                    ]
        ]);
    }

    /**
     * Adiciona um produto ao carrinho
     * POST /api/carrinho
     */
    public function create() {
        $sessionID = session_id();

        $produtoId = $this->request->getPost('produto_id') ?? $this->request->getJSON()->produto_id ?? null;
        $quantidade = (int) ($this->request->getPost('quantidade') ?? $this->request->getJSON()->quantidade ?? 1);
        $observacoes = $this->request->getPost('observacoes') ?? $this->request->getJSON()->observacoes ?? '';

        if (!$produtoId) {
            return $this->fail('ID do produto é obrigatório', 400);
        }

        // Buscar informações do produto
        $produto = $this->db->table('produtos')
                ->where('id', $produtoId)
                ->where('ativo', true)
                ->get()
                ->getRowArray();

        if (!$produto) {
            return $this->fail('Produto não encontrado ou inativo', 404);
        }

        // Verificar se já existe no carrinho
        $itemExistente = $this->db->table('carrinho_temporario')
                ->where('session_id', $sessionID)
                ->where('produto_id', $produtoId)
                ->where('observacoes', $observacoes)
                ->get()
                ->getRowArray();

        if ($itemExistente) {
            // Atualizar quantidade
            $novaQuantidade = $itemExistente['quantidade'] + $quantidade;
            $novoTotal = $novaQuantidade * $produto['preco'];

            $this->db->table('carrinho_temporario')
                    ->where('id', $itemExistente['id'])
                    ->update([
                        'quantidade' => $novaQuantidade,
                        'preco_total' => $novoTotal,
                        'atualizado_em' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Inserir novo item
            $precoTotal = $quantidade * $produto['preco'];

            $this->db->table('carrinho_temporario')->insert([
                'session_id' => $sessionID,
                'produto_id' => $produtoId,
                'produto_nome' => $produto['nome'],
                'produto_imagem' => $produto['imagem'] ?? '',
                'quantidade' => $quantidade,
                'preco_unitario' => $produto['preco'],
                'preco_total' => $precoTotal,
                'observacoes' => $observacoes,
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ]);
        }

        // Retornar carrinho atualizado
        return $this->index();
    }

    /**
     * Atualiza a quantidade de um item
     * PUT /api/carrinho/{id}
     */
    public function update($id = null) {
        if (!$id) {
            return $this->fail('ID do item é obrigatório', 400);
        }

        $sessionID = session_id();

        $quantidade = (int) ($this->request->getJSON()->quantidade ?? 1);

        if ($quantidade < 1) {
            return $this->fail('Quantidade deve ser maior que zero', 400);
        }

        // Verificar se o item pertence à sessão atual
        $item = $this->db->table('carrinho_temporario')
                ->where('id', $id)
                ->where('session_id', $sessionID)
                ->get()
                ->getRowArray();

        if (!$item) {
            return $this->fail('Item não encontrado no carrinho', 404);
        }

        // Atualizar quantidade
        $novoTotal = $quantidade * $item['preco_unitario'];

        $this->db->table('carrinho_temporario')
                ->where('id', $id)
                ->update([
                    'quantidade' => $quantidade,
                    'preco_total' => $novoTotal,
                    'atualizado_em' => date('Y-m-d H:i:s')
        ]);

        // Retornar carrinho atualizado
        return $this->index();
    }

    /**
     * Remove um item do carrinho
     * DELETE /api/carrinho/{id}
     */
    public function delete($id = null) {
        if (!$id) {
            return $this->fail('ID do item é obrigatório', 400);
        }

        $sessionID = session_id();

        // Verificar se o item pertence à sessão atual
        $item = $this->db->table('carrinho_temporario')
                ->where('id', $id)
                ->where('session_id', $sessionID)
                ->get()
                ->getRowArray();

        if (!$item) {
            return $this->fail('Item não encontrado no carrinho', 404);
        }

        // Remover item
        $this->db->table('carrinho_temporario')
                ->where('id', $id)
                ->delete();

        // Retornar carrinho atualizado
        return $this->index();
    }

    /**
     * Limpa todo o carrinho
     * DELETE /api/carrinho
     */
    public function limpar() {
        $sessionID = session_id();

        $this->db->table('carrinho_temporario')
                ->where('session_id', $sessionID)
                ->delete();

        return $this->respond([
                    'success' => true,
                    'message' => 'Carrinho limpo com sucesso',
                    'data' => [
                        'itens' => [],
                        'total_itens' => 0,
                        'valor_total' => 0
                    ]
        ]);
    }

    /**
     * Atualiza as observações de um item
     * PATCH /api/carrinho/{id}/observacoes
     */
    public function atualizarObservacoes($id = null) {
        if (!$id) {
            return $this->fail('ID do item é obrigatório', 400);
        }

        $sessionID = session_id();

        $observacoes = $this->request->getJSON()->observacoes ?? '';

        // Verificar se o item pertence à sessão atual
        $item = $this->db->table('carrinho_temporario')
                ->where('id', $id)
                ->where('session_id', $sessionID)
                ->get()
                ->getRowArray();

        if (!$item) {
            return $this->fail('Item não encontrado no carrinho', 404);
        }

        // Atualizar observações
        $this->db->table('carrinho_temporario')
                ->where('id', $id)
                ->update([
                    'observacoes' => $observacoes,
                    'atualizado_em' => date('Y-m-d H:i:s')
        ]);

        return $this->respond([
                    'success' => true,
                    'message' => 'Observações atualizadas com sucesso'
        ]);
    }
}
