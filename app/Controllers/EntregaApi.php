<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class EntregaApi extends ResourceController
{
    protected $format = 'json';

    public function configuracaoEntrega()
    {
        $json = $this->request->getJSON();
        $email = $json->email ?? '';

        if (empty($email)) {
            return $this->respond(['sucesso' => false, 'msg' => 'Email não informado']);
        }

        $db = \Config\Database::connect();

        try {
            // Buscar cliente diretamente pelo email
            $cliente = $db->query("SELECT * FROM clientes WHERE email = ?", [$email])->getRow();
            
            if (!$cliente) {
                return $this->respond(['sucesso' => false, 'msg' => 'Cliente não encontrado']);
            }

            // Log para debug
            log_message('info', 'Cliente encontrado - CEP: ' . $cliente->cep . ', Bairro: ' . $cliente->Bairro);

            // Sempre usar cálculo por bairro
            $tipoEntrega = 'bairro';

            $response = [
                'sucesso' => true,
                'tipo' => $tipoEntrega,
                'cliente' => [
                    'bairro' => $cliente->Bairro
                ]
            ];

            // Buscar bairros - com dados de fallback
            try {
                $bairros = $db->query("SELECT * FROM bairros WHERE ativo = 1")->getResult();
            } catch (\Exception $e) {
                log_message('info', 'Tabela bairros não existe, usando fallback');
                $bairros = [];
            }
            
            if (empty($bairros)) {
                // Dados de fallback se tabela estiver vazia ou não existir
                $bairros = [
                    (object)['nome' => 'Centro', 'valor_entrega' => 5.00, 'ativo' => 1],
                    (object)['nome' => 'Vila Nova', 'valor_entrega' => 8.00, 'ativo' => 1]
                ];
            }
            
            $response['bairros'] = $bairros;

            log_message('info', 'Resposta configuração: ' . json_encode($response));
            return $this->respond($response);

        } catch (\Exception $e) {
            log_message('error', 'Erro configuração entrega: ' . $e->getMessage());
            return $this->respond(['sucesso' => false, 'msg' => 'Erro interno: ' . $e->getMessage()]);
        }
    }

    public function carrinhoCliente()
    {
        $json = $this->request->getJSON();
        $email = $json->email ?? '';

        if (empty($email)) {
            return $this->respond(['sucesso' => false, 'msg' => 'Email não informado']);
        }

        $db = \Config\Database::connect();

        try {
            // Buscar cliente
            $cliente = $db->query("SELECT id FROM clientes WHERE email = ?", [$email])->getRow();
            
            if (!$cliente) {
                return $this->respond(['sucesso' => false, 'msg' => 'Cliente não encontrado']);
            }

            // Buscar itens do carrinho usando session_id baseado no email
            $sessionId = 'cliente_' . $cliente->id;
            $itensCarrinho = $db->query("
                SELECT produto_id as id, produto_nome as nome, preco_unitario as preco, 
                       quantidade, preco_total as totalCalculado, observacoes
                FROM carrinho 
                WHERE session_id = ?
            ", [$sessionId])->getResult();

            return $this->respond([
                'sucesso' => true,
                'itens' => $itensCarrinho
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erro ao buscar carrinho: ' . $e->getMessage());
            return $this->respond(['sucesso' => false, 'msg' => 'Erro interno']);
        }
    }
}
