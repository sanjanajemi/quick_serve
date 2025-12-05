<?php

//Admin interface uses this controller
namespace App\Controllers;

use App\Core\View;
use App\Models\AdminModel;
use App\Models\StaffModel;
use App\Models\MenuModel;
use App\Models\OrderModel;
use App\Models\CustomerModel;


class AdminController
{
    // Admin Login
    public function login()
    {
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminId = $_POST['admin_id'];
            $password = $_POST['password'];

            $admin = AdminModel::findById($adminId);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['role'] = 'admin';
                $_SESSION['admin_id'] = $admin['admin_id'];
                header('Location: /quick_serve/admin/dashboard');
                exit;
            } else {
                View::render('admin.login', ['error' => 'Invalid Admin ID or password']);
            }
        } else {
            View::render('admin.login');
        }
    }

 
    // Logout Admin
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        header('Location: /quick_serve');
        exit;
    }

    // Admin Dashboard
   public function dashboard()
{
    $this->requireAdmin();

    // Chart data for last 7 days
   $weeklySummary = OrderModel::getLast7DaysSummary();

    // Today's orders
    $ordersToday = OrderModel::getOrdersByDate(date('Y-m-d'));

        // Recent orders 
    $recentOrders = OrderModel::getRecentOrders();


    // Status breakdown using exact statuses from staff/customer views
    $statusCounts = [
        'pending' => 0,
        'received' => 0,
        'preparing' => 0,
        'in progress' => 0,
        'ready' => 0,
        'ready for pickup' => 0,
        'completed' => 0,
        'cancelled' => 0,
        'canceled' => 0,
        'nostatus' => 0,
    ];

    foreach ($ordersToday ?? [] as $order) {
        $status = strtolower(trim($order['status'] ?? 'nostatus'));
        if (array_key_exists($status, $statusCounts)) {
            $statusCounts[$status]++;
        } else {
            $statusCounts['nostatus']++;
        }
    }

    $todayMetrics = [
        'total_orders' => count($ordersToday),
        'status_breakdown' => $statusCounts
    ];

   View::render('admin.dashboard', [
    'stats' => $weeklySummary,
    'weeklySummary' => $weeklySummary,
    'todayMetrics' => $todayMetrics,
    'recentOrders' => $recentOrders

]);
}

    public function search()
    {
        $this->requireAdmin();

        $query = $_GET['query'] ?? '';
        $results = [];

        if (!empty($query)) {
            $results = OrderModel::searchOrders($query); 
        }

        View::render('admin.search_results', [
            'query' => $query,
            'results' => $results
        ]);
    }

    //  View Admin Profile
    public function adminProfile()
    {
        $this->requireAdmin();
        $id = $_SESSION['admin_id'];
        $admin = AdminModel::findById($id);
        View::render('admin.admin_profile', ['admin' => $admin]);
    }

    //  Show Edit Admin Form
    public function adminEditForm()
    {
        $this->requireAdmin();
        $id = $_SESSION['admin_id'];
        $admin = AdminModel::findById($id);
        View::render('admin.admin_edit', ['admin' => $admin]);
    }

    //  Handle Admin Update
    public function adminUpdate()
    {
        $this->requireAdmin();
        $id = $_POST['id'] ?? null;
        $data = $_POST;
        $errors = [];

        // Validate input
       if (empty($data['name'])) {
    $errors[] = "Name is required.";
    } elseif (!preg_match("/^[\p{L}\s]+$/u", $data['name'])) {
     $errors[] = "Invalid name: only letters allowed.";
    }

       if (empty($data['email'])) {
    $errors[] = "Email is required.";
} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}


        // If validation fails, re-render the form with errors and old input
        if ($errors) {
            $data['admin_id'] = $id;
            View::render('admin.admin_edit', [
                'errors' => $errors,
                'admin' => $data
            ]);
            return;
        }

        // Prepare update data
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email']
        ];

        // Update admin in database
        $adminModel = new AdminModel();
        $adminModel->update((int)$id, $updateData);

        // Redirect to profile
        header('Location: /quick_serve/admin/profile');
        exit;
    }

    //  Handle Password Update
    public function adminPasswordForm()
    {
        $this->requireAdmin();
        $id = $_SESSION['admin_id'];
        $admin = AdminModel::findById($id);
        View::render('admin.admin_password', ['admin' => $admin]);
    }

    public function adminPasswordUpdate()
    {
        $this->requireAdmin();
        $id = $_SESSION['admin_id'];
        $admin = AdminModel::findById($id);

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $errors = [];

        // Validate current password
        if (!password_verify($current, $admin['password'])) {
            $errors[] = "Current password is incorrect.";
        }

        // Validate new password
        if (strlen($new) < 6) {
            $errors[] = "New password must be at least 6 characters.";
        }

        if ($new !== $confirm) {
            $errors[] = "New password and confirmation do not match.";
        }

        if ($errors) {
            View::render('admin.admin_password', ['errors' => $errors, 'admin' => $admin]);
            return;
        }

        // Update password
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $adminModel = new AdminModel();
        $adminModel->update($id, ['password' => $hashed]);

        // Redirect or show success
        View::render('admin.admin_password', ['success' => "Password updated successfully.", 'admin' => $admin]);
    }

// admin create new admin
 public function adminCreate()
{
    $this->requireAdmin();
    $data = $_POST;
    $errors = [];

    // Validate input
    if (empty($data['name'])) {
        $errors[] = "Invalid name: only letters allowed.";
    }

    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    if (empty($data['password'])) {
    $errors[] = "Password is required.";
    } elseif (strlen($data['password']) < 6) {
    $errors[] = "Password must be at least 6 characters.";
    }

    //  If validation fails, re-render profile with errors
    if ($errors) {
        $admin = AdminModel::findById($_SESSION['admin_id']);
        View::render('admin.admin_profile', [
            'errors' => $errors,
            'admin' => $admin
        ]);
        return;
    }

    //  Hash password and create admin
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $newAdminId = AdminModel::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => $hashedPassword
    ]);

    //  Check if creation succeeded
    if (!$newAdminId) {
        $admin = AdminModel::findById($_SESSION['admin_id']);
        View::render('admin.admin_profile', [
            'error' => "Failed to create admin.",
            'admin' => $admin
        ]);
        return;
    }

    //  Fetch current and new admin
    $admin = AdminModel::findById($_SESSION['admin_id']);
    $newAdmin = AdminModel::findById($newAdminId);

    //  Prepare success message
    $success = "New admin created successfully.";
    if (is_array($newAdmin)) {
        $success .= " ID: {$newAdmin['admin_id']}, Name: {$newAdmin['name']}";
    }

    // Render profile with success
    View::render('admin.admin_profile', [
        'success' => $success,
        'admin' => $admin
    ]);
}
    //  Delete Admin Account
    public function adminDelete()
    {
        $this->requireAdmin();
        session_destroy(); // Log out the user
        View::render('admin.delete_account'); // Show the countdown page
    }

    public function adminDeleteFinal()
    {
        if ($_POST['confirm'] === 'yes') {
            $adminId = $_SESSION['admin_id'] ?? null;
            if ($adminId) {
                AdminModel::deleteById((int)$adminId);
            }
            header('Location: /quick_serve');
            exit;
        }
    }
    public function cancelDeletion()
    {
        session_start(); // Restart session
        header('Location: /quick_serve/admin/profile');
        exit;
    }
    //  Staff List
    public function staffList()
    {
        $this->requireAdmin();
        $staff = StaffModel::getAll();
        View::render('admin.staff_list', ['staff' => $staff]);
    }

    //  Show Add Staff Form
    public function staffAddForm()
    {
        $this->requireAdmin();
        View::render('admin.staff_add');
    }

    //  Add New Staff
  public function createStaff()
{
    $this->requireAdmin(); // Ensure only admins can add staff

    // Collect form data
    $name     = $_POST['name'] ?? '';
    $email    = $_POST['email'] ?? '';
    $phone    = $_POST['phone'] ?? '';
    $role     = $_POST['role'] ?? '';
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

    // Handle profile picture upload
    $profilePicture = null;
    if (!empty($_FILES['profile_picture']['name'])) {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename    = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
        $targetPath  = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
            $profilePicture = $filename;
        }
    }
$success = StaffModel::create([
    'name'            => $name,
    'email'           => $email,
    'phone'           => $phone,
    'role'            => $role,
    'password'        => $password,
    'profile_picture' => $profilePicture
]);

if ($success) {
    header('Location: /quick_serve/admin/staff/list');
    exit;
}

echo " Failed to create staff. Please try again.";
exit;}

    //  Show Edit Staff Form
    public function staffEditForm()
    {
        $this->requireAdmin();
        $staffId = $_GET['id'] ?? null;

        if (!$staffId) {
            echo "Staff ID is missing.";
            return;
        }

        $staff = StaffModel::findById($staffId);
        View::render('admin.staff_edit', ['staff' => $staff]);
    }
    public function staffUpdate()
    {
        $this->requireAdmin();
        $staffId = $_POST['id'] ?? null;

        if (!$staffId) {
            echo "Staff ID is missing.";
            return;
        }

        $staff = StaffModel::findById($staffId);

        // Extract input BEFORE validation
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? '';
        $profilePicture = $staff['profile_picture'];

        // Now validate
        $validRoles = ['Manager', 'Chef', 'Waiter'];
        $errors = [];

        if (!preg_match('/^[A-Za-z\s]+$/', $name)) {
            $errors[] = 'Full Name must contain only letters and spaces.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }

        if (!empty($phone) && !preg_match('/^\d{10}$/', $phone)) {
            $errors[] = 'Phone number must be exactly 10 digits.';
        }

        if (!in_array($role, $validRoles)) {
            $errors[] = 'Invalid role selected.';
        }

        if (!empty($_FILES['profile_picture']['name'])) {
            $filename = basename($_FILES['profile_picture']['name']);
            $targetPath = __DIR__ . '/../../storage/uploads/' . $filename;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($_FILES['profile_picture']['type'], $allowedTypes)) {
                $errors[] = 'Profile picture must be a JPG or PNG image.';
            } elseif (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
                $profilePicture = $filename;
            }
        }

        if (!empty($errors)) {
            View::render('admin.staff_edit', [
                'staff' => $staff,
                'error' => implode('<br>', $errors)
            ]);
            return;
        }

        StaffModel::update($staffId, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'profile_picture' => $profilePicture
        ]);

        header('Location: /quick_serve/admin/staff/list');
        exit;
    }

    //  Delete Staff
    public function staffDelete()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        StaffModel::delete($id);
        header('Location: /quick_serve/admin/staff/list');
        exit;
    }

    //  View Staff Profile
    public function staffView()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        $staff = StaffModel::findById($id);
        View::render('admin.staff_profile', ['staff' => $staff]);
    }

    //  Access Control Helper
    private function requireAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /quick_serve/admin/login');
            exit;
        }
    }

    //Menu management
    public function menu()
    {
        $menuModel = new MenuModel();
        $menuItems = $menuModel->getAllItems(); // includes all status values
        View::render('admin.menu_list', ['menuItems' => $menuItems]);
    }

    public function menuAddForm()
    {
        $this->requireAdmin();
        View::render('admin.menu_add', ['item' => []]);
    }

    public function menuCreate()
    {
        $this->requireAdmin();
        $data = $_POST;
        $errors = [];

        if (empty($data['name'])) $errors[] = "Name is required.";
        if (!is_numeric($data['price']) || $data['price'] <= 0) $errors[] = "Price must be positive.";
        if (!in_array($data['status'], ['published', 'unpublished'])) $errors[] = "Invalid status.";

        if ($errors) {
            View::render('admin.menu_add', ['errors' => $errors, 'old' => $data]);
            return;
        }

        $menuModel = new MenuModel();
        $menuModel->create($data);
        header('Location: /quick_serve/admin/menu');
        exit;
    }

    public function menuEditForm()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            require 'views/errors/404.php';
            return;
        }

        $menuModel = new MenuModel();
        $item = $menuModel->findById((int)$id); // 

        if (!$item) {
            require 'views/errors/404.php';
            return;
        }

        View::render('admin.menu_edit', ['item' => $item]);
    }
public function menuUpdate()
{
    $this->requireAdmin();
    $id     = $_POST['id'] ?? null;
    $data   = $_POST;
    $errors = [];

    // basic validation
    if (empty($data['name'])) $errors[] = "Name is required.";
    if (!is_numeric($data['price']) || $data['price'] <= 0) $errors[] = "Price must be positive.";
    if (!in_array($data['status'], ['published','unpublished'])) $errors[] = "Invalid status.";

    $menuModel = new MenuModel();
    $existing  = $menuModel->findById((int)$id);

    // keep old image by default
    $data['image_url'] = $existing['image_url'];

    // upload new image if provided
    if (!empty($_FILES['image_file']['name'])) {
        $uploadDir = __DIR__ . '/../../public/uploads/menu/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $filename   = time().'_'.basename($_FILES['image_file']['name']);
        $targetFile = $uploadDir.$filename;

        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)) {
            // save relative path for browser access
            $data['image_url'] = "/uploads/menu/".$filename;
        } else {
            $errors[] = "Image upload failed.";
        }
    }

    // if errors, re-render form
    if ($errors) {
        $data['menu_item_id'] = $id;
        View::render('admin.menu_edit', ['errors'=>$errors,'item'=>$data]);
        return;
    }

    // update and redirect
    $menuModel->update((int)$id, $data);
    header('Location: /quick_serve/admin/menu');
    exit;
}

    public function menuDelete()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;

        if ($id && is_numeric($id)) {
            $menuModel = new MenuModel();
            $menuModel->delete((int)$id);
            $_SESSION['flash'] = "Menu item deleted successfully.";
        }

        header('Location: /quick_serve/admin/menu');
        exit;
    }

    public function publishMenu()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;

        if ($id && is_numeric($id)) {
            $menuModel = new MenuModel();
            $menuModel->updateStatus((int)$id, 'published');
            $_SESSION['flash'] = "Menu item published successfully.";
        }

        header('Location: /quick_serve/admin/menu');
        exit;
    }

    public function unpublishMenu()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;

        if ($id && is_numeric($id)) {
            $menuModel = new MenuModel();
            $menuModel->updateStatus((int)$id, 'unpublished');
            $_SESSION['flash'] = "Menu item unpublished successfully.";
        }

        header('Location: /quick_serve/admin/menu');
        exit;
    }

    public function orderList()
    {
        $this->requireAdmin();


        $orderModel = new OrderModel();
        $orders = $orderModel->getAllOrdersWithCustomer();
        $statuses = OrderModel::getAvailableStatuses();

      View::render('admin.order_list', [
        'orders'   => $orders,
        'statuses' => $statuses
    ]);

    }

    public function orderView()
    {
        $this->requireAdmin();
        //for order delete from the list


        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            View::render('errors/404');
            return;
        }

        //  Fetch order details using corrected model method
        $order = OrderModel::getOrderDetails($orderId);

        if (empty($order)) {
            View::render('errors/404');
            return;
        }

        View::render('admin.order_view', ['order' => $order]);
    }

    public function orderUpdateStatus()
    {
        $this->requireAdmin();

        $orderId = $_POST['order_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;

        if ($orderId && $newStatus) {
            OrderModel::setOrderStatus($orderId, $newStatus);
        }

        header('Location: /quick_serve/admin/order/list');
        exit;
    }
//new 
    public function orderDelete()
{
    $this->requireAdmin();

    // Accept order_id from POST (safer for destructive actions)
    $orderId = $_POST['order_id'] ?? null;

    if (!$orderId) {
        View::render('errors/404');
        return;
    }

    $success = OrderModel::deleteOrder($orderId);

    if ($success) {
        $_SESSION['flash'] = "Order deleted successfully!";
        header("Location: /quick_serve/admin/order/list");
        exit;
    } else {
        View::render('errors/500', ['message' => 'Failed to delete order']);
    }
}

    
  public function orderAnalyticsDashboard()
{
    $this->requireAdmin();

    // Automatically delete orders older than 7 days
    OrderModel::deleteOldOrders();

    $ordersToday = OrderModel::getOrdersByDate(date('Y-m-d'));
    $weeklySummary = OrderModel::getLast7DaysSummary() ?? [];

    $statusCounts = [
        'pending' => 0,
        'received' => 0,
        'preparing' => 0,
        'in progress' => 0,
        'ready' => 0,
        'ready for pickup' => 0,
        'completed' => 0,
        'cancelled' => 0,
        'canceled' => 0,
        'nostatus' => 0,
    ];

    foreach ($ordersToday ?? [] as $order) {
        $status = strtolower(trim($order['status'] ?? 'nostatus'));
        if (array_key_exists($status, $statusCounts)) {
            $statusCounts[$status]++;
        } else {
            $statusCounts['nostatus']++;
        }
    }

    $metrics = [
        'total_orders' => count($ordersToday),
        'status_breakdown' => $statusCounts
    ];

    View::render('admin.order_analytics', [
        'metrics' => $metrics,
        'weeklySummary' => $weeklySummary,
        'ordersToday' => $ordersToday
    ]);
}
   

   public function customerList()
    {
        $this->requireAdmin();
        $customers = CustomerModel::getAllCustomers();
        View::render('admin.customer_list', ['customers' => $customers]);
    }


   public function customerView()
{
    $this->requireAdmin();

    // Get customer ID from query string
    $customerId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$customerId) {
        View::render('errors/404');
        return;
    }

    // Fetch customer from database
    $customer = CustomerModel::getCustomerById($customerId);
    if (!$customer) {
        View::render('errors/404');
        return;
    }

    // Pass customer data to the view (without status validation)
    View::render('admin.customer_view', [
        'customer' => [
            'customer_id' => $customer['customer_id'],
            'name'        => $customer['name'],
            'email'       => $customer['email'],
            // deliberately not passing account_status to avoid validation error
            'orders'      => $customer['orders'] ?? []
        ]
    ]);
}


    public function customerDetailOverview()
    {
        $this->requireAdmin();

        $customers = CustomerModel::getAllCustomersWithOrders();
        View::render('admin.customer_full_list', ['customers' => $customers]);
    }

   public function customerStatus()
{
    $this->requireAdmin();

    $customerId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $status     = isset($_GET['status']) ? $_GET['status'] : null;

    if (!$customerId || !$status) {
        View::render('errors/404');
        return;
    }

    $success = CustomerModel::updateStatus($customerId, $status);

    if ($success) {
        header("Location: /quick_serve/admin/customer/detail");
        exit;
    } else {
        View::render('errors/500', ['message' => 'Failed to update status']);
    }
}

public function customerDelete()
{
    $this->requireAdmin();

    $customerId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$customerId) {
        View::render('errors/404');
        return;
    }

    $success = CustomerModel::deleteCustomer($customerId);

    if ($success) {
        header("Location: /quick_serve/admin/customer/detail");
        exit;
    } else {
        View::render('errors/500', ['message' => 'Failed to delete customer']);
    }
}
}