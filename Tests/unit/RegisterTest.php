<?php

use PHPUnit\Framework\TestCase;
require_once ('src/Register.php');
use src\Register;


class RegisterTest extends TestCase
{
    private $register;
    private $pdo;
    private $stmt;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        // Create a mock for the PDO class
        $this->pdo = $this->createMock(PDO::class);

        // Create a mock for the PDOStatement class
        $this->stmt = $this->createMock(PDOStatement::class);

        // Configure the PDO mock to return the PDOStatement mock when the prepare method is called
        $this->pdo->method('prepare')
            ->willReturn($this->stmt);

        $this->register = new Register($this->pdo);
    }
    public function testUsernameValidation()
    {
        // Test the username validation logic
        $this->assertEquals(1, $this->register->validateUsername('validUsr'));
        $this->assertEquals(1, $this->register->validateUsername('invalidUsername'));
    }



    public function testPasswordValidation()
{
    // Test the password validation logic
    $this->assertEquals(1, $this->register->validatePassword('validPass1'));
    $this->assertEquals(0, $this->register->validatePassword('invalidPassword'));
}


    public function testRegisterUser()
    {
        // Mock the execute method to return true
        $this->stmt->method('execute')->willReturn(true);

        // Test the user registration logic
        $this->assertTrue($this->register->registerUser('testUsername', 'testPassword'));
    }
}