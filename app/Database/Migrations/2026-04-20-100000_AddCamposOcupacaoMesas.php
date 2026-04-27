<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCamposOcupacaoMesas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('mesas', [
            'ocupado' => [
                'type' => 'BOOLEAN',
                'default' => 0,
                'after' => 'ativo',
            ],
            'pedido_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'default' => null,
                'after' => 'ocupado',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('mesas', ['ocupado', 'pedido_id']);
    }
}
