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

    $sqlStatement = $connection->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $sqlStatement->bind_param("sss", $name, $emailAddress, $password);
    $sqlStatement->execute();

    $connection->close();
}
?>
<html>
<div id="login_box">
    <center>
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
    </center>
</div>
</html>