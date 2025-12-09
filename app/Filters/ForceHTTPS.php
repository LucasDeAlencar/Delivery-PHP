<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ForceHTTPS implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verifica se o forçamento de HTTPS está habilitado
        $appConfig = config('App');
        $httpsConfig = config('HTTPSSecurity');
        
        // Só força HTTPS se estiver configurado e não for ambiente de teste
        if ($appConfig->forceGlobalSecureRequests && !$request->isSecure() && ENVIRONMENT !== 'testing') {
            // Constrói a URL HTTPS
            $httpsURL = 'https://' . $request->getServer('HTTP_HOST') . $request->getServer('REQUEST_URI');
            
            // Redireciona para HTTPS
            return redirect()->to($httpsURL, 301);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Adiciona cabeçalhos de segurança HTTPS
        if ($request->isSecure()) {
            $httpsConfig = config('HTTPSSecurity');
            
            if ($httpsConfig->enableHSTS) {
                $hstsHeader = 'max-age=' . $httpsConfig->hstsMaxAge;
                if ($httpsConfig->hstsIncludeSubdomains) {
                    $hstsHeader .= '; includeSubDomains';
                }
                $response->setHeader('Strict-Transport-Security', $hstsHeader);
            }
        }
        
        return $response;
    }
}