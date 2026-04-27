<?php

namespace App\Models;

use CodeIgniter\Model;

class ExtraModel extends Model {

    protected $table = 'extras';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'App\Entities\Extra';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'slug', 'preco', 'ativo', 'multitude', 'descricao', 'atualizado_em', 'deletado_em'];
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
    protected $afterInsert = ['resetAutoIncrement'];

    public function criaSlug(array $data) {
        $datetime = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        if (isset($data['data']['nome'])) {

            $data['data']['slug'] = mb_url_title($data['data']['nome'], '-', TRUE);
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
                ->like('nome', $term, 'both', null, true)
                ->withDeleted(true)
                ->get()
                ->getResult();
    }

     /**
      * Busca extras com filtros de pesquisa
      */
     public function buscarComFiltros($search = null, $ativo = null) {
         $builder = $this->withDeleted(true);
         
         // Tratar string vazia como null
         if ($ativo === '') {
             $ativo = null;
         }
         
         if ($search) {
             $builder->groupStart()
                    ->like('nome', $search, 'both', null, true)
                    ->orLike('descricao', $search, 'both', null, true)
                    ->groupEnd();
         }
         
         if ($ativo !== null) {
             $builder->where('ativo', $ativo);
         }
         
         return $builder->orderBy('nome', 'ASC')->findAll();
     }

    /**
     * Associa todos os produtos de uma categoria aos extras selecionados
     */
    public function associarPorCategoria($extraId, $categoriaId) {
        try {
            // Conexão direta com PDO usando configurações do .env
            $pdo = new PDO("mysql:host=localhost;dbname=food;charset=utf8", 'root', 'Legnu.131807');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Busca todos os produtos ativos da categoria
            $stmt = $pdo->prepare("SELECT id FROM produtos WHERE categoria_id = ? AND ativo = 1");
            $stmt->execute([$categoriaId]);
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($produtos)) {
                log_message('info', "Nenhum produto ativo encontrado para categoria ID: {$categoriaId}");
                return 0;
            }
            
            $sucessos = 0;
            
            foreach ($produtos as $produto) {
                // Verifica se a associação já existe
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM produtos_extras WHERE produto_id = ? AND extra_id = ?");
                $stmt->execute([$produto['id'], $extraId]);
                $existe = $stmt->fetchColumn();
                
                if ($existe == 0) {
                    // Insere a associação produto -> extra
                    $stmt = $pdo->prepare("INSERT INTO produtos_extras (produto_id, extra_id) VALUES (?, ?)");
                    $resultado = $stmt->execute([$produto['id'], $extraId]);
                    
                    if ($resultado) {
                        $sucessos++;
                        log_message('info', "Associação criada: Produto {$produto['id']} -> Extra {$extraId}");
                    } else {
                        log_message('error', "Falha ao criar associação: Produto {$produto['id']} -> Extra {$extraId}");
                    }
                }
            }
            
            log_message('info', "Associação por categoria concluída. Total de sucessos: {$sucessos}");
            return $sucessos;
            
        } catch (Exception $e) {
            log_message('error', "Erro na associação por categoria: " . $e->getMessage());
            return 0;
        }
    }
}
