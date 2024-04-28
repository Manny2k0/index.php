<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use src\TopUp;

require_once ('src/TopUp.php');

class TopUpTest extends TestCase
{
    private TopUp $topUp;

    protected function setUp(): void
    {
        global $pdo;
        $this->pdo = new PDO('mysql:host=localhost;dbname=Register', 'root', 'Eo606752k18!');
        $this->topUp = new TopUp($pdo);
    }

    public static function expirationDateProvider(): array
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
    $this->expectNotToPerformAssertions();

    $result = $this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 500);
    if ($result !== true) {
        $this->fail("Expected true, got {$result}");
    }

    $result = $this->topUp->topUp('1234-5678-9012-345', '12', '2030', '123', 500);
    if ($result !== false) {
        $this->fail("Expected false, got {$result}");
    }

    $result = $this->topUp->topUp('1234-5678-9012-3456', '13', '2030', '123', 500);
    if ($result !== false) {
        $this->fail("Expected false, got {$result}");
    }

    $result = $this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '1234', 500);
    if ($result !== false) {
        $this->fail("Expected false, got {$result}");
    }

    $result = $this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 499);
    if ($result !== false) {
        $this->fail("Expected false, got {$result}");
    }
}

    public function testTopUpWithBoundaryAmounts()
    {
        // Test with amount just below the minimum limit
        $this->assertFalse($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 499));

        // Test with amount exactly at the minimum limit
        $this->assertTrue($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 500));

        // Test with amount just above the minimum limit
        $this->assertTrue($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 501));

        // Test with amount just below the maximum limit
        $this->assertTrue($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 999999));

        // Test with amount exactly at the maximum limit
        $this->assertTrue($this->topUp->topUp('1234-5678-9012-3456', '12', '2030', '123', 1000000));

    }
}