<?php

namespace App\Core;

class View
{
   public static function render($viewPath, $data = [])
{
    $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $viewPath) . '.php';

    if (file_exists($viewFile)) {
        extract($data); 
        include $viewFile;
    } else {
        error_log("View not found: " . $viewFile);
        echo "<h3>404 - View not found</h3>";
    }
}
}