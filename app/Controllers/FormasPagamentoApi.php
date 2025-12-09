<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class FormasPagamentoApi extends ResourceController {

    use ResponseTrait;

    protected $db;
    protected $format = 'json';

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    /**
     * Retorna formas de pagamento ativas
     * GET /api/formas-pagamento
     */
    public function index() {
        $formas = $this->db->table('formas_pagamento')
                ->where('ativo', 1)
                ->where('deletado_em IS NULL')
                ->orderBy('ordem', 'ASC')
                ->get()
                ->getResultArray();

        return $this->respond([
                    'success' => true,
                    'data' => $formas
        ]);
    }
}
