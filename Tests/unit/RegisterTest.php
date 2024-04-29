<?php

use PHPUnit\Framework\TestCase;
require_once ('src/Register.php');
use src\Register;


class RegisterTest extends TestCase
{
    private $register; // Add this property
    private $pdo; // Add this property
    private $stmt; // Add this property

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        // Create a mock for the PDO class
        $this->pdo = $this->createMock(PDO::class); // Create a mock for the PDO class

        // Create a mock for the PDOStatement class
        $this->stmt = $this->createMock(PDOStatement::class); // Create a mock for the PDOStatement class

        // Configure the PDO mock to return the PDOStatement mock when the prepare method is called
        $this->pdo->method('prepare') // Configure the PDO mock to return the PDOStatement mock when the prepare method is called
            ->willReturn($this->stmt); // Configure the PDO mock to return the PDOStatement mock when the prepare method is called

        $this->register = new Register($this->pdo); // Inject the mock PDO object into the Register class
    }
    public function testUsernameValidation()
    {
        // Test the username validation logic
        $this->assertEquals(1, $this->register->validateUsername('validUsr'));
        $this->assertEquals(1, $this->register->validateUsername('invalidUsername'));
    }



    public function testPasswordValidation() // Add this method
{
    // Test the password validation logic
    $this->assertEquals(1, $this->register->validatePassword('validPass1')); // Test with a valid password
    $this->assertEquals(0, $this->register->validatePassword('invalidPassword')); // Test with an invalid password
}


    public function testRegisterUser()
    {
        // Mock the execute method to return true
        $this->stmt->method('execute')->willReturn(true); // Configure the execute method to return true

        // Test the user registration logic
        $this->assertTrue($this->register->registerUser('testUsername', 'testPassword')); // Test with valid username and password
    }
}