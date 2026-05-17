<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Expedientes extends BaseController {

    private $expedienteModel;

    public function __construct() {
        $this->expedienteModel = new \App\Models\ExpedienteModel();
    }

    public function expedientes() {
        // VERIFICAÇÃO CORRIGIDA
        $metodo = $this->request->getMethod();
        log_message('info', "=== MÉTODO EXPEDIENTES ===");
        log_message('info', "Método requisitado: {$metodo}");

        // CORREÇÃO PRINCIPAL: Comparação case-insensitive
        if (strtolower($metodo) === 'post') {
            log_message('info', '🎯🎯🎯 POST IDENTIFICADO - Processando salvamento');
            return $this->salvarExpedientes();
        }

        // Se for GET, exibe a página
        log_message('info', '📄 Carregando página de expedientes (GET)...');

        try {
            $expedientes = $this->expedienteModel->findAll();

            $data = [
                'titulo' => 'Gerenciando o horário de funcionamento',
                'expedientes' => $expedientes,
            ];

            log_message('info', '✅ Página carregada com ' . count($expedientes) . ' expedientes');
            return view('Admin/Expedientes/expedientes', $data);
        } catch (\Exception $e) {
            log_message('error', '❌ Erro ao carregar expedientes: ' . $e->getMessage());
            return redirect()->back()->with('erro', 'Erro ao carregar dados: ' . $e->getMessage());
        }
    }

    public function salvarExpedientes() {
        log_message('info', 'Iniciando processo de salvamento...');

        try {
            $ids = $this->request->getPost('id');
            $aberturas = $this->request->getPost('abertura');
            $fechamentos = $this->request->getPost('fechamento');
            $situacoes = $this->request->getPost('situacao');
            $virasDia = $this->request->getPost('vira_dia') ?? [];

            log_message('info', 'Dados recebidos - IDs: ' . json_encode($ids));
            log_message('info', 'Dados recebidos - Aberturas: ' . json_encode($aberturas));
            log_message('info', 'Dados recebidos - Fechamentos: ' . json_encode($fechamentos));
            log_message('info', 'Dados recebidos - Situações: ' . json_encode($situacoes));

            $errors = [];
            $success = true;
            $atualizados = 0;

            // Validações básicas
            if (!$ids || !$aberturas || !$fechamentos || !$situacoes) {
                log_message('error', 'Dados não enviados corretamente');
                log_message('error', 'IDs: ' . ($ids ? 'OK' : 'NULL'));
                log_message('error', 'Aberturas: ' . ($aberturas ? 'OK' : 'NULL'));
                log_message('error', 'Fechamentos: ' . ($fechamentos ? 'OK' : 'NULL'));
                log_message('error', 'Situações: ' . ($situacoes ? 'OK' : 'NULL'));
                return redirect()->back()->with('erro', 'Dados não foram enviados corretamente.');
            }

            $countIds = count($ids);
            $countAberturas = count($aberturas);
            $countFechamentos = count($fechamentos);
            $countSituacoes = count($situacoes);

            log_message('info', "Contagens - IDs: $countIds, Aberturas: $countAberturas, Fechamentos: $countFechamentos, Situações: $countSituacoes");

            if ($countIds !== $countAberturas || $countIds !== $countFechamentos || $countIds !== $countSituacoes) {
                log_message('error', 'Dados inconsistentes - tamanhos diferentes dos arrays');
                return redirect()->back()->with('erro', 'Dados inconsistentes enviados.');
            }

            // Processa cada expediente
            for ($i = 0; $i < $countIds; $i++) {
                $id = $ids[$i];
                $abertura = $aberturas[$i];
                $fechamento = $fechamentos[$i];
                $situacao = $situacoes[$i];
                $viraDia = isset($virasDia[$id]) ? 1 : 0;

                // Valida horários (apenas se aberto e sem virada de dia)
                if ($situacao == 1 && !$this->expedienteModel->validarHorarioExpediente($abertura, $fechamento, (bool)$viraDia)) {
                    $success = false;
                    $errors[] = "Expediente ID $id: O horário de fechamento deve ser posterior ao de abertura (ou marque 'Vira dia').";
                    continue;
                }

                $dados = [
                    'abertura'  => $abertura,
                    'fechamento' => $fechamento,
                    'vira_dia'  => $viraDia,
                    'situacao'  => $situacao,
                ];

                log_message('info', "Tentando atualizar expediente $id com dados: " . json_encode($dados));

                // Tenta atualizar o registro
                $resultado = $this->expedienteModel->update($id, $dados);

                if ($resultado) {
                    $atualizados++;
                    log_message('info', "Expediente $id atualizado com sucesso");
                } else {
                    $success = false;
                    $modelErrors = $this->expedienteModel->errors();
                    log_message('error', "Erro ao atualizar expediente $id: " . json_encode($modelErrors));

                    if (!empty($modelErrors)) {
                        $errors = array_merge($errors, $modelErrors);
                    } else {
                        $errors[] = "Erro ao atualizar expediente ID $id";
                    }
                }
            }

            log_message('info', "Processo finalizado - Atualizados: $atualizados, Erros: " . count($errors));

            if ($atualizados > 0) {
                log_message('info', 'Redirecionando com mensagem de sucesso');
                \Config\Services::cache()->delete('expedientes');

                if (!empty($errors)) {
                    return redirect()->to('admin/expedientes')
                                    ->with('sucesso', "$atualizados expediente(s) atualizado(s) com sucesso!")
                                    ->with('errors_model', $errors);
                } else {
                    return redirect()->to('admin/expedientes')
                                    ->with('sucesso', "Expedientes atualizados com sucesso! ($atualizados registros)");
                }
            } else {
                log_message('error', 'Nenhum registro foi atualizado. Erros: ' . json_encode($errors));
                return redirect()->back()->with('erro', 'Nenhum expediente foi atualizado.')->with('errors_model', $errors);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exceção capturada: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('erro', 'Erro interno durante o salvamento: ' . $e->getMessage());
        }
    }
}
