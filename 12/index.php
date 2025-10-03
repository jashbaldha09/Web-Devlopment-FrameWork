<?php
// ---------- DATABASE CONNECTION ----------
$host = "localhost";
$user = "root";  // XAMPP default
$pass = "";      // XAMPP default
$db   = "event_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ---------- CREATE (ADD EVENT) ----------
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $event_date = $_POST['event_date'];   // corrected
    $location = $_POST['location'];
    $status = $_POST['status'];

    $sql = "INSERT INTO events (title, event_date, location, status) 
            VALUES ('$title','$event_date','$location','$status')";
    if ($conn->query($sql)) {
        echo " Event added successfully!<br>";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}

// ---------- UPDATE EVENT ----------
if (isset($_POST['update'])) {
    $id = $_POST['event_id'];
    $title = $_POST['title'];
    $event_date = $_POST['event_date'];   // corrected
    $location = $_POST['location'];
    $status = $_POST['status'];

    $sql = "UPDATE events 
            SET title='$title', event_date='$event_date', location='$location', status='$status' 
            WHERE event_id=$id";
    if ($conn->query($sql)) {
        echo " Event updated successfully!<br>";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}

// ---------- DELETE EVENT ----------
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM events WHERE event_id=$id";
    if ($conn->query($sql)) {
        echo " Event deleted successfully!<br>";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}

// ---------- FETCH EVENTS ----------
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event CRUD</title>
</head>
<body>
    <h2>Event Management (CRUD)</h2>

    <!-- ADD EVENT FORM -->
    <h3>Add New Event</h3>
    <form method="post">
        Title: <input type="text" name="title" required><br>
        Date: <input type="date" name="event_date" required><br> <!-- corrected -->
        Location: <input type="text" name="location" required><br>
        Status:
        <select name="status">
            <option value="open">Open</option>
            <option value="closed">Closed</option>
        </select><br>
        <input type="submit" name="add" value="Add Event">
    </form>

    <hr>

    <!-- DISPLAY EVENTS -->
    <h3>All Events</h3>
    <h3>Created by :</h3>
    <p><strong>KRRISH BHARDWAJ || 24CE010</strong></p>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Date</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['event_id'] ?></td>
            <td><?= $row['title'] ?></td>
            <td><?= $row['event_date'] ?></td> <!-- corrected -->
            <td><?= $row['location'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <!-- EDIT FORM INLINE -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="event_id" value="<?= $row['event_id'] ?>">
                    Title: <input type="text" name="title" value="<?= $row['title'] ?>" required>
                    Date: <input type="date" name="event_date" value="<?= $row['event_date'] ?>" required> <!-- corrected -->
                    Location: <input type="text" name="location" value="<?= $row['location'] ?>" required>
                    Status:
                    <select name="status">
                        <option value="open" <?= $row['status']=='open'?'selected':'' ?>>Open</option>
                        <option value="closed" <?= $row['status']=='closed'?'selected':'' ?>>Closed</option>
                    </select>
                    <input type="submit" name="update" value="Update">
                </form>

                <!-- DELETE LINK -->
                <a href="?delete=<?= $row['event_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>