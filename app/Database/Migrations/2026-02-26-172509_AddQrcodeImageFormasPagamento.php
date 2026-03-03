<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQrcodeImageFormasPagamento extends Migration
{
    public function up()
    {
        $this->forge->addColumn('formas_pagamento', [
            'qrcode_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'codigo',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('formas_pagamento', 'qrcode_image');
    }
}
