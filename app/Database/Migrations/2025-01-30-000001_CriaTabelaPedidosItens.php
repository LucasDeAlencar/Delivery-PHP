<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriaTabelaPedidosItens extends Migration {

    public function up() {
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
                'comment' => 'ID do pedido',
            ],
            'produto_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'ID do produto',
            ],
            'produto_nome' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'comment' => 'Nome do produto (snapshot)',
            ],
            'quantidade' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Quantidade do produto',
            ],
            'preco_unitario' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Preço unitário no momento da compra',
            ],
            'preco_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Preço total do item (quantidade * preço unitário)',
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Observações específicas do item',
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'atualizado_em' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('pedido_id');
        $this->forge->addKey('produto_id');
        
        // Foreign keys
        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produto_id', 'produtos', 'id', 'RESTRICT', 'CASCADE');
        
        $this->forge->createTable('pedidos_itens');
    }

    public function down() {
        $this->forge->dropTable('pedidos_itens');
    }
}
