<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class UsuariosPublico extends BaseController {

    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index() {
        // Busca todos os usuários do banco de dados
        $usuarios = $this->usuarioModel->withDeleted(true)->findAll();

        $data = [
            'titulo' => 'Relatório de Usuários',
            'usuarios' => $usuarios
        ];

        return view('Publico/usuarios.php', $data);
    }
}