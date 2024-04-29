<?php

use PHPUnit\Framework\TestCase;
use src\Account;

class AccountTest extends TestCase
{
    private $account; // Add this property

    protected function setUp(): void // Add this method
    {
        $this->account = new Account(1, 1000); // Create a new Account object with ID 1 and balance 1000
    }

    public function testGetId() // Add this method
    {
        $this->assertEquals(1, $this->account->getId()); // Check if ID is 1
    }

    public function testSetId() // Add this method
    {
        $this->account->setId(2); // Set ID to 2
        $this->assertEquals(2, $this->account->getId()); // Check if ID is 2
    }

    public function testGetBalance() // Add this method
    {
        $this->assertEquals(1000, $this->account->getBalance()); // Check if balance is 1000
    }

    public function testSetBalance() // Add this method
    {
        $this->account->setBalance(2000); // Set balance to 2000
        $this->assertEquals(2000, $this->account->getBalance()); //
    }
}