<?php

namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\CategoriaModel;

class Home extends BaseController {

    public function index(): string {
        $produtoModel = new ProdutoModel();
        $categoriaModel = new CategoriaModel();
        
        // Buscar categorias ativas
        $categorias = $categoriaModel->where('ativo', true)
                                   ->orderBy('nome', 'ASC')
                                   ->findAll();
        
        // Buscar produtos ativos com suas categorias
        $produtos = $produtoModel->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug')
                                ->join('categorias', 'categorias.id = produtos.categoria_id')
                                ->where('produtos.ativo', true)
                                ->where('categorias.ativo', true)
                                ->orderBy('categorias.nome', 'ASC')
                                ->orderBy('produtos.nome', 'ASC')
                                ->findAll();
        
        $data = [
            'titulo' => 'Seja muito bem vindo(a)',
            'categorias' => $categorias,
            'produtos' => $produtos,
        ];
        
        return view('Home/index', $data);
    }
}
