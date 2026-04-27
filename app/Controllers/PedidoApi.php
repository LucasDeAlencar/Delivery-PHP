<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PedidoApi extends ResourceController
{
    protected $format = 'json';

    public function finalizarPedido()
    {
        $json = $this->request->getJSON();
        
        if (!$json || !$json->email) {
            return $this->respond(['sucesso' => false, 'mensagem' => 'Email não fornecido']);
        }
        
        if (!$json->itens || empty($json->itens)) {
            return $this->respond(['sucesso' => false, 'mensagem' => 'Carrinho vazio']);
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            // Buscar dados do cliente na tabela clientes
            $cliente = $db->query("SELECT * FROM clientes WHERE email = ?", [$json->email])->getRow();
            
            if (!$cliente) {
                throw new \Exception('Cliente não encontrado');
            }
            
            // Buscar endereço se for entrega
            $enderecoCompleto = 'Retirada no local';
            if ($json->tipo_entrega === 'entrega') {
                if ($cliente->Endereco) {
                    $enderecoCompleto = trim("{$cliente->Endereco}, {$cliente->Numero} - {$cliente->Bairro}, {$cliente->Cidade}");
                    if ($cliente->cep) {
                        $enderecoCompleto .= " - CEP: {$cliente->cep}";
                    }
                }
            }
            
            // Salvar pedido principal
            $dadosPedido = [
                'codigo' => 'PED' . date('YmdHis'),
                'nome_cliente' => $cliente->nome,
                'telefone_cliente' => $cliente->telefone,
                'endereco_entrega' => $enderecoCompleto,
                'forma_pagamento' => 'A definir',
                'valor_produtos' => $json->subtotal ?? 0,
                'valor_entrega' => $json->taxa_entrega ?? 0,
                'valor_total' => $json->total ?? 0,
                'observacoes' => $json->observacoes ?? '',
                'status' => 'pendente',
                'criado_em' => date('Y-m-d H:i:s')
            ];
            
            $db->table('pedidos')->insert($dadosPedido);
            $pedidoId = $db->insertID();
            
            // Ocupar mesa se selecionada
            if (!empty($json->local_retirada) && strpos($json->local_retirada, 'mesa_') === 0) {
                $mesaId = str_replace('mesa_', '', $json->local_retirada);
                $db->table('mesas')->where('id', $mesaId)->update([
                    'ocupado' => 1,
                    'pedido_id' => $pedidoId,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            // 2. Salvar itens do pedido
            foreach ($json->itens as $item) {
                $dadosItem = [
                    'pedido_id' => $pedidoId,
                    'produto_id' => $item->id,
                    'produto_nome' => $item->nome,
                    'quantidade' => $item->quantidade,
                    'preco_unitario' => $item->preco,
                    'preco_total' => $item->preco * $item->quantidade,
                    'observacoes' => $item->observacoes ?? '',
                    'criado_em' => date('Y-m-d H:i:s')
                ];
                
                $db->table('pedidos_itens')->insert($dadosItem);
                $itemId = $db->insertID();
                
                // 3. Salvar extras do item (se houver)
                if (!empty($item->extras)) {
                    foreach ($item->extras as $extra) {
                        $dadosExtra = [
                            'pedido_item_id' => $itemId,
                            'extra_id' => $extra->id,
                            'extra_nome' => $extra->nome,
                            'extra_preco' => $extra->preco,
                            'quantidade' => $extra->quantidade ?? 1
                        ];
                        
                        $db->table('pedidos_itens_extras')->insert($dadosExtra);
                    }
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Erro ao salvar pedido');
            }
            
            return $this->respond([
                'sucesso' => true,
                'pedido_id' => $pedidoId,
                'codigo_pedido' => $dadosPedido['codigo'],
                'usuario_email' => $json->email,
                'mensagem' => 'Pedido realizado com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return $this->respond(['sucesso' => false, 'mensagem' => 'Erro: ' . $e->getMessage()]);
        }
    }
    
    public function statusPedido()
    {
        $json = $this->request->getJSON();
        
        if (!$json || !$json->codigo) {
            return $this->respond([
                'sucesso' => false,
                'mensagem' => 'Código do pedido não informado'
            ]);
        }

        $db = \Config\Database::connect();
        
        try {
            $pedido = $db->table('pedidos')
                        ->where('codigo', $json->codigo)
                        ->get()
                        ->getRow();
            
            if (!$pedido) {
                return $this->respond([
                    'sucesso' => false,
                    'mensagem' => 'Pedido não encontrado'
                ]);
            }
            
            return $this->respond([
                'sucesso' => true,
                'pedido' => $pedido
            ]);
            
        } catch (\Exception $e) {
            return $this->respond([
                'sucesso' => false,
                'mensagem' => 'Erro ao consultar pedido'
            ]);
        }
    }
    
    public function usuarioAtual()
    {
        // Como agora usamos localStorage, este método pode retornar dados básicos
        // O email será obtido do localStorage no frontend
        return $this->respond([
            'sucesso' => true,
            'email' => 'obtido_do_localstorage',
            'nome' => 'Cliente'
        ]);
    }
}
