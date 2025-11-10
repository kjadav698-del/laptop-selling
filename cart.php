<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle remove from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_cart_id'])) {
    $remove_cart_id = intval($_POST['remove_cart_id']);
    $user_id = $_SESSION['user_id'];
    $stmt_remove = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt_remove->bind_param("ii", $remove_cart_id, $user_id);
    $stmt_remove->execute();
    $stmt_remove->close();
    // Refresh to update cart and show message
    header('Location: cart.php?removed=1');
    exit();
}

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart_id'], $_POST['update_action'])) {
    $update_cart_id = intval($_POST['update_cart_id']);
    $user_id = $_SESSION['user_id'];
    $action = $_POST['update_action'];
    $stmt_qty = $conn->prepare("SELECT quantity FROM cart WHERE id = ? AND user_id = ?");
    $stmt_qty->bind_param("ii", $update_cart_id, $user_id);
    $stmt_qty->execute();
    $result_qty = $stmt_qty->get_result();
    if ($row_qty = $result_qty->fetch_assoc()) {
        $quantity = $row_qty['quantity'];
        if ($action === 'increment') {
            $quantity++;
        } elseif ($action === 'decrement' && $quantity > 1) {
            $quantity--;
        }
        $stmt_update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt_update->bind_param("iii", $quantity, $update_cart_id, $user_id);
        $stmt_update->execute();
        $stmt_update->close();
        // Refresh to update cart quantity and show message
        header('Location: cart.php?quantity=1');
        exit();
    }
    $stmt_qty->close();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT c.id, c.quantity, p.name, p.price, p.image
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
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-container { max-width: 800px; margin: 50px auto; background: #fff; border-radius: 10px; box-shadow: 0 5px 15px #0001; padding: 30px; }
        .cart-item { display: flex; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #eee; padding-bottom: 18px; }
        .cart-item img { width: 120px; height: 120px; object-fit: contain; border-radius: 8px; margin-right: 24px; }
        .cart-item-details { flex: 1; }
        .cart-item-title { font-size: 1.2rem; font-weight: 600; margin-bottom: 8px; }
        .cart-item-price { color: #007bff; font-weight: bold; margin-bottom: 8px; }
        .cart-item-qty { color: #444; display: flex; align-items: center; }
        .qty-btn { background: #007bff; color: #fff; border: none; padding: 3px 10px; border-radius: 3px; cursor: pointer; font-weight: 600; margin: 0 5px; }
        .qty-btn:disabled { background: #ccc; cursor: not-allowed; }
        .remove-btn { background:#444; color: #fff; border: none; padding: 7px 18px; border-radius: 5px; cursor: pointer; font-weight: 600; transition: background 0.2s; }
        .remove-btn:hover { background: #444; }
        .qty-value { min-width: 32px; text-align: center; font-weight: bold; }
        .cart-item-actions { margin-top: 10px; }
        .cart-item-actions form { display: inline; }
        .checkout-btn { background: #28a745; color: #fff; border: none; padding: 10px 28px; border-radius: 5px; cursor: pointer; font-weight: 600; font-size: 1.1em; margin-top: 10px; transition: background 0.2s; }
        .checkout-btn:hover { background: #218838; }
    </style>
    <script>
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('added')) {
            alert('Product added to cart!');
        }
        if (urlParams.get('removed')) {
            alert('Product removed from cart!');
        }
        if (urlParams.get('quantity')) {
            alert('Cart quantity updated!');
        }
    };
    </script>
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
    <div class="cart-container">
        <h2>Your Cart</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php $item_total = $row['price'] * $row['quantity']; $total_price += $item_total; ?>
                <div class="cart-item">
                    <img src="upload/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image">
                    <div class="cart-item-details">
                        <div class="cart-item-title"><?php echo htmlspecialchars($row['name']); ?></div>
                        <div class="cart-item-price">₹<?php echo number_format($item_total); ?>/-</div>
                        <div class="cart-item-qty">
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="update_cart_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="update_action" value="decrement">
                                <button type="submit" class="qty-btn" <?php if ($row['quantity'] <= 1) echo 'disabled'; ?>>-</button>
                            </form>
                            <span class="qty-value"><?php echo $row['quantity']; ?></span>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="update_cart_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="update_action" value="increment">
                                <button type="submit" class="qty-btn">+</button>
                            </form>
                        </div>
                        <div class="cart-item-actions">
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="remove_cart_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <div style="text-align:right; font-size:1.2em; font-weight:600; margin-top:30px;">Cart Total: ₹<?php echo number_format($total_price); ?>/-</div>
            <form action="checkout.php" method="get" style="text-align:right; margin-top:20px;">
                <button type="submit" class="checkout-btn">Proceed to Checkout</button>
            </form>
        <?php else: ?>
            <div class="empty-cart">Your cart is empty.</div>
        <?php endif; ?>
        <!-- <a href="shop.php" class="back-link">&larr; Continue Shopping</a> -->
    </div>
</body>
</html>
<?php $stmt->close(); ?>