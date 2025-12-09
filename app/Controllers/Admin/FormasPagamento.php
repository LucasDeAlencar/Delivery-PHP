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
        if ($this->request->getMethod() === 'post') {
            return redirect()->to('admin/formas-pagamento');
        }

        try {
            $formasPagamento = $this->formaPagamentoModel->findAll();
            $atualizados = 0;

            foreach ($formasPagamento as $forma) {
                // Verifica se o checkbox foi marcado
                $ativo = $this->request->getPost('forma_' . $forma->id) ? 1 : 0;

                // Atualiza apenas se mudou
                if ($forma->ativo != $ativo) {
                    $this->formaPagamentoModel->update($forma->id, ['ativo' => $ativo]);
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
