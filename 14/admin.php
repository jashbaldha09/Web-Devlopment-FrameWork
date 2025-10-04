<?php
session_start();
include("db.php");

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Delete user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($id != $_SESSION['user_id']) { // cannot delete self
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Toggle status
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $stmt = $conn->prepare("SELECT status FROM users WHERE user_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    $newStatus = ($status == 'active') ? 'inactive' : 'active';
    $stmt = $conn->prepare("UPDATE users SET status=? WHERE user_id=?");
    $stmt->bind_param("si", $newStatus, $id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #007bff; color: white; }
        .btn { padding: 5px 10px; margin: 2px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn:hover { opacity: 0.8; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
    <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> | <a href="logout.php">Logout</a></p>

    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th>
        </tr>

        <?php
        $result = $conn->query("SELECT user_id, username, email, role, status FROM users ORDER BY user_id DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['user_id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['email']}</td>
                <td>{$row['role']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='admin.php?delete={$row['user_id']}'>Delete</a> | 
                    <a href='admin.php?toggle={$row['user_id']}'>Toggle Status</a>
                </td>
            </tr>";
        }
        ?>
    </table>
    </div>
    <footer style="margin-top: 50px; text-align: center; color: #666; font-size: 12px;">
        Created by Jashkumar : 24CE004
    </footer>
</body>
</html>