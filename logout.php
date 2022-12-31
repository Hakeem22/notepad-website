<?php
session_start();
if(isset($_SESSION["logged_in"])) {
    session_destroy();
    unset($_SESSION["logged_in"]);
}
header("Location: index.php");