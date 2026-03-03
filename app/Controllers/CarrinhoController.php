<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\BaseController; // Certifique-se de que está usando o BaseController correto

class CarrinhoController extends BaseController {

    // Método auxiliar para buscar os itens do carrinho na tabela temporária
    private function getItensCarrinho(string $sessionID) {
        $db = \Config\Database::connect();

        // Puxa todos os itens do carrinho da sessão atual
        $itens = $db->table('carrinho_temporario')
                ->where('session_id', $sessionID)
                ->orderBy('criado_em', 'asc') // Opcional: ordenar
                ->get()
                ->getResultArray(); // Retorna como array para a View

        return $itens;
    }

    // Método auxiliar para contar o total de itens (para o badge)
    private function contarItensCarrinho(string $sessionID): int {
        $db = \Config\Database::connect();

        // Retorna a soma das quantidades
        $total = $db->table('carrinho_temporario')
                        ->where('session_id', $sessionID)
                        ->selectSum('quantidade', 'total_itens')
                        ->get()
                        ->getRow()
                ->total_itens ?? 0;

        return (int) $total;
    }

    // Método index para exibir a página do carrinho
    public function index() {
        // Recupera a Session ID
        $sessionID = session_id();

        // Busca os dados no banco
        $carrinho_itens = $this->getItensCarrinho($sessionID);

        // Buscar formas de pagamento ativas
        $db = \Config\Database::connect();
        $formasPagamento = $db->table('formas_pagamento')
                ->where('ativo', 1)
                ->where('deletado_em IS NULL')
                ->orderBy('ordem', 'ASC')
                ->get()
                ->getResultArray();

        // Prepara a data para a view
        $data = [
            'carrinho_itens' => $carrinho_itens,
            'total_itens' => $this->contarItensCarrinho($sessionID),
            'formas_pagamento' => $formasPagamento
        ];

        // Retorna a view principal do carrinho
        return view('carrinho/index', $data);
    }

    // NOVO MÉTODO: Retorna a lista de itens do carrinho em HTML (para ser carregada via AJAX)
    public function lista() {
        // 1. Não precisa da verificação isAJAX, pois é uma rota GET
        // 2. Recupera a Session ID
        $sessionID = session_id();

        // 3. Busca os dados no banco
        $carrinho_itens = $this->getItensCarrinho($sessionID);

        // 4. Prepara a data para a view
        $data = [
            'carrinho_itens' => $carrinho_itens,
        ];

        // 5. Retorna a view que contém a estrutura do carrinho.
        // O nome da view deve ser 'carrinho/lista_ajax' (ou o que você definir)
        return view('carrinho/lista_ajax', $data);
    }

    // Método para adicionar produtos ao carrinho
    public function adicionar() {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Acesso negado');
        }

        // Coleta os dados enviados pelo POST
        $produtoID = $this->request->getPost('produto_id');
        $produtoNome = $this->request->getPost('produto_nome');
        $produtoImagem = $this->request->getPost('produto_imagem');
        $quantidade = (int) $this->request->getPost('quantidade');
        $precoUnitario = (float) $this->request->getPost('preco_unitario');
        $precoTotal = (float) $this->request->getPost('preco_total');
        $observacoes = $this->request->getPost('observacoes') ?? '';
        $sessionID = session_id();

        // Validação básica
        if (!$produtoID || !$produtoNome || $quantidade <= 0 || $precoUnitario <= 0) {
            return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Dados inválidos. Verifique os campos obrigatórios.'
                    ])->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        // Verifica se o produto já existe no carrinho com as mesmas observações
        $itemExistente = $db->table('carrinho_temporario')
                ->where('session_id', $sessionID)
                ->where('produto_id', $produtoID)
                ->where('observacoes', $observacoes)
                ->get()
                ->getRowArray();

        if ($itemExistente) {
            // Atualiza a quantidade do item existente
            $novaQuantidade = $itemExistente['quantidade'] + $quantidade;
            $novoTotal = $novaQuantidade * $precoUnitario;

            $db->table('carrinho_temporario')
                    ->where('id', $itemExistente['id'])
                    ->update([
                        'quantidade' => $novaQuantidade,
                        'preco_total' => $novoTotal,
                        'atualizado_em' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Insere novo item no carrinho
            $db->table('carrinho_temporario')->insert([
                'session_id' => $sessionID,
                'produto_id' => $produtoID,
                'produto_nome' => $produtoNome,
                'produto_imagem' => $produtoImagem,
                'quantidade' => $quantidade,
                'preco_unitario' => $precoUnitario,
                'preco_total' => $precoTotal,
                'observacoes' => $observacoes,
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ]);
        }

        // Retorna sucesso com o total de itens atualizado
        return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Produto adicionado ao carrinho!',
                    'total_itens' => $this->contarItensCarrinho($sessionID),
                    'csrf_token' => csrf_hash()
        ]);
    }

    // Método para remover item do carrinho
    public function remover($itemId = null) {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Acesso negado');
        }

        if (!$itemId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID do item é obrigatório'
            ])->setStatusCode(400);
        }

        $sessionID = session_id();
        $db = \Config\Database::connect();

        // Verificar se o item pertence à sessão atual
        $item = $db->table('carrinho_temporario')
                ->where('id', $itemId)
                ->where('session_id', $sessionID)
                ->get()
                ->getRowArray();

        if (!$item) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Item não encontrado'
            ])->setStatusCode(404);
        }

        // Remover o item
        $db->table('carrinho_temporario')
                ->where('id', $itemId)
                ->delete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Item removido do carrinho',
            'total_itens' => $this->contarItensCarrinho($sessionID)
        ]);
    }

    // Método para limpar carrinho
    public function limpar() {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Acesso negado');
        }

        $sessionID = session_id();
        $db = \Config\Database::connect();

        $db->table('carrinho_temporario')
                ->where('session_id', $sessionID)
                ->delete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Carrinho limpo com sucesso',
            'total_itens' => 0
        ]);
    }
}
