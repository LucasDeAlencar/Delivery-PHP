<?php

/**
 * Helper para carregar assets de forma confiável
 * Funciona tanto em desenvolvimento quanto em produção
 */

if (!function_exists('asset_url')) {
    /**
     * Gera URL para assets (CSS, JS, imagens)
     * Usa caminho relativo para garantir compatibilidade
     *
     * @param string $path Caminho do asset (ex: 'web/src/css/style.css')
     * @return string URL completa do asset
     */
    function asset_url(string $path): string
    {
        // Remove barra inicial se houver
        $path = ltrim($path, '/');
        
        // Usa base_url que já detecta o ambiente automaticamente
        return base_url($path);
    }
}

if (!function_exists('public_asset')) {
    /**
     * Gera URL para assets na pasta public
     *
     * @param string $path Caminho do asset (ex: 'assets/css/carrinho.css')
     * @return string URL completa do asset
     */
    function public_asset(string $path): string
    {
        $path = ltrim($path, '/');
        return base_url($path);
    }
}

if (!function_exists('web_asset')) {
    /**
     * Gera URL para assets na pasta web/src
     *
     * @param string $path Caminho do asset (ex: 'css/style.css')
     * @return string URL completa do asset
     */
    function web_asset(string $path): string
    {
        $path = ltrim($path, '/');
        return base_url('web/src/' . $path);
    }
}

if (!function_exists('img_url')) {
    /**
     * Gera URL para imagens
     *
     * @param string $path Caminho da imagem (ex: 'bg_1.jpg')
     * @param string $folder Pasta (padrão: 'web/src/images')
     * @return string URL completa da imagem
     */
    function img_url(string $path, string $folder = 'web/src/images'): string
    {
        $path = ltrim($path, '/');
        $folder = trim($folder, '/');
        return base_url($folder . '/' . $path);
    }
}
