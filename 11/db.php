<?php
$host = "localhost";
$user = "root";   // default XAMPP user
$pass = "";       // default XAMPP password is empty
$db   = "studentportal";

$conn = new mysqli($host, $user, $pass, $db);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>