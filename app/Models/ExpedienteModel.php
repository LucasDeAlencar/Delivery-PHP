<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpedienteModel extends Model
{
    protected $table            = 'expedientes';
    protected $returnType       = 'object';
    protected $allowedFields    = ['abertura', 'fechamento','dia_descricao', 'situacao'];

    // Validation
    protected $validationRules = [
        'abertura' => 'required',
        'fechamento' => 'required',
        'dia_descricao' => 'required',
        'situacao' => 'required',
    ];
    protected $beforeUpdate = ['criaSlug'];
    protected $beforeInsert = ['criaSlug'];

    public function criaSlug(array $data) {
        $datetime = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        
        // Só gera o slug automaticamente se:
        // 1. O nome foi fornecido
        // 2. O slug não foi fornecido ou está vazio
        if (isset($data['data']['nome'])) {
            // Se o slug não foi fornecido ou está vazio, gera automaticamente
            if (!isset($data['data']['slug']) || empty(trim($data['data']['slug']))) {
                $data['data']['slug'] = mb_url_title($data['data']['nome'], '-', TRUE);
            } else {
                // Se o slug foi fornecido, limpa e formata
                $data['data']['slug'] = mb_url_title($data['data']['slug'], '-', TRUE);
            }
            
            $data['data']['atualizado_em'] = $datetime->format('Y-m-d H:i:s');
        }

        return $data;
    }
}
