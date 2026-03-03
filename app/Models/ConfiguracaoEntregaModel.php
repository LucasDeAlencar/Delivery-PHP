<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracaoEntregaModel extends Model
{
    protected $table = 'configuracao_entrega';
    protected $primaryKey = 'id';
    protected $allowedFields = ['modo_cobranca', 'taxa_por_km', 'taxa_minima', 'distancia_maxima', 'cep_loja', 'preco_minimo_compra'];
    protected $useTimestamps = true;
    
    public function getConfiguracao()
    {
        return $this->first();
    }
}
