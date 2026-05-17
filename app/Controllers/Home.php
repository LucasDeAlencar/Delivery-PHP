<?php

namespace App\Controllers;

class Home extends BaseController {

    public function index() {
        $cache = \Config\Services::cache();
        $db = \Config\Database::connect();

        // Busca modo_cadastro antes de qualquer proteção
        $dadosCorporativos = $cache->get('dados_corporativos');
        if ($dadosCorporativos === null) {
            $dadosCorporativos = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
            $cache->save('dados_corporativos', $dadosCorporativos, 300);
        }
        $modoCadastro = (int)($dadosCorporativos->modo_cadastro ?? 1);

        // Modo 1 e 2: exige cliente logado. Modo 3: home pública, login via popup.
        if ($modoCadastro !== 3 && !((int)session()->get('cliente_id') > 0)) {
            session()->destroy();
            return redirect()->to(site_url('login'));
        }

        // Categorias — cache 5 min
        $categorias = $cache->get('categorias_ativas');
        if ($categorias === null) {
            $categoriaModel = new \App\Models\CategoriaModel();
            $categorias = $categoriaModel->where('ativo', true)->orderBy('ordem','ASC')->orderBy('nome','ASC')->findAll();
            $cache->save('categorias_ativas', $categorias, 300);
        }

        // Produtos com tamanhos — cache 5 min
        $produtos = $cache->get('produtos_home');
        if ($produtos === null) {
            $produtoModel = new \App\Models\ProdutoModel();
            $produtos = $produtoModel->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug, categorias.ordem as categoria_ordem')
                ->join('categorias', 'categorias.id = produtos.categoria_id')
                ->where('categorias.ativo', true)
                ->orderBy('categorias.ordem','ASC')->orderBy('categorias.nome','ASC')
                ->orderBy('produtos.ativo','DESC')->orderBy('produtos.nome','ASC')
                ->findAll();

            // Tamanhos — query única
            $produtosComTamanho = array_filter($produtos, fn($p) => $p->com_tamanho);
            $tamanhosPorProduto = [];
            if ($produtosComTamanho) {
                $ids = implode(',', array_map(fn($p) => (int)$p->id, $produtosComTamanho));
                $tamanhos = $db->query("SELECT * FROM produtos_tamanhos WHERE produto_id IN ($ids) AND ativo = 1 ORDER BY produto_id, id")->getResult();
                foreach ($tamanhos as $t) $tamanhosPorProduto[$t->produto_id][] = $t;
            }
            foreach ($produtos as $produto) {
                $produto->tamanhos = ($produto->com_tamanho && isset($tamanhosPorProduto[$produto->id]))
                    ? $tamanhosPorProduto[$produto->id] : [];
            }
            $cache->save('produtos_home', $produtos, 300);
        }

        // Expedientes — cache 1 min (dado crítico de horário)
        $expedientes = $cache->get('expedientes');
        if ($expedientes === null) {
            $expedienteModel = new \App\Models\ExpedienteModel();
            $expedientes = $expedienteModel->orderBy('dia','ASC')->findAll();
            $cache->save('expedientes', $expedientes, 60);
        }
        
        // Verificar se está aberto agora
        $estaAberto = $this->verificarSeEstaAberto($expedientes);
        
        // Pegar expediente de hoje
        $expedienteHoje = $this->getExpedienteHoje($expedientes);
        
        // Buscar config de mesas — cache 2 min
        $configMesas = $cache->get('config_mesas');
        if ($configMesas === null) {
            $configMesas = $db->table('configuracao_mesas')->where('id', 1)->get()->getRow();
            $cache->save('config_mesas', $configMesas, 120);
        }
        $mesasAtivas = [];
        if ($configMesas && $configMesas->sistema_ativo == 1) {
            $mesasAtivas = $cache->get('mesas_ativas');
            if ($mesasAtivas === null) {
                $mesasAtivas = $db->table('mesas')->where('ativo', 1)->orderBy('numero','ASC')->get()->getResult();
                $cache->save('mesas_ativas', $mesasAtivas, 120);
            }
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
            'modoCadastro' => $modoCadastro,
        ];
        
        // Modo 3: carregar bairros da área de cobertura para o popup
        if ($modoCadastro === 3) {
            $data['bairrosCobertura'] = $db->table('bairros')->where('ativo', 1)->orderBy('nome', 'ASC')->get()->getResult();
        }
        
        return view('Home/index', $data);
    }
    
    /**
     * API: retorna se está aberto agora (para polling do frontend)
     */
    public function statusExpediente() {
        $cache = \Config\Services::cache();
        $expedientes = $cache->get('expedientes');
        if ($expedientes === null) {
            $expedienteModel = new \App\Models\ExpedienteModel();
            $expedientes = $expedienteModel->orderBy('dia','ASC')->findAll();
            $cache->save('expedientes', $expedientes, 60);
        }
        return $this->response->setJSON(['aberto' => $this->verificarSeEstaAberto($expedientes)]);
    }

    /**
     * Verifica se o estabelecimento está aberto no momento
     */
    private function verificarSeEstaAberto($expedientes): bool {
        if (empty($expedientes)) return false;

        helper('timezone');
        $agora       = sao_paulo_now('H:i:s');
        $diaAtual    = (int) sao_paulo_now('w');
        $diaAnterior = ($diaAtual + 6) % 7;

        // Normaliza horário para "HH:MM:SS" garantindo comparação correta
        $normalizar = function($h) {
            $partes = explode(':', $h);
            return sprintf('%02d:%02d:%02d', $partes[0] ?? 0, $partes[1] ?? 0, $partes[2] ?? 0);
        };

        // Indexa expedientes por dia
        $porDia = [];
        foreach ($expedientes as $exp) {
            $porDia[(int)$exp->dia] = $exp;
        }

        // 1. Verifica expediente de hoje
        if (isset($porDia[$diaAtual]) && $porDia[$diaAtual]->situacao == 1) {
            $exp      = $porDia[$diaAtual];
            $abertura = $normalizar($exp->abertura);
            if ((int)$exp->vira_dia === 1) {
                // Vira dia: aberto de abertura até meia-noite
                if ($agora >= $abertura) return true;
            } else {
                $fechamento = $normalizar($exp->fechamento);
                if ($agora >= $abertura && $agora <= $fechamento) return true;
            }
        }

        // 2. Verifica se o dia anterior virou dia e ainda está no período (após meia-noite)
        if (isset($porDia[$diaAnterior]) && $porDia[$diaAnterior]->situacao == 1 && (int)$porDia[$diaAnterior]->vira_dia === 1) {
            $fechamento = $normalizar($porDia[$diaAnterior]->fechamento);
            if ($agora <= $fechamento) return true;
        }

        return false;
    }
    
    /**
     * Retorna o expediente "ativo" agora — considera virada de dia
     */
    private function getExpedienteHoje($expedientes) {
        if (empty($expedientes)) return null;

        helper('timezone');
        $agora       = sao_paulo_now('H:i:s');
        $diaAtual    = (int) sao_paulo_now('w');
        $diaAnterior = ($diaAtual + 6) % 7;

        $normalizar = fn($h) => sprintf('%02d:%02d:%02d', ...array_pad(explode(':', $h), 3, 0));

        $porDia = [];
        foreach ($expedientes as $exp) $porDia[(int)$exp->dia] = $exp;

        // Se o dia anterior virou dia e ainda estamos dentro do período, retorna o dia anterior
        if (isset($porDia[$diaAnterior]) && $porDia[$diaAnterior]->situacao == 1 && (int)$porDia[$diaAnterior]->vira_dia === 1) {
            if ($agora <= $normalizar($porDia[$diaAnterior]->fechamento)) {
                return $porDia[$diaAnterior];
            }
        }

        return $porDia[$diaAtual] ?? null;
    }
}
