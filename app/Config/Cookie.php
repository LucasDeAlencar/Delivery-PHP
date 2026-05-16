<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cookie extends BaseConfig
{
    public string $prefix   = '';
    public $expires         = 0;
    public string $path     = '/';
    // Domínio vazio: o browser usa o domínio atual automaticamente
    public string $domain   = '';
    // false: funciona em HTTP e HTTPS
    public bool $secure     = false;
    public bool $httponly   = true;
    // Lax: compatível com redirects normais (POST→GET)
    public string $samesite = 'Lax';
    public bool $raw        = false;
}
