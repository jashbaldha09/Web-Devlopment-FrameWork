<?php
// ====================== CONFIG ======================
$host = "localhost";      
$user = "root";           
$pass = "";               
$db   = "studenthub";     

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database Connection failed: " . htmlspecialchars($conn->connect_error));
}

$msg = "";
$editStudent = null; // for storing data while editing

// ====================== INSERT ======================
if (isset($_POST['add_student'])) {
    $stmt = $conn->prepare("INSERT INTO students (student_id, name, email, course, year) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $_POST['student_id'], $_POST['name'], $_POST['email'], $_POST['course'], $_POST['year']);
    if ($stmt->execute()) $msg = "Student added successfully!";
    else $msg = "Error: " . htmlspecialchars($stmt->error);
    $stmt->close();
}

// ====================== UPDATE ======================
if (isset($_POST['update_student'])) {
    $stmt = $conn->prepare("UPDATE students SET name=?, email=?, course=?, year=? WHERE student_id=?");
    $stmt->bind_param("sssii", $_POST['name'], $_POST['email'], $_POST['course'], $_POST['year'], $_POST['student_id']);
    if ($stmt->execute()) $msg = "Student updated successfully!";
    else $msg = "Error: " . htmlspecialchars($stmt->error);
    $stmt->close();
}

// ====================== DELETE ======================
if (isset($_POST['delete_student'])) {
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id=?");
    $stmt->bind_param("i", $_POST['student_id']);
    if ($stmt->execute()) $msg = "Student deleted successfully!";
    else $msg = "Error: " . htmlspecialchars($stmt->error);
    $stmt->close();
}

// ====================== LOAD STUDENT FOR EDIT ======================
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = $conn->prepare("SELECT * FROM students WHERE student_id=?");
    $res->bind_param("i", $id);
    $res->execute();
    $editStudent = $res->get_result()->fetch_assoc();
    $res->close();
}

// ====================== FETCH STUDENTS ======================
$students = $conn->query("SELECT * FROM students ORDER BY student_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StudentHub Portal</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        table, th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #007bff; color: white; }
        form { margin: 20px 0; }
        input { padding: 8px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; }
        .msg { padding: 15px; margin: 10px 0; border-radius: 5px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        button { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 2px; }
        button:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>StudentHub Portal</h1>
        <p><strong>Created by Jashkumar : 24CE004</strong></p>

    <?php if (!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>

    <!-- Student Form -->
    <h2><?= $editStudent ? "Edit Student (ID: {$editStudent['student_id']})" : "Add New Student" ?></h2>
    <form method="POST">
        <input type="number" name="student_id" placeholder="ID" value="<?= $editStudent['student_id'] ?? '' ?>" <?= $editStudent ? 'readonly' : 'required' ?>>
        <input type="text" name="name" placeholder="Full Name" value="<?= $editStudent['name'] ?? '' ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= $editStudent['email'] ?? '' ?>" required>
        <input type="text" name="course" placeholder="Course" value="<?= $editStudent['course'] ?? '' ?>" required>
        <input type="number" name="year" placeholder="Year" value="<?= $editStudent['year'] ?? '' ?>" required>

        <?php if ($editStudent): ?>
            <button type="submit" name="update_student">Update</button>
            <a href="studenthub.php" class="btn">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_student">Add</button>
        <?php endif; ?>
    </form>

    <!-- Student List -->
    <h2>All Students</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Year</th><th>Action</th></tr>
        <?php while ($row = $students->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['student_id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['course']) ?></td>
            <td><?= htmlspecialchars($row['year']) ?></td>
            <td>
                <form method="GET" style="display:inline;">
                    <input type="hidden" name="edit" value="<?= $row['student_id'] ?>">
                    <button type="submit">Edit</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="student_id" value="<?= $row['student_id'] ?>">
                    <button type="submit" name="delete_student">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
