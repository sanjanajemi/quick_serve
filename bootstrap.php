<?php

session_start();

// Load configuration
$config = require __DIR__ . '/config/config.php';

// Set timezone if defined
if (isset($config['timezone'])) {
    date_default_timezone_set($config['timezone']);
}

// Enable error reporting in development
if (!empty($config['debug'])) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Autoload classes
require_once $config['autoload'] ?? __DIR__ . '/config/autoload.php';

// Connect to database
$dbConfig = $config['db'];
try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}",
        $dbConfig['username'],
        $dbConfig['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start routing
require_once __DIR__ . '/app/core/Router.php';