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
        
        // Buscar categorias ativas ordenadas por ordem
        $categorias = $categoriaModel->where('ativo', true)
                                   ->orderBy('ordem', 'ASC')
                                   ->orderBy('nome', 'ASC')
                                   ->findAll();
        
        // Buscar produtos (ativos e inativos) com suas categorias, ordenados por ordem da categoria e depois alfabeticamente
        $produtos = $produtoModel->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug, categorias.ordem as categoria_ordem')
                                ->join('categorias', 'categorias.id = produtos.categoria_id')
                                ->where('categorias.ativo', true)
                                ->orderBy('categorias.ordem', 'ASC')
                                ->orderBy('categorias.nome', 'ASC')
                                ->orderBy('produtos.ativo', 'DESC')
                                ->orderBy('produtos.nome', 'ASC')
                                ->findAll();
        
        // Carregar tamanhos para produtos com com_tamanho=1
        $tamanhoProdutoModel = new \App\Models\TamanhoProdutoModel();
        foreach ($produtos as $produto) {
            $produto->tamanhos = ($produto->com_tamanho) ? $tamanhoProdutoModel->buscaPorProduto($produto->id) : [];
        }
        
        // Buscar expedientes
        $expedientes = $expedienteModel->orderBy('dia', 'ASC')->findAll();
        
        // Verificar se está aberto agora
        $estaAberto = $this->verificarSeEstaAberto($expedientes);
        
        // Pegar expediente de hoje
        $expedienteHoje = $this->getExpedienteHoje($expedientes);
        
        // Buscar config de mesas
        $configMesas = $db->table('configuracao_mesas')->where('id', 1)->get()->getRow();
        $mesasAtivas = [];
        if ($configMesas && $configMesas->sistema_ativo == 1) {
            $mesasAtivas = $db->table('mesas')
                ->where('ativo', 1)
                ->orderBy('numero', 'ASC')
                ->get()->getResult();
        }

        $data = [
            'titulo' => 'Seja muito bem vindo(a)',
            'categorias' => $categorias,
            'produtos' => $produtos,
            'expedientes' => $expedientes,
            'estaAberto' => $estaAberto,
            'expedienteHoje' => $expedienteHoje,
            'dadosCorporativos' => $dadosCorporativos,
            'sistemaMessasAtivo' => $configMesas ? (bool)$configMesas->sistema_ativo : false,
            'mesasAtivas' => $mesasAtivas,
            'modoCadastro' => (int)($dadosCorporativos->modo_cadastro ?? 1),
        ];
        
        // Modo 3: carregar bairros da área de cobertura para o popup
        if ((int)($dadosCorporativos->modo_cadastro ?? 1) === 3) {
            $data['bairrosCobertura'] = $db->table('bairros')->where('ativo', 1)->orderBy('nome', 'ASC')->get()->getResult();
        }
        
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
