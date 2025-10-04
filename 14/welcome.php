<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .btn { padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
        <p>You are logged in as a user.</p>
        <a href="logout.php" class="btn">Logout</a>
    </div>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>