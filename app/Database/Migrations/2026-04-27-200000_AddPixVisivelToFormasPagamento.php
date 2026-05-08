<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPixVisivelToFormasPagamento extends Migration
{
    public function up()
    {
        $this->forge->addColumn('formas_pagamento', [
            'pix_visivel' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'qrcode_image',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('formas_pagamento', 'pix_visivel');
    }
}
