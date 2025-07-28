<?php

namespace App\Validacoes;

class MinhasValidacoes
{
    /**
     * Exemplo de validação de CPF
     *
     * @param string $str
     * @param string|null $error
     * @return bool
     */
    public function validaCpf(string $str, string &$error = null): bool
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/\D/', '', $str);

        // Verifica se tem 11 dígitos
        if (strlen($cpf) !== 11) {
            $error = 'CPF deve conter 11 dígitos.';
            return false;
        }

        // Elimina CPFs inválidos conhecidos
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            $error = 'CPF inválido.';
            return false;
        }

        // Calcula e verifica os dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $error = 'CPF inválido.';
                return false;
            }
        }

        return true;
    }
}
