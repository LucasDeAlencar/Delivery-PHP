<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model {

    protected $table = 'produtos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'categoria_id',
        'nome',
        'slug',
        'ingredientes',
        'ativo',
        'imagem',
        'preco',
        'obrigatorio_extras'
    ];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    // Validation - Regras básicas (is_unique é verificado manualmente no criarProduto)
    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[120]',
        'categoria_id' => 'required|integer',
        'ingredientes' => 'required',
        'preco' => 'required|numeric',
        'imagem' => 'permit_empty',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo nome é obrigatório.',
            'min_length' => 'O campo nome precisa ter pelo menos 3 caracteres.',
            'max_length' => 'O campo nome não pode ter mais de 120 caracteres.',
            'is_unique' => 'Já existe um produto com este nome. Por favor, escolha outro nome.',
        ],
        'categoria_id' => [
            'required' => 'O campo categoria é obrigatório.',
            'integer' => 'O campo categoria deve ser um número inteiro.',
        ],
        'ingredientes' => [
            'required' => 'O campo ingredientes é obrigatório.',
        ],
        'preco' => [
            'required' => 'O campo preço é obrigatório.',
            'numeric' => 'O campo preço deve ser um valor numérico válido.',
        ],
    ];
    // Callbacks
    protected $beforeInsert = ['geraSlug'];
    protected $beforeUpdate = ['geraSlug'];

    /**
     * Gera o slug automaticamente antes de inserir/atualizar
     */
    protected function geraSlug(array $data) {
        if (isset($data['data']['nome'])) {
            $data['data']['slug'] = mb_url_title($data['data']['nome'], '-', true);
        }
        return $data;
    }

    /**
     * Busca produtos por categoria com informações da categoria
     */
    public function buscaProdutosComCategoria() {
        return $this->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug')
                        ->join('categorias', 'categorias.id = produtos.categoria_id')
                        ->where('produtos.ativo', true)
                        ->findAll();
    }

    /**
     * Busca produto por ID com informações da categoria
     */
    public function buscaProdutoPorId($id) {
        return $this->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug')
                        ->join('categorias', 'categorias.id = produtos.categoria_id')
                        ->where('produtos.id', $id)
                        ->where('produtos.ativo', true)
                        ->first();
    }

    /**
     * Busca produtos por slug da categoria
     */
    public function buscaProdutosPorCategoriaSlug($categoriaSlug) {
        return $this->select('produtos.*, categorias.nome as categoria_nome, categorias.slug as categoria_slug')
                        ->join('categorias', 'categorias.id = produtos.categoria_id')
                        ->where('categorias.slug', $categoriaSlug)
                        ->where('produtos.ativo', true)
                        ->findAll();
    }

    /**
     * Busca todos os produtos ativos
     */
    public function buscaProdutosAtivos() {
        return $this->where('ativo', true)
                        ->findAll();
    }

    /**
     * Verifica se a imagem existe e retorna o caminho completo
     */
    public function getCaminhoImagem($produto) {
        if (!empty($produto->imagem) && file_exists(ROOTPATH . 'public/uploads/produtos/' . $produto->imagem)) {
            return base_url('uploads/produtos/' . $produto->imagem);
        }
        return null; // Retorna null se a imagem não existir
    }

    public function criarProduto($dadosProduto) {
        // Trata o preço - aceita tanto formato brasileiro quanto decimal
        $preco = $dadosProduto['preco'] ?? '0';
        if (is_string($preco)) {
            // Se contém vírgula, assume formato brasileiro (1.234,56)
            if (strpos($preco, ',') !== false) {
                $preco = str_replace('.', '', $preco); // Remove pontos de milhar
                $preco = str_replace(',', '.', $preco); // Converte vírgula para ponto
            }
            // Se já está no formato decimal (1234.56), não faz nada
        }
        $preco = floatval($preco);
        
        // Prepara os dados para inserção com os campos corretos do allowedFields
        $dadosParaInserir = [
            'nome' => $dadosProduto['nome'] ?? '',
            'categoria_id' => $dadosProduto['categoria_id'] ?? null,
            'ingredientes' => $dadosProduto['ingredientes'] ?? '',
            'preco' => $preco,
            'imagem' => $dadosProduto['imagem'] ?? null,
            'ativo' => $dadosProduto['ativo'] ?? 0,
            'obrigatorio_extras' => $dadosProduto['obrigatorio_extras'] ?? 0,
        ];

        // Verifica se já existe um produto com o mesmo nome
        $produtoExistente = $this->where('nome', $dadosParaInserir['nome'])->first();
        if ($produtoExistente) {
            $this->validationErrors = ['nome' => 'Já existe um produto com este nome. Por favor, escolha outro nome.'];
            return false;
        }

        // Usa o método insert() do Model que aplica automaticamente:
        // - Validações (validationRules)
        // - Geração de slug (beforeInsert callback)
        // - Timestamps automáticos (useTimestamps)
        // - Proteção de campos (allowedFields)
        try {
            $produtoId = $this->insert($dadosParaInserir);

            if ($produtoId) {
                return $produtoId;
            }

            // Se insert() retornou false, os erros estão em $this->errors()
            return false;
        } catch (\mysqli_sql_exception $e) {
            // Trata erro de duplicidade
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $this->validationErrors = ['nome' => 'Já existe um produto com este nome. Por favor, escolha outro nome.'];
            } else {
                $this->validationErrors = ['geral' => 'Erro no banco de dados: ' . $e->getMessage()];
            }
            log_message('error', 'Erro ao criar produto: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            $this->validationErrors = ['geral' => 'Erro ao salvar: ' . $e->getMessage()];
            log_message('error', 'Erro ao criar produto: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Armazena erros de validação customizados
     */
    protected $validationErrors = [];
    
    /**
     * Sobrescreve o método errors() para incluir erros customizados
     */
    public function errors(bool $forceDB = false): array {
        $errors = parent::errors($forceDB);
        return array_merge($errors, $this->validationErrors);
    }

    /**
     * Atualiza um produto existente
     */
    public function atualizarProduto($dadosProduto) {
        // Prepara os dados para atualização
        $dadosParaAtualizar = [
            'nome' => $dadosProduto['nome'] ?? '',
            'categoria_id' => $dadosProduto['categoria_id'] ?? null,
            'ingredientes' => $dadosProduto['ingredientes'] ?? '',
            'preco' => isset($dadosProduto['preco']) ? str_replace(',', '.', $dadosProduto['preco']) : 0,
            'ativo' => $dadosProduto['ativo'] ?? 0,
            'obrigatorio_extras' => $dadosProduto['obrigatorio_extras'] ?? 0,
        ];

        // Adiciona imagem apenas se foi fornecida
        if (isset($dadosProduto['imagem'])) {
            $dadosParaAtualizar['imagem'] = $dadosProduto['imagem'];
        }

        // Usa o método update() do Model
        try {
            return $this->update($dadosProduto['id'], $dadosParaAtualizar);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao atualizar produto: ' . $e->getMessage());
            return false;
        }
    }
}
