<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
// Find corresponding customer id by logged-in user's email (if available)
$customer_id = null;
$user_email = $_SESSION['user_email'] ?? '';
if ($user_email) {
    $stmt_c = $conn->prepare("SELECT id FROM customers WHERE email = ? LIMIT 1");
    $stmt_c->bind_param("s", $user_email);
    $stmt_c->execute();
    $res_c = $stmt_c->get_result();
    if ($rc = $res_c->fetch_assoc()) {
        $customer_id = $rc['id'];
    }
    $stmt_c->close();
}

if ($customer_id) {
    // Fetch orders for the customer
    $stmt = $conn->prepare(
        "SELECT o.order_id, o.order_number, o.total_amount, o.status, o.order_date
         FROM orders o
         WHERE o.customer_id = ?
         ORDER BY o.order_date DESC"
    );
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // No customer mapped - no orders
    $result = (object) ['num_rows' => 0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .orders-container { max-width: 800px; margin: 50px auto; background: #fff; border-radius: 10px; box-shadow: 0 5px 15px #0001; padding: 30px; }
        .order-item { border-bottom: 1px solid #eee; padding: 18px 0; }
        .order-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 8px; }
        .order-details { color: #444; margin-bottom: 6px; }
        .order-total { color: #007bff; font-weight: bold; }
        .order-date { color: #888; font-size: 0.95em; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">Gaming<span>Laptop</span></a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="orders.php">My Orders</a></li>
                <li><a href="AboutUS.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <div class="nav-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="logout-btn">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="login-btn">Login</a>
                    <a href="register.php" class="register-btn">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="orders-container">
        <h2>My Orders</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="order-item">
                    <div class="order-title">Order <?php echo htmlspecialchars($row['order_number']); ?> — <span style="font-weight:600;"><?php echo htmlspecialchars($row['status']); ?></span></div>
                    <div class="order-date">Placed on: <?php echo htmlspecialchars($row['order_date']); ?></div>
                    <div class="order-details">
                        <table style="width:100%;border-collapse:collapse;margin-top:10px;">
                            <thead>
                                <tr style="text-align:left;border-bottom:1px solid #eee;">
                                    <th>Product</th>
                                    <th style="width:120px;">Unit Price</th>
                                    <th style="width:80px;">Qty</th>
                                    <th style="width:120px;text-align:right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                // fetch order items for this order
                                $stmt_items = $conn->prepare("SELECT oi.quantity, oi.price, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                                $stmt_items->bind_param("i", $row['order_id']);
                                $stmt_items->execute();
                                $res_items = $stmt_items->get_result();
                                while ($it = $res_items->fetch_assoc()):
                                    $item_total = $it['price'] * $it['quantity'];
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($it['name']); ?></td>
                                    <td>₹<?php echo number_format($it['price']); ?>/-</td>
                                    <td><?php echo $it['quantity']; ?></td>
                                    <td style="text-align:right;">₹<?php echo number_format($item_total); ?>/-</td>
                                </tr>
                            <?php endwhile; $stmt_items->close(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align:right;margin-top:10px;" class="order-total">Order Total: ₹<?php echo number_format($row['total_amount']); ?>/-</div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-cart">You have no orders yet.</div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php if (isset($stmt) && $stmt) { $stmt->close(); } ?>