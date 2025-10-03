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

    echo "<p style='color:green;'>Data saved successfully!</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Submission</title>
</head>
<body>
    <h2>Submit Your Details</h2>
    <form method="POST">
        Name: <input type="text" name="name" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Age: <input type="number" name="age" required><br><br>
        <button type="submit">Submit</button>
    </form>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>