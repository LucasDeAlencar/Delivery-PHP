<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class TaxaEntregaClienteApi extends ResourceController
{
    use ResponseTrait;

    protected $db;
    protected $format = 'json';

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Calcular taxa de entrega baseada no email do cliente
     * POST /api/taxa-entrega-cliente
     */
    public function calcular()
    {
        $email = $this->request->getPost('email');
        
        if (!$email) {
            return $this->respond([
                'success' => false,
                'message' => 'Email é obrigatório'
            ], 400);
        }

        // Buscar cliente pelo email
        $cliente = $this->db->table('clientes')
            ->where('email', $email)
            ->get()
            ->getRowArray();

        if (!$cliente) {
            return $this->respond([
                'success' => false,
                'message' => 'Cliente não encontrado',
                'taxa_entrega' => 0,
                'pode_entregar' => false
            ]);
        }

        // Buscar bairro correspondente - primeira tentativa: busca exata
        $bairro = $this->db->table('bairros')
            ->where('nome', $cliente['Bairro'])
            ->where('cidade', $cliente['Cidade'])
            ->where('ativo', 1)
            ->where('deletado_em IS NULL')
            ->get()
            ->getRowArray();

        // Se não encontrou, busca por bairro universal (*) para a cidade
        if (!$bairro) {
            $bairro = $this->db->table('bairros')
                ->where('nome', '*')
                ->where('cidade', $cliente['Cidade'])
                ->where('ativo', 1)
                ->where('deletado_em IS NULL')
                ->get()
                ->getRowArray();
        }

        if (!$bairro) {
            return $this->respond([
                'success' => false,
                'message' => 'Não entregamos neste bairro/cidade',
                'taxa_entrega' => 0,
                'pode_entregar' => false,
                'cliente' => [
                    'bairro' => $cliente['Bairro'],
                    'cidade' => $cliente['Cidade']
                ]
            ]);
        }

        return $this->respond([
            'success' => true,
            'message' => 'Taxa calculada com sucesso',
            'taxa_entrega' => (float) $bairro['valor_entrega'],
            'pode_entregar' => true,
            'cliente' => [
                'nome' => $cliente['nome'],
                'bairro' => $cliente['Bairro'],
                'cidade' => $cliente['Cidade']
            ]
        ]);
    }
}
