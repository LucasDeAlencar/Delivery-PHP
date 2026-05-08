<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMostrarNoCarrinhoMesas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('mesas', [
            'mostrar_no_carrinho' => [
                'type'    => 'BOOLEAN',
                'default' => 1,
                'after'   => 'ativo',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('mesas', 'mostrar_no_carrinho');
    }
}
