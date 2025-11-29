<?php

$routes = require_once __DIR__ . '/../../config/routes.php';

$path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$baseFolder = '/quick_serve';
if (strpos($path, $baseFolder) === 0) {
    $path = substr($path, strlen($baseFolder));
    if ($path === '') $path = '/';
}

if (isset($routes[$path])) {
    [$controllerName, $method] = $routes[$path];

    
    $fullyQualifiedController = 'App\\Controllers\\' . $controllerName;

    if (class_exists($fullyQualifiedController)) {
        $controller = new $fullyQualifiedController();

        if (method_exists($controller, $method)) {
            $controller->$method();
        } else {
            require_once __DIR__ . '/../views/errors/404.php';
        }
    } else {
        require_once __DIR__ . '/../views/errors/404.php';
    }
} else {
    require_once __DIR__ . '/../views/errors/404.php';
}