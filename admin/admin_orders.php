<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch all orders
$query = "SELECT o.order_id, o.total_amount, o.status, o.order_date, u.name AS user_name 
          FROM orders o
          JOIN customers u ON o.customer_id = u.id
          ORDER BY o.order_id DESC";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
        }

        h1 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color:#2a2a2a;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a.action-link {
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
        }

        a.action-link:hover {
            text-decoration: underline;
        }

        a.delete-link {
            color: red;
        }

        .message {
            background-color: #dff0d8;
            padding: 10px 15px;
            border-radius: 6px;
            color: #3c763d;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php
if ($stmt = $conn->prepare($query)) {
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h1>All Orders</h1>";
        echo "<table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";

        while ($order = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($order['order_id']) . "</td>
                    <td>" . htmlspecialchars($order['user_name']) . "</td>
                    <td>₹" . number_format($order['total_amount'], 2) . "</td>
                    <td>" . htmlspecialchars($order['status']) . "</td>
                    <td>" . date('d-m-Y', strtotime($order['order_date'])) . "</td>
                    <td>
                        <a class='action-link delete-link' href='admin_orders.php?delete_order=" . $order['order_id'] . "' onclick='return confirm(\"Are you sure you want to delete this order?\")'>Delete</a> |
                        <a class='action-link' href='update_order_status.php?order_id=" . $order['order_id'] . "'>Update</a>
                    </td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p class='message'>No orders found.</p>";
    }
} else {
    echo "<p class='message'>Error fetching orders.</p>";
}
?>

<?php
// Handle order deletion
if (isset($_GET['delete_order'])) {
    $order_id = $_GET['delete_order'];

    $delete_items_query = "DELETE FROM order_items WHERE order_id = ?";
    if ($stmt_items = $conn->prepare($delete_items_query)) {
        $stmt_items->bind_param('i', $order_id);
        $stmt_items->execute();
    }

    $delete_order_query = "DELETE FROM orders WHERE order_id = ?";
    if ($stmt_order = $conn->prepare($delete_order_query)) {
        $stmt_order->bind_param('i', $order_id);
        if ($stmt_order->execute()) {
            header("Location: admin_orders.php");
            exit();
        } else {
            echo "<p class='message'>Error deleting the order.</p>";
        }
    }
}
?>

</body>
</html>
