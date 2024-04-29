<?php

namespace src;

/**
 * Class User
 *
 * This class represents a user with an ID, username, and password.
 */
class User {
    // The ID of the user
    private $id;

    // The username of the user
    private $username;

    // The password of the user
    private $password;

    /**
     * User constructor.
     *
     * @param $id - The ID of the user
     * @param $username - The username of the user
     * @param $password - The password of the user
     */
    public function __construct($id, $username, $password) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get the ID of the user
     *
     * @return mixed - The ID of the user
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the ID of the user
     *
     * @param $id - The new ID of the user
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Get the username of the user
     *
     * @return mixed - The username of the user
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set the username of the user
     *
     * @param $username - The new username of the user
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * Get the password of the user
     *
     * @return mixed - The password of the user
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set the password of the user
     *
     * @param $password - The new password of the user
     */
    public function setPassword($password) {
        $this->password = $password;
    }
}
