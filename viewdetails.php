<?php
require_once 'db_connection.php';

// Get product ID from query string
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT name, description, price, image FROM products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .details-container { max-width: 600px; margin: 50px auto; background: #fff; border-radius: 10px; box-shadow: 0 5px 15px #0001; padding: 30px; }
        .details-img { width: 100%; max-height: 300px; object-fit: contain; border-radius: 8px; margin-bottom: 20px; }
        .details-title { font-size: 2rem; margin-bottom: 10px; color: #222; }
        .details-price { color: red; font-size: 1.3rem; font-weight: bold; margin-bottom: 18px; }
        .details-desc { font-size: 1.1rem; color: #444; margin-bottom: 20px; }
        .back-link { display: inline-block; margin-top: 20px; color: #ff4d4d; text-decoration: none; font-weight: 500; }
        .back-link:hover { text-decoration: underline; }
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
    <div class="details-container">
        <?php if ($product): ?>
            <img src="upload/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" class="details-img">
            <div class="details-title"><?php echo htmlspecialchars($product['name']); ?></div>
            <div class="details-price">₹<?php echo number_format($product['price']); ?>/-</div>
            <div class="details-desc"><?php echo htmlspecialchars($product['description']); ?></div>
            <form method="post" action="add_to_cart.php" style="margin-top:18px; text-align:center;">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <button type="submit" style="padding:10px 22px; background:#333; color:#fff; border:none; border-radius:5px; font-size:1rem; font-weight:500; cursor:pointer;">Add to Cart</button>
            </form>
        <?php else: ?>
            <p>Product not found.</p>
        <?php endif; ?>
        <!-- <a href="shop.php" class="back-link">&larr; Back to Shop</a> -->
    </div>
</body>
</html>
