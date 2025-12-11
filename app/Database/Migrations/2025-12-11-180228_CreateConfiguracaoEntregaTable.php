<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConfiguracaoEntregaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'modo_cobranca' => [
                'type'       => 'ENUM',
                'constraint' => ['bairro', 'km'],
                'default'    => 'bairro',
            ],
            'taxa_por_km' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'taxa_minima' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'distancia_maxima' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,1',
                'null'       => true,
            ],
            'cep_loja' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('configuracao_entrega');
        
        // Inserir configuração padrão
        $this->db->table('configuracao_entrega')->insert([
            'modo_cobranca' => 'bairro',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('configuracao_entrega');
    }
}
