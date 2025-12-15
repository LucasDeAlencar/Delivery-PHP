<?php

namespace App\Controllers;

use App\Models\ConfiguracaoEntregaModel;
use App\Models\UsuarioModel;

class TaxaEntregaApi extends BaseController
{
    public function calcular()
    {
        $email = $this->request->getPost('email');
        
        if (!$email) {
            return $this->response->setJSON(['erro' => 'Email não informado']);
        }
        
        $configModel = new ConfiguracaoEntregaModel();
        $config = $configModel->getConfiguracao();
        
        if (!$config || $config['modo_cobranca'] !== 'km') {
            return $this->response->setJSON(['taxa' => 0]);
        }
        
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('email', $email)->first();
        
        if (!$usuario || !$usuario['cep']) {
            return $this->response->setJSON(['erro' => 'CEP do cliente não encontrado']);
        }
        
        $distancia = $this->calcularDistancia($config['cep_loja'], $usuario['cep']);
        $taxa = $distancia * $config['taxa_por_km'];
        
        if ($config['taxa_minima'] && $taxa < $config['taxa_minima']) {
            $taxa = $config['taxa_minima'];
        }
        
        return $this->response->setJSON([
            'taxa' => $taxa,
            'distancia' => $distancia
        ]);
    }
    
    private function calcularDistancia($cep1, $cep2)
    {
        // Função simplificada usando coordenadas aproximadas por CEP
        $coord1 = $this->obterCoordenadas($cep1);
        $coord2 = $this->obterCoordenadas($cep2);
        
        if (!$coord1 || !$coord2) {
            return 0;
        }
        
        return $this->distanciaHaversine($coord1['lat'], $coord1['lng'], $coord2['lat'], $coord2['lng']);
    }
    
    private function obterCoordenadas($cep)
    {
        $cep = preg_replace('/\D/', '', $cep);
        
        // API gratuita ViaCEP + OpenStreetMap
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $response = @file_get_contents($url);
        
        if (!$response) {
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (isset($data['erro'])) {
            return null;
        }
        
        // Coordenadas aproximadas baseadas na cidade/estado
        $coordenadas = $this->coordenadasPorCidade($data['localidade'], $data['uf']);
        
        return $coordenadas;
    }
    
    private function coordenadasPorCidade($cidade, $uf)
    {
        // Coordenadas aproximadas de algumas cidades principais
        $cidades = [
            'São Paulo-SP' => ['lat' => -23.5505, 'lng' => -46.6333],
            'Rio de Janeiro-RJ' => ['lat' => -22.9068, 'lng' => -43.1729],
            'Belo Horizonte-MG' => ['lat' => -19.9167, 'lng' => -43.9345],
            'Salvador-BA' => ['lat' => -12.9714, 'lng' => -38.5014],
            'Brasília-DF' => ['lat' => -15.7939, 'lng' => -47.8828],
        ];
        
        $chave = $cidade . '-' . $uf;
        
        return $cidades[$chave] ?? ['lat' => -23.5505, 'lng' => -46.6333]; // Default SP
    }
    
    private function distanciaHaversine($lat1, $lng1, $lat2, $lng2)
    {
        $raioTerra = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $raioTerra * $c;
    }
}
