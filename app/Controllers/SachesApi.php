<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Retorna sachês disponíveis para os produtos do carrinho.
 * POST /api/saches/disponiveis
 * Body: { "categoria_ids": [1, 3, 5] }
 */
class SachesApi extends Controller
{
    public function disponiveis()
    {
        $json = $this->request->getJSON(true);
        $categoriaIds = $json['categoria_ids'] ?? [];

        $db = \Config\Database::connect();

        if (empty($categoriaIds)) {
            return $this->response->setJSON(['saches' => []]);
        }

        $placeholders = implode(',', array_fill(0, count($categoriaIds), '?'));

        // Busca sachês ativos que tenham ao menos uma categoria em comum com o carrinho
        $saches = $db->query("
            SELECT s.id, s.nome, s.categoria_sache, s.preco, s.ativo,
                   s.limite_tipo, s.limite_fixo, s.limite_por_valor, s.limite_minimo,
                   g.qtd_inicial, g.qtd_max
            FROM saches s
            LEFT JOIN saches_grupos g ON g.nome = s.categoria_sache
            WHERE s.ativo = 1
              AND EXISTS (
                  SELECT 1 FROM saches_categorias sc
                  WHERE sc.sache_id = s.id AND sc.categoria_id IN ({$placeholders})
              )
            ORDER BY s.categoria_sache, s.nome
        ", $categoriaIds)->getResultArray();

        return $this->response->setJSON(['saches' => $saches]);
    }
}
