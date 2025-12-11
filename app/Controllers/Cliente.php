<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Cliente extends BaseController
{
    public function dados()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }

        $json = $this->request->getJSON();
        $email = $json->email ?? '';

        if (empty($email)) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Email não informado']);
        }

        $db = \Config\Database::connect();
        $cliente = $db->query("SELECT * FROM clientes WHERE email = ?", [$email])->getRow();

        if ($cliente) {
            return $this->response->setJSON([
                'sucesso' => true,
                'cliente' => $cliente
            ]);
        }

        return $this->response->setJSON(['erro' => true, 'msg' => 'Cliente não encontrado']);
    }

    public function atualizar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }

        $json = $this->request->getJSON();
        $email = $json->email ?? '';

        if (empty($email)) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Email não informado']);
        }

        $dados = [
            'telefone' => $json->telefone ?? '',
            'cep' => $json->cep ?? '',
            'Cidade' => $json->cidade ?? '',
            'Bairro' => $json->bairro ?? '',
            'Endereco' => $json->endereco ?? '',
            'Numero' => (int)($json->numero ?? 0),
            'complemento' => $json->complemento ?? ''
        ];

        // Validações básicas
        if (empty($dados['telefone'])) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Telefone é obrigatório']);
        }

        $db = \Config\Database::connect();
        
        try {
            $resultado = $db->table('clientes')
                           ->where('email', $email)
                           ->update($dados);

            if ($resultado) {
                return $this->response->setJSON(['sucesso' => true, 'msg' => 'Dados atualizados com sucesso']);
            } else {
                return $this->response->setJSON(['erro' => true, 'msg' => 'Nenhuma alteração foi feita']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao atualizar dados']);
        }
    }
}
