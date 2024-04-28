<?php

namespace src;

// Account class
class Account {
    private $id;
    private $balance;

    public function __construct($id, $balance) {
        $this->id = $id;
        $this->balance = $balance;
    }

    // Getters and setters for id and balance
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getBalance() {
        return $this->balance;
    }

    public function setBalance($balance) {
        $this->balance = $balance;
    }
}