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

    public function __construct() {
        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->categoriaModel = new \App\Models\CategoriaModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoExtraModel = new \App\Models\ProdutoExtraModel();
    }

    public function index() {
        $search = $this->request->getGet('search');
        $categoria_filtro = $this->request->getGet('categoria');
        
        $builder = $this->produtoModel
            ->select('produtos.*, categorias.nome AS categoria')
            ->join('categorias', 'categorias.id = produtos.categoria_id', 'left');
        
        if ($search) {
            $builder->like('produtos.nome', $search);
        }
        
        if ($categoria_filtro) {
            $builder->where('produtos.categoria_id', $categoria_filtro);
        }

        $data = [
            'titulo' => 'Listando os produtos',
            'produtos' => $builder->paginate(10),
            'pager' => $this->produtoModel->pager,
            'categorias' => $this->categoriaModel->orderBy('ordem', 'ASC')->orderBy('nome', 'ASC')->findAll(),
            'search' => $search,
            'categoria_filtro' => $categoria_filtro,
        ];

        return view('Admin/Produtos/index', $data);
    }

    private function buscaProdutoOu404(int $id = null) {
        if (!$id || !$produto = $this->produtoModel
                ->select('produtos.*, categorias.nome AS categoria')
                ->join('categorias', 'categorias.id = produtos.categoria_id', 'left')
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
        // Verifica se é uma requisição POST
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->back()->with('atencao', 'Método não permitido');
        }

        // Pega os dados do formulário
        $dadosProduto = $this->request->getPost();

        // Log para debug - remover depois
        log_message('debug', 'Dados do produto recebidos: ' . json_encode($dadosProduto));
        // Trata o campo ativo (checkbox) - se não foi enviado, define como 0
        $dadosProduto['ativo'] = isset($dadosProduto['ativo']) ? 1 : 0;

        // Processa upload da imagem
        $imagem = $this->request->getFile('imagem');

        // Debug: verifique o arquivo de imagem
        // if ($imagem) {
        //     dd([
        //         'isValid' => $imagem->isValid(),
        //         'hasMoved' => $imagem->hasMoved(),
        //         'getName' => $imagem->getName(),
        //         'getError' => $imagem->getError()
        //     ]);
        // }

        if ($imagem && $imagem->isValid() && !$imagem->hasMoved()) {
            // Garante que o diretório existe
            $diretorio = FCPATH . 'uploads/produtos/';
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }

            // Gera nome único para a imagem
            $nomeImagem = $imagem->getRandomName();

            // Move a imagem para a pasta de uploads
            if ($imagem->move($diretorio, $nomeImagem)) {
                $dadosProduto['imagem'] = $nomeImagem;
            } else {
                // Se o upload falhar, retorna com erro
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['imagem' => 'Erro ao fazer upload da imagem: ' . $imagem->getErrorString()])
                    ->with('atencao', 'Erro ao fazer upload da imagem.');
            }
        } else {
            // Se uma imagem foi enviada mas é inválida
            if ($imagem && !$imagem->isValid()) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['imagem' => 'Erro na imagem: ' . $imagem->getErrorString()])
                    ->with('atencao', 'Arquivo de imagem inválido.');
            }
        }

        // Tenta salvar o produto
        if ($produtoId = $this->produtoModel->criarProduto($dadosProduto)) {
            return redirect()->to(site_url('admin/produtos'))
                            ->with('sucesso', 'Produto criado com sucesso!');
        }

        // Se houver erros, volta para o formulário
        // Obtém os erros de validação do model
        $errors = $this->produtoModel->errors();
        
        // Log dos erros para debug
        log_message('debug', 'Erros de validação: ' . json_encode($errors));
        
        // Se não houver erros específicos, adiciona mensagem genérica
        if (empty($errors)) {
            $errors = ['geral' => 'Erro desconhecido ao salvar o produto. Verifique os dados e tente novamente.'];
        }
        
        // Passa os erros para a sessão no formato que a view espera
        return redirect()->back()
                        ->withInput()
                        ->with('errors', $errors)
                        ->with('errors_model', $errors)
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
        $errors = $this->produtoModel->errors();
        
        return redirect()->back()
                        ->withInput()
                        ->with('errors', $errors)
                        ->with('errors_model', $errors)
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

    public function adicionarExtra($produtoId, $extraId) {
        // Verifica se é uma requisição AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Requisição inválida']);
        }

        // Busca o produto
        $produto = $this->produtoModel->find($produtoId);
        if (!$produto) {
            return $this->response->setJSON(['success' => false, 'message' => 'Produto não encontrado']);
        }

        // Verifica se o extra existe
        $extra = $this->extraModel->find($extraId);
        if (!$extra) {
            return $this->response->setJSON(['success' => false, 'message' => 'Extra não encontrado']);
        }

        // Verifica se já está associado
        $jaAssociado = $this->produtoExtraModel
            ->where('produto_id', $produtoId)
            ->where('extra_id', $extraId)
            ->first();

        if ($jaAssociado) {
            return $this->response->setJSON(['success' => false, 'message' => 'Extra já está associado a este produto']);
        }

        // Adiciona a associação
        $dados = [
            'produto_id' => $produtoId,
            'extra_id' => $extraId
        ];

        if ($this->produtoExtraModel->insert($dados)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Extra adicionado com sucesso']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Erro ao adicionar extra']);
    }

    public function removerExtra($produtoId, $extraId) {
        // Verifica se é uma requisição AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Requisição inválida']);
        }

        // Remove a associação
        $resultado = $this->produtoExtraModel
            ->where('produto_id', $produtoId)
            ->where('extra_id', $extraId)
            ->delete();

        if ($resultado) {
            return $this->response->setJSON(['success' => true, 'message' => 'Extra removido com sucesso']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Erro ao remover extra']);
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
        $produto = $this->buscaProdutoOu404($id);

        if (!$produto) {
            return redirect()->back()->with('erro', 'Produto não encontrado');
        }

        $db = \Config\Database::connect();
        
        try {
            log_message('info', "=== INICIANDO EXCLUSÃO DO PRODUTO ID: {$id} - Nome: {$produto->nome} ===");
            
            // Inicia transação para garantir integridade
            $db->transStart();
            log_message('info', 'Transação iniciada');
            
            // 1. Remove a imagem se existir
            if (!empty($produto->imagem)) {
                $caminhoImagem = FCPATH . 'uploads/produtos/' . $produto->imagem;
                if (file_exists($caminhoImagem)) {
                    unlink($caminhoImagem);
                    log_message('info', "Imagem removida: {$caminhoImagem}");
                } else {
                    log_message('warning', "Imagem não encontrada no servidor: {$caminhoImagem}");
                }
            }

            // 2. Remove os extras associados ao produto (produtos_extras)
            $extrasCount = $db->table('produtos_extras')->where('produto_id', $id)->countAllResults(false);
            $db->table('produtos_extras')->where('produto_id', $id)->delete();
            log_message('info', "Removidos {$extrasCount} extras associados");

            // 3. Remove as especificações associadas (produtos_especificacoes)
            $especCount = $db->table('produtos_especificacoes')->where('produto_id', $id)->countAllResults(false);
            $db->table('produtos_especificacoes')->where('produto_id', $id)->delete();
            log_message('info', "Removidas {$especCount} especificações associadas");

            // 4. Atualiza pedidos_itens para remover referência ao produto (SET NULL)
            $pedidosCount = $db->table('pedidos_itens')->where('produto_id', $id)->countAllResults(false);
            if ($pedidosCount > 0) {
                // Altera a coluna para aceitar NULL
                $db->query("ALTER TABLE pedidos_itens MODIFY produto_id INT(11) UNSIGNED NULL");
                log_message('info', 'Coluna produto_id alterada para aceitar NULL');
                
                $db->query("UPDATE pedidos_itens SET produto_id = NULL WHERE produto_id = ?", [$id]);
                log_message('info', "Atualizados {$pedidosCount} itens de pedidos (produto_id = NULL)");
            }

            // 5. Remove itens do carrinho temporário se existir
            if ($db->tableExists('carrinho_temporario')) {
                $carrinhoCount = $db->table('carrinho_temporario')->where('produto_id', $id)->countAllResults(false);
                $db->table('carrinho_temporario')->where('produto_id', $id)->delete();
                log_message('info', "Removidos {$carrinhoCount} itens do carrinho temporário");
            }

            // 6. Deleta permanentemente o produto
            log_message('info', 'Deletando produto do banco de dados');
            $this->produtoModel->delete($id, true);
            log_message('info', 'Produto deletado com sucesso');
            
            // Confirma transação
            $db->transComplete();
            log_message('info', 'Transação completada');
            
            if ($db->transStatus() === false) {
                log_message('error', 'TRANSAÇÃO FALHOU - transStatus() retornou false');
                throw new \Exception('Falha na transação');
            }
            
            log_message('info', "=== PRODUTO ID: {$id} EXCLUÍDO COM SUCESSO ===");
            
            return redirect()->to(site_url('admin/produtos'))
                            ->with('sucesso', "Produto '{$produto->nome}' excluído com sucesso!");
                            
        } catch (\Exception $e) {
            // Rollback em caso de erro
            $db->transRollback();
            
            log_message('error', "ERRO AO EXCLUIR PRODUTO ID: {$id}");
            log_message('error', 'Mensagem: ' . $e->getMessage());
            log_message('error', 'Arquivo: ' . $e->getFile() . ' - Linha: ' . $e->getLine());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->with('erro', 
                'Erro ao excluir o produto: ' . $e->getMessage()
            );
        }
    }

    public function toggleAtivo() {
        $produtoId = $this->request->getPost('produto_id');
        $ativo = $this->request->getPost('ativo');

        if (!$produtoId) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID do produto não informado']);
        }

        try {
            $produto = $this->produtoModel->find($produtoId);
            
            if (!$produto) {
                return $this->response->setJSON(['success' => false, 'message' => 'Produto não encontrado']);
            }
            
            $novoStatus = $ativo ? 1 : 0;
            $this->produtoModel->update($produtoId, ['ativo' => $novoStatus]);
            
            $status = $novoStatus ? 'ativado' : 'desativado';
            return $this->response->setJSON(['success' => true, 'message' => "Produto {$status} com sucesso"]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
        }
    }

    public function acaoColetiva() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Requisição inválida']);
        }

        $ids = $this->request->getPost('ids');
        $acao = $this->request->getPost('acao');

        if (empty($ids) || !is_array($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nenhum produto selecionado']);
        }

        if (!in_array($acao, ['ativar', 'inativar', 'excluir'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ação inválida']);
        }

        $db = \Config\Database::connect();
        
        try {
            $db->transStart();

            if ($acao === 'ativar') {
                $this->produtoModel->whereIn('id', $ids)->set(['ativo' => 1])->update();
                $mensagem = count($ids) . ' produto(s) ativado(s) com sucesso';
            } elseif ($acao === 'inativar') {
                $this->produtoModel->whereIn('id', $ids)->set(['ativo' => 0])->update();
                $mensagem = count($ids) . ' produto(s) inativado(s) com sucesso';
            } else {
                // Excluir produtos
                foreach ($ids as $id) {
                    $produto = $this->produtoModel->find($id);
                    if ($produto) {
                        // Remove imagem
                        if (!empty($produto->imagem)) {
                            $caminhoImagem = FCPATH . 'uploads/produtos/' . $produto->imagem;
                            if (file_exists($caminhoImagem)) {
                                unlink($caminhoImagem);
                            }
                        }
                        
                        // Remove extras e especificações
                        $db->table('produtos_extras')->where('produto_id', $id)->delete();
                        $db->table('produtos_especificacoes')->where('produto_id', $id)->delete();
                        
                        // Atualiza pedidos_itens
                        $db->query("ALTER TABLE pedidos_itens MODIFY produto_id INT(11) UNSIGNED NULL");
                        $db->query("UPDATE pedidos_itens SET produto_id = NULL WHERE produto_id = ?", [$id]);
                        
                        // Remove do carrinho
                        if ($db->tableExists('carrinho_temporario')) {
                            $db->table('carrinho_temporario')->where('produto_id', $id)->delete();
                        }
                        
                        // Deleta produto
                        $this->produtoModel->delete($id, true);
                    }
                }
                $mensagem = count($ids) . ' produto(s) excluído(s) com sucesso';
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Falha na transação');
            }

            return $this->response->setJSON(['success' => true, 'message' => $mensagem]);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Erro na ação coletiva: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erro ao executar ação: ' . $e->getMessage()]);
        }
    }

}
