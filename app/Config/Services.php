<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Arquivo de configuração de serviços.
 *
 * Serviços são simplesmente outras classes/bibliotecas que o sistema usa
 * para fazer seu trabalho. Isso é usado pelo CodeIgniter para permitir que o núcleo do
 * framework seja facilmente trocado sem afetar o uso dentro
 * do restante do seu aplicativo.
 *
 * Este arquivo contém quaisquer serviços específicos do aplicativo ou substituições de serviço
 * que você possa precisar. Um exemplo foi incluído com o formato geral
 * de método que você deve usar para seus métodos de serviço. Para mais exemplos,
 * consulte o arquivo principal de serviços em system/Config/Services.php.
 */
class Services extends BaseService {

    public static function autenticacao($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('autenticacao');
        }

        return new \App\Libraries\Autenticacao ;
    }
}
