<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model {

    protected $table = 'produtos';
    protected $returnType = 'App\Entities\Produto';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'categoria_id',
        'nome',
        'slug',
        'ingredientes',
        'preco',
        'ativo',
        'imagem',
    ];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';
    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[2]|max_length[120]',
        'categoria_id' => 'required|integer',
        'ingredientes' => 'permit_empty|min_length[10]|max_length[1000]',
    ];
    
    // Regras específicas para criação
    protected $validationRulesCreate = [
        'nome' => 'required|min_length[2]|max_length[120]|is_unique[produtos.nome]',
        'categoria_id' => 'required|integer',
        'ingredientes' => 'permit_empty|min_length[10]|max_length[1000]',
    ];
    
    // Regras específicas para edição
    protected $validationRulesUpdate = [
        'id' => 'required|integer',
        'nome' => 'required|min_length[2]|max_length[120]|is_unique[produtos.nome,id,{id}]',
        'categoria_id' => 'required|integer',
        'ingredientes' => 'permit_empty|min_length[10]|max_length[1000]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório',
            'is_unique' => 'Esse produto já existe',
            'min_length' => 'O nome deve ter pelo menos 2 caracteres',
            'max_length' => 'O nome não pode ter mais de 120 caracteres',
        ],
        'categoria_id' => [
            'required' => 'Por favor, selecione uma categoria',
            'integer' => 'Categoria inválida',
        ],
        'ingredientes' => [
            'min_length' => 'Os ingredientes devem ter pelo menos 10 caracteres',
            'max_length' => 'Os ingredientes não podem ter mais de 1000 caracteres',
        ],
        'imagem' => [
            'uploaded' => 'Por favor, selecione uma imagem',
            'max_size' => 'A imagem deve ter no máximo 2MB',
            'is_image' => 'O arquivo deve ser uma imagem válida',
            'mime_in' => 'A imagem deve ser nos formatos: JPG, JPEG, PNG ou GIF',
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
     * Valida dados para criação de produto
     */
    public function validateCreate($data) {
        $this->setValidationRules($this->validationRulesCreate);
        return $this->validate($data);
    }
    
    /**
     * Valida dados para edição de produto
     */
    public function validateUpdate($data) {
        $this->setValidationRules($this->validationRulesUpdate);
        return $this->validate($data);
    }
    
    /**
     * Salva produto com validação apropriada
     */
    public function save($row): bool {
        // Se tem ID, é edição, senão é criação
        if (isset($row['id']) && !empty($row['id'])) {
            $this->setValidationRules($this->validationRulesUpdate);
        } else {
            $this->setValidationRules($this->validationRulesCreate);
        }
        
        return parent::save($row);
    }
    
    /**
     * Cria um novo produto e retorna o ID
     */
    public function criarProduto($data) {
        $this->setValidationRules($this->validationRulesCreate);
        if (parent::save($data)) {
            return $this->getInsertID();
        }
        return false;
    }
    
    /**
     * Atualiza um produto existente
     */
    public function atualizarProduto($data) {
        $this->setValidationRules($this->validationRulesUpdate);
        return parent::save($data);
    }

    /**
     * @uso Controller asunarios no método procurar com o autocomplete
     * @param string $term
     * @return array categorias
     */
    public function procurar($term) {

        if ($term === null) {
            return [];
        }

        return $this->select('id,nome')
                        ->like('nome', $term)
                        ->withDeleted(true)
                        ->get()
                        ->getResult();
    }
}
