<?php

namespace App\Libraries;

/*
 * @descrição essa biblioteca / classe cuidará da parte de auteticação na nossa aplicação
 */

class Autenticacao{
    private $usuario;
    
    public function login(string $email, string $password) {
        
        log_message('info', 'Autenticacao::login() - Email: ' . $email);
        
        $usuarioModel = new \App\Models\UsuarioModel();
        
        $usuario = $usuarioModel->buscaUsuarioPorEmail($email);
        
        log_message('info', 'Usuário encontrado: ' . ($usuario ? 'SIM' : 'NÃO'));
        
        /* Se não encontrar o usuário por e-mail, retorna false */
        if($usuario === null){
            return false;
        }
        
        /* Se não encontrar o usuário por e-mail, retorna false*/
        $senhaCorreta = $usuario->verificaPassword($password);
        log_message('info', 'Senha correta: ' . ($senhaCorreta ? 'SIM' : 'NÃO'));
        
        if(!$senhaCorreta){
            return false;
        }
        
        /* Só permitiremos o login de usuários ativos */
        log_message('info', 'Usuário ativo: ' . ($usuario->ativo ? 'SIM' : 'NÃO'));
        
        if(!$usuario->ativo){
            return false;
        }
        
        // Nesse ponto está tudo certo e podemos logar o usuário na aplicação invocando o método abaixo
        log_message('info', 'Logando usuário na sessão...');
        $this->logaUsuario($usuario);
        log_message('info', 'Login concluído com sucesso!');
        return true;
        
    }
    
    public function logout(){
        session()->destroy();
    }

    public function pegaUsuarioLogado(){
        
        /** 
         * Não esquecer de compartilhar a instancia  
         */
        if($this->usuario === null){
            $this->usuario = $this->pegaUsuarioDaSessao();
        }
        
        /* Retornamos o usuario*/
        return $this->usuario;
    }
    
    /**
     * @descrição: O método só permite ficar logado na aplicação aquele que ainda existir na base e que esteja ativo.
     *             Do contrário será feito o logout do mesmo, caso haja uma mudança na sua conta durante a sua sessão
     * 
     * @uso: No filtro LoginFilter
     * @return type
     */
    public function estaLogado() {
        return $this->pegaUsuarioLogado() !== null;
    }
    
    private function pegaUsuarioDaSessao (){
        
        if(!session()->has('usuario_id')){
            return null;
        }
        
        /* Instanciamos o model usuário*/
        $usuarioModel = new \App\Models\UsuarioModel();
        
        /* Recupero o usrário de acordo com a chave de sessão 'usuario_id ' */
        $usuario = $usuarioModel->find(session()->get('usuario_id'));
        
        if($usuario && $usuario->ativo){
            return $usuario;
        }
        
        return null;
    }

    private function logaUsuario(object $usuario){
        $session = session();
        $session->regenerate();
        $session->set('usuario_id', $usuario->id);
    }
} 

