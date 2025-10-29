<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'tasks';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// API routes
$route['api/tasks'] = 'api/Task_api';
$route['api/tasks/(:num)'] = 'api/Task_api/item/$1';

// Nice routes for web controller
$route['create'] = 'tasks/create';
$route['edit/(:num)'] = 'tasks/edit/$1';
$route['delete/(:num)'] = 'tasks/delete/$1';
$route['toggle/(:num)'] = 'tasks/toggle/$1';

?>
