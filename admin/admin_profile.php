<?php
session_start();
require_once '../db_connection.php'; // Include database connection

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch admin details
$admin_id = $_SESSION['admin_id'];
$query = "SELECT * FROM admin_users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Handle form submission for profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($new_password) && $new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_query = "UPDATE admin_users SET full_name = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $name, $hashed_password, $admin_id);
    } else {
        $update_query = "UPDATE admin_users SET full_name = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $name, $admin_id);
    }

    if ($stmt->execute()) {
        $_SESSION['admin_name'] = $name; // Update session name
        $success_message = "Profile updated successfully.";
    } else {
        $error_message = "Error updating profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
        }

        .profile-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .profile-container label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .profile-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .profile-container button {
            width: 100%;
            padding: 10px;
            background-color: #2a2a2a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .profile-container button:hover {
            background-color: #2a2a2a;
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
            margin-top: 10px;
            color: #3498db;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Admin Profile</h1>
        <?php if (isset($success_message)): ?>
            <p class="message success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <p class="message error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($admin['full_name']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" disabled>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password">

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">

            <button type="submit">Update Profile</button>
        </form>
        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
