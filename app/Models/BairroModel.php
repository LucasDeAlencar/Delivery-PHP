<?php

namespace App\Models;

use CodeIgniter\Model;

class BairroModel extends Model{
    
    protected $table            = 'bairros';
    protected $returnType       = 'App\Entities\Bairro';
    protected $useAutoIncrement = true; 
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['nome', 'slug', 'cidade', 'valor_entrega', 'ativo'];
    protected bool $updateOnlyChanged = true;
    protected array $casts = [];
    protected array $castHandlers = [];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';
    
    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[1]|max_length[120]',
        'cidade' => 'required|min_length[2]|max_length[20]',
        'valor_entrega' => 'required|numeric|greater_than[0]'
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório',
            'min_length' => 'O campo Nome deve ter pelo menos 1 caractere',
            'max_length' => 'O campo Nome deve ter no máximo 120 caracteres',
        ],
        'cidade' => [
            'required' => 'O campo Cidade é obrigatório',
            'min_length' => 'O campo Cidade deve ter pelo menos 2 caracteres',
            'max_length' => 'O campo Cidade deve ter no máximo 20 caracteres',
        ],
        'valor_entrega' => [
            'required' => 'O campo Valor de entrega é obrigatório',
            'numeric' => 'O campo Valor de entrega deve ser um número válido',
            'greater_than' => 'O campo Valor de entrega deve ser maior que zero'
        ]
    ];
    protected $beforeUpdate = ['criaSlug'];
    protected $beforeInsert = ['criaSlug'];
    protected $afterInsert = ['resetAutoIncrement'];

    public function criaSlug(array $data) {
        $datetime = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        
        if (isset($data['data']['nome'])) {
            if (!isset($data['data']['slug']) || empty(trim($data['data']['slug']))) {
                if (trim($data['data']['nome']) === '*') {
                    $cidade = $data['data']['cidade'] ?? 'cidade';
                    $data['data']['slug'] = mb_url_title($cidade . '-todos', '-', TRUE);
                } else {
                    $data['data']['slug'] = mb_url_title($data['data']['nome'], '-', TRUE);
                }
            } else {
                $data['data']['slug'] = mb_url_title($data['data']['slug'], '-', TRUE);
            }
            
            $data['data']['atualizado_em'] = $datetime->format('Y-m-d H:i:s');
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

    public function desfazerExclusao(int $id) {
        return $this->protect(false)
                    ->where('id', $id)
                    ->set([
                        'deletado_em' => null,
                        'atualizado_em' => date('Y-m-d H:i:s')
                    ])
                    ->update();
    }
}