<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdicionaCampoPrecoTabelaProdutos extends Migration
{
    public function up()
    {
        // Adicionar campo preço à tabela produtos
        $fields = [
            'preco' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'default' => null,
                'after' => 'ingredientes'
            ]
        ];
        
        $this->forge->addColumn('produtos', $fields);
    }

    public function down()
    {
        // Remover campo preço da tabela produtos
        $this->forge->dropColumn('produtos', 'preco');
    }
}