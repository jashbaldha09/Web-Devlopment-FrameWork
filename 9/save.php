<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $age   = $_POST['age'];

    // Save to text file
    $textData = "Name: $name | Email: $email | Age: $age" . PHP_EOL;
    file_put_contents("data.txt", $textData, FILE_APPEND);

    // Save to CSV file
    $csvFile = fopen("data.csv", "a");
    fputcsv($csvFile, array($name, $email, $age));
    fclose($csvFile);

    echo "<p class='success'>Data saved successfully!</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Submission</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .success { color: green; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Your Details</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Enter Name" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="number" name="age" placeholder="Enter Age" required>
            <button type="submit">Submit</button>
        </form>
    </div>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>