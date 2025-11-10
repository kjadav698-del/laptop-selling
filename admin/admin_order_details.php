<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "Invalid order ID.";
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order and user info
$query = "SELECT o.*, u.name AS user_name, u.email 
          FROM orders o 
          JOIN customers u ON o.user_id = u.id 
          WHERE o.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit();
}

// Fetch order items
$query_items = "SELECT * FROM order_items WHERE order_id = ?";
$stmt_items = $conn->prepare($query_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            padding: 40px;
            color:#2a2a2a;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        h1, h2 {
            color: #2a2a2a;
        }

        .section {
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #2a2a2a;
            color: white;
        }

        .info p {
            margin: 6px 0;
        }

        .btn-back {
            display: inline-block;
            padding: 10px 18px;
            background-color: #2a2a2a;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .btn-back:hover {
            background-color: #2a2a2a;
        }
    </style>
</head>
<body>
<div class="container">

    <h1>Order Details</h1>

    <div class="section info">
        <h2>Customer Info</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
        <p><strong>Shipping Name:</strong> <?= htmlspecialchars($order['name']) ?></p>
        <p><strong>Shipping Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
    </div>

    <div class="section info">
        <h2>Order Info</h2>
        <p><strong>Order ID:</strong> <?= $order['order_id'] ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
        <p><strong>Total Price:</strong> ₹<?= number_format($order['total_price'], 2) ?></p>
        <p><strong>Order Date:</strong> <?= date('d-m-Y', strtotime($order['order_date'])) ?></p>
    </div>

    <div class="section">
        <h2>Ordered Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price (₹)</th>
                    <th>Quantity</th>
                    <th>Total (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $items->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a class="btn-back" href="admin_orders.php">← Back to Orders</a>

</div>
</body>
</html>
