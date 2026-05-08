<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniquePedidosSaches extends Migration
{
    public function up()
    {
        // Remove duplicatas existentes antes de adicionar a constraint
        $this->db->query("
            DELETE ps1 FROM pedidos_saches ps1
            INNER JOIN pedidos_saches ps2
            WHERE ps1.id > ps2.id
              AND ps1.pedido_id = ps2.pedido_id
              AND ps1.sache_id = ps2.sache_id
        ");

        $this->forge->addUniqueKey(['pedido_id', 'sache_id'], 'uq_pedido_sache');
        $this->forge->processIndexes('pedidos_saches');
    }

    public function down()
    {
        $this->db->query("ALTER TABLE pedidos_saches DROP INDEX uq_pedido_sache");
    }
}
