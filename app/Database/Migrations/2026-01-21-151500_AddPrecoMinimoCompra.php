<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrecoMinimoCompra extends Migration
{
    public function up()
    {
        // Adicionar campo preco_minimo_compra à tabela configuracao_entrega
        $fields = [
            'preco_minimo_compra' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'default' => 0.00,
                'after' => 'cep_loja'
            ]
        ];
        
        $this->forge->addColumn('configuracao_entrega', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('configuracao_entrega', 'preco_minimo_compra');
    }
}
