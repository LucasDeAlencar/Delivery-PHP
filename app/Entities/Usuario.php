<?php

namespace App\Entities;

use App\Libraries\Token;
use CodeIgniter\Entity\Entity;

class Usuario extends Entity
{
    protected $datamap = [];
    protected $dates   = ['criado_em', 'atualizado_em', 'deletado_em'];
    protected $casts   = [
        'ativo' => 'boolean',
        'is_admin' => 'boolean',
    ];
    
    // Campos que podem ser preenchidos em massa
    protected $attributes = [
        'id' => null,
        'nome' => null,
        'email' => null,
        'cpf' => null,
        'telefone' => null,
        'ativo' => true,
        'is_admin' => false,
        'password_hash' => null,
        'ativacao_hash' => null,
        'reset_hash' => null,
        'reset_expira_em' => null,
        'criado_em' => null,
        'atualizado_em' => null,
        'deletado_em' => null,
    ];
    
    public function verificaPassword(string $password){
        return password_verify($password, $this->password_hash);
    }
    
    public function iniciaPasswordReset() {
        
        /* Instancio novo objeto da classe Token */
        $token = new Token();
        
        /**
         * @Descricao: atribuimos ao objeto Entitie Usuario($this) o atributo 'reset_token' que contera o token gerado
         */
        $this->reset_token = $token->getValue();
        
        
        
        /**
         * @Descricao: Atribuimos ao objeto Entitie Usuario ($this) o atributo 'reset_hash' que conterá o hash do token
         */
        $this->reset_hash = $token->getHash();
        
        $this->reset_expira_em = date('Y-m-d H:i:s', time() + 7200); // Expira em 2hs a patir da data e hora atual
    }
    
    public function completaPasswordReset() {
        $this->reset_hash = null;
        $this->reset_expira_em = null;
    }
}
