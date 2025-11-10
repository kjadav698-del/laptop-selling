<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .mission-container { max-width: 800px; margin: 60px auto; padding: 40px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; }
        .mission-container h2 { text-align: center; margin-bottom: 20px; }
        .mission-container p { font-size: 1.2em; line-height: 1.7; text-align: center; }
    </style>
</head>
<body>
    <?php session_start(); ?>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">Gaming<span>PC</span></a>
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
    <div class="mission-container">
        <h2>Our Mission</h2>
        <p>
            Our mission is to empower gamers and creators by providing the best selection of high-performance gaming laptops and accessories. We strive to deliver top-quality products, expert advice, and exceptional customer service to help you build your dream gaming setup. Whether you're a casual gamer or a professional, we are dedicated to making cutting-edge technology accessible and affordable for everyone.
        </p>
    </div>
</body>
</html>
