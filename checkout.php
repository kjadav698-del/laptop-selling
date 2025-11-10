<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT c.quantity, p.name, p.price, p.image
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total_price = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .checkout-container { max-width: 800px; margin: 50px auto; background: #fff; border-radius: 10px; box-shadow: 0 5px 15px #0001; padding: 30px; }
        .checkout-item { display: flex; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #eee; padding-bottom: 18px; }
        .checkout-item img { width: 120px; height: 120px; object-fit: contain; border-radius: 8px; margin-right: 24px; }
        .checkout-item-details { flex: 1; }
        .checkout-item-title { font-size: 1.2rem; font-weight: 600; margin-bottom: 8px; }
        .checkout-item-price { color: #007bff; font-weight: bold; margin-bottom: 8px; }
        .checkout-item-qty { color: #444; }
        .checkout-total { text-align:right; font-size:1.2em; font-weight:600; margin-top:30px; }
        .confirm-btn { background: #007bff; color: #fff; border: none; padding: 10px 28px; border-radius: 5px; cursor: pointer; font-weight: 600; font-size: 1.1em; margin-top: 10px; transition: background 0.2s; }
        .confirm-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <?php session_start(); ?>
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
    <div class="checkout-container">
        <h2>Checkout - Your Cart Details</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php $item_total = $row['price'] * $row['quantity']; $total_price += $item_total; ?>
                <div class="checkout-item">
                    <img src="upload/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image">
                    <div class="checkout-item-details">
                        <div class="checkout-item-title"><?php echo htmlspecialchars($row['name']); ?></div>
                        <div class="checkout-item-price">₹<?php echo number_format($item_total); ?>/-</div>
                        <div class="checkout-item-qty">Quantity: <?php echo $row['quantity']; ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
            <div class="checkout-total">Total: ₹<?php echo number_format($total_price); ?>/-</div>
            <form method="post" style="margin-top:40px;">
                <div style="margin-bottom:18px;">
                    <label for="name" style="font-weight:600;">Name:</label><br>
                    <input type="text" id="name" name="name" required style="width:100%;padding:8px;border-radius:5px;border:1px solid #ccc;">
                </div>
                <div style="margin-bottom:18px;">
                    <label for="address" style="font-weight:600;">Address:</label><br>
                    <textarea id="address" name="address" rows="3" required style="width:100%;padding:8px;border-radius:5px;border:1px solid #ccc;"></textarea>
                </div>
                <button type="submit" class="confirm-btn">Place Order</button>
            </form>
        <?php else: ?>
            <div class="empty-cart">Your cart is empty.</div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $stmt->close(); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['address'])) {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $user_id = $_SESSION['user_id'];

    // Fetch cart items (product_id, quantity, price)
    $stmt_cart = $conn->prepare("SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $stmt_cart->bind_param("i", $user_id);
    $stmt_cart->execute();
    $result_cart = $stmt_cart->get_result();
    $cart_items = [];
    $total_price = 0;
    while ($row = $result_cart->fetch_assoc()) {
        $cart_items[] = $row;
        $total_price += $row['price'] * $row['quantity'];
    }
    $stmt_cart->close();

    if (empty($cart_items)) {
        // nothing to order
        $_SESSION['order_error'] = 'Your cart is empty.';
        header('Location: cart.php');
        exit();
    }

    // Determine customer_id: try to find by session email, otherwise create a customer record
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

    if (!$customer_id) {
        // create new customer using provided name and address and email if available
        $stmt_ins_c = $conn->prepare("INSERT INTO customers (name, email, address) VALUES (?, ?, ?)");
        $stmt_ins_c->bind_param("sss", $name, $user_email, $address);
        $stmt_ins_c->execute();
        $customer_id = $conn->insert_id;
        $stmt_ins_c->close();
    }

    // Create an order record
    $order_number = 'ORD-' . time() . '-' . rand(1000,9999);
    $stmt_order = $conn->prepare("INSERT INTO orders (order_number, customer_id, total_amount, status) VALUES (?, ?, ?, 'Pending')");
    $stmt_order->bind_param("sdi", $order_number, $customer_id, $total_price);
    $stmt_order->execute();
    $order_id = $conn->insert_id;
    $stmt_order->close();

    // Insert order items
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $it) {
        $stmt_item->bind_param("iiid", $order_id, $it['product_id'], $it['quantity'], $it['price']);
        $stmt_item->execute();
    }
    $stmt_item->close();

    // Clear cart
    $stmt_clear = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt_clear->bind_param("i", $user_id);
    $stmt_clear->execute();
    $stmt_clear->close();

    // Redirect to thank you page
    $_SESSION['order_name'] = $name;
    $_SESSION['order_address'] = $address;
    header('Location: thankyou.php');
    exit();
}