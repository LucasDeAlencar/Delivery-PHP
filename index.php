<?php
// CODEIGNITER 4 - VERSÃO COMPATÍVEL
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

// Mostrar erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
chdir(FCPATH);

// LOAD OUR PATHS CONFIG FILE
$pathsConfig = FCPATH . 'app/Config/Paths.php';

// Verificar se o arquivo existe
if (!file_exists($pathsConfig)) {
    die('ERROR: Paths config not found at: ' . $pathsConfig);
}

require $pathsConfig;
$paths = new Config\Paths();

// Verificar se o system directory existe
if (!is_dir($paths->systemDirectory)) {
    die('ERROR: System directory not found: ' . $paths->systemDirectory);
}

// LOAD THE FRAMEWORK BOOTSTRAP FILE
$bootFile = $paths->systemDirectory . '/Boot.php';

if (!file_exists($bootFile)) {
    die('ERROR: Boot.php not found at: ' . $bootFile);
}

require $bootFile;

// Boot the application
exit(CodeIgniter\Boot::bootWeb($paths));
