<?php

$conn = new mysqli("localhost", "root", "", "LoginDB", 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}