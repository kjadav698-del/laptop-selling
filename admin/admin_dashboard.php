<?php
session_start();
require_once '../db_connection.php'; // Include your DB connection file

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
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
            background-color:#2a2a2a;
        }

        /* .sidebar a.active {
            /* background-color: ; */

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
        <div class="header">
            <h1>Welcome to the Admin Dashboard</h1>
        </div>
        <div class="content">
            <h1>Dashboard</h1>
            <p>Here you can manage all aspects of the website, such as users, plants, orders, and settings.</p>
        </div>
    </div>

</body>
</html>
