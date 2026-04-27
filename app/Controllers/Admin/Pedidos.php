<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PedidoModel;
use App\Models\PedidoItemModel;

class Pedidos extends BaseController {

    private $pedidoModel;
    private $pedidoItemModel;

    public function __construct() {
        $this->pedidoModel = new PedidoModel();
        $this->pedidoItemModel = new PedidoItemModel();
    }

    /**
     * Lista todos os pedidos
     */
    public function index() {
        $usuarioLogado = service('autenticacao')->pegaUsuarioLogado();
        $isAdmin = $usuarioLogado->is_admin == 1;
        
        // Não-admin só vê pedidos do dia
        if ($isAdmin) {
            $pedidos = $this->pedidoModel->buscaPedidosCompletos(100);
            $estatisticas = $this->pedidoModel->getEstatisticas();
        } else {
            $pedidos = $this->pedidoModel->buscaPedidosDoDia(100);
            $estatisticas = $this->pedidoModel->getEstatisticasDoDia();
        }
        
        $data = [
            'titulo' => 'Gerenciar Pedidos',
            'pedidos' => $pedidos,
            'estatisticas' => $estatisticas,
            'isAdmin' => $isAdmin,
        ];
        
        // Buscar suportes pendentes
        $db = \Config\Database::connect();
        $suportes = $db->table('suporte_pedidos')
            ->where('status', 'pendente')
            ->orderBy('criado_em', 'DESC')
            ->get()
            ->getResultArray();
        
        $data['suportes_pendentes'] = $suportes;
        $data['total_suportes'] = count($suportes);

        return view('Admin/Pedidos/index', $data);
    }

    /**
     * Visualiza detalhes de um pedido
     */
    public function show($id) {
        $pedido = $this->pedidoModel->buscaPedidoPorId($id);

        if (!$pedido) {
            return redirect()->to('admin/pedidos')
                            ->with('erro', 'Pedido não encontrado.');
        }
        
        // Não-admin só pode ver pedidos do dia
        $usuarioLogado = service('autenticacao')->pegaUsuarioLogado();
        $isAdmin = $usuarioLogado->is_admin == 1;
        
        if (!$isAdmin) {
            $dataPedido = date('Y-m-d', strtotime($pedido->criado_em));
            if ($dataPedido !== date('Y-m-d')) {
                return redirect()->to('admin/pedidos')
                                ->with('atencao', 'Você só pode visualizar pedidos do dia atual.');
            }
        }

        $data = [
            'titulo' => 'Detalhes do Pedido ' . $pedido->codigo,
            'pedido' => $pedido,
            'itens' => $this->pedidoItemModel->buscaItensDoPedido($id),
            'isAdmin' => $isAdmin,
            'podeAlterar' => $this->pedidoModel->podeAlterar($pedido),
        ];

        return view('Admin/Pedidos/show', $data);
    }

    /**
     * Atualiza o status do pedido (AJAX)
     */
    public function atualizarStatus() {
        // Obter dados de POST
        $pedidoId = $this->request->getPost('pedido_id');
        $novoStatus = $this->request->getPost('status');

        // Log para debug
        log_message('debug', "Atualizando status - Pedido: {$pedidoId}, Status: {$novoStatus}");

        if (!$pedidoId || !$novoStatus) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dados incompletos'
            ]);
        }

        // Validar status permitidos
        $statusPermitidos = ['pendente', 'confirmado', 'finalizado', 'cancelado'];
        if (!in_array($novoStatus, $statusPermitidos)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status inválido: ' . $novoStatus
            ]);
        }
        
        // Verificar se o pedido existe e pode ser alterado
        $pedido = $this->pedidoModel->find($pedidoId);
        if (!$pedido) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pedido não encontrado'
            ]);
        }
        
        // Usar o getter da Entity que já normaliza o status
        $statusAtual = $pedido->status; // Getter retorna 'pendente' se vazio/null
        
        log_message('debug', "Controller atualizarStatus - Pedido: {$pedidoId}, Status atual: '{$statusAtual}', Novo: '{$novoStatus}'");
        
        // Verificar se o pedido pode ser alterado
        if (!$this->pedidoModel->podeAlterar($pedido)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Este pedido não pode ser alterado (status: ' . $statusAtual . ')'
            ]);
        }
        
        // Verificar se a transição de status é permitida
        if (!$this->pedidoModel->transicaoPermitida($statusAtual, $novoStatus)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Transição não permitida: ' . $statusAtual . ' → ' . $novoStatus
            ]);
        }
        
        // Não-admin só pode alterar pedidos do dia
        $usuarioLogado = service('autenticacao')->pegaUsuarioLogado();
        if ($usuarioLogado->is_admin != 1) {
            $dataPedido = date('Y-m-d', strtotime($pedido->criado_em));
            if ($dataPedido !== date('Y-m-d')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Você só pode alterar pedidos do dia atual'
                ]);
            }
        }

        $resultado = $this->pedidoModel->atualizarStatus($pedidoId, $novoStatus);

        if ($resultado) {
            if (in_array($novoStatus, ['finalizado', 'cancelado'])) {
                $this->liberarMesa((int) $pedidoId);
            }
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status atualizado com sucesso!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao atualizar status no banco de dados'
            ]);
        }
    }

    /**
     * Filtra pedidos por status (AJAX)
     */
    public function filtrarPorStatus() {
        $status = $this->request->getPost('status');

        if ($status === 'todos') {
            $pedidos = $this->pedidoModel->buscaPedidosCompletos(100);
        } else {
            $pedidos = $this->pedidoModel->buscaPedidosPorStatus($status);
        }

        return $this->response->setJSON([
                    'success' => true,
                    'pedidos' => $pedidos
        ]);
    }

    /**
     * Libera a mesa associada ao pedido, se houver
     */
    private function liberarMesa(int $pedidoId): void
    {
        $db = \Config\Database::connect();
        $db->table('mesas')
            ->where('pedido_id', $pedidoId)
            ->update(['ocupado' => 0, 'pedido_id' => null, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Cancela um pedido
     */
    public function cancelar($id) {
        $pedido = $this->pedidoModel->find($id);

        if (!$pedido) {
            return redirect()->to('admin/pedidos')
                            ->with('erro', 'Pedido não encontrado.');
        }

        if ($pedido->status === 'finalizado') {
            return redirect()->to('admin/pedidos')
                            ->with('erro', 'Não é possível cancelar um pedido já finalizado.');
        }

        $resultado = $this->pedidoModel->atualizarStatus($id, 'cancelado');

        if ($resultado) {
            $this->liberarMesa($id);
            return redirect()->to('admin/pedidos')
                            ->with('sucesso', 'Pedido cancelado com sucesso!');
        } else {
            return redirect()->to('admin/pedidos')
                            ->with('erro', 'Erro ao cancelar pedido.');
        }
    }

    /**
     * Exclui um pedido (soft delete)
     */
    public function excluir($id) {
        $pedido = $this->pedidoModel->find($id);

        if (!$pedido) {
            return redirect()->to('admin/pedidos')
                            ->with('erro', 'Pedido não encontrado.');
        }

        $data = [
            'titulo' => 'Excluir Pedido ' . $pedido->codigo,
            'pedido' => $pedido,
        ];

        return view('Admin/Pedidos/excluir', $data);
    }

    /**
     * Confirma exclusão do pedido
     */
    public function deletar($id) {
        // A rota já garante que é POST

        $pedido = $this->pedidoModel->find($id);

        if (!$pedido) {
            return redirect()->to('admin/pedidos')
                            ->with('erro', 'Pedido não encontrado.');
        }

        if ($this->pedidoModel->delete($id)) {
            return redirect()->to('admin/pedidos')
                            ->with('sucesso', 'Pedido excluído com sucesso!');
        } else {
            return redirect()->to('admin/pedidos')
                            ->with('erro', 'Erro ao excluir pedido.');
        }
    }

    /**
     * Imprime pedido
     */
    public function imprimir($id) {
        $pedido = $this->pedidoModel->buscaPedidoPorId($id);

        if (!$pedido) {
            return redirect()->to('admin/pedidos')
                            ->with('erro', 'Pedido não encontrado.');
        }

        $data = [
            'pedido' => $pedido,
            'itens' => $this->pedidoItemModel->buscaItensDoPedido($id),
        ];

        return view('Admin/Pedidos/imprimir', $data);
    }

    /**
     * Exporta pedidos para CSV
     */
    public function exportarCsv() {
        $usuarioLogado = service('autenticacao')->pegaUsuarioLogado();
        $isAdmin = $usuarioLogado->is_admin == 1;
        
        if ($isAdmin) {
            $pedidos = $this->pedidoModel->buscaPedidosCompletos(500);
            $filename = 'pedidos_todos_' . date('Ymd_His') . '.csv';
        } else {
            $pedidos = $this->pedidoModel->buscaPedidosDoDia(500);
            $filename = 'pedidos_dia_' . date('Ymd') . '.csv';
        }
        
        $csvData = [];
        $csvData[] = ['Código', 'Data', 'Cliente', 'Telefone', 'Endereço', 'Bairro', 'Mesa', 'Status', 'Forma Pagamento', 'Valor Produtos', 'Valor Entrega', 'Total'];
        
        foreach ($pedidos as $p) {
            $csvData[] = [
                $p->codigo,
                date('d/m/Y H:i', strtotime($p->criado_em)),
                $p->nome_cliente,
                $p->telefone_cliente,
                $p->endereco_entrega,
                $p->bairro_nome ?? '',
                $p->mesa_numero ?? '',
                $p->status,
                $p->forma_pagamento,
                number_format($p->valor_produtos, 2, ',', '.'),
                number_format($p->valor_entrega, 2, ',', '.'),
                number_format($p->valor_total, 2, ',', '.')
            ];
        }
        
        $csvContent = '';
        
        foreach ($csvData as $linha) {
            $csvContent .= implode(';', $linha) . "\n";
        }
        
        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csvContent);
    }

    public function verificarNovos($ultimoId = null) {
        // Se não passar ID, assume 0
        $ultimoId = (int) $ultimoId;
        
        // Processar pedidos inativos primeiro e verificar se houve alterações
        $alterados = $this->pedidoModel->processarInativos();

        // Verificar se é admin
        $usuarioLogado = service('autenticacao')->pegaUsuarioLogado();
        $isAdmin = $usuarioLogado->is_admin == 1;

        // Busca novos pedidos e estatísticas atualizadas
        $novosPedidos = $this->pedidoModel->recuperarNovosPedidos($ultimoId);
        
        // Verificar pedidos cancelados recentemente (nos últimos 5 minutos)
        // Isso captura cancelamentos feitos pelo cliente
        $pedidosCancelados = $this->pedidoModel->buscarCanceladosRecentes();
        
        // Estatísticas baseadas no tipo de usuário
        if ($isAdmin) {
            $estatisticas = $this->pedidoModel->getEstatisticas();
        } else {
            $estatisticas = $this->pedidoModel->getEstatisticasDoDia();
            // Filtrar novos pedidos para apenas os do dia
            $novosPedidos = array_filter($novosPedidos, function($pedido) {
                return date('Y-m-d', strtotime($pedido->criado_em)) === date('Y-m-d');
            });
        }

        return $this->response->setJSON([
            'success' => true,
            'novos_pedidos' => array_values($novosPedidos),
            'pedidos_cancelados' => $pedidosCancelados,
            'estatisticas' => $estatisticas,
            'recarregar' => $alterados > 0
        ]);
    }

    /**
     * Limpa todos os pedidos (apenas admin)
     */
    public function limparPedidos() {
        $usuarioLogado = service('autenticacao')->pegaUsuarioLogado();
        
        if ($usuarioLogado->is_admin != 1) {
            return $this->response->setJSON(['sucesso' => false, 'msg' => 'Acesso negado.']);
        }
        
        $db = \Config\Database::connect();
        
        try {
            $db->query('SET FOREIGN_KEY_CHECKS = 0');
            $db->table('pedidos_itens')->truncate();
            $db->table('pedidos')->truncate();
            $db->query('SET FOREIGN_KEY_CHECKS = 1');
            
            $db->table('mesas')->update(['ocupado' => 0, 'pedido_id' => null]);
            
            return $this->response->setJSON(['sucesso' => true, 'msg' => 'Pedidos limpos com sucesso.']);
        } catch (\Exception $e) {
            $db->query('SET FOREIGN_KEY_CHECKS = 1');
            return $this->response->setJSON(['sucesso' => false, 'msg' => 'Erro ao limpar pedidos.']);
        }
    }
}
