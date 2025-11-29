<?php

return [
    // Base URL of your project
    'base_url' => 'http://localhost/quick_serve',

    // Environment
    'env' => 'development',

    // Timezone
    'timezone' => 'Europe/Copenhagen',

    // Database configuration
    'db' => [
        'host' => 'localhost',
        'name' => 'brock_cafe', // or 'quick_serve_db' if that's correct
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],

    // Default controller and method
    'default_route' => [
        'controller' => 'HomeController',
        'method' => 'dashboard',
    ],

    // Error reporting
    'debug' => true,

    // Paths
    'paths' => [
        'controllers' => __DIR__ . '/../app/controllers/',
        'models' => __DIR__ . '/../app/models/',
        'views' => __DIR__ . '/../app/views/',
        'core' => __DIR__ . '/../app/core/',
    ],

    // Autoloader path (optional)
    'autoload' => __DIR__ . '/autoload.php',
];