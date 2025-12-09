<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpedienteModel extends Model
{
    protected $table            = 'expedientes';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['dia', 'dia_descricao', 'abertura', 'fechamento', 'situacao'];

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
    
    protected function validarHorarios(array $data)
    {
        if (isset($data['data']['abertura']) && isset($data['data']['fechamento'])) {
            $abertura = $data['data']['abertura'];
            $fechamento = $data['data']['fechamento'];
            
            // Converte para timestamp para comparação
            $horaAbertura = strtotime($abertura);
            $horaFechamento = strtotime($fechamento);
            
            if ($horaFechamento <= $horaAbertura) {
                $this->errors[] = 'O horário de fechamento deve ser posterior ao horário de abertura.';
                return false;
            }
        }
        
        return $data;
    }
    
    /**
     * Valida se o horário de fechamento é posterior ao de abertura
     */
    public function validarHorarioExpediente($abertura, $fechamento)
    {
        $horaAbertura = strtotime($abertura);
        $horaFechamento = strtotime($fechamento);
        
        return $horaFechamento > $horaAbertura;
    }
}