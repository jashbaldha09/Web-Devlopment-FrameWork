<?php include("db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>StudentPortal</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; text-align: center; }
        form { margin: 20px 0; }
        input, button { padding: 10px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>StudentPortal</h2>
    <!-- Insert Form -->
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="course" placeholder="Course" required>
        <input type="number" name="year" placeholder="Year" required>
        <button type="submit" name="insert">Add Student</button>
    </form>

    <?php
    // INSERT
    if (isset($_POST['insert'])) {
        $name  = $_POST['name'];
        $email = $_POST['email'];
        $course= $_POST['course'];
        $year  = $_POST['year'];

        $sql = "INSERT INTO students (name,email,course,year) VALUES ('$name','$email','$course',$year)";
        if ($conn->query($sql)) {
            echo "<p style='color:green;'>Student inserted successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    }

    // DELETE
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM students WHERE student_id=$id");
    }
    ?>

    <!-- Search Form -->
    <form method="GET">
        <input type="text" name="search" placeholder="Search by Name">
        <button type="submit">Search</button>
    </form>

    <!-- Student Table -->
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Year</th><th>Action</th>
        </tr>
        <?php
        $condition = "";
        if (isset($_GET['search']) && $_GET['search'] != "") {
            $search = $_GET['search'];
            $condition = "WHERE name LIKE '%$search%'";
        }

        $result = $conn->query("SELECT * FROM students $condition ORDER BY student_id DESC");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['student_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['course']}</td>
                <td>{$row['year']}</td>
                <td><a href='index.php?delete={$row['student_id']}'>Delete</a></td>
            </tr>";
        }
        ?>
    </table>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Nijal Bhanderi : 24CE009
    </footer>
</body>
</html>