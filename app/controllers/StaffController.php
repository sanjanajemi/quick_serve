<?php

namespace App\Controllers;

//Staff interface uses this controller

use App\Core\View;
use App\Models\StaffModel;
use App\Models\MenuModel;
use App\Helpers\SessionHelper;
use App\Core\Database;
use App\Models\OrderModel;
use PDO;

class StaffController
{

    /**
     *Handles staff login request.
     *Validates staff ID and password, authenticates user and redirects to dashboard.
     *@return void
     */
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

            if (strlen($password) < 8) {
                View::render('staff.login', ['error' => 'Password must be at least 8 characters long.']);
                return;
            } elseif (!preg_match('/[^a-zA-Z0-9]/', $password)) {
                View::render('staff.login', ['error' => 'Password must include at least one special character.']);
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


    /**
     * Displays staff dashboard
     * Requires staff login and loads staff <details class="
     * @return void
     */
    public function dashboard()
    {
        SessionHelper::requireStaffLogin();
        $staffId = $_SESSION['staff_id'];
        $staff = StaffModel::findById($staffId);
        View::render('staff.dashboard', ['staff' => $staff]);
    }


     /*  Read functionality */
    /**
     * Shows staff profile view
     * Requires staff login
     * @return void
     */
    public function viewProfile()
    {
        SessionHelper::requireStaffLogin();
        $staffId = $_SESSION['staff_id'];
        $staff = StaffModel::findById($staffId);
        View::render('staff.view_profile', ['staff' => $staff]);
    }


     /* Edit Functionality */
    /**
     * Renders staff profile edit page
     * @return void
     */
    public function editProfile()
    {
        SessionHelper::requireStaffLogin();
        $staffId = $_SESSION['staff_id'];
        $staff = StaffModel::findById($staffId);
        View::render('staff.edit_profile', ['staff' => $staff]);
    }



     /*  Backend validation for profile update */
    /**
     * Validates staff profile data and uploaded picture
     * @param array $data Form data
     * @param array $files Uploaded files
     * @param string $currentPicture Current profile picture filename
     * @return array [errors array, profile picture filename]
     */

    public function validateProfile($data, $files, $currentPicture)
    {
        $errors = [];
        $validRoles = ['Manager', 'Chef', 'Waiter'];
        $profilePicture = $currentPicture;


        if (strlen($data['name']) < 3) {
            $errors[] = 'Full Name must be at least 3 characters long.';
        } 
        elseif (strlen($data['name']) > 25) {
            $errors[] = 'Full Name must not exceed 25 characters.';
        } 
        elseif (!preg_match('/^[A-Za-z\s]+$/', $data['name'])) {
            $errors[] = 'Full Name must contain only letters and spaces.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }

        if (!empty($data['phone']) && !preg_match('/^\d{10}$/', $data['phone'])) {
            $errors[] = '❌ Phone number must be exactly 10 digits (no spaces or symbols).';
        }

    

        if (!empty($files['profile_picture']['name'])) {
            $filename = basename($files['profile_picture']['name']);
            $targetPath = __DIR__ . '/../../storage/uploads/' . $filename;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($files['profile_picture']['type'], $allowedTypes)) {
                $errors[] = 'Profile picture must be a JPG or PNG image.';
            } elseif (move_uploaded_file($files['profile_picture']['tmp_name'], $targetPath)) {
                $profilePicture = $filename;
            }
        }
        return [$errors, $profilePicture];
    }



    /*Edit Functionality */
    /**
     * Updtaes staff profile with validated data
     * @return void
     */
    public function updateProfile()
    {
        SessionHelper::requireStaffLogin();
        $staffId = $_SESSION['staff_id'];
        $staff = StaffModel::findById($staffId);

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'role' => $_POST['role'] ?? ''
        ];

        [$errors, $profilePicture] = $this->validateProfile($data, $_FILES, $staff['profile_picture']);

        if (!empty($errors)) {
            View::render('staff.edit_profile', [
                'staff' => $staff,
                'error' => implode('<br>', $errors)
            ]);
            return;
        }

        StaffModel::update($staffId, [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'role' => $data['role'],
            'profile_picture' => $profilePicture
        ]);

        header('Location: /quick_serve/staff/dashboard?updated=true');
        exit;
    }


    /*  Edit Functionality */
    /**
     * Change staff account password
     * Validates current password, matches new password twice and updates if it's valid.
     * @return void
     */
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


     /*   Delete Functionality */
    /**
     * Schedule staff account deletion
     * Marks deletion time in session and shows confirmation view
     * @return void
     */
    public function deleteAccount()
    {
        SessionHelper::requireStaffLogin();
        $_SESSION['delete_scheduled_at'] = time();
        View::render('staff.delete_account');
    }


    /**
     * Finalize staff account deletion
     * Deletes account after a 5-minute waiting period otherwis
     * @return void
     */
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

    /**
     * Cancel scheduled account deletion
     * Removes deletion mark from session and redirects to dashboard
     * @return void
     */
    public function cancelDeletion()
    {
        unset($_SESSION['delete_scheduled_at']);
        header('Location: /quick_serve/staff/dashboard');
        exit;
    }


    /**
     * Logs out staff user
     * Destroys session and redirects to login page
     * @return void
     */
    public function logout()
    {
        session_destroy();
        header('Location: /quick_serve');
        exit;
    }

    // Menu Management By Staff


    /**
     * Displays all menu items to staff 
     * Requires staff login and renders menu list
     * @return void
     */

    public function menu()
    {
        SessionHelper::requireStaffLogin();
        $menuModel = new MenuModel();
        $menuItems = $menuModel->getAllItems();
        View::render('staff.menu_list', ['menuItems' => $menuItems]);
    }


    /**
     * Publishes a menu item by ID
     * Validates ID, checks item status and updates to published
     * @return void
     */

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

    /**
     * Unpublishes a menu item by $id
     * Validates ID, checks item status and updates to unpublished
     * @return void
     */

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
