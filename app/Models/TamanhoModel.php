<?php

namespace App\Models;

use CodeIgniter\Model;

class TamanhoModel extends Model
{
    protected $table = 'tamanhos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'nome',
        'slug',
        'ativo'
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';

    protected $beforeInsert = ['criaSlug'];
    protected $beforeUpdate = ['criaSlug'];

    public function criaSlug(array $data)
    {
        if (isset($data['data']['nome'])) {
            $data['data']['slug'] = mb_url_title($data['data']['nome'], '-', true);
        }
        return $data;
    }

    /**
     * Busca tamanhos ativos
     */
    public function buscaAtivos()
    {
        return $this->where('ativo', true)
                    ->where('deletado_em IS NULL')
                    ->orderBy('nome', 'ASC')
                    ->findAll();
    }
}
