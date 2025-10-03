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
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 80%; margin: 20px 0; }
        table, th, td { border: 1px solid #666; padding: 8px; text-align: left; }
        form { margin: 20px 0; }
        .msg { padding: 10px; margin: 10px 0; border-radius: 5px; background: #eef; font-weight: bold; }
        a.btn, button { padding: 4px 8px; background: #ddd; border: 1px solid #999; text-decoration: none; margin: 2px; cursor: pointer; }
        a.btn { display: inline-block; }
    </style>
</head>
<body>
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
</body>
</html>
<?php $conn->close(); ?>
