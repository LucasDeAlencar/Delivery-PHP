<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriaTabelaMesas extends Migration
{
    public function up()
    {
        // Tabela de configuração do sistema de mesas
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sistema_ativo' => [
                'type' => 'BOOLEAN',
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('configuracao_mesas');

        // Tabela de mesas
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'numero' => [
                'type' => 'INT',
                'constraint' => 5,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'capacidade' => [
                'type' => 'INT',
                'constraint' => 5,
                'default' => 4,
            ],
            'ativo' => [
                'type' => 'BOOLEAN',
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('numero');
        $this->forge->createTable('mesas');
    }

    public function down()
    {
        $this->forge->dropTable('mesas');
        $this->forge->dropTable('configuracao_mesas');
    }
}