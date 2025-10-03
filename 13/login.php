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
</head>
<body>
    <h2>Login</h2>
    <p>Anshkumar Darji - 24CE022</p>
    <?php if($msg) echo "<p>$msg</p>"; ?>

    <form method="POST">
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        CAPTCHA (enter 1234): <input type="text" name="captcha" required><br><br>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>