<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlteraUniqueKeyBairros extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE bairros DROP INDEX nome');
        $this->db->query('ALTER TABLE bairros ADD UNIQUE KEY nome_cidade (nome, cidade)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE bairros DROP INDEX nome_cidade');
        $this->db->query('ALTER TABLE bairros ADD UNIQUE KEY nome (nome)');
    }
}
