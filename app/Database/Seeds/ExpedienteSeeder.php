<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExpedienteSeeder extends Seeder {

    public function run() {

        $expedienteModel = new \App\Models\ExpedienteModel();

        $expedientes = [
            [
                'dia' => 0,
                'dia_descricao' => 'Domingo',
                'abertura' => '18:00:00',
                'fechamento' => '23:00:00',
                'situacao' => 1,
            ],
            [
                'dia' => 1,
                'dia_descricao' => 'Segunda-feira',
                'abertura' => '18:00:00',
                'fechamento' => '23:00:00',
                'situacao' => 1,
            ],
            [
                'dia' => 2,
                'dia_descricao' => 'Terça-feira',
                'abertura' => '18:00:00',
                'fechamento' => '23:00:00',
                'situacao' => 1,
            ],
            [
                'dia' => 3,
                'dia_descricao' => 'Quarta-feira',
                'abertura' => '18:00:00',
                'fechamento' => '23:00:00',
                'situacao' => 1,
            ],
            [
                'dia' => 4,
                'dia_descricao' => 'Quinta-feira',
                'abertura' => '18:00:00',
                'fechamento' => '23:00:00',
                'situacao' => 1,
            ],
            [
                'dia' => 5,
                'dia_descricao' => 'Sexta-feira',
                'abertura' => '18:00:00',
                'fechamento' => '23:00:00',
                'situacao' => 1,
            ],
            [
                'dia' => 6,
                'dia_descricao' => 'Sábado',
                'abertura' => '18:00:00',
                'fechamento' => '23:00:00',
                'situacao' => 1,
            ],
        ];
        
        foreach ($expedientes as $expediente){
            $expedienteModel->protect(false)->insert($expediente);
        }
    }
}
