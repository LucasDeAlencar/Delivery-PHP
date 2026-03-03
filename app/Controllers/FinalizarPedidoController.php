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
        $db->transStart();

        try {
            // Dados do pedido
            $dadosPedido = $this->request->getJSON(true);
            $sessionID = session_id();
            $email = $dadosPedido['email'] ?? null;

            // Buscar dados do cliente
            $cliente = null;
            if ($email) {
                $cliente = $db->table('clientes')->where('email', $email)->get()->getRowArray();
            }

            // Gerar código do pedido
            $codigo = 'PED' . date('YmdHis') . rand(100, 999);

            // Calcular valores
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
                'endereco_entrega' => $dadosPedido['tipo_entrega'] === 'entrega' ? 
                    ($cliente ? "{$cliente['Endereco']}, {$cliente['Numero']} - {$cliente['Bairro']}, {$cliente['Cidade']}" : 'Endereço não informado') : 
                    'Retirada no local',
                'forma_pagamento' => $dadosPedido['forma_pagamento'],
                'tipo_entrega' => $dadosPedido['tipo_entrega'],
                'troco_para' => $dadosPedido['troco_para'] ?? null,
                'valor_produtos' => $valorProdutos,
                'valor_entrega' => $valorEntrega,
                'valor_total' => $valorTotal,
                'status' => 'pendente',
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
                    'criado_em' => date('Y-m-d H:i:s')
                ];

                $db->table('pedidos_itens')->insert($itemData);
                $itemId = $db->insertID();

                // Inserir extras do item
                if (!empty($item['extras'])) {
                    foreach ($item['extras'] as $extra) {
                        $extraData = [
                            'pedido_item_id' => $itemId,
                            'extra_id' => $extra['id'],
                            'extra_nome' => $extra['nome'],
                            'extra_preco' => $extra['preco'],
                            'quantidade' => $extra['quantidade'] ?? 1
                        ];
                        $db->table('pedidos_itens_extras')->insert($extraData);
                    }
                }
            }

            // Limpar carrinho temporário
            $db->table('carrinho_temporario')->where('session_id', $sessionID)->delete();

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Erro ao salvar pedido');
            }

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
                
                if ($formaPix) {
                    $chavePix = $formaPix['codigo'] ?? null;
                    $qrcodeImage = $formaPix['qrcode_image'] ?? null;
                }
            }

            // Enviar para WhatsApp
            $whatsappUrl = $this->enviarWhatsApp($codigo, $pedidoData, $dadosPedido['itens']);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pedido realizado com sucesso!',
                'pedido_codigo' => $codigo,
                'pedido_id' => $pedidoId,
                'pedido' => $pedidoCompleto,
                'itens' => $itensPedido,
                'chave_pix' => $chavePix,
                'qrcode_image' => $qrcodeImage,
                'whatsapp_url' => $whatsappUrl
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao processar pedido: ' . $e->getMessage()
            ]);
        }
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
            $mensagem .= "• {$item['quantidade']}x {$item['nome']} - R$ " . number_format($item['total'], 2, ',', '.') . "\n";
            
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

        // Buscar chave PIX se forma de pagamento for PIX
        $chavePix = null;
        $qrcodeImage = null;
        if (strtolower($pedido['forma_pagamento']) === 'pix') {
            $formaPix = $db->table('formas_pagamento')
                ->where('slug', 'pix')
                ->where('ativo', 1)
                ->get()
                ->getRowArray();
            
            if ($formaPix && !empty($formaPix['codigo'])) {
                $chavePix = $formaPix['codigo'];
            }
            if ($formaPix && !empty($formaPix['qrcode_image'])) {
                $qrcodeImage = $formaPix['qrcode_image'];
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'pedido' => $pedido,
            'itens' => $itens,
            'chave_pix' => $chavePix,
            'qrcode_image' => $qrcodeImage
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
            ->update(['status' => 'cancelado', 'atualizado_em' => date('Y-m-d H:i:s')]);

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
