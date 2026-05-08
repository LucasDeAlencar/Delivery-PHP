<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PedidoModel;
use App\Models\ProdutoModel;

class VendaEspecifica extends BaseController
{
    private $pedidoModel;
    private $produtoModel;
    private $produtoExtraModel;

    public function __construct()
    {
        $this->pedidoModel = new PedidoModel();
        $this->produtoModel = new ProdutoModel();
        $this->produtoExtraModel = new \App\Models\ProdutoExtraModel();
    }

    public function index()
    {
        $usuarioLogado = service('autenticacao')->pegaUsuarioLogado();
        $isAdmin = $usuarioLogado->is_admin == 1;

        $data = [
            'titulo' => 'Venda Específica',
            'isAdmin' => $isAdmin,
        ];

        return view('Admin/VendaEspecifica/index', $data);
    }

    public function abrirComanda()
    {
        return $this->_processarPedido('em_aberto');
    }

    public function listarComandasAbertas()
    {
        $db = \Config\Database::connect();
        $comandas = $db->query("
            SELECT p.id, p.codigo, p.nome_cliente, p.telefone_cliente, p.valor_total, p.criado_em,
                   COUNT(pi.id) as total_itens
            FROM pedidos p
            LEFT JOIN pedidos_itens pi ON pi.pedido_id = p.id
            WHERE p.status = 'em_aberto' AND p.deletado_em IS NULL
            GROUP BY p.id
            ORDER BY p.criado_em DESC
        ")->getResultArray();

        return $this->response->setJSON(['success' => true, 'data' => $comandas]);
    }

    public function buscarItensComanda($pedidoId)
    {
        $db = \Config\Database::connect();
        $itens = $db->query("
            SELECT pi.id, pi.produto_id, pi.produto_nome, pi.quantidade, pi.preco_unitario, pi.preco_total, pi.observacoes,
                   p.categoria_id
            FROM pedidos_itens pi
            LEFT JOIN produtos p ON p.id = pi.produto_id
            WHERE pi.pedido_id = ?
            ORDER BY pi.id
        ", [$pedidoId])->getResultArray();

        // Carregar extras de cada item
        foreach ($itens as &$item) {
            $item['extras'] = $db->query("
                SELECT pie.id, pie.extra_id, pie.extra_nome as nome, pie.extra_preco as preco, pie.quantidade
                FROM pedidos_itens_extras pie
                WHERE pie.pedido_item_id = ?
            ", [$item['id']])->getResultArray();
        }

        // Carregar sachês do pedido
        $saches = $db->query("
            SELECT ps.id, ps.sache_id, ps.sache_nome as nome, ps.preco_unitario as preco,
                   ps.quantidade, ps.quantidade_gratuita, ps.preco_total
            FROM pedidos_saches ps
            WHERE ps.pedido_id = ?
        ", [$pedidoId])->getResultArray();

        return $this->response->setJSON(['success' => true, 'data' => $itens, 'saches' => $saches]);
    }

    public function atualizarExtrasItem()
    {
        $json = $this->request->getJSON(true);
        $itemId   = $json['item_id'] ?? null;
        $pedidoId = $json['pedido_id'] ?? null;
        $extras   = $json['extras'] ?? [];
        if (!$itemId || !$pedidoId) return $this->response->setJSON(['success' => false]);

        $db = \Config\Database::connect();
        $item = $db->query("SELECT * FROM pedidos_itens WHERE id = ? AND pedido_id = ?", [$itemId, $pedidoId])->getRow();
        if (!$item) return $this->response->setJSON(['success' => false, 'message' => 'Item não encontrado']);

        // Recalcular total do item
        $totalExtras = 0;
        foreach ($extras as $e) { $totalExtras += floatval($e['preco']) * intval($e['quantidade']); }
        $novoTotal = $item->preco_unitario * $item->quantidade + $totalExtras * $item->quantidade;
        $diffTotal = $novoTotal - $item->preco_total;

        $db->table('pedidos_itens_extras')->where('pedido_item_id', $itemId)->delete();
        foreach ($extras as $e) {
            $db->table('pedidos_itens_extras')->insert([
                'pedido_item_id' => $itemId,
                'extra_id'       => $e['id'],
                'extra_nome'     => $e['nome'],
                'extra_preco'    => floatval($e['preco']),
                'quantidade'     => intval($e['quantidade']),
            ]);
        }
        $db->table('pedidos_itens')->where('id', $itemId)->update(['preco_total' => $novoTotal]);
        $db->query("UPDATE pedidos SET valor_produtos = valor_produtos + ?, valor_total = valor_total + ?, atualizado_em = NOW() WHERE id = ?",
            [$diffTotal, $diffTotal, $pedidoId]);

        return $this->response->setJSON(['success' => true, 'novo_total' => $novoTotal]);
    }

    public function removerItemComanda()
    {
        $json = $this->request->getJSON(true);
        $itemId   = $json['item_id'] ?? null;
        $pedidoId = $json['pedido_id'] ?? null;
        if (!$itemId || !$pedidoId) return $this->response->setJSON(['success' => false]);

        $db = \Config\Database::connect();
        $item = $db->query("SELECT * FROM pedidos_itens WHERE id = ? AND pedido_id = ?", [$itemId, $pedidoId])->getRow();
        if (!$item) return $this->response->setJSON(['success' => false, 'message' => 'Item não encontrado']);

        $db->table('pedidos_itens_extras')->where('pedido_item_id', $itemId)->delete();
        $db->table('pedidos_itens')->where('id', $itemId)->delete();
        $db->query("UPDATE pedidos SET valor_produtos = valor_produtos - ?, valor_total = valor_total - ?, atualizado_em = NOW() WHERE id = ?",
            [$item->preco_total, $item->preco_total, $pedidoId]);

        return $this->response->setJSON(['success' => true]);
    }

    public function alterarQtdItemComanda()
    {
        $json = $this->request->getJSON(true);
        $itemId   = $json['item_id'] ?? null;
        $pedidoId = $json['pedido_id'] ?? null;
        $novaQtd  = max(1, intval($json['quantidade'] ?? 1));
        if (!$itemId || !$pedidoId) return $this->response->setJSON(['success' => false]);

        $db = \Config\Database::connect();
        $item = $db->query("SELECT * FROM pedidos_itens WHERE id = ? AND pedido_id = ?", [$itemId, $pedidoId])->getRow();
        if (!$item) return $this->response->setJSON(['success' => false, 'message' => 'Item não encontrado']);

        $novoTotal = $item->preco_unitario * $novaQtd;
        $diff = $novoTotal - $item->preco_total;
        $db->table('pedidos_itens')->where('id', $itemId)->update(['quantidade' => $novaQtd, 'preco_total' => $novoTotal]);
        $db->query("UPDATE pedidos SET valor_produtos = valor_produtos + ?, valor_total = valor_total + ?, atualizado_em = NOW() WHERE id = ?",
            [$diff, $diff, $pedidoId]);

        return $this->response->setJSON(['success' => true, 'novo_total' => $novoTotal]);
    }

    public function adicionarItemComanda()
    {
        $json = $this->request->getJSON(true);
        $pedidoId = $json['pedido_id'] ?? null;
        $itens    = $json['itens'] ?? [];
        $saches   = $json['saches'] ?? [];

        if (!$pedidoId || (empty($itens) && empty($json['finalizar']) && empty($saches))) {
            return $this->response->setJSON(['success' => false, 'message' => 'Dados inválidos']);
        }

        $db = \Config\Database::connect();
        $pedido = $db->query("SELECT * FROM pedidos WHERE id = ? AND status = 'em_aberto' AND deletado_em IS NULL", [$pedidoId])->getRow();

        if (!$pedido) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comanda não encontrada ou não está em aberto']);
        }

        try {
            $addProdutos = 0;
            foreach ($itens as $item) {
                $precoExtras = 0;
                foreach ($item['extras'] ?? [] as $extra) {
                    $precoExtras += floatval($extra['preco']) * intval($extra['quantidade']);
                }
                $precoTotal = ($item['preco'] + $precoExtras) * $item['quantidade'];

                $db->table('pedidos_itens')->insert([
                    'pedido_id'      => $pedidoId,
                    'produto_id'     => $item['id'] ?? null,
                    'produto_nome'   => $item['nome'],
                    'quantidade'     => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                    'preco_total'    => $precoTotal,
                    'observacoes'    => $item['observacoes'] ?? '',
                    'criado_em'      => date('Y-m-d H:i:s'),
                ]);
                $itemId = $db->insertID();
                $addProdutos += $precoTotal;

                foreach ($item['extras'] ?? [] as $extra) {
                    $db->table('pedidos_itens_extras')->insert([
                        'pedido_item_id' => $itemId,
                        'extra_id'       => $extra['id'],
                        'extra_nome'     => $extra['nome'],
                        'extra_preco'    => $extra['preco'],
                        'quantidade'     => $extra['quantidade'],
                    ]);
                }
            }

            // Substituir sachês: deletar existentes e reinserir o estado atual
            $valorSaches = $this->_salvarSaches($db, $pedidoId, $saches, $pedido->valor_produtos + $addProdutos);

            // Recalcular valor_total incluindo sachês pagos
            $db->query("UPDATE pedidos SET valor_produtos = valor_produtos + ?, valor_total = valor_total + ?, atualizado_em = NOW() WHERE id = ?",
                [$addProdutos, $addProdutos + $valorSaches, $pedidoId]);

            // Se solicitado, mover para pendente e salvar pagamento
            if (!empty($json['finalizar'])) {
                $updateFinalizar = ['status' => 'pendente', 'atualizado_em' => date('Y-m-d H:i:s')];
                if (!empty($json['forma_pagamento'])) {
                    $updateFinalizar['forma_pagamento'] = $json['forma_pagamento'];
                }
                if (isset($json['troco_para']) && $json['troco_para'] !== null) {
                    $updateFinalizar['troco_para'] = floatval($json['troco_para']);
                }
                $db->table('pedidos')->where('id', $pedidoId)->update($updateFinalizar);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Itens adicionados à comanda!', 'pedido_id' => $pedidoId]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function criar()
    {
        return $this->_processarPedido('pendente');
    }

    private function _processarPedido(string $status)
    {
        $json = $this->request->getJSON(true);

        if (!$json) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dados não enviados'
            ]);
        }

        $nomeCliente = $json['nome_cliente'] ?? '';
        $telefone = $json['telefone'] ?? '';
        $endereco = $json['endereco'] ?? '';
        $bairro = $json['bairro'] ?? '';
        $formaPagamento = $json['forma_pagamento'] ?? 'dinheiro';
        $trocoPara = isset($json['troco_para']) ? floatval($json['troco_para']) : null;
        $itens = $json['itens'] ?? [];
        $observacoes = $json['observacoes'] ?? '';
        $tipoEntrega = $json['tipo_entrega'] ?? 'entrega';
        $mesaId = !empty($json['mesa_id']) ? intval($json['mesa_id']) : null;

        if (empty($nomeCliente)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nome do cliente é obrigatório']);
        }

        if ($status !== 'em_aberto' && empty($itens)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Adicione pelo menos um produto']);
        }

        $db = \Config\Database::connect();

        // Gerar código do pedido
        $codigo = 'PED' . date('YmdHis');

        // Calcular valores
        $valorProdutos = 0;
        foreach ($itens as $item) {
            $valorProdutos += ($item['preco'] * $item['quantidade']);
        }

        // Buscar taxa de entrega e ID do bairro
        $taxaEntrega = 0;
        $bairroId = null;
        if ($tipoEntrega === 'entrega' && !empty($bairro)) {
            $bairroData = $db->query("SELECT id, valor_entrega, ativo FROM bairros WHERE TRIM(LOWER(nome)) = ? AND deletado_em IS NULL", [strtolower(trim($bairro))])->getRow();
            if ($bairroData && $bairroData->ativo == 1) {
                $taxaEntrega = floatval($bairroData->valor_entrega ?? 0);
                $bairroId = $bairroData->id;
            }
        }

        $valorTotal = $valorProdutos + $taxaEntrega;

        // Inserir pedido
        $pedidoData = [
            'codigo' => $codigo,
            'nome_cliente' => $nomeCliente,
            'telefone_cliente' => $telefone,
            'endereco_entrega' => $endereco,
            'bairro_id' => $bairroId,
            'forma_pagamento' => $formaPagamento,
            'troco_para' => $trocoPara,
            'valor_produtos' => $valorProdutos,
            'valor_entrega' => $taxaEntrega,
            'valor_total' => $valorTotal,
            'status' => $status,
            'criado_em' => date('Y-m-d H:i:s'),
            'atualizado_em' => date('Y-m-d H:i:s'),
            'observacoes' => $observacoes,
            'tipo_entrega' => $tipoEntrega,
            'mesa_id' => $mesaId,
        ];

        try {
            $db->table('pedidos')->insert($pedidoData);
            $pedidoId = $db->insertID();

            // Inserir itens
            foreach ($itens as $item) {
                $itemData = [
                    'pedido_id' => $pedidoId,
                    'produto_id' => $item['id'] ?? null,
                    'produto_nome' => $item['nome'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                    'preco_total' => $item['preco'] * $item['quantidade'],
                    'observacoes' => $item['observacoes'] ?? '',
                    'criado_em' => date('Y-m-d H:i:s')
                ];
                $db->table('pedidos_itens')->insert($itemData);
                $itemId = $db->insertID();

                // Inserir extras do item
                if (!empty($item['extras'])) {
                    foreach ($item['extras'] as $extra) {
                        $extraData = [
                            'pedido_item_id' => $itemId,
                            'extra_id' => $extra['id'],
                            'extra_nome' => $extra['nome'],
                            'extra_preco' => $extra['preco'],
                            'quantidade' => $extra['quantidade']
                        ];
                        $db->table('pedidos_itens_extras')->insert($extraData);
                    }
                }
            }

            // Salvar sachês (pedido novo — não há registros anteriores)
            $valorSaches = $this->_salvarSaches($db, $pedidoId, $json['saches'] ?? [], $valorProdutos);

            // Atualizar valor_total com sachês pagos
            if ($valorSaches > 0) {
                $db->query("UPDATE pedidos SET valor_total = valor_total + ?, atualizado_em = NOW() WHERE id = ?",
                    [$valorSaches, $pedidoId]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $status === 'em_aberto' ? 'Comanda aberta com sucesso!' : 'Venda criada com sucesso!',
                'codigo' => $codigo,
                'pedido_id' => $pedidoId
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao criar venda: ' . $e->getMessage()
            ]);
        }
    }

    public function taxaBairro()
    {
        $bairro = $this->request->getPost('bairro') ?? '';

        if (empty($bairro)) {
            return $this->response->setJSON([
                'success' => true,
                'taxa' => 0,
                'coberto' => true
            ]);
        }

        $db = \Config\Database::connect();
        $bairroData = $db->query("SELECT id, valor_entrega, ativo FROM bairros WHERE TRIM(LOWER(nome)) = ? AND deletado_em IS NULL", [strtolower(trim($bairro))])->getRow();

        $taxa = 0;
        $coberto = false;
        if ($bairroData && $bairroData->ativo == 1) {
            $taxa = floatval($bairroData->valor_entrega ?? 0);
            $coberto = true;
        }

        return $this->response->setJSON([
            'success' => true,
            'taxa' => $taxa,
            'coberto' => $coberto
        ]);
    }

    public function listarProdutos()
    {
        $db = \Config\Database::connect();
        $produtos = $db->query("SELECT id, nome, preco, categoria_id, obrigatorio_extras, max_extras FROM produtos ORDER BY nome")->getResult();

        foreach ($produtos as $p) {
            $p->tamanhos = $db->query(
                "SELECT id, nome, preco FROM produtos_tamanhos WHERE produto_id = ? AND ativo = 1 ORDER BY id",
                [$p->id]
            )->getResult();
        }

        return $this->response->setJSON(['success' => true, 'data' => $produtos]);
    }

    public function listarClientes()
    {
        $db = \Config\Database::connect();
        
        $clientes = $db->query("SELECT id, nome, telefone, email, endereco, bairro, cidade, numero, complemento FROM clientes ORDER BY nome")->getResult();

        return $this->response->setJSON([
            'success' => true,
            'data' => $clientes
        ]);
    }

    public function listarBairros()
    {
        try {
            $db = \Config\Database::connect();
            
            $bairros = $db->query("SELECT id, nome, valor_entrega as taxa_entrega, ativo FROM bairros WHERE deletado_em IS NULL ORDER BY ativo DESC, nome ASC")->getResult();

            return $this->response->setJSON([
                'success' => true,
                'data' => $bairros
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao buscar bairros: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function buscarExtrasProduto($produtoId = null)
    {
        if (!$produtoId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID do produto não fornecido'
            ]);
        }

        $produto = $this->produtoModel->find($produtoId);
        if (!$produto) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Produto não encontrado'
            ]);
        }

        $produtosExtras = $this->produtoExtraModel
            ->select('extras.id, extras.nome, extras.descricao, extras.preco, extras.multitude')
            ->join('extras', 'extras.id = produtos_extras.extra_id')
            ->where('produtos_extras.produto_id', $produtoId)
            ->where('extras.ativo', 1)
            ->findAll();

        $extras = [];
        foreach ($produtosExtras as $extra) {
            $extras[] = [
                'id' => $extra->id,
                'nome' => $extra->nome,
                'descricao' => $extra->descricao ?? '',
                'preco' => floatval($extra->preco ?? 0),
                'multitude' => intval($extra->multitude ?? 0)
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'obrigatorio_extras' => intval($produto->obrigatorio_extras ?? 0),
            'max_extras' => intval($produto->max_extras ?? 0),
            'extras' => $extras
        ]);
    }

    public function buscarClientes()
    {
        $termo = $this->request->getGet('q') ?? '';

        if (strlen($termo) < 2) {
            return $this->response->setJSON(['success' => true, 'data' => []]);
        }

        $db = \Config\Database::connect();
        $clientes = $db->query(
            "SELECT id, nome, telefone, Endereco as endereco, Bairro as bairro, Cidade as cidade
             FROM clientes
             WHERE nome LIKE ? OR telefone LIKE ?
             ORDER BY nome LIMIT 20",
            ["%$termo%", "%$termo%"]
        )->getResult();

        return $this->response->setJSON(['success' => true, 'data' => $clientes]);
    }

    public function criarCliente()
    {
        $json = $this->request->getJSON(true);

        if (!$json || empty($json['nome'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nome é obrigatório']);
        }

        $db = \Config\Database::connect();

        try {
            $db->table('clientes')->insert([
                'nome'       => $json['nome'],
                'email'      => $json['email'] ?? ('ve_' . time() . '@interno.local'),
                'telefone'   => $json['telefone'] ?? '',
                'Endereco'   => $json['endereco'] ?? '',
                'Bairro'     => $json['bairro'] ?? '',
                'Cidade'     => $json['cidade'] ?? '',
                'complemento'=> $json['complemento'] ?? '',
                'Numero'     => $json['numero'] ?? 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $clienteId = $db->insertID();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cliente criado com sucesso!',
                'cliente' => [
                    'id'       => $clienteId,
                    'nome'     => $json['nome'],
                    'telefone' => $json['telefone'] ?? '',
                    'endereco' => $json['endereco'] ?? '',
                    'bairro'   => $json['bairro'] ?? '',
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Erro ao criar cliente: ' . $e->getMessage()]);
        }
    }

    public function listarMesas()
    {
        $db = \Config\Database::connect();
        $mesas = $db->query("SELECT id, numero, slug, capacidade, CAST(ocupado AS UNSIGNED) as ocupado FROM mesas WHERE ativo = 1 ORDER BY numero")->getResultArray();
        return $this->response->setJSON(['success' => true, 'data' => $mesas]);
    }

    /**
     * Substitui todos os sachês de um pedido (DELETE + INSERT).
     * Retorna o valor total cobrado pelos sachês pagos.
     */
    private function _salvarSaches($db, int $pedidoId, array $saches, float $valorProdutos): float
    {
        $db->query("DELETE FROM pedidos_saches WHERE pedido_id = ?", [$pedidoId]);
        $valorSaches = 0.0;

        foreach ($saches as $sache) {
            $sacheId = intval($sache['id'] ?? 0);
            if (!$sacheId) continue;

            $sacheData = $db->table('saches')->where('id', $sacheId)->get()->getRowArray();
            if (!$sacheData) continue;

            $qtd   = max(1, intval($sache['quantidade'] ?? 1));
            $preco = floatval($sacheData['preco']);

            $limite = 0;
            if ($sacheData['limite_tipo'] === 'fixo') {
                $limite = max(0, intval($sacheData['limite_fixo']));
            } elseif ($sacheData['limite_tipo'] === 'personalizado') {
                $min      = intval($sacheData['limite_minimo']);
                $porValor = floatval($sacheData['limite_por_valor'] ?: 1);
                $limite   = $min + (int) floor($valorProdutos / $porValor);
            }

            $gratuitos    = min($qtd, $limite);
            $pagos        = max(0, $qtd - $limite);
            $total        = $pagos * $preco;
            $valorSaches += $total;

            $db->table('pedidos_saches')->insert([
                'pedido_id'           => $pedidoId,
                'sache_id'            => $sacheId,
                'sache_nome'          => $sacheData['nome'],
                'quantidade'          => $qtd,
                'quantidade_gratuita' => $gratuitos,
                'quantidade_paga'     => $pagos,
                'preco_unitario'      => $preco,
                'preco_total'         => $total,
                'criado_em'           => date('Y-m-d H:i:s'),
            ]);
        }

        return $valorSaches;
    }
}
