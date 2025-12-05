<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\StaffController;

class StaffLoginTest extends TestCase
{
    // Extreme Min: empty string
    public function testLoginRejectsEmptyStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = '';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Staff ID must be exactly 6 digits.', $output);
    }

    // Min -1: 5 digits
    public function testLoginRejectsFiveDigitStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = '12345';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Staff ID must be exactly 6 digits.', $output);
    }

    // Min (Boundary): 6 digits valid
    public function testLoginAcceptsSixDigitStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = '123456';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        // Depending on DB, either dashboard or invalid password
        $this->assertTrue(strpos($output, 'Staff ID must be exactly 6 digits.') === false);
    }

    // Min +1: 7 digits
    public function testLoginRejectsSevenDigitStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = '1234567';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Staff ID must be exactly 6 digits.', $output);
    }

    // Max -1: 999998
    public function testLoginAcceptsMaxMinusOneStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = '999998';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        $this->assertTrue(strpos($output, 'Staff ID must be exactly 6 digits.') === false);
    }

    // Max (Boundary): 999999
    public function testLoginAcceptsMaxBoundaryStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = '999999';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        $this->assertTrue(strpos($output, 'Staff ID must be exactly 6 digits.') === false);
    }

    // Max +1: 1000000 (7 digits)
    public function testLoginRejectsMaxPlusOneStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = '1000000';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Staff ID must be exactly 6 digits.', $output);
    }

    // Mid: 555555
    public function testLoginAcceptsMidStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = '555555';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        $this->assertTrue(strpos($output, 'Staff ID must be exactly 6 digits.') === false);
    }

    // Extreme Max: 9999999999 (10 digits)
    public function testLoginRejectsExtremeMaxStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = '9999999999';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Staff ID must be exactly 6 digits.', $output);
    }

    // Invalid data type: abc123
    public function testLoginRejectsNonNumericStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = 'abc123';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Staff ID must be exactly 6 digits.', $output);
    }

    // Other tests: leading zeros
    public function testLoginAcceptsLeadingZeroStaffId()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['staff_id'] = '000000';
        $_POST['password'] = 'securePass';

        ob_start();
        $controller = new StaffController();
        $controller->login();
        $output = ob_get_clean();

        $this->assertTrue(strpos($output, 'Staff ID must be exactly 6 digits.') === false);
    }
}