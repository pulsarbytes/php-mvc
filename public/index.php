<?php
use Core\Router;

/**
 * Composer autoloader
 */
require_once '../vendor/autoload.php';

/**
 * Error and exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Router();

// Add routes
$router->add('', ['controller' => 'Form', 'action' => 'display']);
$router->add('admin', ['namespace' => 'Admin', 'controller' => 'Dashboard', 'action' => 'display']);
$router->add('admin/{controller}', ['namespace' => 'Admin', 'action' => 'display']);
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
$router->add('{controller}', ['action' => 'display']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

// Get the requested route url
$url = $_SERVER['QUERY_STRING'];

// Dispatch the url
$router->dispatch($url);
