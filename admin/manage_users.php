<?php
session_start();
require_once '../db_connection.php'; // Include the database connection

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch users from the database
$query = "SELECT * FROM users";
$result = $conn->query($query);

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "User deleted successfully.";
        header("Location:manage_users.php");
    } else {
        $message = "Error deleting user.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #2a2a2a;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
        }

        .sidebar h2 {
            margin-bottom: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            width: 100%;
            padding: 10px 20px;
            text-align: left;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #2a2a2a;
        }
/* 
        .sidebar a.active {
            background-color: ;
        } */

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .main-content h1 {
            margin-bottom: 20px;
        }

        /* Header */
        .header {
            width: 100%;
            background-color: #2a2a2a;
            color: white;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 250px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 1.5em;
        }

        .content {
            margin-top: 60px; /* To avoid content hiding under header */
        }
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-table th, .user-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .user-table th {
            background-color: #2a2a2a;
            color: white;
        }

        .user-table td {
            background-color: #f9f9f9;
        }

        .user-table tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        .action-btn {
            padding: 5px 10px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .action-btn:hover {
            background-color: #c0392b;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .message.success {
            color: #4caf50;
        }

        .message.error {
            color: #e74c3c;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_profile.php">Profile</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_product.php" class="active">Manage Product</a>
    <a href="admin_orders.php">Orders</a>
    <a href="logout.php">Logout</a>
</div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
        <!-- <a href="dashboard.php" class="back-link">Back to Dashboard</a> -->
        <h1>Manage Users</h1>

            <?php if (isset($message)): ?>
                <p class="message <?= isset($success) ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($user = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <a class="action-btn" href="manage_users.php?delete_id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    <!-- <a href="#" class="action-btn">Deactivate</a> -->
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
