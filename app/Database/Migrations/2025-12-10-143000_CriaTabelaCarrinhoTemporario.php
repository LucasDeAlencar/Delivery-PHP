<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriaTabelaCarrinhoTemporario extends Migration
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
            'session_id' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => false,
            ],
            'produto_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'tamanho_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'tamanho_nome' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
            ],
            'produto_nome' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'produto_imagem' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'quantidade' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 1,
            ],
            'preco_unitario' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'preco_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'atualizado_em' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('session_id');
        $this->forge->addKey('produto_id');
        $this->forge->addKey('tamanho_id');
        
        // Foreign key
        $this->forge->addForeignKey('tamanho_id', 'tamanhos', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('carrinho_temporario');
    }

    public function down()
    {
        $this->forge->dropTable('carrinho_temporario');
    }
}
