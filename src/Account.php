<?php

namespace src;

/**
 * Class Account
 *
 * This class represents an account with an ID and balance.
 */
class Account {
    // The ID of the account
    private $id;

    // The balance of the account
    private $balance;

    /**
     * Account constructor.
     *
     * @param $id - The ID of the account
     * @param $balance - The balance of the account
     */
    public function __construct($id, $balance) {
        $this->id = $id;
        $this->balance = $balance;
    }

    /**
     * Get the ID of the account
     *
     * @return mixed - The ID of the account
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the ID of the account
     *
     * @param $id - The new ID of the account
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get the balance of the account
     *
     * @return mixed - The balance of the account
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * Set the balance of the account
     *
     * @param $balance - The new balance of the account
     */
    public function setBalance($balance) {
        $this->balance = $balance;
    }
}
