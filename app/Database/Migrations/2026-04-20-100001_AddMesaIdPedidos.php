<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMesaIdPedidos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pedidos', [
            'mesa_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'default' => null,
                'after' => 'tipo_entrega',
            ],
            'local_retirada' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => null,
                'after' => 'mesa_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pedidos', ['mesa_id', 'local_retirada']);
    }
}
