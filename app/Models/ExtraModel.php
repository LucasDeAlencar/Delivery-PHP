<?php

namespace App\Models;

use CodeIgniter\Model;

class ExtraModel extends Model {

    protected $table = 'extras';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'App\Entities\Extra';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'slug', 'preco', 'ativo', 'descricao', 'atualizado_em'];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts = [];
    protected array $castHandlers = [];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';
    
    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[2]|max_length[120]|is_unique[extras.nome]',
        'preco' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'descricao' => 'permit_empty|max_length[1000]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório',
            'is_unique' => 'Esse Extra já existe'
        ],
        'preco' => [
            'decimal' => 'O preço deve ser um valor decimal válido',
            'greater_than_equal_to' => 'O preço deve ser maior ou igual a zero'
        ],
        'descricao' => [
            'max_length' => 'A descrição deve ter no máximo 1000 caracteres'
        ],
    ];
    protected $beforeUpdate = ['criaSlug'];
    protected $beforeInsert = ['criaSlug'];

    public function criaSlug(array $data) {
        $datetime = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        if (isset($data['data']['nome'])) {

            $data['data']['slug'] = mb_url_title($data['data']['nome'], '-', TRUE);
            $data['data']['atualizado_em'] = $datetime->format('Y-m-d H:i:s');
        }

        return $data;
    }
    
    /**
     * @uso Controller asunarios no método procurar com o autocomplete
     * @param string $term
     * @return array categorias
     */
    public function procurar($term) {
        
        if($term === null){
            return [];
        }
        
        return $this->select('id,nome')
                ->like('nome', $term)
                ->withDeleted(true)
                ->get()
                ->getResult();
    }
}
