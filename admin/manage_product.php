<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $query = "SELECT image FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $imagePath = $product['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $delete_query = "DELETE FROM products WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $delete_id);
        if ($delete_stmt->execute()) {
            $_SESSION['message'] = "Product deleted successfully!";
        } else {
            $_SESSION['message'] = "Error deleting plant from the database.";
        }
    }
    header("Location: manage_product.php");
    exit();
}

$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Product - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f9f9f9;
        }

        .sidebar {
            width: 250px;
            background-color: #2a2a2a;
            color: white;
            padding-top: 30px;
            position: fixed;
            top: 0; left: 0; bottom: 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .sidebar a:hover,
        /* .sidebar a.active {
            background-color: #16a085;
        } */

        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        .main-content h1 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        label {
            font-weight: 600;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #2a2a2a;
            color: white;
            padding: 10px 20px;
            border: none;
            margin-top: 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2a2a2a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2a2a2a;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 12px;
            margin: 15px 0;
            border-radius: 6px;
        }

        a.delete-link {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        a.delete-link:hover {
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
    <h1>Add a New Product</h1>

    <?php 
    if (isset($_SESSION['message'])) {
        echo "<div class='message'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    ?>

    <form action="addproduct.php" method="post" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Product Price:</label>
        <input type="text" id="price" name="price" required>

        <label for="description">Product Description:</label>
        <input type="text" id="description" name="description" required>

        <label for="image">Upload Image:</label>
        <input type="file" id="image" name="image" required>

        <button type="submit">Add Product</button>
    </form>

    <h1>All Product</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price (₹)</th>
                <th>Description</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['price']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><img src="<?= htmlspecialchars($row['image']) ?>" alt="Plant Image"></td>
                <td>
                    <a class="delete-link" href="manage_product.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this plant?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
