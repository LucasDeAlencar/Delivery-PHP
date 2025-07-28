<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixAtivacaoHashField extends Migration
{
    public function up()
    {
        // Modify the ativacao_hash field to allow NULL values
        $this->forge->modifyColumn('usuarios', [
            'ativacao_hash' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null,
                'unique' => true,
            ],
            'reset_hash' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null,
                'unique' => true,
            ],
        ]);
    }

    public function down()
    {
        // Revert the changes
        $this->forge->modifyColumn('usuarios', [
            'ativacao_hash' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => true,
            ],
            'reset_hash' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => true,
            ],
        ]);
    }
}