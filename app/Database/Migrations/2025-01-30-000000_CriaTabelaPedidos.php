<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriaTabelaPedidos extends Migration {

    public function up() {
        $db = \Config\Database::connect();
        // Skip if table already exists (avoids duplicate creation when running migrations)
        if ($db->tableExists('pedidos')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'codigo' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'comment' => 'Código único do pedido (ex: PED-20250130-0001)',
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID do usuário (se estiver logado)',
            ],
            'nome_cliente' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'comment' => 'Nome do cliente',
            ],
            'telefone_cliente' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'comment' => 'Telefone do cliente',
            ],
            'endereco_entrega' => [
                'type' => 'TEXT',
                'comment' => 'Endereço completo de entrega',
            ],
            'bairro_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID do bairro',
            ],
            'complemento' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
                'comment' => 'Complemento do endereço',
            ],
            'forma_pagamento' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'comment' => 'Forma de pagamento escolhida',
            ],
            'troco_para' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Valor para troco (se pagamento em dinheiro)',
            ],
            'valor_produtos' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Valor total dos produtos',
            ],
            'valor_entrega' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
                'comment' => 'Valor da taxa de entrega',
            ],
            'valor_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Valor total do pedido (produtos + entrega)',
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Observações gerais do pedido',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pendente', 'confirmado', 'preparando', 'saiu_entrega', 'finalizado', 'cancelado'],
                'default' => 'pendente',
                'comment' => 'Status do pedido',
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'atualizado_em' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'deletado_em' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('codigo');
        $this->forge->addKey('usuario_id');
        $this->forge->addKey('status');
        
        // Foreign keys
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('bairro_id', 'bairros', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('pedidos');
    }

    public function down() {
        $this->forge->dropTable('pedidos');
    }
}
