<?php

namespace App\Controllers;
use App\Core\View;
use App\Models\MenuModel;

class HomeController
{
    public function dashboard()
    {
        View::render('home.dashboard');
    }

    public function menu()
    {
        $menuModel = new MenuModel();

        $category = $_GET['category'] ?? '';
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        // Fetch items based on search or category
        if ($search) {
            $menuItems = $menuModel->searchItems($search, $category);
        } elseif ($category) {
            $menuItems = $menuModel->getItemsByCategory($category);
        } else {
            $menuItems = $menuModel->getAvailableItems();
        }

        // Get total count of published items
        $totalItems = $menuModel->countItems($search, $category);
        $totalPages = ceil($totalItems / $limit);

        // Get paginated published items
        $menuItems = $menuModel->getPaginatedItems($search, $category, $limit, $offset);

        // Sanitize input for view
        $category = htmlspecialchars($category);
        $search = htmlspecialchars($search);

        require_once __DIR__ . '/../views/home/menu_list.php';
    }

    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['customer_id'])) {
            header('Location: /quick_serve/customer/login');
            exit;
        }

        $customerModel = new \App\Models\CustomerModel();
        $customer = $customerModel::findById($_SESSION['customer_id']);

        require_once __DIR__ . '/../views/home/profile.php';
    }
}