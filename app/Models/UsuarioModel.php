<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Token;

class UsuarioModel extends Model {

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'App\Entities\Usuario';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['nome', 'email', 'cpf', 'telefone', 'is_admin', 'ativo','password', 'password_hash', 'ativacao_hash', 'reset_hash', 'reset_expira_em'];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts = [];
    protected array $castHandlers = [];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';
    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[4]|max_length[120]',
        'email' => 'required|valid_email|is_unique[usuarios.email]',
        'cpf' => 'required|exact_length[14]|validaCpf|is_unique[usuarios.cpf]',
        'telefone' => 'required',
        'password' => 'required|min_length[6]',
        'password_confirm' => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório',
        ],
        'email' => [
            'required' => 'O campo E-mail é obrigatório',
            'is_unique' => 'Infelizmente esse email já existe',
        ],
        'cpf' => [
            'required' => 'O campo CPF é obrigatório',
            'is_unique' => 'Infelizmente esse CPF já existe',
        ],
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['hashPassword'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function hashPassword(array $data) {
        if (isset($data['data']['password'])) {

            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

            unset($data['data']['password']);
            unset($data['data']['password_confirmation']);
        }

        return $data;
    }

    public function desabilitaValidacaoSenha() {

        unset($this->validationRules['password']);
        unset($this->validationRules['password_confirm']);
    }

    public function configurarValidacaoParaAtualizacao($id) {
        // Permite que o próprio registro mantenha email e CPF únicos
        $this->validationRules['email'] = "required|valid_email|is_unique[usuarios.email,id,$id]";
        $this->validationRules['cpf'] = "permit_empty|exact_length[14]|is_unique[usuarios.cpf,id,$id]";
        $this->validationRules['telefone'] = "required";
    }

    public function desfazerExclusao(int $id) {

        return $this->protect(false)
                        ->where('id', $id)
                        ->set('deletado_em', null)
                        ->update();
    }

    /**
     * @uso Classe Autenticacao
     * @param string $email
     * @return objeto $usuario
     */
    public function buscaUsuarioPorEmail(string $email) {
        return $this->where('email', $email)->first();
    }

    public function buscaUsuarioParaResetarSenha(string $token) {
        $token = new Token($token);

        $tokenHash = $token->getHash();

        $usuario = $this->where('reset_hash', $tokenHash)->first();

        if ($usuario != null) {

            if ($usuario->reset_expira_em < date('Y-m-d H:i:s')) {
                $usuario = null;
            }
            
            return $usuario;
        }
    }
}
