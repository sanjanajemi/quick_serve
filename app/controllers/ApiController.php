<?php
use App\Models\MenuModel;

class ApiController {
    public function searchSuggestions() {
        $query = $_GET['query'] ?? '';
        $menuModel = new MenuModel();
        $results = $menuModel->searchNames($query);

        header('Content-Type: application/json');
        echo json_encode($results);
    }
}