<?php
require_once('../template/header.php'); // Include the header file
require_once('session.php'); // Include the session handling file
// session_start();
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../css/index.css">
<head>


</head>


<div class="container">
    <div class="header">
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="Contact.php">Contact</a></li>
            </ul>
        </nav>

    </div>

    <div class="mainarea">
        <h1>About</h1>

            <p class="lead">Welcome to our about page! Here, you can learn more about our company and our team. Feel free to explore and don't
                hesitate to reach out if you have any questions.</p>

    </div>

    <div class="row marketing">
        <div>
            <h4>About page</h4>
            <p>Welcome to our about page! Here, you can learn more about our company and our team. Feel free to explore and don't
                hesitate to reach out if you have any questions.</p>
            <?php require_once('../template/footer.php'); ?>
        </div>
    </div>
</div>


