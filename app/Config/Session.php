<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\DatabaseHandler;

class Session extends BaseConfig
{
    // Banco de dados: funciona em qualquer hospedagem sem depender de permissão de pasta
    public string $driver = DatabaseHandler::class;

    public string $cookieName = 'ci_session';

    // 8 horas
    public int $expiration = 28800;

    // Nome da tabela no banco
    public string $savePath = 'ci_sessions';

    // false: não invalida sessão ao trocar de IP (proxies, CDN, mobile)
    public bool $matchIP = false;

    // 0: nunca regenera o session ID automaticamente — evita o loop de redirect
    public int $timeToUpdate = 0;

    // false: não destrói dados ao regenerar (redundante com timeToUpdate=0, mas seguro)
    public bool $regenerateDestroy = false;

    public ?string $DBGroup = null;

    public int $lockRetryInterval = 100_000;
    public int $lockMaxRetries = 300;
}
