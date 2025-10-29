<?php
// Front controller adapted from CodeIgniter 3.x default index.php
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
$system_path = 'system';
$application_folder = 'application';

// Error reporting based on environment
switch(ENVIRONMENT) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 0); // Cambiar a 0 para evitar "headers already sent"
        break;
    case 'production':
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

// Resolve the system path
if (realpath($system_path) !== FALSE) {
    $system_path = realpath($system_path).'/';
}
// Ensure there's a trailing slash
$system_path = rtrim($system_path, '/').'/';

// Is the system path correct?
if ( ! is_dir($system_path)) {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo "Your system folder path does not appear to be set correctly. Please open the following file and correct this: index.php";
    exit(3); // EXIT_CONFIG
}

// Name of this file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// Path to the front controller (this file)
define('FCPATH', dirname(__FILE__).'/');

// Path to the system folder
define('BASEPATH', str_replace('\\', '/', $system_path));

// The name of the "system" directory
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

// Path to the application folder
if (is_dir($application_folder)) {
    if (realpath($application_folder) !== FALSE) {
        $application_folder = realpath($application_folder).'/';
    } else {
        $application_folder = rtrim($application_folder, '/').'/';
    }
    define('APPPATH', $application_folder);
} else {
    if ( ! is_dir(BASEPATH.$application_folder.'/')) {
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo "Your application folder path does not appear to be set correctly. Please open the following file and correct this: index.php";
        exit(3); // EXIT_CONFIG
    }
    define('APPPATH', BASEPATH.$application_folder.'/');
}

// Views path
define('VIEWPATH', APPPATH.'views/');

require_once BASEPATH.'core/CodeIgniter.php';
