<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePedidosSachesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'pedido_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'sache_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'sache_nome' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'quantidade' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'quantidade_gratuita' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'quantidade_paga' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'preco_unitario' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'preco_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sache_id', 'saches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pedidos_saches');
    }

    public function down()
    {
        $this->forge->dropTable('pedidos_saches', true);
    }
}
