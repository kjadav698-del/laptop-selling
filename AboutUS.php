<?php
// about.php
$pageTitle = "About Us - SmartLaptops";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        .header {
            background-color: #1a1a1a;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .back-button {
            position: absolute;
            left: 20px;
            top: 20px;
            background-color: white;
            color: #1a1a1a;
            border: none;
            padding: 8px 14px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .back-button:hover {
            background-color: #ddd;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 20px;
        }
        h2 {
            margin-top: 0;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        @media (max-width: 768px) {
            table, tbody, tr, td {
                display: block;
                width: 100%;
            }
            .back-button {
                position: static;
                margin: 10px auto;
                display: block;
            }
            td {
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar (same as other pages) -->
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

    <!-- Page Header -->
    <!-- <div class="header">
        <button class="back-button" onclick="history.back()">← Back</button>
        <h1><?php echo $pageTitle; ?></h1>
    </div> -->

    <!-- About Content -->
    <div class="container">
        <table>
            <tr>
                <td>
                    <h2>Who We Are</h2>
                    <p>At <strong>SmartLaptops</strong>, we specialize in offering top-tier laptops for professionals, gamers, and everyday users. With a focus on performance, reliability, and modern design, we ensure every product meets the highest standards.</p>
                    <p>Founded in 2020, our mission is to help customers find the perfect laptop for their needs — whether it's for work, play, or creative projects. Our expert team is passionate about technology and ready to assist you.</p>
                </td>
                <td>
                    <img src="https://www.shutterstock.com/image-photo/using-laptop-show-icon-address-600nw-2521386695.jpg" alt="Smart laptop display">
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
