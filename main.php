<?php

require_once 'classes/Person.php';
require_once 'classes/User.php';
require_once 'classes/Account.php';
require_once 'classes/Transaction.php';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);

// Create a Person object
$person = new Person('John Doe', 'johndoe@example.com', '123 Main St');

// Create a User object
$user = new User($pdo, 1, 'username', 'password');

// Create an Account object and add it to the User
$account = new Account($pdo, 1, $user->getId(), 1000);
$user->addAccount($account);

// Create a Transaction object
$transaction = new Transaction($pdo, 1, $user->getId(), 500, 'debit', $account);

// Display the properties of each object
$person->display();
$user->display();
$account->display();
$transaction->display();

?>
