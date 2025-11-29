<?php

namespace App\Helpers;

class SessionHelper
{
    public static function requireStaffLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
            header('Location: /login');
            exit;
        }
    }
}
