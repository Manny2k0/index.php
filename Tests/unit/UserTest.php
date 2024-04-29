<?php

use PHPUnit\Framework\TestCase;
use src\User;

class UserTest extends TestCase
{
    private User $user; // Add this property

    protected function setUp(): void // Add this method
    {
        $this->user = new User(1, 'testuser', 'testpassword'); // Create a new User object with ID 1, username 'testuser', and password 'testpassword'
    }

    public function testGetId() //
    {
        $this->assertEquals(1, $this->user->getId()); // Check if ID is 1
    }

    public function testSetId()
    {
        $this->user->setId(2); // Set ID to 2
        $this->assertEquals(2, $this->user->getId()); // Check if ID is 2
    }

    public function testGetUsername()
    {
        $this->assertEquals('testuser', $this->user->getUsername()); // Check if username is 'testuser'
    }

    public function testSetUsername()
    {
        $this->user->setUsername('newuser'); // Set username to 'newuser'
        $this->assertEquals('newuser', $this->user->getUsername()); // Check if username is 'newuser'
    }

    public function testGetPassword()
    {
        $this->assertEquals('testpassword', $this->user->getPassword()); // Check if password is 'testpassword'
    }

    public function testSetPassword() // Add this method
    {
        $this->user->setPassword('newpassword'); // Set password to 'newpassword'
        $this->assertEquals('newpassword', $this->user->getPassword()); // Check if password is 'newpassword'
    }
}