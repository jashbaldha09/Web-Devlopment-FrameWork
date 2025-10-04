<?php
session_start();

// If cookies exist, pre-fill the form
$savedUser = isset($_COOKIE['username']) ? $_COOKIE['username'] : "";
$savedPass = isset($_COOKIE['password']) ? $_COOKIE['password'] : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dummy username and password
    $username = "admin";
    $password = "1234";

    $inputUser = $_POST['username'];
    $inputPass = $_POST['password'];

    if ($inputUser === $username && $inputPass === $password) {
        // Save session variable
        $_SESSION['username'] = $username;

        // If "Remember Me" is checked → Save cookies for 7 days
        if (!empty($_POST['remember'])) {
            setcookie("username", $inputUser, time() + (86400 * 7), "/");
            setcookie("password", $inputPass, time() + (86400 * 7), "/");
        } else {
            // Remove cookies if unchecked
            setcookie("username", "", time() - 3600, "/");
            setcookie("password", "", time() - 3600, "/");
        }

        header("Location: welcome.php");
        exit();
    } else {
        echo "<p style='color:red;'>Invalid username or password</p>";
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
        input[type="text"], input[type="password"] { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .checkbox { margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Page</h2>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" value="<?php echo $savedUser; ?>" required>
            <input type="password" name="password" placeholder="Password" value="<?php echo $savedPass; ?>" required>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember" <?php if($savedUser) echo "checked"; ?>> Remember Me
                </label>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>
