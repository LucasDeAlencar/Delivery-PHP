<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSachesGruposTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nome' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nome');
        $this->forge->createTable('saches_grupos');

        // Migrar grupos já existentes na coluna categoria_sache
        $db = \Config\Database::connect();
        $db->query("
            INSERT IGNORE INTO saches_grupos (nome)
            SELECT DISTINCT categoria_sache FROM saches
            WHERE categoria_sache IS NOT NULL AND categoria_sache != ''
        ");
    }

    public function down()
    {
        $this->forge->dropTable('saches_grupos', true);
    }
}
