<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriaTabelaTamanhos extends Migration
{
    public function up()
    {
        // Tabela de tamanhos base (ex: Pequeno, Médio, Grande)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
            ],
            'ativo' => [
                'type'    => 'BOOLEAN',
                'null'    => false,
                'default' => true,
            ],
            'criado_em' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'atualizado_em' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'deletado_em' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
        ]);

    }

    public function down()
    {
        $this->forge->dropTable('produtos_tamanhos');
        $this->forge->dropTable('tamanhos');
    }
}
