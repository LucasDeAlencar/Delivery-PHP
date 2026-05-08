<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Saches extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $saches = $this->db->query("
            SELECT s.*, GROUP_CONCAT(c.nome ORDER BY c.nome SEPARATOR ', ') AS categorias_nomes
            FROM saches s
            LEFT JOIN saches_categorias sc ON sc.sache_id = s.id
            LEFT JOIN categorias c ON c.id = sc.categoria_id
            GROUP BY s.id
            ORDER BY s.categoria_sache, s.ordem, s.nome
        ")->getResultArray();

        $categorias = $this->db->table('categorias')->where('ativo', 1)->orderBy('nome')->get()->getResultArray();
        $grupos     = $this->db->table('saches_grupos')->orderBy('ordem')->orderBy('nome')->get()->getResultArray();

        return view('Admin/Saches/index', [
            'titulo'    => 'Sachês',
            'saches'    => $saches,
            'categorias'=> $categorias,
            'grupos'    => $grupos,
        ]);
    }

    public function salvar()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $json = $this->request->getJSON(true);
        $id   = $json['id'] ?? null;

        $limiteTipo = $json['limite_tipo'] ?? 'fixo';

        $dados = [
            'nome'             => trim($json['nome'] ?? ''),
            'categoria_sache'  => trim($json['categoria_sache'] ?? '') ?: null,
            'preco'            => (float)($json['preco'] ?? 0),
            'ativo'            => isset($json['ativo']) ? (int)$json['ativo'] : 1,
            'limite_tipo'      => $limiteTipo,
            'limite_fixo'      => $limiteTipo === 'fixo' ? (int)($json['limite_fixo'] ?? 1) : null,
            'limite_por_valor' => $limiteTipo === 'personalizado' ? (float)($json['limite_por_valor'] ?? 0) : null,
            'limite_minimo'    => $limiteTipo === 'personalizado' ? (int)($json['limite_minimo'] ?? 0) : null,
            'atualizado_em'    => date('Y-m-d H:i:s'),
        ];

        if (empty($dados['nome'])) {
            return $this->response->setJSON(['erro' => 'Nome obrigatório']);
        }

        try {
            if ($id) {
                $this->db->table('saches')->where('id', $id)->update($dados);
            } else {
                $dados['criado_em'] = date('Y-m-d H:i:s');
                $this->db->table('saches')->insert($dados);
                $id = $this->db->insertID();
            }

            $this->db->table('saches_categorias')->where('sache_id', $id)->delete();
            foreach ($json['categorias'] ?? [] as $catId) {
                $this->db->table('saches_categorias')->insert(['sache_id' => $id, 'categoria_id' => (int)$catId]);
            }

            return $this->response->setJSON(['sucesso' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => $e->getMessage()]);
        }
    }

    public function toggleAtivo($id)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $sache = $this->db->table('saches')->where('id', $id)->get()->getRowArray();
        if (!$sache) return $this->response->setJSON(['erro' => 'Não encontrado']);

        $novoAtivo = $sache['ativo'] ? 0 : 1;
        $this->db->table('saches')->where('id', $id)->update(['ativo' => $novoAtivo, 'atualizado_em' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON(['sucesso' => true, 'ativo' => $novoAtivo]);
    }

    public function excluir($id)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $this->db->table('saches_categorias')->where('sache_id', $id)->delete();
        $this->db->table('saches')->where('id', $id)->delete();

        return $this->response->setJSON(['sucesso' => true]);
    }

    public function salvarGrupo()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $json      = $this->request->getJSON(true);
        $original  = trim($json['original'] ?? '');
        $novo      = trim($json['novo'] ?? '');
        $qtdInicial = max(0, (int)($json['qtd_inicial'] ?? 1));
        $qtdMax    = max(0, (int)($json['qtd_max'] ?? 0)) ?: null;

        if (empty($novo)) {
            return $this->response->setJSON(['erro' => 'Nome obrigatório']);
        }

        try {
            if ($original === '') {
                $existe = $this->db->table('saches_grupos')->where('nome', $novo)->countAllResults();
                if ($existe) return $this->response->setJSON(['erro' => 'Grupo já existe']);
                $this->db->table('saches_grupos')->insert([
                    'nome' => $novo, 'qtd_inicial' => $qtdInicial, 'qtd_max' => $qtdMax,
                ]);
            } else {
                $this->db->table('saches_grupos')->where('nome', $original)->update([
                    'nome' => $novo, 'qtd_inicial' => $qtdInicial, 'qtd_max' => $qtdMax,
                ]);
                $this->db->table('saches')
                    ->where('categoria_sache', $original)
                    ->update(['categoria_sache' => $novo, 'atualizado_em' => date('Y-m-d H:i:s')]);
            }

            return $this->response->setJSON(['sucesso' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => $e->getMessage()]);
        }
    }

    public function excluirGrupo()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $json = $this->request->getJSON(true);
        $nome = trim($json['nome'] ?? '');

        if (empty($nome)) {
            return $this->response->setJSON(['erro' => 'Nome obrigatório']);
        }

        try {
            $this->db->table('saches_grupos')->where('nome', $nome)->delete();
            $this->db->table('saches')
                ->where('categoria_sache', $nome)
                ->update(['categoria_sache' => null, 'atualizado_em' => date('Y-m-d H:i:s')]);

            return $this->response->setJSON(['sucesso' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => $e->getMessage()]);
        }
    }

    public function reordenar()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $json  = $this->request->getJSON(true);
        $tipo  = $json['tipo'] ?? ''; // 'grupos' ou 'saches'
        $ids   = $json['ids'] ?? [];  // array de IDs na nova ordem

        try {
            $tabela = $tipo === 'grupos' ? 'saches_grupos' : 'saches';
            foreach ($ids as $ordem => $id) {
                $this->db->table($tabela)->where('id', (int)$id)->update(['ordem' => $ordem + 1]);
            }
            return $this->response->setJSON(['sucesso' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => $e->getMessage()]);
        }
    }

    public function get($id)
    {
        $sache = $this->db->table('saches')->where('id', $id)->get()->getRowArray();
        if (!$sache) return $this->response->setJSON(['erro' => 'Não encontrado']);

        $sache['categorias'] = array_column(
            $this->db->table('saches_categorias')->where('sache_id', $id)->get()->getResultArray(),
            'categoria_id'
        );

        return $this->response->setJSON($sache);
    }
}
