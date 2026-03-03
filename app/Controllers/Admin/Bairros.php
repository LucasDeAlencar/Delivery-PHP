<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Entities\Bairro;

class Bairros extends BaseController {
    
    private $bairroModel;
    
    public function __construct() {
        $this->bairroModel = new \App\Models\BairroModel();
    }

    public function index() {
        $db = \Config\Database::connect();
        
        // Carrega configuração de entrega
        $configModel = new \App\Models\ConfiguracaoEntregaModel();
        $config = $configModel->first();
        
        // Se não existe configuração, cria uma padrão
        if (!$config) {
            $config = (object)[
                'modo_cobranca' => 'bairro',
                'taxa_por_km' => 0,
                'taxa_minima' => 0,
                'distancia_maxima' => 0,
                'cep_loja' => ''
            ];
        }
        
        $data = [
            'titulo'  => "Listando os bairros atendidos",
            'bairros' => $this->bairroModel->withDeleted(true)->paginate(10),
            'pager' => $this->bairroModel->pager,
            'configuracao' => $config
        ];
        
        return view('Admin/Bairros/index', $data);
    }

    public function criar() {
        $bairro = new Bairro();
        
        $data = [
            'titulo' => 'Criando novo bairro',
            'bairro' => $bairro,
        ];
        
        return view('Admin/Bairros/criar', $data);
    }

    public function cadastrar() {
        // Filtrar apenas os campos permitidos (CEP não é salvo no banco)
        $dadosPost = $this->request->getPost();
        $dadosLimpos = [];
        
        // Campos permitidos
        $camposPermitidos = ['nome', 'cidade', 'valor_entrega', 'ativo'];
        
        foreach ($camposPermitidos as $campo) {
            if (isset($dadosPost[$campo])) {
                $dadosLimpos[$campo] = $dadosPost[$campo];
            }
        }
        
        // Garantir que ativo seja 0 se não foi enviado (checkbox desmarcado)
        if (!isset($dadosLimpos['ativo'])) {
            $dadosLimpos['ativo'] = 0;
        }
        
        // Converter valor de entrega do formato brasileiro para decimal
        if (isset($dadosLimpos['valor_entrega'])) {
            $dadosLimpos['valor_entrega'] = $this->converterValorParaDecimal($dadosLimpos['valor_entrega']);
        }

        // Verificar duplicação de bairro
        if (isset($dadosLimpos['nome'])) {
            $bairroExistente = $this->verificarBairroDuplicado($dadosLimpos['nome'], $dadosLimpos['cidade'] ?? null);
            if ($bairroExistente) {
                return redirect()->back()
                               ->withInput()
                               ->with('atencao', "Bairro já existe: {$bairroExistente->nome}");
            }
        }

        $bairro = new Bairro($dadosLimpos);

        if ($this->bairroModel->save($bairro)) {
            return redirect()->to(site_url("admin/bairros/show/" . $this->bairroModel->getInsertID()))
                           ->with('sucesso', 'Bairro criado com sucesso!');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('errors_model', $this->bairroModel->errors())
                           ->with('atencao', 'Por favor, verifique os erros abaixo');
        }
    }

    private function verificarBairroValido($nomeBairro) {
        $bairrosValidos = $this->getBairrosBrasil();
        $nomeNormalizado = $this->normalizarNome($nomeBairro);
        
        foreach ($bairrosValidos as $bairro) {
            if ($this->normalizarNome($bairro) === $nomeNormalizado) {
                return true;
            }
        }
        
        return false;
    }

    private function getBairrosBrasil() {
        return [
            // São Paulo - SP
            'Centro', 'Liberdade', 'Bela Vista', 'Consolação', 'Vila Madalena', 'Pinheiros', 'Itaim Bibi',
            'Moema', 'Ibirapuera', 'Jardim Paulista', 'Higienópolis', 'Santa Cecília', 'Perdizes',
            'Vila Olímpia', 'Brooklin', 'Campo Belo', 'Saúde', 'Vila Mariana', 'Paraíso', 'Aclimação',
            'Cambuci', 'Ipiranga', 'Cursino', 'Sacomã', 'Vila Prudente', 'Tatuapé', 'Mooca',
            'Brás', 'Pari', 'Belém', 'Penha', 'Vila Matilde', 'Aricanduva', 'Carrão', 'Vila Formosa',
            'Anália Franco', 'Tatuapé', 'Água Rasa', 'Vila Esperança', 'Penha de França',
            'Cangaíba', 'Vila Guilherme', 'Vila Maria', 'Vila Medeiros', 'Tucuruvi', 'Santana',
            'Mandaqui', 'Casa Verde', 'Limão', 'Freguesia do Ó', 'Brasilândia', 'Cachoeirinha',
            'Vila Nova Cachoeirinha', 'Jaçanã', 'Tremembé', 'Barra Funda', 'Água Branca', 'Lapa',
            'Vila Leopoldina', 'Jaguaré', 'Rio Pequeno', 'Raposo Tavares', 'Butantã', 'Morumbi',
            'Vila Sônia', 'Jardim Bonfiglioli', 'Jaguara', 'Cidade Universitária', 'Pinheiros',
            'Alto de Pinheiros', 'Boaçava', 'Sumaré', 'Pompéia', 'Vila Romana', 'Vila Anglo',
            
            // Rio de Janeiro - RJ  
            'Copacabana', 'Ipanema', 'Leblon', 'Barra da Tijuca', 'Botafogo', 'Flamengo', 'Catete',
            'Laranjeiras', 'Cosme Velho', 'Santa Teresa', 'Centro', 'Lapa', 'Glória', 'Urca',
            'Tijuca', 'Vila Isabel', 'Grajaú', 'Andaraí', 'Maracanã', 'Praça da Bandeira',
            'São Cristóvão', 'Benfica', 'Mangueira', 'Caju', 'Gamboa', 'Santo Cristo', 'Saúde',
            'Cidade Nova', 'Estácio', 'Rio Comprido', 'Catumbi', 'Humaitá', 'Gávea', 'São Conrado',
            'Recreio dos Bandeirantes', 'Jacarepaguá', 'Taquara', 'Freguesia', 'Pechincha',
            'Curicica', 'Camorim', 'Vargem Grande', 'Vargem Pequena', 'Guaratiba', 'Campo Grande',
            'Santa Cruz', 'Sepetiba', 'Cosmos', 'Inhoaíba', 'Senador Camará', 'Realengo',
            'Padre Miguel', 'Bangu', 'Senador Vasconcelos', 'Santíssimo', 'Campo dos Afonsos',
            
            // Belo Horizonte - MG
            'Centro', 'Savassi', 'Funcionários', 'Lourdes', 'Anchieta', 'Sion', 'Mangabeiras',
            'Belvedere', 'Buritis', 'Estoril', 'Gutierrez', 'Cidade Jardim', 'São Pedro',
            'Santa Efigênia', 'Floresta', 'Lagoinha', 'Carlos Prates', 'Caiçara', 'Padre Eustáquio',
            'São Cristóvão', 'Prado Lopes', 'Jardim Montanhês', 'Planalto', 'Betânia', 'Esplanada',
            'Santa Amélia', 'Havaí', 'Colegiado', 'União', 'Sagrada Família', 'São Geraldo',
            'Pompéia', 'Aparecida', 'Comiteco', 'Boa Vista', 'Horto', 'São Bento', 'Santa Tereza',
            'Carmo', 'Serra', 'Luxemburgo', 'Castelo', 'Coração Eucarístico', 'Jardim América',
            'Nova Suíça', 'Ouro Preto', 'Cruzeiro', 'Barreiro', 'Lindéia', 'Miramar', 'Olhos d\'Água',
            
            // Brasília - DF
            'Asa Norte', 'Asa Sul', 'Lago Norte', 'Lago Sul', 'Sudoeste', 'Noroeste', 'Octogonal',
            'Cruzeiro Novo', 'Cruzeiro Velho', 'Guará I', 'Guará II', 'Águas Claras', 'Vicente Pires',
            'Taguatinga Norte', 'Taguatinga Sul', 'Taguatinga Centro', 'Ceilândia Norte', 'Ceilândia Sul',
            'Samambaia Norte', 'Samambaia Sul', 'Planaltina', 'Sobradinho', 'Sobradinho II',
            'Brazlândia', 'Núcleo Bandeirante', 'Candangolândia', 'Riacho Fundo', 'Riacho Fundo II',
            'Santa Maria', 'Gama', 'Recanto das Emas', 'São Sebastião', 'Jardim Botânico',
            'Itapoã', 'Paranoá', 'Varjão', 'Estrutural', 'Fercal', 'Park Way'
        ];
    }

    private function verificarBairroDuplicado($nomeBairro, $cidade = null) {
        if (!$cidade) {
            return false;
        }
        
        $nomeNormalizado = $this->normalizarNome($nomeBairro);
        $cidadeNormalizada = $this->normalizarNome($cidade);
        
        $bairrosExistentes = $this->bairroModel->findAll();
        
        foreach ($bairrosExistentes as $bairro) {
            if ($this->normalizarNome($bairro->nome) === $nomeNormalizado && 
                $this->normalizarNome($bairro->cidade) === $cidadeNormalizada) {
                return $bairro;
            }
        }
        
        return false;
    }

    private function normalizarNome($nome) {
        // Converter para maiúsculo
        $nome = strtoupper($nome);
        
        // Remover acentos
        $nome = $this->removerAcentos($nome);
        
        // Remover espaços
        $nome = str_replace(' ', '', $nome);
        
        return $nome;
    }

    private function removerAcentos($string) {
        $acentos = [
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
            'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'Ç' => 'C', 'Ñ' => 'N'
        ];
        
        return strtr($string, $acentos);
    }

    public function salvarModoCobranca() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Requisição inválida']);
        }

        $json = $this->request->getJSON();
        $modoCobranca = $json->modo_cobranca ?? 'bairro';

        $db = \Config\Database::connect();
        
        try {
            $resultado = $db->table('configuracoes_sistema')
                           ->where('chave', 'modo_cobranca_entrega')
                           ->update([
                               'valor' => $modoCobranca,
                               'updated_at' => date('Y-m-d H:i:s')
                           ]);

            if ($resultado !== false) {
                return $this->response->setJSON(['sucesso' => true]);
            } else {
                return $this->response->setJSON(['erro' => true, 'msg' => 'Erro ao salvar']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['erro' => true, 'msg' => 'Erro no banco de dados']);
        }
    }

    public function show($id = null) {
        $bairro = $this->buscaBairroOu404($id);
        
        $data = [
            'titulo' => "Detalhando o bairro " . esc($bairro->nome),
            'bairro' => $bairro,
        ];
        
        return view('Admin/Bairros/show', $data);
    }

    public function editar($id = null) {
        $bairro = $this->buscaBairroOu404($id);
        
        $data = [
            'titulo' => "Editando o bairro " . esc($bairro->nome),
            'bairro' => $bairro,
        ];
        
        return view('Admin/Bairros/editar', $data);
    }

    public function atualizar($id = null) {
        $bairro = $this->buscaBairroOu404($id);
        
        // Filtrar apenas os campos permitidos (CEP não é salvo no banco)
        $dadosPost = $this->request->getPost();
        $dadosLimpos = [];
        
        // Campos permitidos
        $camposPermitidos = ['nome', 'cidade', 'valor_entrega', 'ativo'];
        
        foreach ($camposPermitidos as $campo) {
            if (isset($dadosPost[$campo])) {
                $dadosLimpos[$campo] = $dadosPost[$campo];
            }
        }
        
        // Garantir que ativo seja 0 se não foi enviado (checkbox desmarcado)
        if (!isset($dadosLimpos['ativo'])) {
            $dadosLimpos['ativo'] = 0;
        }
        
        // Converter valor de entrega do formato brasileiro para decimal
        if (isset($dadosLimpos['valor_entrega'])) {
            $dadosLimpos['valor_entrega'] = $this->converterValorParaDecimal($dadosLimpos['valor_entrega']);
        }
        
        $bairro->fill($dadosLimpos);

        if (!$bairro->hasChanged()) {
            return redirect()->back()->with('info', 'Não há dados para serem atualizados');
        }

        if ($this->bairroModel->save($bairro)) {
            return redirect()->to(site_url("admin/bairros/show/" . $bairro->id))
                           ->with('sucesso', 'Bairro atualizado com sucesso!');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('errors_model', $this->bairroModel->errors())
                           ->with('atencao', 'Por favor, verifique os erros abaixo');
        }
    }

    public function excluir($id = null) {
        $bairro = $this->buscaBairroOu404($id);
        
        if ($bairro->deletado_em != null) {
            return redirect()->back()->with('info', 'Este bairro já foi excluído anteriormente');
        }
        
        $data = [
            'titulo' => "Excluindo o bairro " . esc($bairro->nome),
            'bairro' => $bairro,
        ];
        
        return view('Admin/Bairros/excluir', $data);
    }

    public function deletar($id = null) {
        $bairro = $this->buscaBairroOu404($id);
        
        if ($bairro->deletado_em != null) {
            return redirect()->back()->with('info', 'Este bairro já foi excluído anteriormente');
        }

        if ($this->bairroModel->delete($id)) {
            return redirect()->to(site_url('admin/bairros'))
                           ->with('sucesso', 'Bairro excluído com sucesso!');
        } else {
            return redirect()->back()
                           ->with('errors_model', $this->bairroModel->errors())
                           ->with('atencao', 'Não foi possível excluir o bairro. Verifique se não há dependências.');
        }
    }

    public function desfazerExclusao($id = null) {
        $bairro = $this->buscaBairroOu404($id);
        
        if ($bairro->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas bairros excluídos podem ser restaurados');
        }

        if ($this->bairroModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', 'Exclusão desfeita com sucesso!');
        } else {
            return redirect()->back()
                           ->with('errors_model', $this->bairroModel->errors())
                           ->with('atencao', 'Não foi possível desfazer a exclusão. Tente novamente.');
        }
    }

    public function deletarDefinitivamente($id = null) {
        $bairro = $this->buscaBairroOu404($id);
        
        if ($bairro->deletado_em == null) {
            return redirect()->back()->with('atencao', 'Apenas bairros excluídos podem ser apagados definitivamente');
        }

        if ($this->bairroModel->delete($id, true)) {
            return redirect()->to(site_url('admin/bairros'))
                           ->with('sucesso', 'Bairro apagado definitivamente!');
        } else {
            return redirect()->back()
                           ->with('atencao', 'Não foi possível apagar o bairro definitivamente.');
        }
    }

    private function buscaBairroOu404(int $id = null) {
        if (!$id || !is_numeric($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Bairro $id não encontrado");
        }

        $bairro = $this->bairroModel
                      ->withDeleted(true)
                      ->where('id', $id)
                      ->first();

        if (!$bairro) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Bairro $id não encontrado");
        }

        return $bairro;
    }
    
    private function converterValorParaDecimal($valor) {
        // Remove pontos (separadores de milhares) e substitui vírgula por ponto
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
        
        // Garante que seja um número válido
        return is_numeric($valor) ? (float) $valor : 0;
    }

    public function salvarConfiguracao() {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['sucesso' => false, 'msg' => 'Requisição inválida']);
        }

        $json = $this->request->getJSON();
        
        $configModel = new \App\Models\ConfiguracaoEntregaModel();
        
        // Busca configuração existente ou cria nova
        $config = $configModel->first();
        
        $dados = [
            'modo_cobranca' => $json->modo_cobranca ?? 'bairro',
            'taxa_por_km' => $json->taxa_por_km ?? 0,
            'taxa_minima' => $json->taxa_minima ?? 0,
            'distancia_maxima' => $json->distancia_maxima ?? 0,
            'cep_loja' => $json->cep_loja ?? ''
        ];

        try {
            if ($config) {
                $resultado = $configModel->update($config->id, $dados);
            } else {
                $resultado = $configModel->insert($dados);
            }

            if ($resultado) {
                return $this->response->setJSON(['sucesso' => true, 'msg' => 'Configuração salva com sucesso']);
            } else {
                return $this->response->setJSON(['sucesso' => false, 'msg' => 'Erro ao salvar configuração']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Erro ao salvar configuração: ' . $e->getMessage());
            return $this->response->setJSON(['sucesso' => false, 'msg' => 'Erro no banco de dados']);
        }
    }
}