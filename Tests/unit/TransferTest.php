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

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('bindParam')->willReturn(true);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchColumn')->willReturn(1000);
        $stmt->method('fetch')->willReturnCallback(function ($username) {
            return $username[':username'] == 'Recipient' ? ['id' => 1] : false;
        });

        $pdo->method('prepare')->willReturn($stmt);

        $this->transfer = new Transfer($pdo);
    }

    // Additional test cases for transfer method
    public function testTransferExceptions()
    {
        // Test exception when recipient is empty
        $this->expectException(PDOException::class);
        $this->transfer->transfer('Purpose', '', 500);

        // Test exception when purpose is empty
        $this->expectException(PDOException::class);
        $this->transfer->transfer('', 'Recipient', 500);

        // Test exception when amount is negative
        $this->expectException(PDOException::class);
        $this->transfer->transfer('Purpose', 'Recipient', -500);

        // Test exception when amount exceeds balance
        $this->expectException(PDOException::class);
        $this->transfer->transfer('Purpose', 'Recipient', 10000);
    }

    // Test case for return values
    public function testReturnValues()
    {
        $this->assertEquals('positive', $this->transfer->checkNumberType(500));
        $this->assertEquals('negative', $this->transfer->checkNumberType(-500));
        $this->assertEquals('zero', $this->transfer->checkNumberType(0));
    }

    // Test case for dependencies
    public function testDependencies()
    {
        $this->assertInstanceOf(PDO::class, $this->transfer->getPdo());
    }

    // Test case for output
    public function testOutput()
    {
        $this->expectOutputString('positive');
        echo $this->transfer->checkNumberType(500);
    }

    // Empty test
    public function testEmpty()
    {
        $this->assertTrue(true);
    }

    // Test cases for checkNumberType method
    public function testCheckNumberType()
    {
        // Test positive numbers
        $this->assertEquals('positive', $this->transfer->checkNumberType(500));
        $this->assertEquals('positive', $this->transfer->checkNumberType(1));

        // Test negative numbers
        $this->assertEquals('negative', $this->transfer->checkNumberType(-500));
        $this->assertEquals('negative', $this->transfer->checkNumberType(-1));

        // Test zero
        $this->assertEquals('zero', $this->transfer->checkNumberType(0));
    }

    // Test cases for transfer method
    public function testTransfer()
    {
        // Test valid inputs
        $this->assertFalse($this->transfer->transfer('Purpose', 'Recipient', 500));

        // Test invalid recipient
        $result = $this->transfer->transfer('Purpose', 'InvalidRecipient', 500);
        if ($result) {
            $this->fail("Expected transfer to fail with invalid recipient, but it succeeded.");
        } else {
            $this->assertFalse(false, "Transfer failed with invalid recipient as expected.");
        }

        // Test invalid purpose
        $result = $this->transfer->transfer('', 'Recipient', 500);
        if ($result) {
            $this->fail("Expected transfer to fail with invalid purpose, but it succeeded.");
        } else {
            $this->assertTrue(true, "Transfer failed with invalid purpose as expected.");
        }

        // Test invalid amount (negative)
        $result = $this->transfer->transfer('Purpose', 'Recipient', -500);
        if ($result) {
            $this->fail("Expected transfer to fail with negative amount, but it succeeded.");
        } else {
            $this->assertTrue(true, "Transfer failed with negative amount as expected.");
        }

        // Test invalid amount (exceeds balance)
        $result = $this->transfer->transfer('Purpose', 'Recipient', 10000);
        if ($result) {
            $this->fail("Expected transfer to fail with amount exceeding balance, but it succeeded.");
        } else {
            $this->assertTrue(true, "Transfer failed with amount exceeding balance as expected.");
        }
    }

    // Test cases for factorial method
    public function testFactorial()
    {
        // Test positive numbers
        $this->assertEquals(120, $this->transfer->factorial(5));
        $this->assertEquals(24, $this->transfer->factorial(4));

        // Test zero
        $this->assertEquals(1, $this->transfer->factorial(0));

        // Test negative numbers
        $this->assertEquals('undefined', $this->transfer->factorial(-1));
        $this->assertEquals('undefined', $this->transfer->factorial(-5));
    }
}
