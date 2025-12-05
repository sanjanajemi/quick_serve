<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\StaffController;

class StaffLoginPasswordTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [];
        $_SESSION = [];
    }

    // Extreme Min: empty password
    public function testLoginRejectsEmptyPassword()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = '';

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Password must be at least 8 characters', $output);
    }

    // Min -1: 7 characters
    public function testLoginRejectsSevenCharPassword()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = 'abcdefg'; 

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Password must be at least 8 characters', $output);
    }

    // Min (Boundary): 8 characters with special char
    public function testLoginAcceptsEightCharPasswordWithSpecialChar()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = 'abcdefg@'; // 8 chars, includes @

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        // Should not trigger length or special char error
        $this->assertTrue(strpos($output, 'Password must be at least 8 characters') === false);
        $this->assertTrue(strpos($output, 'Password must include at least one special character') === false);
    }

    // Min+1: 9 characters with special char
    public function testLoginAcceptsNineCharPasswordWithSpecialChar()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = 'abcdefghi@'; // 9 chars, includes @

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        $this->assertTrue(strpos($output, 'Password must be at least 8 characters') === false);
        $this->assertTrue(strpos($output, 'Password must include at least one special character') === false);
    }

    // Max -1: 255 chars with special char
    public function testLoginAcceptsMaxMinusOnePasswordWithSpecialChar()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = str_repeat('A', 254) . '@'; // 255 chars, includes @

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        $this->assertTrue(strpos($output, 'Password must be at least 8 characters') === false);
        $this->assertTrue(strpos($output, 'Password must include at least one special character') === false);
    }

    // Max (Boundary): 256 chars with special char
    public function testLoginAcceptsMaxBoundaryPasswordWithSpecialChar()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = str_repeat('B', 255) . '!'; // 256 chars, includes !

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        $this->assertTrue(strpos($output, 'Password must be at least 8 characters') === false);
        $this->assertTrue(strpos($output, 'Password must include at least one special character') === false);
    }

    // Max+1: 257 chars
    public function testLoginFailsMaxPlusOnePassword()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = str_repeat('C', 256) . '@'; // 257 chars

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Invalid Staff ID or password', $output);
    }

    // Mid: typical valid password
    public function testLoginAcceptsMidPasswordWithSpecialChar()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = 'Pass123!Pass'; // 12 chars, includes !

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        $this->assertTrue(strpos($output, 'Password must be at least 8 characters') === false);
        $this->assertTrue(strpos($output, 'Password must include at least one special character') === false);
    }

    // Extreme Max: 1000 chars
    public function testLoginFailsExtremeMaxPassword()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = str_repeat('D', 999) . '$'; // 1000 chars

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Invalid Staff ID or password', $output);
    }

    // Invalid data type: numeric-only password
    public function testLoginRejectsNumericOnlyPassword()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = '12345678'; // 8 digits, no special char

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Password must include at least one special character', $output);
    }

    // Other tests: valid mixed password
    public function testLoginAcceptsValidMixedPassword()
    {
        $_POST['staff_id'] = '123456';
        $_POST['password'] = 'Test123@'; // 8 chars, includes @

        ob_start();
        (new StaffController())->login();
        $output = ob_get_clean();

        $this->assertTrue(strpos($output, 'Password must be at least 8 characters') === false);
        $this->assertTrue(strpos($output, 'Password must include at least one special character') === false);
    }
}