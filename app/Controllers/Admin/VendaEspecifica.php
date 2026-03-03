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

    public function criar()
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
        $itens = $json['itens'] ?? [];
        $observacoes = $json['observacoes'] ?? '';
        $tipoEntrega = $json['tipo_entrega'] ?? 'entrega';

        if (empty($nomeCliente) || empty($telefone)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nome e telefone do cliente são obrigatórios'
            ]);
        }

        if (empty($itens)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Adicione pelo menos um produto'
            ]);
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
            'valor_produtos' => $valorProdutos,
            'valor_entrega' => $taxaEntrega,
            'valor_total' => $valorTotal,
            'status' => 'preparando',
            'criado_em' => date('Y-m-d H:i:s'),
            'atualizado_em' => date('Y-m-d H:i:s'),
            'observacoes' => $observacoes,
            'tipo_entrega' => $tipoEntrega
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

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Venda criada com sucesso!',
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
        
        // Tenta buscar produtos ativos, se não encontrar busca todos
        $produtos = $db->query("SELECT id, nome, preco, obrigatorio_extras, max_extras FROM produtos ORDER BY nome")->getResult();

        return $this->response->setJSON([
            'success' => true,
            'data' => $produtos
        ]);
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
            return $this->response->setJSON([
                'success' => true,
                'data' => []
            ]);
        }

        $db = \Config\Database::connect();
        $clientes = $db->query(
            "SELECT id, nome, telefone, endereco, bairro, cidade 
             FROM clientes 
             WHERE nome LIKE ? OR telefone LIKE ? OR email LIKE ?
             LIMIT 20", 
            ["%$termo%", "%$termo%", "%$termo%"]
        )->getResult();

        return $this->response->setJSON([
            'success' => true,
            'data' => $clientes
        ]);
    }

    public function criarCliente()
    {
        $json = $this->request->getJSON(true);

        if (!$json) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dados não enviados'
            ]);
        }

        $nome = $json['nome'] ?? '';
        $email = $json['email'] ?? '';
        $telefone = $json['telefone'] ?? '';
        $endereco = $json['endereco'] ?? '';
        $bairro = $json['bairro'] ?? '';
        $cidade = $json['cidade'] ?? '';
        $complemento = $json['complemento'] ?? '';
        $numero = $json['numero'] ?? 0;

        if (empty($nome)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nome é obrigatório'
            ]);
        }

        if (empty($email)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email é obrigatório'
            ]);
        }

        $db = \Config\Database::connect();

        // Verificar se email já existe
        $clienteExistente = $db->query("SELECT id FROM clientes WHERE email = ?", [$email])->getRow();
        if ($clienteExistente) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email já cadastrado'
            ]);
        }

        try {
            $clienteData = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'endereco' => $endereco,
                'bairro' => $bairro,
                'cidade' => $cidade,
                'complemento' => $complemento,
                'numero' => $numero,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $db->table('clientes')->insert($clienteData);
            $clienteId = $db->insertID();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cliente criado com sucesso!',
                'cliente' => [
                    'id' => $clienteId,
                    'nome' => $nome,
                    'telefone' => $telefone,
                    'endereco' => $endereco,
                    'bairro' => $bairro
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao criar cliente: ' . $e->getMessage()
            ]);
        }
    }
}
