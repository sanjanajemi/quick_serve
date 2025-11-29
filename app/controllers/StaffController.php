<?php

namespace App\Controllers;

require_once __DIR__ . '/../../libs/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../../libs/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/../../libs/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Core\View;
use App\Models\StaffModel;
use App\Models\MenuModel;
use App\Helpers\SessionHelper;
use App\Core\Database;
use App\Models\OrderModel;
use PDO;

class StaffController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $staffId = $_POST['staff_id'] ?? '';
            $password = $_POST['password'] ?? '';

            //  Backend validation
            if (!preg_match('/^\d{6}$/', $staffId)) {
                View::render('staff.login', ['error' => 'Staff ID must be exactly 6 digits.']);
                return;
            }

            if (strlen($password) < 6) {
                View::render('staff.login', ['error' => 'Password must be at least 6 characters long.']);
                return;
            }

            //  Authentication
            $staff = StaffModel::findById($staffId);

            if ($staff && password_verify($password, $staff['password'])) {
                $_SESSION['role'] = 'staff';
                $_SESSION['staff_id'] = $staff['staff_id'];
                header('Location: /quick_serve/staff/dashboard');
                exit;
            } else {
                View::render('staff.login', ['error' => 'Invalid Staff ID or password']);
            }
        } else {
            View::render('staff.login');
        }
    }

    public function dashboard()
    {
        SessionHelper::requireStaffLogin();
        $staffId = $_SESSION['staff_id'];
        $staff = StaffModel::findById($staffId);
        View::render('staff.dashboard', ['staff' => $staff]);
    }

    public function viewProfile()
    {
        SessionHelper::requireStaffLogin();
        $staffId = $_SESSION['staff_id'];
        $staff = StaffModel::findById($staffId);
        View::render('staff.view_profile', ['staff' => $staff]);
    }

    public function editProfile()
    {
        SessionHelper::requireStaffLogin();
        $staffId = $_SESSION['staff_id'];
        $staff = StaffModel::findById($staffId);
        View::render('staff.edit_profile', ['staff' => $staff]);
    }
    public function updateProfile()
    {
        SessionHelper::requireStaffLogin();
        $staffId = $_SESSION['staff_id'];
        $staff = StaffModel::findById($staffId);

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? '';
        $profilePicture = $staff['profile_picture'];

        //  Backend validation
        $validRoles = ['Manager', 'Chef', 'Waiter'];
        $errors = [];

        if (!preg_match('/^[A-Za-z\s]+$/', $name)) {
            $errors[] = 'Full Name must contain only letters and spaces.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }

       if (!empty($phone) && !preg_match('/^\d{10}$/', $phone)) {
       $errors[] = '❌ Phone number must be exactly 10 digits (no spaces or symbols).';
       }

        if (!in_array($role, $validRoles)) {
            $errors[] = 'Invalid role selected.';
        }

        if (!empty($_FILES['profile_picture']['name'])) {
            $filename = basename($_FILES['profile_picture']['name']);
            $targetPath = __DIR__ . '/../../storage/uploads/' . $filename;

            //  validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($_FILES['profile_picture']['type'], $allowedTypes)) {
                $errors[] = 'Profile picture must be a JPG or PNG image.';
            } elseif (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
                $profilePicture = $filename;
            }
        }

        //  re-render the form with error messages
        if (!empty($errors)) {
            View::render('staff.edit_profile', [
                'staff' => $staff,
                'error' => implode('<br>', $errors)
            ]);
            return;
        }

        //  Save valid data
        StaffModel::update($staffId, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'profile_picture' => $profilePicture
        ]);

        header('Location: /quick_serve/staff/dashboard?updated=true');
        exit;
    }

    public function changePassword()
    {
        SessionHelper::requireStaffLogin();
        $staffId = $_SESSION['staff_id'];
        $staff = StaffModel::findById($staffId);

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if (empty($current) || empty($new) || empty($confirm)) {
                $error = 'All fields are required.';
            } elseif (!password_verify($current, $staff['password'])) {
                $error = 'Current password is incorrect.';
            } elseif ($new !== $confirm) {
                $error = 'New passwords do not match.';
            } elseif (strlen($new) < 6) {
                $error = 'New password must be at least 6 characters.';
            } else {
                $hashed = password_hash($new, PASSWORD_DEFAULT);
                StaffModel::updatePassword($staffId, $hashed);
                $success = 'Password updated successfully.';
            }
        }

        View::render('staff.change_password', [
            'staff' => $staff,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function deleteAccount()
    {
        SessionHelper::requireStaffLogin();
        $_SESSION['delete_scheduled_at'] = time();
        View::render('staff.delete_account');
    }

    public function deleteAccountFinal()
    {
        SessionHelper::requireStaffLogin();
        $scheduled = $_SESSION['delete_scheduled_at'] ?? 0;
        if (time() - $scheduled >= 300) {
            $staffId = $_SESSION['staff_id'];
            StaffModel::deleteById($staffId);
            session_destroy();
            header('Location: /quick_serve/staff/login');
            exit;
        } else {
            header('Location: /quick_serve/staff/delete-account');
            exit;
        }
    }

    public function cancelDeletion()
    {
        unset($_SESSION['delete_scheduled_at']);
        header('Location: /quick_serve/staff/dashboard');
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /quick_serve');
        exit;
    }

    // Menu Management

    public function menu()
    {
        SessionHelper::requireStaffLogin();
        $menuModel = new MenuModel();
        $menuItems = $menuModel->getAllItems();
        View::render('staff.menu_list', ['menuItems' => $menuItems]);
    }

    public function publishMenu()
    {
        SessionHelper::requireStaffLogin();

        // validation: ensure ID is present and numeric
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if (!$id) {
            header('Location: /quick_serve/staff/menu?error=invalid_id');
            exit;
        }

        $menuModel = new MenuModel();

        // validation: fetch item regardless of status
        $menuItem = $menuModel->findById($id);
        if (!$menuItem) {
            header('Location: /quick_serve/staff/menu?error=not_found');
            exit;
        }

        // validation: avoid redundant publish
        if ($menuItem['status'] === 'published') {
            header('Location: /quick_serve/staff/menu?info=already_published');
            exit;
        }

        $menuModel->publish($id);
        header('Location: /quick_serve/staff/menu?success=published');
        exit;
    }

    public function unpublishMenu()
    {
        SessionHelper::requireStaffLogin();

        // validation: ensure ID is present and numeric
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if (!$id) {
            header('Location: /quick_serve/staff/menu?error=invalid_id');
            exit;
        }

        $menuModel = new MenuModel();

        // validation: fetch item regardless of status
        $menuItem = $menuModel->findById($id);
        if (!$menuItem) {
            header('Location: /quick_serve/staff/menu?error=not_found');
            exit;
        }

        // validation: avoid redundant unpublish
        if ($menuItem['status'] === 'unpublished') {
            header('Location: /quick_serve/staff/menu?info=already_unpublished');
            exit;
        }

        $menuModel->unpublish($id);
        header('Location: /quick_serve/staff/menu?success=unpublished');
        exit;
    }


}
