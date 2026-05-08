<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSachesTable extends Migration
{
    public function up()
    {
        // Tabela principal de sachês
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'auto_increment' => true],
            'nome'            => ['type' => 'VARCHAR', 'constraint' => 100],
            'preco'           => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00'],
            'ativo'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            // Tipo de limite: 'fixo' ou 'personalizado'
            'limite_tipo'     => ['type' => 'ENUM', 'constraint' => ['fixo', 'personalizado'], 'default' => 'fixo'],
            // Limite fixo: quantidade máxima por pedido
            'limite_fixo'     => ['type' => 'INT', 'null' => true],
            // Limite personalizado: a cada X reais, +1 sachê (com mínimo)
            'limite_por_valor'=> ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true, 'comment' => 'A cada R$ X, +1 sache'],
            'limite_minimo'   => ['type' => 'INT', 'null' => true, 'comment' => 'Quantidade minima garantida'],
            'criado_em'       => ['type' => 'DATETIME', 'null' => true],
            'atualizado_em'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('saches');

        // Tabela de associação sachê ↔ categorias
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'sache_id'    => ['type' => 'INT'],
            'categoria_id'=> ['type' => 'INT'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('sache_id', 'saches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('categoria_id', 'categorias', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('saches_categorias');
    }

    public function down()
    {
        $this->forge->dropTable('saches_categorias', true);
        $this->forge->dropTable('saches', true);
    }
}
