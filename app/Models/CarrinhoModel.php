<?php

namespace App\Models;

use CodeIgniter\Model;

class CarrinhoModel extends Model
{
    protected $table = 'carrinho_itens';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'session_id',
        'produto_id',
        'produto_nome',
        'produto_imagem',
        'quantidade',
        'preco_unitario',
        'preco_total',
        'observacoes'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    
    protected $validationRules = [
        'session_id' => 'required|max_length[128]',
        'produto_id' => 'required|integer',
        'produto_nome' => 'required|max_length[128]',
        'produto_imagem' => 'max_length[255]',
        'quantidade' => 'required|integer',
        'preco_unitario' => 'required|decimal',
        'preco_total' => 'required|decimal'
    ];
    
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    protected $beforeInsert = ['generateSessionId'];
    
    protected function generateSessionId(array $data)
    {
        $session = session();
        
        // Garantir que session_id existe
        if (!isset($data['data']['session_id']) || empty($data['data']['session_id'])) {
            $data['data']['session_id'] = $session->session_id;
        }
        
        return $data;
    }
}