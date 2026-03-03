<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCamposDadosCorporativos extends Migration
{
    public function up()
    {
        $fields = [
            'cep' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('dados_corporativos', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('dados_corporativos', ['cep', 'created_at', 'updated_at']);
    }
}
