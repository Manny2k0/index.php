<?php

namespace src;

use Tests\unit\ListPageTest;

class dependencies
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function Register(): Register
    {
        return new Register();
    }

    public function Transfer(): Transfer
    {
        return new Transfer($this->container['pdo']);
    }

    public function Login(): Login
    {
        return new Login();
    }

    public function di(): ListPageTest
    {
        return new ListPageTest($this->container);
    }
}