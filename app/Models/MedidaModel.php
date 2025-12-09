<?php

namespace App\Models;

use CodeIgniter\Model;

class MedidaModel extends Model {

    protected $table = 'medidas';
    protected $returnType = 'App\Entities\Medida';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'ativo', 'descricao'];


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';
    
    protected $beforeUpdate = ['execRegulador'];
    protected $beforeInsert = ['execRegulador'];
    
    public function execRegulador(array $data) {
        // Define o timezone para São Paulo nos timestamps
        $datetime = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        
        if (isset($data['data'])) {
            // Atualiza o campo atualizado_em com o timezone correto
            $data['data']['atualizado_em'] = $datetime->format('Y-m-d H:i:s');
        }
        
        return $data;
    }
    
    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[2]|max_length[120]|is_unique[medidas.nome]',
    ];
    
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório',
            'is_unique' => 'Esse Extra já existe'
        ],
    ];
}
