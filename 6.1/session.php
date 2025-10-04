<!DOCTYPE html>
<html>
<head>
    <title>Login Result</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .success { color: #28a745; font-size: 18px; }
        .error { color: #dc3545; font-size: 18px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px; display: inline-block; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Status</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "StudentData";
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo "<p class='error'>Connection failed: " . $conn->connect_error . "</p>";
        } else {
            $uname = $_POST['txtuname'];
            $psd = $_POST['txtpsd'];
            
            $sql = "INSERT INTO krrishtbl VALUES ('$uname','$psd')";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='success'>Login data saved successfully!</p>";
            } else {
                echo "<p class='error'>Error: " . $conn->error . "</p>";
            }
        }
        $conn->close();
        ?>
        <a href="login.php" class="btn">Back to Login</a>
    </div>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>