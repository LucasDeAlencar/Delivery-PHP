<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TaxaEntregaController extends Controller
{
    public function calcularPorEmail()
    {
        $email = $this->request->getPost('email') ?? $this->request->getGet('email');
        
        if (!$email) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email é obrigatório',
                'taxa_entrega' => 5.00,
                'pode_entregar' => true
            ]);
        }

        $db = \Config\Database::connect();

        // Buscar cliente pelo email
        $cliente = $db->table('clientes')
            ->where('email', $email)
            ->get()
            ->getRowArray();

        if (!$cliente) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cliente não encontrado - usando taxa padrão',
                'taxa_entrega' => 5.00,
                'pode_entregar' => true
            ]);
        }

        // Buscar bairro correspondente - primeira tentativa: busca exata
        $bairro = $db->table('bairros')
            ->where('TRIM(LOWER(nome))', strtolower(trim($cliente['Bairro'])))
            ->where('TRIM(LOWER(cidade))', strtolower(trim($cliente['Cidade'])))
            ->where('ativo', 1)
            ->where('deletado_em IS NULL')
            ->get()
            ->getRowArray();

        // Se não encontrou, busca por bairro universal (*) para a cidade
        if (!$bairro) {
            $bairro = $db->table('bairros')
                ->where('nome', '*')
                ->where('TRIM(LOWER(cidade))', strtolower(trim($cliente['Cidade'])))
                ->where('ativo', 1)
                ->where('deletado_em IS NULL')
                ->get()
                ->getRowArray();
        }

        if (!$bairro) {
            return $this->response->setJSON([
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

        return $this->response->setJSON([
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
