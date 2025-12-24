<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    echo "You do not have permission to view this page.";
    exit();
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $query = "SELECT * FROM orders WHERE order_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        if ($order) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
                $status = trim($_POST['status']);

                if (!empty($status) && in_array($status, ['Pending', 'Shipped', 'Completed', 'Cancelled'])) {
                    $update_query = "UPDATE orders SET status = ? WHERE order_id = ?";
                    if ($stmt_update = $conn->prepare($update_query)) {
                        $stmt_update->bind_param('si', $status, $order_id);
                        if ($stmt_update->execute()) {
                            header("Location: admin_orders.php");
                            exit();
                        } else {
                            $message = "Error updating the order status.";
                        }
                    }
                } else {
                    $message = "Invalid status selected.";
                }
            }
        } else {
            $message = "Order not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Order Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f5;
            margin: 0;
            min-height: 100vh;

            /* Center everything */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .wrapper {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            max-width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .message {
            background-color: #ffe0e0;
            color: #c0392b;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <?php if (isset($message)) : ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (isset($order)) : ?>
        <h1>Update Order Status</h1>
        <form action="" method="POST">
            <label for="status">Order Status:</label>
            <select name="status" id="status">
                <option value="Pending"   <?php echo ($order['status'] == 'Pending')   ? 'selected' : ''; ?>>Pending</option>
                <option value="Shipped"   <?php echo ($order['status'] == 'Shipped')   ? 'selected' : ''; ?>>Shipped</option>
                <option value="Completed" <?php echo ($order['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                <option value="Cancelled" <?php echo ($order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
            <button type="submit">Update Status</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
