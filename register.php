<?php
include 'dbconfig.php';
$title= "Registration";
include "header.php";
session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $emailAddress = $_POST['email'];
    $password = $_POST['pw'];
    $_SESSION['logged_in'] = $emailAddress;

    $insertUsers = $connection->prepare("INSERT INTO users (name, email_address, password) VALUES (?, ?, ?)");
    $insertUsers->bind_param("sss", $name, $emailAddress, $password);
    $insertUsers->execute();

    $insertNotes = $connection->prepare("INSERT INTO notes (email_address) VALUES (?)");
    $insertNotes->bind_param("s", $emailAddress, );
    $insertNotes->execute();

    $connection->close();
}
?>
<html>
<div id="login_box_container">
    <h1>Register</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>
    <form action="" method="post">
        <label for="name"><b>Your Name:</b></label>
        <input type="text" placeholder="Enter Name" name="name" required>

        <label for="email"><b>Email Address:</b></label>
        <input type="text" placeholder="Enter Email" name="email" required>

        <label for="pw"><b>Password:</b></label>
        <input type="password" placeholder="Enter Password" name="pw" required>

        <button type="submit" class="registerbtn" name="submit">Register</button>
    </form>
</div>
</html>