<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class BairrosApi extends ResourceController {

    use ResponseTrait;

    protected $db;
    protected $format = 'json';

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    /**
     * Retorna bairros ativos
     * GET /api/bairros
     */
    public function index() {
        $bairros = $this->db->table('bairros')
                ->select('id, nome, valor_entrega')
                ->where('ativo', 1)
                ->where('deletado_em IS NULL')
                ->orderBy('nome', 'ASC')
                ->get()
                ->getResultArray();

        return $this->respond([
                    'success' => true,
                    'data' => $bairros
        ]);
    }
}
