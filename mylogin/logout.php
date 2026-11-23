<?php
session_start();
require "login.php";

    if (isset($_SESSION['username'])){
        $_SESSION['username'] = "";
        header("Location: index.php");
        session_destroy();
        exit;

    }
?>
