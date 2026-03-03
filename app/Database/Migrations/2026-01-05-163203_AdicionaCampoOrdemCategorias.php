<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdicionaCampoOrdemCategorias extends Migration
{
    public function up()
    {
        $this->forge->addColumn('categorias', [
            'ordem' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => 0,
                'after'      => 'ativo'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('categorias', 'ordem');
    }
}
