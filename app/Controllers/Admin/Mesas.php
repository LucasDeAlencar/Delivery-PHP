<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Mesas extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $config = $db->table('configuracao_mesas')->where('id', 1)->get()->getRow();
        if (!$config) {
            $db->table('configuracao_mesas')->insert(['id' => 1, 'sistema_ativo' => 0, 'created_at' => date('Y-m-d H:i:s')]);
            $config = (object)['id' => 1, 'sistema_ativo' => 0];
        }

        $mesas = $db->table('mesas')->orderBy('numero', 'ASC')->get()->getResult();

        return view('Admin/Mesas/index', [
            'titulo' => 'Gerenciar Mesas',
            'config' => $config,
            'mesas' => $mesas,
        ]);
    }

    public function atualizarConfig()
    {
        if (!$this->request->isAJAX()) return redirect()->back();
        $json = $this->request->getJSON();
        $db = \Config\Database::connect();

        $dados = ['updated_at' => date('Y-m-d H:i:s')];
        if (isset($json->sistema_ativo))              $dados['sistema_ativo']              = (int)$json->sistema_ativo;
        if (isset($json->sistema_chamada))            $dados['sistema_chamada']            = (int)$json->sistema_chamada;
        if (isset($json->mostrar_capacidade_carrinho)) $dados['mostrar_capacidade_carrinho'] = (int)$json->mostrar_capacidade_carrinho;

        $existe = $db->table('configuracao_mesas')->where('id', 1)->get()->getRow();
        if (!$existe) {
            $dados['id'] = 1;
            $dados['created_at'] = date('Y-m-d H:i:s');
            $db->table('configuracao_mesas')->insert($dados);
        } else {
            $db->table('configuracao_mesas')->where('id', 1)->update($dados);
        }

        return $this->response->setJSON(['sucesso' => true]);
    }

    /** Próximo número disponível */
    private function proximoNumero($db): int
    {
        $max = $db->table('mesas')->selectMax('numero')->get()->getRow();
        return $max && $max->numero ? (int)$max->numero + 1 : 1;
    }

    public function criar()
    {
        if (!$this->request->isAJAX()) return redirect()->back();
        $json = $this->request->getJSON();
        $db = \Config\Database::connect();

        $numero = $this->proximoNumero($db);
        try {
            $db->table('mesas')->insert([
                'numero'     => $numero,
                'slug'       => 'mesa-' . $numero,
                'capacidade' => $json->capacidade ?? 4,
                'ativo'      => 1,
                'ocupado'    => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->response->setJSON(['sucesso' => true, 'msg' => "Mesa {$numero} criada"]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao criar mesa']);
        }
    }

    public function criarSerie()
    {
        if (!$this->request->isAJAX()) return redirect()->back();
        $json = $this->request->getJSON();
        $db = \Config\Database::connect();

        $quantidade = max(1, (int)($json->quantidade ?? 1));
        $capacidade = (int)($json->capacidade ?? 4);

        try {
            for ($i = 0; $i < $quantidade; $i++) {
                $numero = $this->proximoNumero($db);
                $db->table('mesas')->insert([
                    'numero'     => $numero,
                    'slug'       => 'mesa-' . $numero,
                    'capacidade' => $capacidade,
                    'ativo'      => 1,
                    'ocupado'    => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            return $this->response->setJSON(['sucesso' => true, 'msg' => "{$quantidade} mesas criadas"]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao criar série']);
        }
    }

    public function atualizar()
    {
        if (!$this->request->isAJAX()) return redirect()->back();
        $json = $this->request->getJSON();
        $db = \Config\Database::connect();

        try {
            $dados = [
                'capacidade'          => $json->capacidade,
                'ativo'               => $json->ativo,
                'updated_at'          => date('Y-m-d H:i:s'),
            ];
            if (isset($json->mostrar_no_carrinho)) {
                $dados['mostrar_no_carrinho'] = (int)$json->mostrar_no_carrinho;
            }
            $db->table('mesas')->where('id', $json->id)->update($dados);
            return $this->response->setJSON(['sucesso' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao atualizar']);
        }
    }

    public function excluir()
    {
        if (!$this->request->isAJAX()) return redirect()->back();
        $json = $this->request->getJSON();
        $db = \Config\Database::connect();

        try {
            $db->table('mesas')->where('id', $json->id)->delete();
            return $this->response->setJSON(['sucesso' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao excluir']);
        }
    }

    public function liberar()
    {
        if (!$this->request->isAJAX()) return redirect()->back();
        $json = $this->request->getJSON();
        $db = \Config\Database::connect();

        try {
            $mesa = $db->table('mesas')->where('id', $json->id)->get()->getRow();
            if ($mesa && $mesa->pedido_id) {
                $db->table('pedidos')->where('id', $mesa->pedido_id)->update(['status' => 'finalizado']);
            }
            $db->table('mesas')->where('id', $json->id)->update([
                'ocupado'    => 0,
                'pedido_id'  => null,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->response->setJSON(['sucesso' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao liberar']);
        }
    }

    public function ocupar()
    {
        if (!$this->request->isAJAX()) return redirect()->back();
        $json = $this->request->getJSON();
        $id = (int)($json->id ?? 0);
        if (!$id) return $this->response->setJSON(['erro' => true, 'msg' => 'ID inválido']);
        $db = \Config\Database::connect();

        try {
            $db->table('mesas')->where('id', $id)->update([
                'ocupado'    => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->response->setJSON(['sucesso' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao ocupar']);
        }
    }

    public function status()
    {
        $db = \Config\Database::connect();
        $mesas = $db->table('mesas')->orderBy('numero', 'ASC')->get()->getResult();
        return $this->response->setJSON(['sucesso' => true, 'mesas' => $mesas]);
    }
}