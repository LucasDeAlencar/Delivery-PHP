<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class FinalizarPedidoController extends Controller
{
    public function processar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acesso negado']);
        }

        $db = \Config\Database::connect();

        try {
            $db->transBegin();

            // Verificar se estabelecimento está aberto
            $expedienteModel = new \App\Models\ExpedienteModel();
            $expedientes = $expedienteModel->orderBy('dia', 'ASC')->findAll();
            helper('timezone');
            $agora       = sao_paulo_now('H:i:s');
            $diaAtual    = (int) sao_paulo_now('w');
            $diaAnterior = ($diaAtual + 6) % 7;
            $normalizar  = fn($h) => sprintf('%02d:%02d:%02d', ...array_pad(explode(':', $h), 3, 0));
            $porDia = [];
            foreach ($expedientes as $exp) $porDia[(int)$exp->dia] = $exp;
            $aberto = false;
            if (isset($porDia[$diaAtual]) && $porDia[$diaAtual]->situacao == 1) {
                $exp = $porDia[$diaAtual];
                $ab  = $normalizar($exp->abertura);
                if ((int)$exp->vira_dia === 1) { if ($agora >= $ab) $aberto = true; }
                else { $fe = $normalizar($exp->fechamento); if ($agora >= $ab && $agora <= $fe) $aberto = true; }
            }
            if (!$aberto && isset($porDia[$diaAnterior]) && $porDia[$diaAnterior]->situacao == 1 && (int)$porDia[$diaAnterior]->vira_dia === 1) {
                if ($agora <= $normalizar($porDia[$diaAnterior]->fechamento)) $aberto = true;
            }
            if (!$aberto) {
                $db->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'O estabelecimento está fechado no momento.']);
            }

            // Dados do pedido
            $dadosPedido = $this->request->getJSON(true);
            $sessionID = session_id();

            // Buscar dados do cliente: prioridade para cliente_id da sessão
            $cliente = null;
            $email = $dadosPedido['email'] ?? null;
            $clienteId = session()->get('cliente_id');
            if ($clienteId) {
                $cliente = $db->table('clientes')->where('id', $clienteId)->get()->getRowArray();
                if ($cliente && !empty($cliente['email'])) {
                    $email = $cliente['email'];
                }
            }
            if (!$cliente && $email) {
                $cliente = $db->table('clientes')->where('email', $email)->get()->getRowArray();
            }

            // Gerar código do pedido
            $codigo = 'PED' . date('YmdHis') . rand(100, 999);

            // Validar endereço obrigatório para entrega
            if ($dadosPedido['tipo_entrega'] === 'entrega') {
                $enderecoJS = trim($dadosPedido['endereco_entrega'] ?? '');
                $enderecoInvalido = empty($enderecoJS)
                    || in_array($enderecoJS, ['Retirada na loja', 'Endereço não informado'])
                    || ($cliente && empty($cliente['Endereco']) && empty($enderecoJS));

                if ($enderecoInvalido || (empty($dadosPedido['bairro_id']) && ($dadosPedido['status'] ?? '') !== 'nao_concluido')) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Informe o endereço de entrega antes de finalizar o pedido.',
                        'requer_endereco' => true,
                    ]);
                }
            }

            // Calcular valores provisórios (serão recalculados com dados reais do banco)
            $valorProdutos = (float) $dadosPedido['subtotal'];
            $valorEntrega = $dadosPedido['tipo_entrega'] === 'entrega' ? (float) $dadosPedido['taxa_entrega'] : 0;
            $valorTotal = $valorProdutos + $valorEntrega;

            // Inserir pedido
            $pedidoData = [
                'codigo' => $codigo,
                'usuario_id' => null,
                'nome_cliente' => $cliente ? $cliente['nome'] : 'Cliente',
                'telefone_cliente' => $cliente ? $cliente['telefone'] : '',
                'email_cliente' => $email,
                'endereco_entrega' => $dadosPedido['tipo_entrega'] === 'entrega'
                    ? $this->_montarEndereco($dadosPedido, $cliente)
                    : 'Retirada no local',
                'bairro_id' => $dadosPedido['tipo_entrega'] === 'entrega' ? ($dadosPedido['bairro_id'] ?? null) : null,
                'forma_pagamento' => $dadosPedido['forma_pagamento'],
                'tipo_entrega' => $dadosPedido['tipo_entrega'],
                'mesa_id' => $dadosPedido['tipo_entrega'] === 'retirada' ? ($dadosPedido['mesa_id'] ?? null) : null,
                'local_retirada' => $dadosPedido['tipo_entrega'] === 'retirada' ? ($dadosPedido['local_retirada'] ?? 'balcao') : null,
                'troco_para' => $dadosPedido['troco_para'] ?? null,
                'valor_produtos' => $valorProdutos,
                'valor_entrega' => $valorEntrega,
                'valor_total' => $valorTotal,
                'status' => ($dadosPedido['status'] ?? '') === 'nao_concluido' ? 'nao_concluido' : 'pendente',
                'criado_em' => date('Y-m-d H:i:s')
            ];

            $db->table('pedidos')->insert($pedidoData);
            $pedidoId = $db->insertID();

            // Inserir itens do pedido
            foreach ($dadosPedido['itens'] as $item) {
                $itemData = [
                    'pedido_id' => $pedidoId,
                    'produto_id' => $item['id'],
                    'produto_nome' => $item['nome'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                    'preco_total' => $item['total'],
                    'observacoes' => $item['observacoes'] ?? '',
                    'tamanho_nome' => $item['tamanho']['nome'] ?? null,
                    'tamanho_preco' => $item['tamanho']['preco'] ?? null,
                    'criado_em' => date('Y-m-d H:i:s')
                ];

                $db->table('pedidos_itens')->insert($itemData);
                $itemId = $db->insertID();

                // Inserir extras do item — validar pertencimento e usar preço do banco
                $totalExtrasItem = 0;
                if (!empty($item['extras'])) {
                    foreach ($item['extras'] as $extra) {
                        $extraData = $db->query(
                            "SELECT e.id, e.nome, e.preco FROM extras e
                             INNER JOIN produtos_extras pe ON pe.extra_id = e.id
                             WHERE pe.produto_id = ? AND e.id = ? AND e.ativo = 1 LIMIT 1",
                            [$item['id'], $extra['id']]
                        )->getRowArray();
                        if (!$extraData) continue;
                        $qtdExtra = max(1, intval($extra['quantidade'] ?? 1));
                        $precoExtra = floatval($extraData['preco']);
                        $totalExtrasItem += $precoExtra * $qtdExtra;
                        $db->table('pedidos_itens_extras')->insert([
                            'pedido_item_id' => $itemId,
                            'extra_id'       => $extraData['id'],
                            'extra_nome'     => $extraData['nome'],
                            'extra_preco'    => $precoExtra,
                            'quantidade'     => $qtdExtra
                        ]);
                    }
                }
                // Atualizar preco_total do item com extras reais
                if ($totalExtrasItem > 0) {
                    $novoTotal = ($item['preco'] + $totalExtrasItem) * $item['quantidade'];
                    $db->table('pedidos_itens')->where('id', $itemId)->update(['preco_total' => $novoTotal]);
                }
            }

            // Recalcular valor_produtos com preços reais do banco
            $row = $db->query("SELECT COALESCE(SUM(preco_total),0) as total FROM pedidos_itens WHERE pedido_id = ?", [$pedidoId])->getRowArray();
            $valorProdutosReal = (float)$row['total'];

            // Processar sachês selecionados — deduplicar por sache_id
            if (!empty($dadosPedido['saches'])) {
                $sachesUnicos = [];
                foreach ($dadosPedido['saches'] as $s) {
                    $id = (int)($s['id'] ?? 0);
                    if ($id && !isset($sachesUnicos[$id])) $sachesUnicos[$id] = $s;
                }

                $db->query("DELETE FROM pedidos_saches WHERE pedido_id = ?", [$pedidoId]);
                foreach ($sachesUnicos as $sache) {
                    $sacheData = $db->table('saches')->where('id', $sache['id'])->get()->getRowArray();
                    if (!$sacheData) continue;

                    $limite = 0;
                    if ($sacheData['limite_tipo'] === 'fixo') {
                        $limite = max(0, (int)$sacheData['limite_fixo']);
                    } elseif ($sacheData['limite_tipo'] === 'personalizado') {
                        $limiteMin = (int)$sacheData['limite_minimo'];
                        $porValor  = (float)($sacheData['limite_por_valor'] ?: 1);
                        $limite    = $limiteMin + (int)floor($valorProdutosReal / $porValor);
                    }

                    $quantidade    = (int)$sache['quantidade'];
                    $precoUnitario = (float)$sacheData['preco'];
                    $gratuitos     = min($quantidade, $limite);
                    $pagos         = max(0, $quantidade - $limite);

                    $db->table('pedidos_saches')->insert([
                        'pedido_id'           => $pedidoId,
                        'sache_id'            => $sache['id'],
                        'sache_nome'          => $sacheData['nome'],
                        'quantidade'          => $quantidade,
                        'quantidade_gratuita' => $gratuitos,
                        'quantidade_paga'     => $pagos,
                        'preco_unitario'      => $precoUnitario,
                        'preco_total'         => $pagos * $precoUnitario,
                        'criado_em'           => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            // Atualizar pedido com valores reais (produtos + sachês pagos)
            $valorSachesReal = (float)$db->query(
                "SELECT COALESCE(SUM(preco_total),0) as total FROM pedidos_saches WHERE pedido_id = ?", [$pedidoId]
            )->getRowArray()['total'];
            $db->table('pedidos')->where('id', $pedidoId)->update([
                'valor_produtos' => $valorProdutosReal,
                'valor_total'    => $valorProdutosReal + $valorEntrega + $valorSachesReal,
                'atualizado_em'  => date('Y-m-d H:i:s'),
            ]);

            // Limpar carrinho temporário
            $db->table('carrinho_temporario')->where('session_id', $sessionID)->delete();

            // Ocupar mesa se for retirada em mesa
            if ($dadosPedido['tipo_entrega'] === 'retirada' && !empty($dadosPedido['mesa_id'])) {
                $db->table('mesas')->where('id', $dadosPedido['mesa_id'])->update([
                    'ocupado' => 1,
                    'pedido_id' => $pedidoId,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $db->transCommit();

            // Buscar dados do pedido para retornar ao popup
            $pedidoCompleto = $db->table('pedidos')->where('id', $pedidoId)->get()->getRowArray();
            $itensPedido = $db->table('pedidos_itens')
                ->where('pedido_id', $pedidoId)
                ->get()
                ->getResultArray();

            // Buscar chave PIX e QR Code se pagamento for PIX
            $chavePix = null;
            $qrcodeImage = null;
            if (strtolower($pedidoCompleto['forma_pagamento']) === 'pix') {
                $formaPix = $db->table('formas_pagamento')
                    ->where('slug', 'pix')
                    ->where('ativo', 1)
                    ->get()
                    ->getRowArray();
                
                if ($formaPix && ($formaPix['pix_visivel'] ?? 1)) {
                    $chavePix = $formaPix['codigo'] ?? null;
                    $qrcodeImage = $formaPix['qrcode_image'] ?? null;
                }
            }

            // Buscar tempo de entrega dos dados corporativos
            $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRowArray();
            $tempoEntrega = $dadosCorp['entrega_ate'] ?? 0;

            // Enviar para WhatsApp
            $whatsappUrl = $this->enviarWhatsApp($codigo, $pedidoData, $dadosPedido['itens']);

            $sachesPedido = $db->table('pedidos_saches')->where('pedido_id', $pedidoId)->get()->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pedido realizado com sucesso!',
                'pedido_codigo' => $codigo,
                'pedido_id' => $pedidoId,
                'pedido' => $pedidoCompleto,
                'itens' => $itensPedido,
                'saches' => $sachesPedido,
                'chave_pix' => $chavePix,
                'qrcode_image' => $qrcodeImage,
                'whatsapp_url' => $whatsappUrl,
                'tempo_entrega' => (int) $tempoEntrega
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao processar pedido: ' . $e->getMessage()
            ]);
        }
    }

    private function _montarEndereco(array $dadosPedido, ?array $cliente): string
    {
        // Prioridade 1: endereço enviado pelo JS no payload
        $endJS = trim($dadosPedido['endereco_entrega'] ?? '');
        if ($endJS && $endJS !== 'Retirada na loja' && $endJS !== 'Endereço não informado') {
            $partes = array_filter([
                $endJS,
                trim($dadosPedido['bairro_nome'] ?? ''),
                trim($dadosPedido['cidade'] ?? ''),
            ]);
            return implode(' - ', $partes);
        }

        // Prioridade 2: dados do banco
        if ($cliente && !empty($cliente['Endereco'])) {
            $partes = array_filter([
                trim($cliente['Endereco'] ?? ''),
                trim($cliente['Numero'] ?? ''),
                trim($cliente['Bairro'] ?? ''),
                trim($cliente['Cidade'] ?? ''),
            ]);
            return implode(', ', $partes) ?: 'Endereço não informado';
        }

        return 'Endereço não informado';
    }

    private function enviarWhatsApp($codigo, $pedido, $itens)
    {
        $db = \Config\Database::connect();
        $dados = $db->table('dados_corporativos')->where('id', 1)->get()->getRowArray();
        
        if (!$dados || !$dados['whatsapp']) return null;

        $whatsapp = $dados['whatsapp'];
        
        // Montar mensagem
        $mensagem = "🍕 *NOVO PEDIDO* 🍕\n\n";
        $mensagem .= "📋 *Código:* {$codigo}\n";
        $mensagem .= "👤 *Cliente:* {$pedido['nome_cliente']}\n";
        $mensagem .= "📞 *Telefone:* {$pedido['telefone_cliente']}\n";
        $mensagem .= "🚚 *Tipo:* " . ($pedido['tipo_entrega'] === 'entrega' ? 'Entrega' : 'Retirada') . "\n";
        
        if ($pedido['tipo_entrega'] === 'entrega') {
            $mensagem .= "📍 *Endereço:* {$pedido['endereco_entrega']}\n";
        }
        
        $mensagem .= "💳 *Pagamento:* {$pedido['forma_pagamento']}\n";
        
        if ($pedido['troco_para']) {
            $mensagem .= "💰 *Troco para:* R$ " . number_format($pedido['troco_para'], 2, ',', '.') . "\n";
        }
        
        $mensagem .= "\n📦 *ITENS:*\n";
        
        foreach ($itens as $item) {
            $mensagem .= "• {$item['quantidade']}x {$item['nome']}";
            if (!empty($item['tamanho']['nome'])) {
                $mensagem .= " ({$item['tamanho']['nome']})";
            }
            $mensagem .= " - R$ " . number_format($item['total'], 2, ',', '.') . "\n";
            
            if (!empty($item['extras'])) {
                foreach ($item['extras'] as $extra) {
                    $mensagem .= "  + {$extra['nome']} (+R$ " . number_format($extra['preco'], 2, ',', '.') . ")\n";
                }
            }
            
            if (!empty($item['observacoes'])) {
                $mensagem .= "  📝 {$item['observacoes']}\n";
            }
        }
        
        $mensagem .= "\n💰 *TOTAL: R$ " . number_format($pedido['valor_total'], 2, ',', '.') . "*\n";
        $mensagem .= "\n⏰ " . date('d/m/Y H:i');

        // URL do WhatsApp
        $url = "https://wa.me/{$whatsapp}?text=" . urlencode($mensagem);
        
        // Log para debug
        log_message('info', "WhatsApp URL gerada: {$url}");
        
        return $url;
    }

    public function acompanhar($codigo = null)
    {
        if (!$codigo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Código do pedido é obrigatório']);
        }

        $db = \Config\Database::connect();
        
        $pedido = $db->table('pedidos')
            ->where('codigo', $codigo)
            ->get()
            ->getRowArray();

        if (!$pedido) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pedido não encontrado']);
        }

        $itens = $db->table('pedidos_itens')
            ->where('pedido_id', $pedido['id'])
            ->get()
            ->getResultArray();

        $saches = $db->table('pedidos_saches')
            ->where('pedido_id', $pedido['id'])
            ->get()
            ->getResultArray();

        // Buscar chave PIX se forma de pagamento for PIX
        $chavePix = null;
        $qrcodeImage = null;
        if (strtolower($pedido['forma_pagamento']) === 'pix') {
            $formaPix = $db->table('formas_pagamento')
                ->where('slug', 'pix')
                ->where('ativo', 1)
                ->get()
                ->getRowArray();
            
            if ($formaPix && ($formaPix['pix_visivel'] ?? 1)) {
                if (!empty($formaPix['codigo'])) {
                    $chavePix = $formaPix['codigo'];
                }
                if (!empty($formaPix['qrcode_image'])) {
                    $qrcodeImage = $formaPix['qrcode_image'];
                }
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'pedido' => $pedido,
            'itens' => $itens,
            'saches' => $saches,
            'chave_pix' => $chavePix,
            'qrcode_image' => $qrcodeImage,
            'tempo_entrega' => (int) ($db->table('dados_corporativos')->where('id', 1)->get()->getRowArray()['entrega_ate'] ?? 0)
        ]);
    }

    public function cancelar($codigo = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acesso negado']);
        }

        if (!$codigo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Código do pedido é obrigatório']);
        }

        $db = \Config\Database::connect();
        
        $pedido = $db->table('pedidos')
            ->where('codigo', $codigo)
            ->get()
            ->getRowArray();

        if (!$pedido) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pedido não encontrado']);
        }

        // Só permite cancelar se estiver pendente
        if ($pedido['status'] !== 'pendente') {
            return $this->response->setJSON(['success' => false, 'message' => 'Pedido não pode ser cancelado']);
        }

        // Atualizar status para cancelado
        $db->table('pedidos')
            ->where('codigo', $codigo)
            ->update(['status' => 'cancelado', 'atualizado_em' => date('Y-m-d H:i:s'), 'inativo_em' => date('Y-m-d H:i:s')]);

        // Liberar mesa associada
        $db->table('mesas')
            ->where('pedido_id', $pedido['id'])
            ->update(['ocupado' => 0, 'pedido_id' => null, 'updated_at' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pedido cancelado com sucesso'
        ]);
    }

    public function recibo($codigo = null)
    {
        if (!$codigo) {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();
        
        $pedido = $db->table('pedidos')
            ->where('codigo', $codigo)
            ->get()
            ->getRowArray();

        if (!$pedido) {
            return redirect()->to('/');
        }

        $itens = $db->table('pedidos_itens')
            ->where('pedido_id', $pedido['id'])
            ->get()
            ->getResultArray();

        // Carregar dados corporativos
        $dadosCorp = $db->table('dados_corporativos')->where('id', 1)->get()->getRowArray();

        // Carregar extras dos itens
        $extrasItens = [];
        foreach ($itens as $item) {
            $extras = $db->table('pedidos_itens_extras')
                ->where('pedido_item_id', $item['id'])
                ->get()
                ->getResultArray();
            $extrasItens[$item['id']] = $extras;
        }

        // Carregar sachês do pedido
        $saches = $db->table('pedidos_saches')
            ->where('pedido_id', $pedido['id'])
            ->get()
            ->getResultArray();

        // Carregar nome do bairro
        $bairroNome = '';
        if (!empty($pedido['bairro_id'])) {
            $bairro = $db->table('bairros')->where('id', $pedido['bairro_id'])->get()->getRowArray();
            if ($bairro) {
                $bairroNome = $bairro['nome'];
            }
        }

        $data = [
            'pedido' => (object) $pedido,
            'itens' => $itens,
            'extrasItens' => $extrasItens,
            'saches' => $saches,
            'dadosCorporativos' => $dadosCorp,
            'bairroNome' => $bairroNome
        ];

        $html = view('Home/recibo_pdf', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("recibo_pedido_{$pedido['codigo']}.pdf", ['Attachment' => true]);
        return '';
    }
}
