<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'titulo' => 'Home da área restrita',
        ];
        
        return view('Home/index.php', $data);
    }
}
