<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| El valor de base_url debe incluir el puerto si la app se sirve en un puerto no estándar.
| En docker-compose este proyecto expone la web en el puerto 8080 del host.
|
*/
$config['base_url'] = 'http://127.0.0.1:8080/';

/* Nombre del archivo index (si no usas mod_rewrite mantener index.php) */
$config['index_page'] = '';

/* Otros valores básicos por defecto necesarios para que CI arranque */
$config['uri_protocol']    = 'REQUEST_URI';
$config['url_suffix']      = '';
$config['language']        = 'english';
$config['charset']         = 'UTF-8';
$config['enable_hooks']    = FALSE;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

// Subclass prefix for extending CI libraries
$config['subclass_prefix'] = 'MY_';

// Composer autoload
$config['composer_autoload'] = FALSE;

// Session (usar por defecto filesystem). Use a safe temp dir inside container.
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = sys_get_temp_dir();

// Encryption key (set a real key for production)
$config['encryption_key'] = 'change_this_for_production';

// Logging defaults to avoid undefined index notices
$config['log_path'] = '';
$config['log_threshold'] = 0;
$config['log_date_format'] = 'Y-m-d H:i:s';

// Other defaults (keep minimal)
$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['global_xss_filtering'] = FALSE;

/* End of file config.php */
