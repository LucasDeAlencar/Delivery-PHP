<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdicionaEmailClientePedidos extends Migration
{
    public function up()
    {
        $fields = [
            'email_cliente' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
                'after' => 'telefone_cliente',
                'comment' => 'Email do cliente para acompanhamento do pedido'
            ]
        ];
        
        $this->forge->addColumn('pedidos', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('pedidos', 'email_cliente');
    }
}
