<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Expedientes extends BaseController{
    
    private $expedienteModel;
    
    public function __construct() {
        $this->expedienteModel = new \App\Models\ExpedienteModel();
    }
    
    public function expedientes() {
        // Se for POST, processa o salvamento
        if ($this->request->getMethod() === 'post') {
            log_message('info', 'Recebido POST para salvamento de expedientes');
            return $this->salvarExpedientes();
        }
        
        // Se for GET, exibe a página
        log_message('info', 'Carregando página de expedientes...');
        try {
            $expedientes = $this->expedienteModel->findAll();
            
            // Se não há expedientes, tenta executar o seeder
            if (empty($expedientes)) {
                log_message('info', 'Nenhum expediente encontrado, executando seeder...');
                $seeder = \Config\Database::seeder();
                $seeder->call('ExpedienteSeeder');
                $expedientes = $this->expedienteModel->findAll();
                log_message('info', 'Seeder executado, encontrados ' . count($expedientes) . ' expedientes');
            }
            
            $data = [
                'titulo' => 'Gerenciando o horário de funcionamento',
                'expedientes' => $expedientes,
            ];
            
            log_message('info', 'Carregando view com ' . count($expedientes) . ' expedientes');
            return view('Admin/Expedientes/expedientes', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro ao carregar expedientes: ' . $e->getMessage());
            return redirect()->back()->with('erro', 'Erro ao carregar dados dos expedientes: ' . $e->getMessage());
        }
    }
    
    private function salvarExpedientes() {
        log_message('info', 'Iniciando processo de salvamento...');
        
        try {
            $ids = $this->request->getPost('id');
            $aberturas = $this->request->getPost('abertura');
            $fechamentos = $this->request->getPost('fechamento');
            $situacoes = $this->request->getPost('situacao');
            
            $errors = [];
            $success = true;
            $atualizados = 0;
            
            // Validações básicas
            if (!$ids || !$aberturas || !$fechamentos || !$situacoes) {
                log_message('error', 'Dados não enviados corretamente');
                return redirect()->back()->with('erro', 'Dados não foram enviados corretamente.');
            }
            
            $countIds = count($ids);
            $countAberturas = count($aberturas);
            $countFechamentos = count($fechamentos);
            $countSituacoes = count($situacoes);
            
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
                
                // Valida horários antes de salvar
                if (!$this->expedienteModel->validarHorarioExpediente($abertura, $fechamento)) {
                    $success = false;
                    $error = "Expediente ID $id: O horário de fechamento deve ser posterior ao horário de abertura.";
                    $errors[] = $error;
                    log_message('error', $error);
                    continue;
                }
                
                $dados = [
                    'abertura' => $abertura,
                    'fechamento' => $fechamento,
                    'situacao' => $situacao
                ];
                
                // Tenta atualizar o registro
                if ($this->expedienteModel->protect(false)->update($id, $dados)) {
                    $atualizados++;
                    log_message('info', "Expediente $id atualizado com sucesso");
                } else {
                    $success = false;
                    $modelErrors = $this->expedienteModel->errors();
                    $errors = array_merge($errors, $modelErrors);
                }
            }
            
            if ($success && $atualizados > 0) {
                log_message('info', 'Redirecionando com mensagem de sucesso');
                return redirect()->to('admin/expedientes')->with('sucesso', "Expedientes atualizados com sucesso! ($atualizados registros)");
            } else {
                log_message('error', 'Redirecionando com erros: ' . json_encode($errors));
                return redirect()->back()->with('errors_model', $errors)->withInput();
            }
            
        } catch (\Exception $e) {
            return redirect()->back()->with('erro', 'Erro interno durante o salvamento: ' . $e->getMessage());
        }
    }
}