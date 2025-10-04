<?php
session_start(); // start session

$servername = "localhost";
$dbusername = "root"; // XAMPP default
$dbpassword = "";     // XAMPP default
$dbname = "user_auth_system"; // your manually created DB

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
