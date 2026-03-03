<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TesteEntrega extends Controller
{
    public function index()
    {
        $email = 'bimdasbalas@gmail.com';
        
        $db = \Config\Database::connect();
        
        // Buscar cliente
        $cliente = $db->table('clientes')
            ->where('email', $email)
            ->get()
            ->getRowArray();
        
        $resultado = [
            'email' => $email,
            'cliente_encontrado' => !empty($cliente),
        ];
        
        if ($cliente) {
            $resultado['cliente'] = [
                'bairro' => $cliente['Bairro'],
                'cidade' => $cliente['Cidade']
            ];
            
            // Busca específica
            $bairroEspecifico = $db->table('bairros')
                ->where('nome', $cliente['Bairro'])
                ->where('cidade', $cliente['Cidade'])
                ->where('ativo', 1)
                ->where('deletado_em IS NULL')
                ->get()
                ->getRowArray();
            
            $resultado['busca_especifica'] = [
                'encontrado' => !empty($bairroEspecifico),
                'dados' => $bairroEspecifico
            ];
            
            // Busca universal
            $bairroUniversal = $db->table('bairros')
                ->where('nome', '*')
                ->where('cidade', $cliente['Cidade'])
                ->where('ativo', 1)
                ->where('deletado_em IS NULL')
                ->get()
                ->getRowArray();
            
            $resultado['busca_universal'] = [
                'encontrado' => !empty($bairroUniversal),
                'dados' => $bairroUniversal
            ];
            
            // Resultado final
            $bairroFinal = $bairroEspecifico ?: $bairroUniversal;
            $resultado['resultado_final'] = [
                'entrega_disponivel' => !empty($bairroFinal),
                'bairro_usado' => $bairroFinal ? $bairroFinal['nome'] : null,
                'valor_entrega' => $bairroFinal ? $bairroFinal['valor_entrega'] : null
            ];
        }
        
        return $this->response->setJSON($resultado);
    }
}
