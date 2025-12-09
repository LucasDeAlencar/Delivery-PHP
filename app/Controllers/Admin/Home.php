<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsuarioModel; // Adicione esta linha

class Home extends BaseController
{
    public function index()
    {
        // Carregar o modelo de usuários
        $usuarioModel = new UsuarioModel();
        
        // Buscar todos os usuários
        $usuarios = $usuarioModel->findAll();
        
        $data = [
            'titulo' => 'Home da área restrita',
            'usuarios' => $usuarios, // Passar os usuários para a view
        ];
        
        return view('Admin/Usuarios/index', $data);
    }
}