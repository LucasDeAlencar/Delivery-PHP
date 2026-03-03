<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMultitudeCampoExtras extends Migration
{
    public function up()
    {
        $this->forge->addColumn('extras', [
            'multitude' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => false,
                'comment' => 'Se permite múltiplas quantidades do extra'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('extras', 'multitude');
    }
}
