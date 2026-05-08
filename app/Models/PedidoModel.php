<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model {

    protected $table = 'pedidos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'App\Entities\Pedido';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'codigo',
        'usuario_id',
        'nome_cliente',
        'telefone_cliente',
        'endereco_entrega',
        'bairro_id',
        'complemento',
        'forma_pagamento',
        'troco_para',
        'valor_produtos',
        'valor_entrega',
        'valor_total',
        'observacoes',
        'status',
        'inativo_em'
    ];
    
    // Tempo limite para inativação (em minutos)
    const TEMPO_INATIVO = 60; // 1 hora

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';

    // Validation
    protected $validationRules = [
        'nome_cliente' => 'required|min_length[3]|max_length[120]',
        'telefone_cliente' => 'required|min_length[10]',
        'endereco_entrega' => 'required',
        'forma_pagamento' => 'required',
        'valor_produtos' => 'required|decimal',
        'valor_total' => 'required|decimal',
    ];

    protected $validationMessages = [
        'nome_cliente' => [
            'required' => 'O nome do cliente é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
        ],
        'telefone_cliente' => [
            'required' => 'O telefone é obrigatório.',
            'min_length' => 'O telefone deve ter pelo menos 10 caracteres.',
        ],
        'endereco_entrega' => [
            'required' => 'O endereço de entrega é obrigatório.',
        ],
        'forma_pagamento' => [
            'required' => 'A forma de pagamento é obrigatória.',
        ],
    ];

    // Callbacks
    protected $beforeInsert = ['gerarCodigo'];
    protected $afterInsert = ['resetAutoIncrement'];

    /**
     * Gera código único para o pedido
     */
    protected function gerarCodigo(array $data) {
        if (!isset($data['data']['codigo'])) {
            // Formato: PED-YYYYMMDD-XXXX
            $data['data']['codigo'] = $this->gerarCodigoUnico();
        }
        return $data;
    }

    /**
     * Reseta o AUTO_INCREMENT da tabela após inserção
     */
    protected function resetAutoIncrement($data): array
    {
        $table = $this->table;
        $db = \Config\Database::connect();
        
        $query = $db->query("SELECT MAX(id) as max_id FROM $table");
        $result = $query->getRow();
        $maxId = $result->max_id ?? 0;

        $db->query("ALTER TABLE $table AUTO_INCREMENT = " . ($maxId + 1));

        return $data;
    }

    /**
     * Gera um código único para o pedido
     */
    private function gerarCodigoUnico(): string {
        $data = date('Ymd');
        
        // Busca o último pedido do dia
        $ultimoPedido = $this->where('codigo LIKE', "PED-{$data}-%")
                             ->orderBy('id', 'DESC')
                             ->first();
        
        if ($ultimoPedido) {
            // Extrai o número sequencial
            $partes = explode('-', $ultimoPedido->codigo);
            $sequencial = intval($partes[2] ?? 0) + 1;
        } else {
            $sequencial = 1;
        }
        
        return sprintf('PED-%s-%04d', $data, $sequencial);
    }

    /**
     * Busca pedidos com informações relacionadas
     */
    public function buscaPedidosCompletos($limit = 50) {
        // Primeiro processa inativos
        $this->processarInativos();
        
        return $this->select('pedidos.*, usuarios.nome as usuario_nome, bairros.nome as bairro_nome, mesas.numero as mesa_numero')
                    ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
                    ->join('bairros', 'bairros.id = pedidos.bairro_id', 'left')
                    ->join('mesas', 'mesas.id = pedidos.mesa_id', 'left')
                    ->orderBy('pedidos.criado_em', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Busca pedido por ID com informações relacionadas
     */
    public function buscaPedidoPorId($id) {
        return $this->select('pedidos.*, usuarios.nome as usuario_nome, usuarios.email as usuario_email, bairros.nome as bairro_nome, mesas.numero as mesa_numero')
                    ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
                    ->join('bairros', 'bairros.id = pedidos.bairro_id', 'left')
                    ->join('mesas', 'mesas.id = pedidos.mesa_id', 'left')
                    ->where('pedidos.id', $id)
                    ->first();
    }

    /**
     * Busca pedidos por status
     */
    public function buscaPedidosPorStatus($status) {
        return $this->select('pedidos.*, usuarios.nome as usuario_nome, bairros.nome as bairro_nome')
                    ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
                    ->join('bairros', 'bairros.id = pedidos.bairro_id', 'left')
                    ->where('pedidos.status', $status)
                    ->orderBy('pedidos.criado_em', 'DESC')
                    ->findAll();
    }

    /**
     * Busca pedidos de um usuário específico
     */
    public function buscaPedidosDoUsuario($usuarioId) {
        return $this->select('pedidos.*, bairros.nome as bairro_nome')
                    ->join('bairros', 'bairros.id = pedidos.bairro_id', 'left')
                    ->where('pedidos.usuario_id', $usuarioId)
                    ->orderBy('pedidos.criado_em', 'DESC')
                    ->findAll();
    }

    /**
     * Atualiza o status do pedido
     */
    public function atualizarStatus($pedidoId, $novoStatus) {
        $novoStatus = strtolower(trim($novoStatus));
        $statusValidos = ['pendente', 'confirmado', 'finalizado', 'cancelado', 'em_aberto'];
        
        if (!in_array($novoStatus, $statusValidos)) {
            log_message('error', "Status inválido: {$novoStatus}");
            return false;
        }
        
        // Verificar se o pedido pode ser alterado
        $pedido = $this->find($pedidoId);
        if (!$pedido) {
            log_message('error', "Pedido {$pedidoId} não encontrado");
            return false;
        }
        
        // Usar o getter da Entity que já normaliza o status
        $statusAtual = $pedido->status; // Getter retorna 'pendente' se vazio/null
        
        log_message('debug', "atualizarStatus - Pedido: {$pedidoId}, Status atual: '{$statusAtual}', Novo: '{$novoStatus}'");
        
        if (!$this->podeAlterar($pedido)) {
            log_message('error', "Pedido {$pedidoId} não pode ser alterado (status: {$statusAtual})");
            return false;
        }
        
        // Validar transição de status
        if (!$this->transicaoPermitida($statusAtual, $novoStatus)) {
            log_message('error', "Transição não permitida: {$statusAtual} -> {$novoStatus}");
            return false;
        }
        
        try {
            $dados = ['status' => $novoStatus];
            
            // Se for cancelado, registra a data de cancelamento (mas mantém status 'cancelado')
            if ($novoStatus === 'cancelado') {
                $dados['inativo_em'] = date('Y-m-d H:i:s');
            }
            
            $result = $this->update($pedidoId, $dados);
            log_message('debug', "Status atualizado - Pedido: {$pedidoId}, Status: {$novoStatus}, Resultado: " . ($result ? 'true' : 'false'));
            return $result;
        } catch (\Exception $e) {
            log_message('error', "Erro ao atualizar status: " . $e->getMessage());
            return false;
        }
    }
    
     /**
      * Verifica se a transição de status é permitida
      * Fluxo: pendente -> confirmado -> finalizado
      * Cancelar é permitido de pendente ou confirmado
      * Não pode voltar atrás (confirmado não volta para pendente)
      */
    public function transicaoPermitida($statusAtual, $novoStatus) {
        // Normalizar status - tratar null/vazio como 'pendente'
        $statusAtual = strtolower(trim($statusAtual ?? ''));
        if (empty($statusAtual)) {
            $statusAtual = 'pendente';
        }
        $novoStatus = strtolower(trim($novoStatus ?? ''));
        
        $transicoesPermitidas = [
            'em_aberto' => ['pendente', 'cancelado'],
            'pendente' => ['confirmado', 'cancelado'],
            'confirmado' => ['finalizado', 'cancelado'],
            'finalizado' => [],
            'cancelado' => [],
            'inativo' => [],
        ];
        
        return in_array($novoStatus, $transicoesPermitidas[$statusAtual] ?? []);
    }

    /**
     * Busca estatísticas de pedidos
     */
    public function getEstatisticas() {
        // Primeiro processa inativos
        $this->processarInativos();
        
        return [
            'total_pedidos' => $this->countAllResults(false),
            'pendentes' => $this->where('status', 'pendente')->countAllResults(false),
            'confirmados' => $this->where('status', 'confirmado')->countAllResults(false),
            'finalizados' => $this->where('status', 'finalizado')->countAllResults(false),
            'cancelados' => $this->where('status', 'cancelado')->countAllResults(false),
            'inativos' => $this->where('status', 'inativo')->countAllResults(false),
            'em_aberto' => $this->where('status', 'em_aberto')->countAllResults(false),
            'valor_total_hoje' => $this->selectSum('valor_total')
                                       ->where('DATE(criado_em)', date('Y-m-d'))
                                       ->where('status !=', 'inativo')
                                       ->first()
                                       ->valor_total ?? 0,
        ];
    }
    
    /**
     * Processa pedidos que devem se tornar inativos
     * - Apenas pedidos com status NULL ou 'pendente' viram inativos após 1 hora desde a criação
     * @return int Número de pedidos alterados
     */
    public function processarInativos() {
        $db = \Config\Database::connect();
        
        // Calcular limite: 1 hora atrás usando função do MySQL
        // Isso evita problemas de timezone entre PHP e MySQL
        $sql = "UPDATE {$this->table} 
                SET status = 'inativo', inativo_em = NOW() 
                WHERE (status IS NULL OR status = '' OR status = 'pendente')
                AND criado_em < DATE_SUB(NOW(), INTERVAL " . self::TEMPO_INATIVO . " MINUTE)
                AND deletado_em IS NULL
                AND status NOT IN ('inativo', 'em_aberto')";
        
        $db->query($sql);
        $totalAlterados = $db->affectedRows();
        
        if ($totalAlterados > 0) {
            log_message('info', "processarInativos: {$totalAlterados} pedido(s) inativado(s)");
        }
        
        return $totalAlterados;
    }
    
    /**
     * Verifica se um pedido pode ser alterado
     */
    public function podeAlterar($pedido) {
        // Usar o getter da Entity que já normaliza o status
        $status = $pedido->status; // Getter retorna 'pendente' se vazio/null
        // Inativos, finalizados e cancelados não podem ser alterados
        if (in_array($status, ['inativo', 'finalizado', 'cancelado'])) {
            return false;
        }
        return true;
    }
    
    /**
     * Busca pedidos do dia (para usuários não-admin)
     */
    public function buscaPedidosDoDia($limit = 100) {
        $this->processarInativos();
        
        return $this->select('pedidos.*, usuarios.nome as usuario_nome, bairros.nome as bairro_nome, mesas.numero as mesa_numero')
                    ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
                    ->join('bairros', 'bairros.id = pedidos.bairro_id', 'left')
                    ->join('mesas', 'mesas.id = pedidos.mesa_id', 'left')
                    ->where('DATE(pedidos.criado_em)', date('Y-m-d'))
                    ->orderBy('pedidos.criado_em', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
    
    /**
     * Busca estatísticas do dia (para usuários não-admin)
     */
    public function getEstatisticasDoDia() {
        $this->processarInativos();
        $hoje = date('Y-m-d');
        
        return [
            'total_pedidos' => $this->where('DATE(criado_em)', $hoje)->countAllResults(false),
            'pendentes' => $this->where('status', 'pendente')->where('DATE(criado_em)', $hoje)->countAllResults(false),
            'confirmados' => $this->where('status', 'confirmado')->where('DATE(criado_em)', $hoje)->countAllResults(false),
            'finalizados' => $this->where('status', 'finalizado')->where('DATE(criado_em)', $hoje)->countAllResults(false),
            'cancelados' => $this->where('status', 'cancelado')->where('DATE(criado_em)', $hoje)->countAllResults(false),
            'inativos' => $this->where('status', 'inativo')->where('DATE(criado_em)', $hoje)->countAllResults(false),
            'valor_total_hoje' => $this->selectSum('valor_total')
                                       ->where('DATE(criado_em)', $hoje)
                                       ->where('status !=', 'inativo')
                                       ->first()
                                       ->valor_total ?? 0,
        ];
    }

    public function recuperarNovosPedidos($ultimoId) {
    return $this->select('pedidos.*, usuarios.nome as usuario_nome, bairros.nome as bairro_nome')
                ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
                ->join('bairros', 'bairros.id = pedidos.bairro_id', 'left')
                ->where('pedidos.id >', $ultimoId)
                ->orderBy('pedidos.id', 'ASC') // Ordem crescente para processar um a um
                ->findAll();
}

    /**
     * Busca pedidos cancelados recentemente (nos últimos 5 minutos)
     * Usado para detectar cancelamentos feitos pelo cliente
     */
    public function buscarCanceladosRecentes() {
        return $this->select('pedidos.*, usuarios.nome as usuario_nome, bairros.nome as bairro_nome')
                    ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
                    ->join('bairros', 'bairros.id = pedidos.bairro_id', 'left')
                    ->where('pedidos.status', 'cancelado')
                    ->groupStart()
                        ->where('pedidos.inativo_em >=', date('Y-m-d H:i:s', strtotime('-5 minutes')))
                        ->orWhere('pedidos.atualizado_em >=', date('Y-m-d H:i:s', strtotime('-5 minutes')))
                    ->groupEnd()
                    ->where('pedidos.deletado_em IS NULL')
                    ->orderBy('pedidos.id', 'ASC')
                    ->findAll();
    }
    
    /**
     * Cria um novo pedido com seus itens
     */
    public function criarPedidoCompleto($dadosPedido, $itens) {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insere o pedido
            $pedidoId = $this->insert($dadosPedido);

            if (!$pedidoId) {
                $db->transRollback();
                return false;
            }

            // Insere os itens
            $pedidoItemModel = new \App\Models\PedidoItemModel();
            foreach ($itens as $item) {
                $item['pedido_id'] = $pedidoId;
                if (!$pedidoItemModel->insert($item)) {
                    $db->transRollback();
                    return false;
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return false;
            }

            return $pedidoId;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Erro ao criar pedido: ' . $e->getMessage());
            return false;
        }
    }
}
