<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixClientesEmailNullable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Tornar email nullable (idempotente — não falha se já for nullable)
        try {
            $db->query("ALTER TABLE clientes MODIFY COLUMN email VARCHAR(100) NULL DEFAULT NULL");
        } catch (\Exception $e) {
            log_message('info', 'FixClientesEmailNullable: email já é nullable - ' . $e->getMessage());
        }

        // Remover unique key de email (para permitir múltiplos NULL / emails vazios)
        try {
            $db->query("ALTER TABLE clientes DROP INDEX email");
        } catch (\Exception $e) {
            log_message('info', 'FixClientesEmailNullable: índice email não existe ou já removido - ' . $e->getMessage());
        }

        // Recriar unique key apenas para emails não-nulos e não-vazios
        try {
            $db->query("CREATE UNIQUE INDEX idx_clientes_email ON clientes (email) WHERE email IS NOT NULL AND email != ''");
        } catch (\Exception $e) {
            // MariaDB/MySQL antigo não suporta partial index — usar trigger ou aceitar sem unique
            log_message('info', 'FixClientesEmailNullable: partial index não suportado, sem unique em email - ' . $e->getMessage());
        }

        // Adicionar modo_cadastro se não existir
        $colunas = $db->getFieldNames('clientes');
        if (!in_array('modo_cadastro', $colunas)) {
            $db->query("ALTER TABLE clientes ADD COLUMN modo_cadastro TINYINT(1) NOT NULL DEFAULT 1 AFTER complemento");
        }
    }

    public function down()
    {
        // Não reverter — operação de segurança
    }
}
