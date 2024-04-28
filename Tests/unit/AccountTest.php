<?php

use PHPUnit\Framework\TestCase;
use src\Account;

class AccountTest extends TestCase
{
    private $account;

    protected function setUp(): void
    {
        $this->account = new Account(1, 1000);
    }

    public function testGetId()
    {
        $this->assertEquals(1, $this->account->getId());
    }

    public function testSetId()
    {
        $this->account->setId(2);
        $this->assertEquals(2, $this->account->getId());
    }

    public function testGetBalance()
    {
        $this->assertEquals(1000, $this->account->getBalance());
    }

    public function testSetBalance()
    {
        $this->account->setBalance(2000);
        $this->assertEquals(2000, $this->account->getBalance());
    }
}