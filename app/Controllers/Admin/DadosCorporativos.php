<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DadosCorporativos extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $dados = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
        
        $data = [
            'titulo' => 'Dados Corporativos',
            'dados' => $dados
        ];

        return view('Admin/DadosCorporativos/index', $data);
    }

    public function atualizar()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('erro', 'Requisição inválida');
        }

        $json = $this->request->getJSON();
        
        $dados = [
            'endereco' => $json->endereco ?? '',
            'numero' => $json->numero ?? '',
            'email' => $json->email ?? '',
            'instagram' => $json->instagram ?? '',
            'twitter' => $json->twitter ?? '',
            'facebook' => $json->facebook ?? '',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
        
        try {
            $resultado = $db->table('dados_corporativos')
                           ->where('id', 1)
                           ->update($dados);

            if ($resultado !== false) {
                return $this->response->setJSON(['sucesso' => true, 'msg' => 'Dados atualizados com sucesso']);
            } else {
                return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao atualizar dados']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro no banco de dados']);
        }
    }
}
