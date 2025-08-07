<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController {

    public function __construct() {
        helper(['form']);
    }

    public function novo() {
        $data = [
            'titulo' => "Realize o login "
        ];

        return view('Login/novo', $data);
    }
    
    public function criar() {
        
        // Log para debug (removido em produção)
        
        if($this->request->getMethod() !== 'post'){
            
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            // Logs removidos para produção
            
            // Validação básica
            if(empty($email) || empty($password)){
                return redirect()->back()->with('atencao', 'Por favor, preencha todos os campos');
            }
            
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                return redirect()->back()->with('atencao', 'Por favor, digite um e-mail válido');
            }
            
            $autenticacao = service('autenticacao');
            
            // Log removido para produção
            
            if($autenticacao->login($email,$password)){
                
                // Log removido para produção
                
                $usuario = $autenticacao->pegaUsuarioLogado();
                
                if(!$usuario->is_admin){
                    
                return redirect()->to(site_url('/'));
                }
                
                return redirect()->to(site_url('admin/home'))->with('sucesso', "Olá $usuario->nome, que bom que está de volta");
            }
            
            // Log removido para produção
            return redirect()->back()->with('atencao', 'E-mail ou senha incorretos');
            
        }
        
        return redirect()->back()->with('atencao', 'Método não permitido');
        
    }
    
    /**
     * VAMOS ALTERAR ESSE MÉTODO
     */
    public function logout() {
        service('autenticacao')->logout();
        
        return redirect()->to(site_url('login/mostraMensagemLogout'));
    }
    
    public function mostraMensagemLogout() {
        
        return redirect()->to(site_url("login"))->with('info', 'Esperamos ver você novamente');
        
    }
}
