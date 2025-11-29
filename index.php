<?php

session_start();

// Load configuration
$config = require_once __DIR__ . '/config/config.php';

// Set timezone if defined
if (isset($config['timezone'])) {
    date_default_timezone_set($config['timezone']);
}

// Enable error reporting if debug mode is on
if (!empty($config['debug'])) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Autoload classes
require_once $config['autoload'] ?? __DIR__ . '/config/autoload.php';

// Start routing
require_once __DIR__ . '/app/core/Router.php';