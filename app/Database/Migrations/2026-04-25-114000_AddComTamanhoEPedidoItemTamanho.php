<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddComTamanhoEPedidoItemTamanho extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Adiciona com_tamanho na tabela produtos
        if (!$db->fieldExists('com_tamanho', 'produtos')) {
            $this->forge->addColumn('produtos', [
                'com_tamanho' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                    'after'      => 'max_extras',
                ],
            ]);
        }

        // 2. Adiciona tamanho_nome e tamanho_preco em pedidos_itens
        if (!$db->fieldExists('tamanho_nome', 'pedidos_itens')) {
            $this->forge->addColumn('pedidos_itens', [
                'tamanho_nome' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 64,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'observacoes',
                ],
            ]);
        }

        if (!$db->fieldExists('tamanho_preco', 'pedidos_itens')) {
            $this->forge->addColumn('pedidos_itens', [
                'tamanho_preco' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'tamanho_nome',
                ],
            ]);
        }

        // 3. Recria produtos_tamanhos com estrutura simples (nome + preco, sem FK)
        // Mas primeiro verifica se já tem a estrutura nova (nome) ou antiga (tamanho_id)
        if ($db->tableExists('produtos_tamanhos')) {
            // Verifica se a coluna 'nome' existe
            if (!$db->fieldExists('nome', 'produtos_tamanhos')) {
                // Drop indexes/constraints first to avoid FK issues
                $db->query('SET FOREIGN_KEY_CHECKS=0');
                
                // Remove unique key if exists
                $db->query('ALTER TABLE produtos_tamanhos DROP INDEX IF EXISTS produto_tamanho_unique');
                
                // Drop FK constraints
                $db->query('ALTER TABLE produtos_tamanhos DROP FOREIGN KEY IF EXISTS produtos_tamanhos_ibfk_1');
                $db->query('ALTER TABLE produtos_tamanhos DROP FOREIGN KEY IF EXISTS produtos_tamanhos_ibfk_2');
                
                // Rename tamanho_id to nome and change type
                $db->query('ALTER TABLE produtos_tamanhos CHANGE COLUMN tamanho_id nome VARCHAR(64) NOT NULL');
                
                // Populate nome from tamanhos table
                $db->query('UPDATE produtos_tamanhos pt JOIN tamanhos t ON pt.nome = t.id SET pt.nome = t.nome');
                
                $db->query('SET FOREIGN_KEY_CHECKS=1');
            }
        } else {
            // Table doesn't exist, create it
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'produto_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => false,
                ],
                'nome' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 64,
                    'null'       => false,
                ],
                'preco' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'null'       => false,
                ],
                'ativo' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 1,
                ],
                'criado_em' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'default' => null,
                ],
                'atualizado_em' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'default' => null,
                ],
            ]);
            $this->forge->addPrimaryKey('id');
            $this->forge->addKey('produto_id');
            $this->forge->createTable('produtos_tamanhos');
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        if ($db->fieldExists('com_tamanho', 'produtos')) {
            $this->forge->dropColumn('produtos', 'com_tamanho');
        }
        if ($db->fieldExists('tamanho_nome', 'pedidos_itens')) {
            $this->forge->dropColumn('pedidos_itens', 'tamanho_nome');
        }
        if ($db->fieldExists('tamanho_preco', 'pedidos_itens')) {
            $this->forge->dropColumn('pedidos_itens', 'tamanho_preco');
        }
    }
}
