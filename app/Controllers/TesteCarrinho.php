<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TesteCarrinho extends Controller {
    
    public function index() {
        // Dados de teste
        $data = [
            'produtos' => [
                ['id' => 1, 'nome' => 'Pizza Margherita', 'preco' => 25.90, 'ativo' => true],
                ['id' => 2, 'nome' => 'Pizza Calabresa', 'preco' => 28.90, 'ativo' => true],
                ['id' => 3, 'nome' => 'Pizza Portuguesa', 'preco' => 32.90, 'ativo' => false],
                ['id' => 999, 'nome' => 'Produto Removido', 'preco' => 15.90, 'ativo' => true]
            ]
        ];
        
        return view('teste_carrinho', $data);
    }
    
    public function validarApi() {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acesso negado']);
        }
        
        $input = $this->request->getJSON(true);
        $produtoIds = $input['produto_ids'] ?? [];
        
        // Simula validação (na vida real usaria o ProdutoModel)
        $produtosAtivos = [1, 2]; // Apenas estes estão ativos
        
        $produtosValidos = array_intersect($produtoIds, $produtosAtivos);
        $produtosInvalidos = array_diff($produtoIds, $produtosAtivos);
        
        return $this->response->setJSON([
            'success' => true,
            'produtos_validos' => array_values($produtosValidos),
            'produtos_invalidos' => array_values($produtosInvalidos)
        ]);
    }
}
