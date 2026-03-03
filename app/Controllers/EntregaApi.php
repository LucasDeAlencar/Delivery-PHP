<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class EntregaApi extends ResourceController
{
    protected $modelName = 'App\Models\BairroModel';
    protected $format = 'json';

    public function verificarEntrega()
    {
        try {
            $json = $this->request->getJSON();
            $email = $json->email ?? null;
            
            log_message('info', "EntregaApi::verificarEntrega - Email recebido: " . ($email ?? 'NULL'));
            
            if (!$email) {
                log_message('error', "Email não fornecido");
                return $this->respond([
                    'disponivel' => false,
                    'mensagem' => 'Email não fornecido'
                ]);
            }

            $db = \Config\Database::connect();
            
            // Buscar na tabela clientes (campos com maiúscula)
            $dadosEndereco = $db->query("SELECT Bairro as bairro, Cidade as cidade FROM clientes WHERE email = ?", [$email])->getRow();
            log_message('info', "Busca em clientes: " . ($dadosEndereco ? json_encode($dadosEndereco) : 'NULL'));
            
            if (!$dadosEndereco || !$dadosEndereco->bairro || !$dadosEndereco->cidade) {
                log_message('error', "Endereço não encontrado ou incompleto");
                return $this->respond([
                    'disponivel' => false,
                    'mensagem' => 'Endereço não cadastrado no perfil'
                ]);
            }

            $bairroModel = new \App\Models\BairroModel();
            
            // Primeira tentativa: busca exata por bairro e cidade
            $bairroEncontrado = $bairroModel
                ->where('TRIM(LOWER(nome))', strtolower(trim($dadosEndereco->bairro)))
                ->where('TRIM(LOWER(cidade))', strtolower(trim($dadosEndereco->cidade)))
                ->where('ativo', 1)
                ->first();

            log_message('info', "Busca específica para: '" . trim($dadosEndereco->bairro) . "', '" . trim($dadosEndereco->cidade) . "' - " . ($bairroEncontrado ? 'ENCONTRADO' : 'NÃO ENCONTRADO'));

            // Se não encontrou, busca por bairro universal (*) para a cidade
            if (!$bairroEncontrado) {
                $bairroEncontrado = $bairroModel
                    ->where('nome', '*')
                    ->where('TRIM(LOWER(cidade))', strtolower(trim($dadosEndereco->cidade)))
                    ->where('ativo', 1)
                    ->first();
                    
                if ($bairroEncontrado) {
                    log_message('info', "Bairro universal (*) encontrado para cidade: {$dadosEndereco->cidade}");
                } else {
                    log_message('info', "Bairro universal (*) NÃO encontrado para cidade: {$dadosEndereco->cidade}");
                }
            }

            log_message('info', "Bairro encontrado: " . ($bairroEncontrado ? json_encode($bairroEncontrado) : 'NULL'));

            if ($bairroEncontrado) {
                return $this->respond([
                    'disponivel' => true,
                    'valor_entrega' => $bairroEncontrado->valor_entrega
                ]);
            } else {
                return $this->respond([
                    'disponivel' => false,
                    'mensagem' => "Delivery não disponível para {$dadosEndereco->bairro}, {$dadosEndereco->cidade}"
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', "Erro em verificarEntrega: " . $e->getMessage());
            return $this->respond([
                'disponivel' => false,
                'mensagem' => 'Erro interno do servidor'
            ], 500);
        }
    }
}
