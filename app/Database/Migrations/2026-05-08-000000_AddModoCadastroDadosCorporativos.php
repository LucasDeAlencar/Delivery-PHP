<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddModoCadastroDadosCorporativos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('dados_corporativos', [
            'modo_cadastro' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
                'after'      => 'entrega_ate',
                'comment'    => '1=com verificação email, 2=sem verificação, 3=simplificado (nome+celular)',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('dados_corporativos', ['modo_cadastro']);
    }
}
