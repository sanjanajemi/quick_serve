<?php
namespace App\Controllers;

use App\Models\MenuModel;

class MenuController {
    public function item() {
        $menuModel = new MenuModel();
        $id = $_GET['id'] ?? null;


        // validation for id
        if (!$id || !is_numeric($id)) {
            require 'views/errors/404.php';
            return;
        }

        // Only fetch item if it's published
        $menuItem = $menuModel->getItemById((int)$id);

        if (!$menuItem || $menuItem['status'] !== 'published') {
            require 'views/errors/404.php';
            return;
        }

        require_once __DIR__ . '/../views/home/menu_item.php';
    }
}