<?php
error_reporting(E_ALL);
include 'application/config/config.php';

use Application\Core\Router;

//Autoload classes
spl_autoload_register(function ($class) {
    $resArr = explode('\\', $class);
    $className = end($resArr) . '.php';
    unset($resArr[count($resArr) - 1]);

    $path = strtolower(SITE_PATH . implode('/', $resArr)) . DIRECTORY_SEPARATOR . $className;

    if (file_exists($path)) {
        require $path;
    }
});

session_start();

$router = new Router();
$router->run();