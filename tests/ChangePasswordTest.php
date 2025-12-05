<?php
use PHPUnit\Framework\TestCase;

class ChangePasswordTest extends TestCase
{
    
    private function isValidPassword(string $password): bool
    {
        if (empty(trim($password))) {
            return false; 
        }
        if (strlen($password) < 8) {
            return false; 
        }
        if (strlen($password) > 256) {
            return false; 
        }
        return true;
    }

    public function testEmptyPasswordFails()
    {
        $this->assertFalse($this->isValidPassword(""), "Empty password should fail");
    }

    public function testWhitespaceOnlyPasswordFails()
    {
        $this->assertFalse($this->isValidPassword("     "), "Whitespace-only password should fail");
    }

    public function testTooShortPasswordFails()
    {
        $this->assertFalse($this->isValidPassword("Test1!"), "Password shorter than 8 chars should fail");
    }

    public function testBoundaryPasswordPasses()
    {
        $this->assertTrue($this->isValidPassword("Test123@"), "8-char password should pass");
    }

    public function testValidStrongPasswordPasses()
    {
        $this->assertTrue($this->isValidPassword("StrongPass!123"), "Strong password should pass");
    }

   public function testValidWithSpecialCharactersPasses()
{
    $this->assertTrue(
        $this->isValidPassword('Pa$$w0rd!'),
        "Password with digits and special chars should pass"
    );
}
    public function testMaxBoundaryPasswordPasses()
    {
        $password = str_repeat("a", 256);
        $this->assertTrue($this->isValidPassword($password), "256-char password should pass");
    }

    public function testTooLongPasswordFails()
    {
        $password = str_repeat("a", 257);
        $this->assertFalse($this->isValidPassword($password), "Password longer than 256 chars should fail");
    }

    public function testExtremeMaxPasswordFails()
    {
        $password = str_repeat("a", 1000);
        $this->assertFalse($this->isValidPassword($password), "1000-char password should fail");
    }
}