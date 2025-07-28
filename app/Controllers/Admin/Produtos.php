<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Entities\Produto;

class Produtos extends BaseController {

    private $produtoModel;
    private $categoriaModel;
    
    private $extraModel;
    private $produtoExtraModel;
    
    private $medidaModel;
    private $produtoEspecificacaoModel;

    public function __construct() {
        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->categoriaModel = new \App\Models\CategoriaModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoExtraModel = new \App\Models\ProdutoExtraModel();
        $this->medidaModel = new \App\Models\MedidaModel();
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
    }

    public function index() {

        $data = [
            'titulo' => 'Listando os produtos',
            'produtos' => $this->produtoModel
                    ->select('produtos.*, categorias.nome AS categoria')
                    ->join('categorias', 'categorias.id = produtos.categoria_id', 'left')
                    ->withDeleted(true)
                    ->paginate(10),
            'pager' => $this->produtoModel->pager,
        ];

        return view('Admin/Produtos/index', $data);
    }

    private function buscaProdutoOu404(int $id = null) {
        if (!$id || !$produto = $this->produtoModel
                ->select('produtos.*, categorias.nome AS categoria')
                ->join('categorias', 'categorias.id = produtos.categoria_id', 'left')
                ->withDeleted(true)
                ->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o produto $id");
        }

        return $produto;
    }

    public function show($id) {
        // Busca a produto específica pelo ID
        $produto = $this->buscaProdutoOu404($id);

        // Verifica se a produto existe
        if (!$produto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produto não encontrada');
        }

        $data = [
            'titulo' => 'Detalhando o produto ' . $produto->nome,
            'produto' => $produto
        ];

        return view('Admin/Produtos/show', $data);
    }

    public function criar() {
        $produto = new \App\Entities\Produto();

        $data = [
            'titulo' => 'Criando novo produto',
            'produto' => $produto,
            'categorias' => $this->categoriaModel->where('ativo', true)->findAll(),
        ];

        return view('Admin/Produtos/criar', $data);
    }

    public function cadastrar() {
        // Pega os dados do formulário
        $dadosProduto = $this->request->getPost();

        // Trata o campo ativo (checkbox) - se não foi enviado, define como 0
        $dadosProduto['ativo'] = isset($dadosProduto['ativo']) ? 1 : 0;

        // Processa upload da imagem
        $imagem = $this->request->getFile('imagem');
        if ($imagem && $imagem->isValid() && !$imagem->hasMoved()) {
            // Gera nome único para a imagem
            $nomeImagem = $imagem->getRandomName();

            // Move a imagem para a pasta de uploads
            if ($imagem->move(FCPATH . 'uploads/produtos/', $nomeImagem)) {
                $dadosProduto['imagem'] = $nomeImagem;
            }
        }

        // Tenta salvar o produto
        if ($produtoId = $this->produtoModel->criarProduto($dadosProduto)) {
            return redirect()->to(site_url('admin/produtos'))
                            ->with('sucesso', 'Produto criado com sucesso!');
        }

        // Se houver erros, volta para o formulário
        return redirect()->back()
                        ->withInput()
                        ->with('errors_model', $this->produtoModel->errors())
                        ->with('atencao', 'Verifique os erros abaixo e tente novamente.');
    }

    public function editar($id) {
        // Busca a produto específica pelo ID
        $produto = $this->buscaProdutoOu404($id);

        // Verifica se a produto existe
        if (!$produto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produto não encontrada');
        }

        $data = [
            'titulo' => 'Editando o produto ' . $produto->nome,
            'produto' => $produto,
            'categorias' => $this->categoriaModel->where('ativo', true)->findAll(),
        ];

        return view('Admin/Produtos/editar', $data);
    }

    public function atualizar($id) {
        // Busca o produto
        $produto = $this->buscaProdutoOu404($id);

        // Verifica se o produto existe
        if (!$produto) {
            return redirect()->back()->with('atencao', 'Produto não encontrado.');
        }

        // Pega os dados do formulário
        $dadosProduto = $this->request->getPost();

        // Trata o campo ativo (checkbox) - se não foi enviado, define como 0
        $dadosProduto['ativo'] = isset($dadosProduto['ativo']) ? 1 : 0;

        // Processa upload da imagem (se uma nova foi enviada)
        $imagem = $this->request->getFile('imagem');
        if ($imagem && $imagem->isValid() && !$imagem->hasMoved()) {
            // Remove a imagem antiga se existir
            if (!empty($produto->imagem)) {
                $caminhoImagemAntiga = FCPATH . 'uploads/produtos/' . $produto->imagem;
                if (file_exists($caminhoImagemAntiga)) {
                    unlink($caminhoImagemAntiga);
                }
            }

            // Gera nome único para a nova imagem
            $nomeImagem = $imagem->getRandomName();

            // Move a nova imagem para a pasta de uploads
            if ($imagem->move(FCPATH . 'uploads/produtos/', $nomeImagem)) {
                $dadosProduto['imagem'] = $nomeImagem;
            }
        }

        // Adiciona o ID aos dados para a validação
        $dadosProduto['id'] = $id;

        // Tenta atualizar o produto
        if ($this->produtoModel->atualizarProduto($dadosProduto)) {
            return redirect()->to(site_url("admin/produtos/show/$id"))
                            ->with('sucesso', 'Produto atualizado com sucesso!');
        }

        // Se houver erros, volta para o formulário
        return redirect()->back()
                        ->withInput()
                        ->with('errors_model', $this->produtoModel->errors())
                        ->with('atencao', 'Verifique os erros abaixo e tente novamente.');
    }

    public function extras($id) {
        // Busca a produto específica pelo ID
        $produto = $this->buscaProdutoOu404($id);

        // Verifica se a produto existe
        if (!$produto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produto não encontrada');
        }

        $data = [
            'titulo' => 'Gerenciar os extras ' . $produto->nome,
            'produto' => $produto,
            'categorias' => $this->categoriaModel->where('ativo', true)->findAll(),
            'extras' => $this->extraModel->where('ativo', true)->findAll(),
            'produtosExtras' => $this->produtoExtraModel->buscaExtrasDoProduto($produto->id)
        ];

        return view('Admin/Produtos/extras', $data);
    }

    public function salvarExtras($id) {
        // Busca o produto
        $produto = $this->buscaProdutoOu404($id);

        if (!$produto) {
            return redirect()->back()->with('erro', 'Produto não encontrado.');
        }

        // Pega os extras selecionados
        $extrasSelecionados = $this->request->getPost('extras') ?? [];

        // Remove todos os extras atuais do produto
        $this->produtoExtraModel->where('produto_id', $id)->delete();

        // Adiciona os novos extras selecionados
        $sucessos = 0;
        foreach ($extrasSelecionados as $extraId) {
            $dados = [
                'produto_id' => $id,
                'extra_id' => $extraId
            ];

            if ($this->produtoExtraModel->insert($dados)) {
                $sucessos++;
            }
        }

        // Mensagem de sucesso
        if (count($extrasSelecionados) === 0) {
            $mensagem = 'Todos os extras foram removidos do produto.';
        } else {
            $mensagem = "$sucessos extra(s) associado(s) ao produto com sucesso.";
        }

        return redirect()->to(site_url("admin/produtos/extras/$id"))
                        ->with('sucesso', $mensagem);
    }

    public function excluir($id) {
        // Busca o produto específico pelo ID
        $produto = $this->buscaProdutoOu404($id);

        // Verifica se o produto existe
        if (!$produto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produto não encontrado');
        }

        $data = [
            'titulo' => 'Confirmar Exclusão do Produto',
            'produto' => $produto
        ];

        return view('Admin/Produtos/excluir', $data);
    }

    public function deletar($id = null) {
        if ($this->request->getMethod() !== 'post') {
            $produto = $this->buscaProdutoOu404($id);

            if (!$produto) {
                return redirect()->back()->with('erro', 'Produto não encontrado');
            }

            // Remove a imagem se existir
            if (!empty($produto->imagem)) {
                $caminhoImagem = FCPATH . 'uploads/produtos/' . $produto->imagem;
                if (file_exists($caminhoImagem)) {
                    unlink($caminhoImagem);
                }
            }

            // Remove os extras associados
            $this->produtoExtraModel->where('produto_id', $id)->delete();

            // Usa soft delete
            if ($this->produtoModel->delete($id)) {
                return redirect()->to(site_url('admin/produtos'))
                                ->with('sucesso', "Produto $produto->nome excluído com sucesso");
            } else {
                return redirect()->back()->with('erro', 'Não foi possível excluir o produto.');
            }
        } else {
            return redirect()->back();
        }
    }

    public function desfazerExclusao($id = null) {
        $produto = $this->produtoModel->withDeleted(true)->find($id);
        if (!$produto) {
            return redirect()->back()->with('erro', 'Produto não encontrado.');
        }
        if ($produto->deletado_em === null) {
            return redirect()->back()->with('info', 'Apenas produtos excluídos podem ser recuperados.');
        }

        // Restaura o produto
        $db = \Config\Database::connect();
        $result = $db->table('produtos')
                ->where('id', $id)
                ->update(['deletado_em' => null]);

        if ($result) {
            return redirect()->to(site_url('admin/produtos/show/' . $id))
                            ->with('sucesso', 'Produto restaurado com sucesso.');
        } else {
            return redirect()->back()->with('erro', 'Não foi possível restaurar o produto.');
        }
    }
    
    public function especificacoes($id = null){
        
        $produto = $this->buscaProdutoOu404($id);
        
        $data = [
            'titulo' => 'Gerenciar as especificações do produto '. $produto->nome,
            'produto' => $produto,
            'medidas' => $this->medidaModel->where('ativo', true)->findAll(),
            'produtoEspecificacoes' => $this->produtoEspecificacaoModel->buscaEspecificacoesDoProduto($produto->id),
        ];
        
        return view('Admin/Produtos/especificacoes', $data);
    }

    public function salvarEspecificacoes($id) {
        // Busca o produto
        $produto = $this->buscaProdutoOu404($id);

        if (!$produto) {
            return redirect()->back()->with('erro', 'Produto não encontrado.');
        }

        // Pega as especificações enviadas
        $especificacoes = $this->request->getPost('especificacoes') ?? [];

        // Remove todas as especificações atuais do produto
        $this->produtoEspecificacaoModel->where('produto_id', $id)->delete();

        // Adiciona as novas especificações
        $sucessos = 0;
        $erros = [];
        
        foreach ($especificacoes as $especificacao) {
            if (!empty($especificacao['medida_id']) && !empty($especificacao['preco'])) {
                // Validar se o preço é numérico e maior que 0
                $preco = floatval($especificacao['preco']);
                if ($preco <= 0) {
                    $erros[] = 'O preço deve ser maior que zero.';
                    continue;
                }
                
                $dados = [
                    'produto_id' => $id,
                    'medida_id' => intval($especificacao['medida_id']),
                    'preco' => $preco,
                    'customizavel' => isset($especificacao['customizavel']) ? 1 : 0
                ];
                
                if ($this->produtoEspecificacaoModel->insert($dados)) {
                    $sucessos++;
                } else {
                    $erros = array_merge($erros, $this->produtoEspecificacaoModel->errors());
                }
            }
        }
        
        // Se houver erros, retorna com mensagem de erro
        if (!empty($erros)) {
            return redirect()->back()
                            ->with('errors_model', $erros)
                            ->with('atencao', 'Alguns erros foram encontrados ao salvar as especificações.');
        }

        // Mensagem de sucesso
        if ($sucessos === 0) {
            $mensagem = 'Todas as especificações foram removidas do produto.';
        } else {
            $mensagem = "$sucessos especificação(ões) associada(s) ao produto com sucesso.";
        }

        return redirect()->to(site_url("admin/produtos/especificacoes/$id"))
                        ->with('sucesso', $mensagem);
    }
}
