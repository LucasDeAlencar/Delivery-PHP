<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CorrigeCampoFechamentoExpediente extends Migration
{
    public function up()
    {
        // Modifica o campo fechamento para TIME
        $this->forge->modifyColumn('expedientes', [
            'fechamento' => [
                'type' => 'TIME',
                'null' => true,
                'default' => null,
            ]
        ]);
    }

    public function down()
    {
        // Reverte para DECIMAL (caso necessário)
        $this->forge->modifyColumn('expedientes', [
            'fechamento' => [
                'type' => 'DECIMAL',
            ]
        ]);
    }
}