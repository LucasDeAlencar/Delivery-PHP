<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CriaTabelaMesas extends Seeder
{
    public function run()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS configuracao_mesas (
                id INT(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                sistema_ativo BOOLEAN DEFAULT 0,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS mesas (
                id INT(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                numero INT(5) NOT NULL,
                slug VARCHAR(50) NOT NULL,
                capacidade INT(5) DEFAULT 4,
                ocupado BOOLEAN DEFAULT 0,
                pedido_id INT(5) DEFAULT NULL,
                ativo BOOLEAN DEFAULT 1,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                UNIQUE KEY numero (numero)
            )
        ");

        $this->db->query("INSERT IGNORE INTO configuracao_mesas (id) VALUES (1)");
    }
}