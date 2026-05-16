<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixStatusEnumPedidos extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE pedidos MODIFY COLUMN status ENUM('em_aberto','pendente','confirmado','preparando','saiu_entrega','finalizado','cancelado','inativo','nao_concluido') NOT NULL DEFAULT 'pendente'");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE pedidos MODIFY COLUMN status ENUM('em_aberto','pendente','confirmado','preparando','saiu_entrega','finalizado','cancelado','inativo') NOT NULL DEFAULT 'pendente'");
    }
}
