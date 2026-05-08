<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoriaSacheSaches extends Migration
{
    public function up()
    {
        $this->forge->addColumn('saches', [
            'categoria_sache' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'nome',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('saches', 'categoria_sache');
    }
}
