<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .contact-container { max-width: 600px; margin: 60px auto; padding: 40px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; }
        .contact-container h2 { text-align: center; margin-bottom: 20px; }
        .contact-container form { display: flex; flex-direction: column; gap: 15px; }
        .contact-container input, .contact-container textarea { padding: 10px; border-radius: 4px; border: 1px solid #ccc; }
        .contact-container button { padding: 10px; background: #222; color: #fff; border: none; border-radius: 4px; }
        .contact-info { text-align: center; margin-top: 30px; color: #555; }
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
    <div class="contact-container">
        <h2>Contact Us</h2>
        <form method="post" action="">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
        <div class="contact-info">
            <p><strong>Address:</strong> 2nd Floor, near Indira circle, Rajkot, Gujarat</p>
            <p><strong>Email:</strong> support@gamingpc.com</p>
            <p><strong>Phone:</strong> +91 12345 67890</p>
        </div>
    </div>
</body>
</html>
