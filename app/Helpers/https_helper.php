<?php

/**
 * Helper para forçar HTTPS
 */

if (!function_exists('force_https')) {
    /**
     * Força redirecionamento para HTTPS
     *
     * @return void
     */
    function force_https()
    {
        if (!is_https() && ENVIRONMENT !== 'testing') {
            $httpsURL = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header("Location: $httpsURL", true, 301);
            exit();
        }
    }
}

if (!function_exists('is_https')) {
    /**
     * Verifica se a conexão está usando HTTPS
     *
     * @return bool
     */
    function is_https(): bool
    {
        return (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
            (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
        );
    }
}

if (!function_exists('get_https_url')) {
    /**
     * Converte uma URL para HTTPS
     *
     * @param string $url
     * @return string
     */
    function get_https_url(string $url): string
    {
        return str_replace('http://', 'https://', $url);
    }
}

if (!function_exists('secure_base_url')) {
    /**
     * Retorna a base URL sempre com HTTPS
     *
     * @param string $uri
     * @return string
     */
    function secure_base_url(string $uri = ''): string
    {
        $baseURL = config('App')->baseURL;
        $secureBaseURL = get_https_url($baseURL);
        
        return rtrim($secureBaseURL, '/') . '/' . ltrim($uri, '/');
    }
}