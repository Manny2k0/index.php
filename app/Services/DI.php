<?php

namespace Codeception\Extension;

use Codeception\Event\TestEvent;
use Codeception\Events;

class DI extends \Codeception\Extension
{
    protected $services = [];

    public function set($name, $value)
    {
        $this->services[$name] = $value;
    }

    public function get($name)
    {
        if (!isset($this->services[$name])) {
            throw new Exception("Service {$name} not found");
        }

        return $this->services[$name];
    }

    // Define what events this extension listens to
    public static $events = [
        Events::TEST_BEFORE => 'beforeTest',
    ];

    // Method executed before each test
    public function beforeTest(TestEvent $e)
    {
        // You can put code here that should be executed before each test
    }
}