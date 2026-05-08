<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PedidoModel;
use App\Models\PedidoItemModel;
use App\Models\BairroModel;

class Pedidos extends BaseController
{
    private $pedidoModel;
    private $pedidoItemModel;
    private $bairroModel;

    public function __construct()
    {
        $this->pedidoModel = new PedidoModel();
        $this->pedidoItemModel = new PedidoItemModel();
        $this->bairroModel = new BairroModel();
    }

    /**
     * Cria um novo pedido (AJAX)
     */
    public function criar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Acesso negado'
            ]);
        }

        try {
            $dados = $this->request->getJSON();

            // Validar dados obrigatórios
            if (empty($dados->nome_cliente) || empty($dados->telefone_cliente) || 
                empty($dados->forma_pagamento) || empty($dados->itens) ||
                empty($dados->tipo_entrega)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Dados incompletos'
                ]);
            }

            // Validar endereço se for entrega
            if ($dados->tipo_entrega === 'entrega' && empty($dados->endereco_entrega)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Endereço de entrega é obrigatório'
                ]);
            }

            // Gerar código único do pedido
            $codigo = $this->gerarCodigoPedido();

            // Calcular valores
            $valorProdutos = $dados->valor_produtos ?? 0;
            $valorEntrega = $dados->valor_entrega ?? 0;
            $valorSaches = $dados->valor_saches ?? 0;
            $valorTotal = $valorProdutos + $valorEntrega + $valorSaches;

            // Preparar dados do pedido
            $dadosPedido = [
                'codigo' => $codigo,
                'usuario_id' => session()->get('usuario_id') ?? null,
                'nome_cliente' => $dados->nome_cliente,
                'telefone_cliente' => $dados->telefone_cliente,
                'endereco_entrega' => $dados->endereco_entrega,
                'bairro_id' => $dados->bairro_id ?? null,
                'complemento' => $dados->complemento ?? null,
                'forma_pagamento' => $dados->forma_pagamento,
                'troco_para' => $dados->valor_dinheiro ?? null,
                'valor_produtos' => $valorProdutos,
                'valor_entrega' => $valorEntrega,
                'valor_total' => $valorTotal,
                'observacoes' => $dados->observacoes ?? null,
                'status' => 'pendente',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ];

            // Inserir pedido
            $pedidoId = $this->pedidoModel->insert($dadosPedido);

            if (!$pedidoId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao criar pedido'
                ]);
            }

            // Inserir itens do pedido
            $pedidoItemExtraModel = new \App\Models\PedidoItemExtraModel();
            
            foreach ($dados->itens as $item) {
                // Usar precoUnitario se existir (já inclui extras), senão usar preco
                $precoUnitario = $item->precoUnitario ?? $item->preco ?? 0;
                
                 $itemId = $this->pedidoItemModel->insert([
                     'pedido_id' => $pedidoId,
                     'produto_id' => $item->id,
                     'produto_nome' => $item->nome,
                     'quantidade' => $item->quantidade,
                     'preco_unitario' => $precoUnitario,
                     'preco_total' => $precoUnitario * $item->quantidade,
                     'observacoes' => $item->observacoes ?? null,
                     'tamanho_nome' => isset($item->tamanho) && isset($item->tamanho->nome) ? $item->tamanho->nome : ($item->tamanho_nome ?? null),
                     'tamanho_preco' => isset($item->tamanho) && isset($item->tamanho->preco) ? $item->tamanho->preco : ($item->tamanho_preco ?? null),
                     'criado_em' => date('Y-m-d H:i:s'),
                     'atualizado_em' => date('Y-m-d H:i:s')
                 ]);
                
                // Salvar extras do item
                if ($itemId && isset($item->extras) && is_array($item->extras) && count($item->extras) > 0) {
                    foreach ($item->extras as $extra) {
                        $pedidoItemExtraModel->insert([
                            'pedido_item_id' => $itemId,
                            'extra_id' => $extra->id ?? null,
                            'extra_nome' => $extra->nome ?? '',
                            'extra_preco' => floatval($extra->preco ?? 0),
                            'quantidade' => intval($extra->quantidade ?? 1)
                        ]);
                    }
                }
            }

            // Salvar sachês do pedido
            if (!empty($dados->saches) && is_array($dados->saches)) {
                $db = \Config\Database::connect();
                foreach ($dados->saches as $sache) {
                    $qtd       = intval($sache->quantidade ?? 1);
                    $qtdPaga   = intval($sache->quantidade_paga ?? 0);
                    $qtdGratis = $qtd - $qtdPaga;
                    $precoUnit = floatval($sache->preco ?? 0);
                    $db->table('pedidos_saches')->insert([
                        'pedido_id'           => $pedidoId,
                        'sache_id'            => $sache->id ?? null,
                        'sache_nome'          => $sache->nome ?? '',
                        'quantidade'          => $qtd,
                        'quantidade_gratuita' => max(0, $qtdGratis),
                        'quantidade_paga'     => $qtdPaga,
                        'preco_unitario'      => $precoUnit,
                        'preco_total'         => $qtdPaga * $precoUnit,
                        'criado_em'           => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            // Enviar email de confirmação se email fornecido
            if (!empty($dados->email_cliente)) {
                $this->enviarEmailConfirmacao([
                    'id_pedido' => $codigo,
                    'nome_cliente' => $dados->nome_cliente,
                    'email_cliente' => $dados->email_cliente,
                    'total' => $valorTotal,
                    'forma_pagamento' => $dados->forma_pagamento
                ]);
            }

            // Retornar sucesso
            $db = \Config\Database::connect();
            $sachesRetorno = $db->table('pedidos_saches')->where('pedido_id', $pedidoId)->get()->getResultArray();
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pedido criado com sucesso!',
                'pedido' => [
                    'id' => $pedidoId,
                    'codigo' => $codigo,
                    'valor_total' => $valorTotal
                ],
                'saches' => $sachesRetorno
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erro ao criar pedido: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno ao processar pedido'
            ]);
        }
    }

    /**
     * Gera código único do pedido
     */
    private function gerarCodigoPedido(): string
    {
        $data = date('Ymd');
        
        // Buscar último pedido do dia
        $ultimoPedido = $this->pedidoModel
            ->where('DATE(criado_em)', date('Y-m-d'))
            ->orderBy('id', 'DESC')
            ->first();

        if ($ultimoPedido) {
            // Extrair número do código
            $partes = explode('-', $ultimoPedido->codigo);
            $numero = isset($partes[2]) ? intval($partes[2]) + 1 : 1;
        } else {
            $numero = 1;
        }

        return 'PED-' . $data . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Envia email de confirmação do pedido
     */
    private function enviarEmailConfirmacao($dadosPedido)
    {
        try {
            return enviarEmailPedido($dadosPedido);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao enviar email de confirmação: ' . $e->getMessage());
            return false;
        }
    }
}
