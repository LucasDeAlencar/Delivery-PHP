<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TamanhosSeeder extends Seeder
{
    public function run()
    {
        echo "Iniciando TamanhosSeeder...\n";
        
        $db = \Config\Database::connect();
        
        // Padrão de tamanhos para lanchonete/pizzaria
        $tamanhos = [
            ['nome' => 'Pequeno', 'slug' => 'pequeno'],
            ['nome' => 'Médio', 'slug' => 'medio'],
            ['nome' => 'Grande', 'slug' => 'grande'],
            ['nome' => 'Brotinho', 'slug' => 'brotinho'],
            ['nome' => 'Família', 'slug' => 'familia']
        ];
        
        foreach ($tamanhos as $tamanho) {
            // Verifica se já existe
            $existe = $db->table('tamanhos')
                ->where('nome', $tamanho['nome'])
                ->get()
                ->getNumRows();
            
            if ($existe === 0) {
                $tamanho['criado_em'] = date('Y-m-d H:i:s');
                $tamanho['atualizado_em'] = date('Y-m-d H:i:s');
                
                $db->table('tamanhos')->insert($tamanho);
                echo "✓ Tamanho '{$tamanho['nome']}' criado\n";
            } else {
                echo "- Tamanho '{$tamanho['nome']}' já existe\n";
            }
        }
        
        echo "\nTamanhosSeeder concluído!\n";
    }
}
