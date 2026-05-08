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
        
        // Usar todos os campos necessários
        $dados = [
            'endereco' => $json->endereco ?? '',
            'cep' => $json->cep ?? '',
            'numero' => $json->numero ?? '',
            'whatsapp' => $json->whatsapp ?? '',
            'email' => $json->email ?? '',
            'instagram' => $json->instagram ?? '',
            'facebook' => $json->facebook ?? '',
            'preco_minimo_compra' => $json->preco_minimo_compra ?? 0,
            'entrega_ate' => $json->entrega_ate ?? 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
        
        try {
            // Verificar se registro existe
            $existe = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();
            
            if (!$existe) {
                // Criar registro se não existir
                $dados['id'] = 1;
                $dados['created_at'] = date('Y-m-d H:i:s');
                $resultado = $db->table('dados_corporativos')->insert($dados);
            } else {
                // Atualizar registro existente
                $resultado = $db->table('dados_corporativos')
                               ->where('id', 1)
                               ->update($dados);
            }

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
