<style>
    .shop-btn {
        padding: 10px 22px;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.2s;
        font-weight: 500;
        margin: 0 2px;
        box-shadow: 0 2px 8px #0001;
    }
    .shop-btn.cart {
        background:#333;
        color: #fff;
    }
    
    .shop-btn.details {
        background:#333;
        color: #fff;
    }
  
</style>
<?php
// shop.php
$conn = new mysqli("localhost", "root", "", "gaming_laptop");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Planet Infoworld</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background-color: #fff;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 20px;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .product {
            border: 1px solid #ddd;
            padding: 15px;
            width: calc(25% - 20px);
            box-sizing: border-box;
            border-radius: 8px;
            transition: box-shadow 0.3s;
            background-color: #fafafa;
            text-align: center;
        }
        .product:hover {
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        .product img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .product h3 {
            font-size: 18px;
            margin: 10px 0 5px;
        }
        .price {
            font-weight: bold;
            color: #555;
        }
        @media (max-width: 768px) {
            .product {
                width: 100%;
            }
            .product img {
                height: 160px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar (copied like Mission page) -->
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

    <!-- Shop Section -->
    <div class="container">
        <div class="product-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product">
                        <img src="upload/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p class="price">₹<?php echo number_format($row['price']); ?>/-</p>
                            <div style="margin-top:12px; display:flex; gap:10px; justify-content:center;">
                                <form method="post" action="add_to_cart.php" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="shop-btn cart">AddCart</button>
                                </form>
                                <a href="viewdetails.php?id=<?= $row['id'] ?>" class="shop-btn details">ViewDetails</a>
                            </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
