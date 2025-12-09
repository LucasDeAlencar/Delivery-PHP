<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriaTabelaExpediente extends Migration
{
    
    public function up() {

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dia' => [
                'type' => 'INT',
                'constraint' => 5,
            ],
            'dia_descricao' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'abertura' => [
                'type' => 'TIME',
                'null' => true,
                'default' => null,
            ],
            'fechamento' => [
                'type' => 'DECIMAL',
            ],
            'situacao' => [ // 0 (fechado) e 1 (aberto)
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('expedientes');
    }

    public function down() {

        $this->forge->dropTable('expedientes');
    }
}
