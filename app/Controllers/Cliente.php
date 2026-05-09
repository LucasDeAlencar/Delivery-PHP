<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Cliente extends BaseController
{
    public function dados_sessao()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['sucesso' => false]);
        }
        $clienteId = session()->get('cliente_id');
        if (!$clienteId) {
            return $this->response->setJSON(['sucesso' => false, 'msg' => 'Não logado']);
        }
        $db = \Config\Database::connect();
        $cliente = $db->table('clientes')->where('id', $clienteId)->get()->getRowArray();
        if (!$cliente) {
            return $this->response->setJSON(['sucesso' => false, 'msg' => 'Cliente não encontrado']);
        }
        return $this->response->setJSON(['sucesso' => true, 'cliente' => $cliente]);
    }

    public function logout()
    {
        session()->remove(['cliente_id', 'cliente_nome', 'cliente_telefone', 'cliente_email']);
        return redirect()->to('/');
    }

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
            'cep' => preg_replace("/[^0-9]/", "", $json->cep ?? ''),
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

    public function atualizar_endereco()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }

        $json = $this->request->getJSON();
        $clienteId = session()->get('cliente_id');

        if (!$clienteId) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Usuário não logado']);
        }

        $dados = [
            'Cidade'     => $json->cidade ?? '',
            'Bairro'     => $json->bairro_nome ?? '',
            'Endereco'   => $json->endereco ?? '',
            'Numero'     => $json->numero ?? '',
            'complemento'=> $json->complemento ?? ''
        ];

        $db = \Config\Database::connect();
        try {
            $db->table('clientes')->where('id', $clienteId)->update($dados);
            return $this->response->setJSON(['sucesso' => true, 'msg' => 'Endereço atualizado']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro: '.$e->getMessage()]);
        }
    }

    public function endereco_atual()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true]);
        }
        $clienteId = session()->get('cliente_id');
        if (!$clienteId) {
            return $this->response->setJSON(['sucesso' => false]);
        }
        $db = \Config\Database::connect();
        $cliente = $db->table('clientes')->select('Endereco, Numero, Bairro, Cidade, complemento')->where('id', $clienteId)->get()->getRowArray();
        return $this->response->setJSON(['sucesso' => true, 'endereco' => $cliente]);
    }

    public function telefone()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Requisição inválida']);
        }

        $json = $this->request->getJSON();
        $email = $json->email ?? '';

        if (empty($email)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email não informado']);
        }

        $db = \Config\Database::connect();
        $cliente = $db->query("SELECT telefone, nome FROM clientes WHERE email = ?", [$email])->getRow();

        if ($cliente) {
            return $this->response->setJSON([
                'success' => true,
                'telefone' => $cliente->telefone ?? '',
                'nome' => $cliente->nome ?? ''
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Cliente não encontrado']);
    }
}
