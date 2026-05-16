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
        $db = \Config\Database::connect();

        $dados = [
            'endereco'            => $json->endereco ?? '',
            'cep'                 => $json->cep ?? '',
            'numero'              => $json->numero ?? '',
            'whatsapp'            => $json->whatsapp ?? '',
            'email'               => $json->email ?? '',
            'instagram'           => $json->instagram ?? '',
            'facebook'            => $json->facebook ?? '',
            'preco_minimo_compra' => $json->preco_minimo_compra ?? 0,
            'entrega_ate'         => $json->entrega_ate ?? 0,
            'modo_cadastro'       => (int)($json->modo_cadastro ?? 1),
            'updated_at'          => date('Y-m-d H:i:s'),
        ];

        // Incluir negociacao_entrega apenas se a coluna existir
        $colunas = $db->getFieldNames('dados_corporativos');
        if (in_array('negociacao_entrega', $colunas)) {
            $dados['negociacao_entrega'] = (int)($json->negociacao_entrega ?? 0);
        }

        try {
            $existe = $db->table('dados_corporativos')->where('id', 1)->get()->getRow();

            if (!$existe) {
                $dados['id'] = 1;
                $dados['created_at'] = date('Y-m-d H:i:s');
                $db->table('dados_corporativos')->insert($dados);
            } else {
                $db->table('dados_corporativos')->where('id', 1)->update($dados);
            }

            \Config\Services::cache()->delete('dados_corporativos');
            return $this->response->setJSON(['sucesso' => true, 'msg' => 'Dados atualizados com sucesso']);
        } catch (\Exception $e) {
            log_message('error', 'DadosCorporativos::atualizar - ' . $e->getMessage());
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }
    }
}
