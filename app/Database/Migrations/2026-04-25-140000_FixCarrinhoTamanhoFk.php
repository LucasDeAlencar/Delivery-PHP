<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixCarrinhoTamanhoFk extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Remove FK antiga que aponta para tamanhos.id (se existir)
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        
        // Tenta remover FK antiga (nome pode variar)
        try {
            $db->query('ALTER TABLE carrinho_temporario DROP FOREIGN KEY carrinho_temporario_tamanho_id_foreign');
        } catch (\Exception $e) { /* ignora se não existir */ }
        
        try {
            $db->query('ALTER TABLE carrinho_temporario DROP FOREIGN KEY carrinho_temporario_ibfk_1');
        } catch (\Exception $e) { /* ignora se não existir */ }

        $db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // Não restaura FK antiga pois a tabela tamanhos pode não ter os IDs corretos
    }
}
