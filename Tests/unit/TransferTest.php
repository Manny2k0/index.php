<?php

use PHPUnit\Framework\TestCase;
use src\Transfer;

require_once ('src/Transfer.php');

class TransferTest extends TestCase
{
    private Transfer $transfer;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $_SESSION = ['Username' => 'TestUser', 'user_id' => 1];

        $pdo = $this->createMock(PDO::class);

        $stmt = $this->createMock(PDOStatement::class); // Create a mock for the PDOStatement class
        $stmt->method('bindParam')->willReturn(true); // Configure the bindParam method to return true
        $stmt->method('execute')->willReturn(true); // Configure the execute method to return true
        $stmt->method('fetchColumn')->willReturn(1000); // Configure the fetchColumn method to return 1000
        $stmt->method('fetch')->willReturnCallback(function ($username) { // Configure the fetch method to return a user ID
            return $username[':username'] == 'Recipient' ? ['id' => 1] : false; // Check if the username is 'Recipient'
        });

        $pdo->method('prepare')->willReturn($stmt); // Configure the PDO mock to return the PDOStatement mock when the prepare method is called

        $this->transfer = new Transfer($pdo); // Inject the mock PDO object into the Transfer class
    }

    // Additional test cases for transfer method
    public function testTransferExceptions() // Add this method
    {
        // Test exception when recipient is empty
        $this->expectException(PDOException::class);
        $this->transfer->transfer('Purpose', '', 500); // Test with empty recipient

        // Test exception when purpose is empty
        $this->expectException(PDOException::class); // Test with empty purpose
        $this->transfer->transfer('', 'Recipient', 500); // Test with empty purpose

        // Test exception when amount is negative
        $this->expectException(PDOException::class); // Test with negative amount
        $this->transfer->transfer('Purpose', 'Recipient', -500); // Test with negative amount

        // Test exception when amount exceeds balance
        $this->expectException(PDOException::class); // Test with amount exceeding balance
        $this->transfer->transfer('Purpose', 'Recipient', 10000); // Test with amount exceeding balance
    }

    // Test case for return values
    public function testReturnValues()
    {
        $this->assertEquals('positive', $this->transfer->checkNumberType(500)); // Test with positive number
        $this->assertEquals('negative', $this->transfer->checkNumberType(-500)); // Test with negative number
        $this->assertEquals('zero', $this->transfer->checkNumberType(0)); // Test with zero
    }

    // Test case for dependencies
    public function testDependencies() // Add this method
    {
        $this->assertInstanceOf(PDO::class, $this->transfer->getPdo()); // Check if the PDO object is created
    }

    // Test case for output
    public function testOutput() // Add this method
    {
        $this->expectOutputString('positive'); // Check if the output is 'positive'
        echo $this->transfer->checkNumberType(500); // Test with positive number
    }

    // Empty test
    public function testEmpty()
    {
        $this->assertTrue(true); // Empty test
    }

    // Test cases for checkNumberType method
    public function testCheckNumberType() // Add this method
    {
        // Test positive numbers
        $this->assertEquals('positive', $this->transfer->checkNumberType(500)); // Test with positive number
        $this->assertEquals('positive', $this->transfer->checkNumberType(1)); // Test with positive number

        // Test negative numbers
        $this->assertEquals('negative', $this->transfer->checkNumberType(-500)); // Test with negative number
        $this->assertEquals('negative', $this->transfer->checkNumberType(-1)); // Test with negative number

        // Test zero
        $this->assertEquals('zero', $this->transfer->checkNumberType(0)); // Test with zero
    }

    // Test cases for transfer method
    public function testTransfer() // Add this method
    {
        // Test valid inputs
        $this->assertFalse($this->transfer->transfer('Purpose', 'Recipient', 500)); // Test with valid inputs

        // Test invalid recipient
        $result = $this->transfer->transfer('Purpose', 'InvalidRecipient', 500); // Test invalid recipient
        if ($result) { // Test invalid recipient
            $this->fail("Expected transfer to fail with invalid recipient, but it succeeded."); // Check if the output is correct
        } else { // Test invalid recipient
            $this->assertFalse(false, "Transfer failed with invalid recipient as expected."); // Check if the output is correct
        }

        // Test invalid purpose
        $result = $this->transfer->transfer('', 'Recipient', 500); // Test with empty purpose
        if ($result) {
            $this->fail("Expected transfer to fail with invalid purpose, but it succeeded."); // Check if the output is correct
        } else {
            $this->assertTrue(true, "Transfer failed with invalid purpose as expected."); // Check if the output is correct
        }

        // Test invalid amount (negative)
        $result = $this->transfer->transfer('Purpose', 'Recipient', -500); // Test with negative amount
        if ($result) { // Test with negative amount
            $this->fail("Expected transfer to fail with negative amount, but it succeeded.");
        } else { //
            $this->assertTrue(true, "Transfer failed with negative amount as expected.");
        }

        // Test invalid amount (exceeds balance)
        $result = $this->transfer->transfer('Purpose', 'Recipient', 10000);
        if ($result) { // Test with amount exceeding balance
            $this->fail("Expected transfer to fail with amount exceeding balance, but it succeeded."); // Check if the output is correct
        } else { // Test with amount exceeding balance
            $this->assertTrue(true, "Transfer failed with amount exceeding balance as expected."); // Check if the output is correct
        }
    }

    // Test cases for factorial method
    public function testFactorial()
    {
        // Test positive numbers
        $this->assertEquals(120, $this->transfer->factorial(5)); // Test with positive number
        $this->assertEquals(24, $this->transfer->factorial(4)); // Test with positive number

        // Test zero
        $this->assertEquals(1, $this->transfer->factorial(0)); // Test with zero

        // Test negative numbers
        $this->assertEquals('undefined', $this->transfer->factorial(-1)); // Test with negative number
        $this->assertEquals('undefined', $this->transfer->factorial(-5));
    }
}
