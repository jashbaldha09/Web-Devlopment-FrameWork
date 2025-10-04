<?php
include("db.php");
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'user'; // default user

    if ($username == '' || $email == '' || $password == '') {
        $msg = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $msg = "Password must be at least 6 characters!";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username,email,password,role) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $username,$email,$hash,$role);

        if ($stmt->execute()) {
            $msg = "✅ Registration successful!";
        } else {
            $msg = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .container { max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        input, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .error { color: red; text-align: center; }
        .success { color: green; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>    
    <?php if($msg) echo "<p>$msg</p>"; ?>
    <form method="POST">
        Username: <input type="text" name="username" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        Role: 
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br><br>
        <button type="submit">Register</button>
    </form>
        <p>Already registered? <a href="login.php">Login here</a></p>
    </div>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>