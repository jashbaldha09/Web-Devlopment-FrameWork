<?php
session_start();
include("db.php");

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get inputs and sanitize
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $captcha  = $_POST['captcha'] ?? '';

    // Basic validation
    if ($email == '' || $password == '' || $captcha == '') {
        $msg = "All fields are required!";
    } elseif ($captcha != '1234') { // placeholder CAPTCHA
        $msg = "Incorrect CAPTCHA!";
    } else {
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $username, $passwordHash);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $passwordHash)) {
                // Login success, save session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                header("Location: welcome.php"); // redirect to dashboard
                exit;
            } else {
                $msg = "Incorrect password!";
            }
        } else {
            $msg = "Email not found!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Secure Portal</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .container { max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
    <?php if($msg) echo "<p>$msg</p>"; ?>

    <form method="POST">
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        CAPTCHA (enter 1234): <input type="text" name="captcha" required><br><br>
        <button type="submit">Login</button>
    </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>