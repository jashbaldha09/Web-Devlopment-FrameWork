<?php
include("db.php");

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize & validate inputs
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $captcha  = $_POST['captcha'] ?? '';

    // simple validation
    if ($username == '' || $email == '' || $password == '' || $captcha == '') {
        $msg = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $msg = "Password must be at least 6 characters!";
    } elseif ($captcha != '1234') {  // placeholder captcha
        $msg = "Incorrect CAPTCHA!";
    } else {
        // 2. Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // 3. Prepare SQL to prevent injection
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwordHash);

        if ($stmt->execute()) {
            $msg = "Registration successful!";
        } else {
            $msg = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure Registration</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .container { max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .error { color: red; text-align: center; }
        .success { color: green; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
    <h2>Register</h2>
    <?php if($msg) echo "<p class='error'>$msg</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="captcha" placeholder="CAPTCHA (enter 1234)" required>
        <button type="submit">Register</button>
    </form>
    </div>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>