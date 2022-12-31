<?php
$connection = new mysqli("127.0.0.1", "root", "", "notepad");
if ($connection->connect_error) {
    die("Connection failed.");
}