<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FormaPagamentoModel;

class FormasPagamento extends BaseController
{
    private $formaPagamentoModel;

    public function __construct()
    {
        $this->formaPagamentoModel = new FormaPagamentoModel();
    }

    /**
     * Lista todas as formas de pagamento
     */
    public function index()
    {
        $data = [
            'titulo' => 'Formas de Pagamento',
            'formasPagamento' => $this->formaPagamentoModel->getAllOrdenadas(),
        ];

        return view('Admin/FormasPagamento/index', $data);
    }

    /**
     * Atualiza o status das formas de pagamento
     */
    public function atualizar()
    {
        if ($this->request->getMethod() === 'get') {
            return redirect()->to('admin/formas-pagamento');
        }

        try {
            $formasPagamento = $this->formaPagamentoModel->findAll();
            $atualizados = 0;
            $chavePix = $this->request->getPost('chave_pix');

            $uploadPath = ROOTPATH . 'public/uploads/qrcode_pix/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($formasPagamento as $forma) {
                // Verifica se o checkbox foi marcado
                $ativo = $this->request->getPost('forma_' . $forma->id) ? 1 : 0;
                $dadosUpdate = ['ativo' => $ativo];

                // Se for PIX, atualizar também a chave e visibilidade
                if ($forma->slug === 'pix') {
                    if ($chavePix) {
                        $dadosUpdate['codigo'] = $chavePix;
                    }
                    $dadosUpdate['pix_visivel'] = $this->request->getPost('pix_visivel') ? 1 : 0;
                }

                // Se for PIX, processar upload do QR Code
                if ($forma->slug === 'pix') {
                    $qrCodeFile = $this->request->getFile('qrcode_pix');
                    if ($qrCodeFile && $qrCodeFile->isValid() && !$qrCodeFile->hasMoved()) {
                        // Remover imagem anterior se existir
                        if (!empty($forma->qrcode_image)) {
                            $oldFile = $uploadPath . $forma->qrcode_image;
                            if (file_exists($oldFile)) {
                                unlink($oldFile);
                            }
                        }

                        // Gerar novo nome aleatório
                        $newName = $qrCodeFile->getRandomName();
                        $qrCodeFile->move($uploadPath, $newName);
                        $dadosUpdate['qrcode_image'] = $newName;
                    }
                }

                // Atualiza apenas se mudou
                $pixVisivelNovo = isset($dadosUpdate['pix_visivel']) ? $dadosUpdate['pix_visivel'] : ($forma->pix_visivel ?? 1);
                if ($forma->ativo != $ativo || ($forma->slug === 'pix' && $forma->codigo !== $chavePix) || isset($dadosUpdate['qrcode_image']) || ($forma->slug === 'pix' && ($forma->pix_visivel ?? 1) != $pixVisivelNovo)) {
                    $this->formaPagamentoModel->update($forma->id, $dadosUpdate);
                    $atualizados++;
                }
            }

            if ($atualizados > 0) {
                return redirect()->to('admin/formas-pagamento')
                    ->with('sucesso', "Formas de pagamento atualizadas com sucesso! ($atualizados alterações)");
            } else {
                return redirect()->to('admin/formas-pagamento')
                    ->with('info', 'Nenhuma alteração foi realizada.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Erro ao atualizar formas de pagamento: ' . $e->getMessage());
            return redirect()->back()
                ->with('erro', 'Erro ao atualizar formas de pagamento: ' . $e->getMessage());
        }
    }
}
