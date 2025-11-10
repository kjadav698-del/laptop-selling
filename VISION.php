<?php
// about.php
$pageTitle = "ABOUT US - Planet Infoworld";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", sans-serif;
            background-color: #fff;
            color: #333;
        }
        .vision-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 40px;
        }
        .vision-text {
            flex: 1;
            min-width: 300px;
        }
        .vision-text h2 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .vision-text p {
            font-size: 16px;
            line-height: 1.7;
            color: #555;
        }
        .vision-image {
            flex: 1;
            min-width: 300px;
        }
        .vision-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        @media (max-width: 768px) {
            .vision-section {
                flex-direction: column;
                margin-top: 30px;
            }
            .vision-text h2 {
                text-align: center;
            }
            .vision-text p {
                text-align: justify;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar (same as Mission/Shop pages) -->
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

    <!-- Vision Section -->
    <div class="vision-section">
        <div class="vision-text">
            <h2>Who We Are</h2>
            <p>
                Our vision is to create an online platform that provides seamless access to the latest technology, 
                expert advice, and personalized solutions for all your computing needs.  
                We aim to offer a user-friendly and informative experience that empowers customers to easily find, 
                purchase, and support the products that enhance their digital lives.  
                Through our website, we envision connecting with customers globally, providing not just products 
                but a community built on trust, innovation, and exceptional service.
            </p>
        </div>
        <div class="vision-image">
            <img src="https://static.vecteezy.com/system/resources/previews/007/692/124/non_2x/people-concept-illustration-of-our-team-management-about-us-for-graphic-and-web-design-business-presentation-and-marketing-material-vector.jpg" alt="About Us Illustration">
        </div>
    </div>
</body>
</html>
