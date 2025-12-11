<?php

namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\CategoriaModel;
use App\Models\ExpedienteModel;

class Home extends BaseController {

    public function index(): string {
        $produtoModel = new ProdutoModel();
        $categoriaModel = new CategoriaModel();
        $expedienteModel = new ExpedienteModel();
        
        // Buscar dados corporativos
        $db = \Config\Database::connect();
        $dadosCorporativos = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        
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
        
        // Buscar expedientes
        $expedientes = $expedienteModel->orderBy('dia', 'ASC')->findAll();
        
        // Verificar se está aberto agora
        $estaAberto = $this->verificarSeEstaAberto($expedientes);
        
        // Pegar expediente de hoje
        $expedienteHoje = $this->getExpedienteHoje($expedientes);
        
        $data = [
            'titulo' => 'Seja muito bem vindo(a)',
            'categorias' => $categorias,
            'produtos' => $produtos,
            'expedientes' => $expedientes,
            'estaAberto' => $estaAberto,
            'expedienteHoje' => $expedienteHoje,
            'dadosCorporativos' => $dadosCorporativos
        ];
        
        return view('Home/index', $data);
    }
    
    /**
     * Verifica se o estabelecimento está aberto no momento
     */
    private function verificarSeEstaAberto($expedientes): bool {
        if (empty($expedientes)) {
            return false;
        }
        
        // Pegar dia da semana atual (0 = Domingo, 6 = Sábado)
        $diaAtual = date('w');
        
        // Buscar expediente do dia atual
        $expedienteHoje = null;
        foreach ($expedientes as $exp) {
            if ($exp->dia == $diaAtual) {
                $expedienteHoje = $exp;
                break;
            }
        }
        
        if (!$expedienteHoje || $expedienteHoje->situacao == 0) {
            return false; // Fechado hoje
        }
        
        // Verificar se está dentro do horário
        $horaAtual = date('H:i:s');
        $abertura = $expedienteHoje->abertura;
        $fechamento = $expedienteHoje->fechamento;
        
        return ($horaAtual >= $abertura && $horaAtual <= $fechamento);
    }
    
    /**
     * Retorna o expediente de hoje
     */
    private function getExpedienteHoje($expedientes) {
        if (empty($expedientes)) {
            return null;
        }
        
        $diaAtual = date('w');
        
        foreach ($expedientes as $exp) {
            if ($exp->dia == $diaAtual) {
                return $exp;
            }
        }
        
        return null;
    }
}
