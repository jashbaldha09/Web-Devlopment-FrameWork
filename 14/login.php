<?php
session_start();
include("db.php");
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email == '' || $password == '') {
        $msg = "All fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $username, $hash, $role);
            $stmt->fetch();

            if (password_verify($password, $hash)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                if ($role == 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: welcome.php");
                }
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
    <title>Login</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .container { max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: red; text-align: center; }
        .link { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if($msg) echo "<p class='error'>$msg</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="link">
            <p>New user? <a href="register.php">Register here</a></p>
        </div>
    </div>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>