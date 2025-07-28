<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class HTTPSSecurity extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Force HTTPS
     * --------------------------------------------------------------------------
     *
     * Force all requests to use HTTPS
     */
    public bool $forceHTTPS = false;

    /**
     * --------------------------------------------------------------------------
     * HSTS (HTTP Strict Transport Security)
     * --------------------------------------------------------------------------
     *
     * Enable HSTS header
     */
    public bool $enableHSTS = true;

    /**
     * --------------------------------------------------------------------------
     * HSTS Max Age
     * --------------------------------------------------------------------------
     *
     * HSTS max age in seconds (1 year = 31536000)
     */
    public int $hstsMaxAge = 31536000;

    /**
     * --------------------------------------------------------------------------
     * HSTS Include Subdomains
     * --------------------------------------------------------------------------
     *
     * Include subdomains in HSTS policy
     */
    public bool $hstsIncludeSubdomains = true;

    /**
     * --------------------------------------------------------------------------
     * SSL Redirect Status Code
     * --------------------------------------------------------------------------
     *
     * HTTP status code for SSL redirects
     */
    public int $sslRedirectCode = 301;

    /**
     * --------------------------------------------------------------------------
     * Secure Cookies
     * --------------------------------------------------------------------------
     *
     * Force cookies to be sent only over HTTPS
     */
    public bool $secureCookies = true;
}