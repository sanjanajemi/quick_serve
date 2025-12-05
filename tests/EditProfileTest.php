<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\StaffController;
require_once __DIR__ . '/../app/controllers/StaffController.php';

class EditProfileTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        $this->controller = new StaffController();
    }


    // NAME TESTS
    public function testRejectsEmptyName()
    {
        $data = ['name' => '', 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('Full Name must be at least 3 characters long.', $errors);
    }

    public function testRejectsTwoCharName()
    {
        $data = ['name' => 'Ab', 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('Full Name must be at least 3 characters long.', $errors);
    }

    public function testAcceptsThreeCharName()
    {
        $data = ['name' => 'Abc', 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testAcceptsFourCharName()
    {
        $data = ['name' => 'Abcd', 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testAcceptsTwentyFourCharName()
    {
        $data = ['name' => str_repeat('A', 24), 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testAcceptsTwentyFiveCharName()
    {
        $data = ['name' => str_repeat('A', 25), 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testRejectsTwentySixCharName()
    {
        $data = ['name' => str_repeat('A', 26), 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
       $this->assertContains('Full Name must not exceed 25 characters.', $errors);
    }

    public function testAcceptsMidName()
    {
        $data = ['name' => 'Sanjana Akter', 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testRejectsExtremeMaxName()
    {
        $data = ['name' => str_repeat('A', 100), 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
       $this->assertContains('Full Name must not exceed 25 characters.', $errors);
    }

    public function testRejectsInvalidCharactersInName()
    {
        $data = ['name' => 'Sanjana123@', 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('Full Name must contain only letters and spaces.', $errors);
    }


   //EMAIL TESTS
    public function testRejectsEmptyEmail()
    {
        $data = ['name' => 'ValidName', 'email' => '', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('Invalid email address.', $errors);
    }

    public function testRejectsTooShortEmail()
    {
        $data = ['name' => 'ValidName', 'email' => 's@j', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('Invalid email address.', $errors);
    }

    public function testAcceptsShortestValidEmail()
    {
        $data = ['name' => 'ValidName', 'email' => 's@j.co', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testAcceptsValidEmail()
    {
        $data = ['name' => 'ValidName', 'email' => 'sa@je.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testAcceptsMaxMinusOneEmail()
    {
        $localPart = str_repeat('a', 54);
        $data = ['name' => 'ValidName', 'email' => $localPart . '@gmail.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testAcceptsMaxBoundaryEmail()
    {
        $localPart = str_repeat('a', 55);
        $data = ['name' => 'ValidName', 'email' => $localPart . '@gmail.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testRejectsMaxPlusOneEmail()
    {
        $localPart = str_repeat('a', 56);
        $data = ['name' => 'ValidName', 'email' => $localPart . '@gmail.com', 'phone' => '1234567890', 'role' => 'Chef'];
        $this->assertTrue(strlen($data['email']) > 55);
    }

    public function testAcceptsMidEmail()
    {
        $data = ['name' => 'ValidName', 'email' => 'Sanjana@gmail.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testRejectsExtremeMaxEmail()
    {
        $localPart = str_repeat('a', 100);
        $data = ['name' => 'ValidName', 'email' => $localPart . '@gmail.com', 'phone' => '1234567890', 'role' => 'Chef'];
        $this->assertTrue(strlen($data['email']) > 55);
    }

    public function testRejectsInvalidEmailFormat()
    {
        $data = ['name' => 'ValidName', 'email' => 'Abc123', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('Invalid email address.', $errors);
    }

//Phone number


    public function testAcceptsEmptyPhone()
    {
        $data = ['name' => 'ValidName', 'email' => 'test@example.com', 'phone' => '', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testRejectsTooShortPhone()
    {
        $data = ['name' => 'ValidName', 'email' => 'test@example.com', 'phone' => '12345', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('❌ Phone number must be exactly 10 digits (no spaces or symbols).', $errors);
    }

    public function testAcceptsValidTenDigitPhone()
    {
        $data = ['name' => 'ValidName', 'email' => 'test@example.com', 'phone' => '1234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testRejectsElevenDigitPhone()
    {
        $data = ['name' => 'ValidName', 'email' => 'test@example.com', 'phone' => '12345678901', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('❌ Phone number must be exactly 10 digits (no spaces or symbols).', $errors);
    }

    public function testAcceptsAnotherValidTenDigitPhone()
    {
        $data = ['name' => 'ValidName', 'email' => 'test@example.com', 'phone' => '9876543210', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertEmpty($errors);
    }

    public function testRejectsExtremeMaxPhone()
    {
        $data = ['name' => 'ValidName', 'email' => 'test@example.com', 'phone' => '12345678901234567890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('❌ Phone number must be exactly 10 digits (no spaces or symbols).', $errors);
    }

    public function testRejectsInvalidPhoneWithLetters()
    {
        $data = ['name' => 'ValidName', 'email' => 'test@example.com', 'phone' => '12345abcd', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('❌ Phone number must be exactly 10 digits (no spaces or symbols).', $errors);
    }

    public function testRejectsInvalidPhoneWithSymbols()
    {
        $data = ['name' => 'ValidName', 'email' => 'test@example.com', 'phone' => '123-456-7890', 'role' => 'Chef'];
        [$errors, ] = $this->controller->validateProfile($data, [], '');
        $this->assertContains('❌ Phone number must be exactly 10 digits (no spaces or symbols).', $errors);
    }
}


