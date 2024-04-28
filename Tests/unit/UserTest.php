<?php

use PHPUnit\Framework\TestCase;
use src\User;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User(1, 'testuser', 'testpassword');
    }

    public function testGetId()
    {
        $this->assertEquals(1, $this->user->getId());
    }

    public function testSetId()
    {
        $this->user->setId(2);
        $this->assertEquals(2, $this->user->getId());
    }

    public function testGetUsername()
    {
        $this->assertEquals('testuser', $this->user->getUsername());
    }

    public function testSetUsername()
    {
        $this->user->setUsername('newuser');
        $this->assertEquals('newuser', $this->user->getUsername());
    }

    public function testGetPassword()
    {
        $this->assertEquals('testpassword', $this->user->getPassword());
    }

    public function testSetPassword()
    {
        $this->user->setPassword('newpassword');
        $this->assertEquals('newpassword', $this->user->getPassword());
    }
}