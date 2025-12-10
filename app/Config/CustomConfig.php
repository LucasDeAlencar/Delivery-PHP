<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class CustomConfig extends BaseConfig
{
    /**
     * Configurações personalizadas para suprimir mensagens de debug
     */
    public function __construct()
    {
        parent::__construct();
        
        // Suprimir todas as mensagens de debug em produção
        if (ENVIRONMENT === 'production') {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
            ini_set('display_errors', '0');
            ini_set('display_startup_errors', '0');
            ini_set('log_errors', '1');
        }
    }
}