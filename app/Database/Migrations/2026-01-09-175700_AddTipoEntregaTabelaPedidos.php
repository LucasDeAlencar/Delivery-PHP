<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTipoEntregaTabelaPedidos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pedidos', [
            'tipo_entrega' => [
                'type' => 'ENUM',
                'constraint' => ['entrega', 'retirada'],
                'default' => 'entrega',
                'after' => 'forma_pagamento',
                'comment' => 'Tipo de entrega: entrega ou retirada'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pedidos', 'tipo_entrega');
    }
}
