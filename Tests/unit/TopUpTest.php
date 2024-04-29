<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use src\TopUp;

require_once ('src/TopUp.php');

class TopUpTest extends TestCase
{
    private TopUp $topUp; // Add this property

    protected function setUp(): void // Add this method
    {
        global $pdo; // Add this line
        $this->pdo = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!'); // Create a new PDO object
        $this->topUp = new TopUp($pdo); // Inject the mock PDO object into the TopUp class
    }

    public static function expirationDateProvider(): array // Add this method
    {
        return [
            ['12', '2024', false],
            ['12', '2025', true],
            ['12', '2045', true],
            ['12', '2046', false],
        ];
    }

 /**
 * @dataProvider expirationDateProvider
 */
public function testTopUpWithExpirationDates($expirationMonth, $expirationYear, $expectedResult)
{
    $this->assertEquals(
        $expectedResult,
        $this->topUp->topUp('1234-5678-9012-3456', $expirationMonth, $expirationYear, '123', 500)
    );
}

    public function testTopUpWithZeroAmount()
    {
        $this->assertFalse($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 0));
    }

    public function testTopUpWithNegativeAmount()
    {
        $this->assertFalse($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', -500));
    }

    public function testTopUpWithExceedingAmount()
    {
        $this->assertTrue($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 1000000));
    }

    public function testTopUpWithInvalidCreditCardNumber()
    {
        $this->assertFalse($this->topUp->topUp('1234-5678-9012-345', '12', '2030', '123', 500));
    }

    public function testTopUpWithInvalidExpirationDate()
    {
        $this->assertFalse($this->topUp->topUp('1234-5678-9012-3456', '13', '2030', '123', 500));
    }

    public function testTopUpWithInvalidCVV()
    {
        $this->assertFalse($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '1234', 500));
    }

 public function testTopUpPaths()
{
    $this->expectNotToPerformAssertions(); // Test with valid inputs

    $result = $this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 500); // Test with valid inputs
    if ($result !== true) { // Test with valid inputs
        $this->fail("Expected true, got {$result}"); // Test with valid inputs
    }

    $result = $this->topUp->topUp('1234-5678-9012-345', '12', '2030', '123', 500);
    if ($result !== false) {
        $this->fail("Expected false, got {$result}");
    }

    $result = $this->topUp->topUp('1234-5678-9012-3456', '13', '2030', '123', 500); // Test with invalid expiration month
    if ($result !== false) {
        $this->fail("Expected false, got {$result}");
    }

    $result = $this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '1234', 500); // Test with invalid CVV
    if ($result !== false) { // Test with invalid CVV
        $this->fail("Expected false, got {$result}"); // Test with invalid CVV
    }

    $result = $this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 499); // Test with amount just below the minimum limit
    if ($result !== false) { // Test with amount just below the minimum limit
        $this->fail("Expected false, got {$result}"); // Test with amount just below the minimum limit
    }
}

    public function testTopUpWithBoundaryAmounts() // Add this method
    {
        // Test with amount just below the minimum limit
        $this->assertFalse($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 499)); // Test with amount just below the minimum limit

        // Test with amount exactly at the minimum limit
        $this->assertTrue($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 500)); // Test with amount exactly at the minimum limit

        // Test with amount just above the minimum limit
        $this->assertTrue($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 501)); // Test with amount just above the minimum limit

        // Test with amount just below the maximum limit
        $this->assertTrue($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 999999)); // Test with amount just below the maximum limit

        // Test with amount exactly at the maximum limit
        $this->assertTrue($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 1000000)); // Test with amount exactly at the maximum limit

    }
}