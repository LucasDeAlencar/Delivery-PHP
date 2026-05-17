<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpedienteModel extends Model
{
    protected $table            = 'expedientes';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['dia', 'dia_descricao', 'abertura', 'fechamento', 'vira_dia', 'situacao'];

    // Validation
    protected $validationRules = [
        'abertura' => 'required',
        'fechamento' => 'required',
        'situacao' => 'required|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'abertura' => [
            'required' => 'O horário de abertura é obrigatório.'
        ],
        'fechamento' => [
            'required' => 'O horário de fechamento é obrigatório.'
        ],
        'situacao' => [
            'required' => 'A situação é obrigatória.',
            'in_list' => 'A situação deve ser Aberto ou Fechado.'
        ]
    ];
    
    protected $beforeInsert = ['validarHorarios'];
    protected $beforeUpdate = ['validarHorarios'];
    protected $afterInsert = ['resetAutoIncrement'];

    protected function validarHorarios(array $data)
    {
        // Com vira_dia=1, fechamento pode ser menor que abertura (passa da meia-noite)
        if (!empty($data['data']['vira_dia'])) {
            return $data;
        }

        if (isset($data['data']['abertura']) && isset($data['data']['fechamento'])) {
            if (strtotime($data['data']['fechamento']) <= strtotime($data['data']['abertura'])) {
                // No CI4, setar data como array vazio cancela a operação
                $this->errors['horario'] = 'O horário de fechamento deve ser posterior ao de abertura (ou marque "Vira dia").';
                $data['data'] = [];
            }
        }

        return $data;
    }

    protected function resetAutoIncrement($data): array
    {
        $table = $this->table;
        $db = \Config\Database::connect();
        
        $query = $db->query("SELECT MAX(id) as max_id FROM $table");
        $result = $query->getRow();
        $maxId = $result->max_id ?? 0;

        $db->query("ALTER TABLE $table AUTO_INCREMENT = " . ($maxId + 1));

        return $data;
    }
    
    /**
     * Valida se o horário de fechamento é posterior ao de abertura
     */
    public function validarHorarioExpediente($abertura, $fechamento, $viraDia = false)
    {
        if ($viraDia) return true;
        return strtotime($fechamento) > strtotime($abertura);
    }
}