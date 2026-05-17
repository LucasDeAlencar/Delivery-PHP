<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddViraDiaExpedientes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('expedientes', [
            'vira_dia' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'fechamento',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('expedientes', 'vira_dia');
    }
}
