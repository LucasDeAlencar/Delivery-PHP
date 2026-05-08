<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEntregaAteDadosCorporativos extends Migration
{
    public function up()
    {
        $fields = [
            'entrega_ate' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
                'after' => 'preco_minimo_compra',
                'comment' => 'Tempo máximo de entrega em minutos'
            ],
        ];

        $this->forge->addColumn('dados_corporativos', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('dados_corporativos', ['entrega_ate']);
    }
}
