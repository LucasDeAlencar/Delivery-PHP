<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model {

    protected $table = 'categorias';
    protected $returnType = 'App\Entities\Categoria';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'ativo', 'slug'];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';
    
    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[2]|max_length[120]|is_unique[categorias.nome]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório',
            'is_unique' => 'Esse categoria já existe',
        ],
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
