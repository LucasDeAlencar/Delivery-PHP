<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddModoCadastroClientes extends Migration
{
    public function up()
    {
        // Tornar email nullable
        $this->forge->modifyColumn('clientes', [
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ]
        ]);

        // Adicionar campo modo_cadastro
        $this->forge->addColumn('clientes', [
            'modo_cadastro' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
                'after'      => 'complemento',
                'comment'    => '1=com verificação email, 2=sem verificação, 3=simplificado (nome+celular)'
            ]
        ]);
    }

    public function down()
    {
        // Remover modo_cadastro
        $this->forge->dropColumn('clientes', ['modo_cadastro']);

        // Restaurar email NOT NULL
        $this->forge->modifyColumn('clientes', [
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ]
        ]);
    }
}
