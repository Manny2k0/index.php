<?php

namespace Tests\unit;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
require_once ('src\Login.php');
use src\Login;
use PDO;
use PDOStatement;

class LoginTest extends TestCase
{
    private $login;
    private $stmt;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        // Create a mock for the PDO class
        $pdo = $this->createMock(PDO::class);

        // Create a mock for the PDOStatement class
        $this->stmt = $this->createMock(PDOStatement::class);

        // Configure the PDO mock to return the PDOStatement mock when the prepare method is called
        $pdo->method('prepare')
            ->willReturn($this->stmt);

        $this->login = new Login($pdo);
    }

  public function testAuthenticateWithMock()
{
    // Configure the PDOStatement mock to return 1, 2, and 3 when the rowCount method is called
    $this->stmt->method('rowCount')->willReturnOnConsecutiveCalls(1, 2, 3);

    // Test the authenticate method with valid username and password
    $this->assertTrue($this->login->authenticate('Manny', 'emmanuelokafor45k'));

    // Configure the PDOStatement mock to return 0 when the rowCount method is called
    $this->stmt->method('rowCount')->willReturn(0);

    // Test the authenticate method with invalid username and password
    $this->assertFalse($this->login->authenticate('invalidUser', 'invalidPassword'));
}

    public function testAuthenticateWithoutMock()
    {
        // Partition 1: Valid username and valid password
        $this->assertTrue($this->login->authenticate('Manny', 'emmanuelokafor45k')); // Test with valid username and password

        // Partition 2: Valid username and invalid password
        $this->assertFalse($this->login->authenticate('Manny', 'invalidPassword')); // Test with valid username and invalid password

        // Partition 3: Invalid username and any password
        $this->assertFalse($this->login->authenticate('invalidUser', 'validPassword')); // Test with invalid username and valid password
        $this->assertFalse($this->login->authenticate('invalidUser', 'invalidPassword')); // Test with invalid username and invalid password

        // Basis Path Testing
        // Path 1: The method returns true
        $this->assertTrue($this->login->authenticate('Manny', 'emmanuelokafor45k')); // Test with valid username and password

        // Path 2: The method returns false
        $this->assertFalse($this->login->authenticate('validUser', 'invalidPass')); // Test with valid username and invalid password
        $this->assertFalse($this->login->authenticate('invalidUser', 'validPass')); // Test with invalid username and valid password
        $this->assertFalse($this->login->authenticate('invalidUser', 'invalidPass')); // Test with invalid username and invalid password
    }
}

