<?php

namespace App\Models;

use CodeIgniter\Model;

class FormaPagamentoModel extends Model
{
    protected $table            = 'formas_pagamento';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome', 'slug', 'icone', 'ativo', 'ordem', 'codigo', 'qrcode_image', 'pix_visivel'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';

    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[100]',
        'slug' => 'required|alpha_dash|max_length[100]|is_unique[formas_pagamento.slug,id,{id}]',
        'ativo' => 'required|in_list[0,1]',
    ];

    protected $validationMessages = [
        'nome' => [
            'required' => 'O nome da forma de pagamento é obrigatório.',
            'min_length' => 'O nome deve ter no mínimo 3 caracteres.',
            'max_length' => 'O nome deve ter no máximo 100 caracteres.',
        ],
        'slug' => [
            'required' => 'O slug é obrigatório.',
            'alpha_dash' => 'O slug deve conter apenas letras, números, traços e underscores.',
            'is_unique' => 'Este slug já está em uso.',
        ],
        'ativo' => [
            'required' => 'O status é obrigatório.',
            'in_list' => 'O status deve ser Ativo ou Inativo.',
        ],
    ];

    // Callbacks
    protected $beforeInsert = [];
    protected $afterInsert  = ['resetAutoIncrement'];
    protected $beforeUpdate = [];
    protected $afterUpdate  = [];
    protected $beforeFind   = [];
    protected $afterFind    = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];

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
     * Retorna apenas formas de pagamento ativas
     */
    public function getAtivas()
    {
        return $this->where('ativo', 1)
                    ->orderBy('ordem', 'ASC')
                    ->findAll();
    }

    /**
     * Retorna todas as formas de pagamento ordenadas
     */
    public function getAllOrdenadas()
    {
        return $this->orderBy('ordem', 'ASC')
                    ->findAll();
    }
}
