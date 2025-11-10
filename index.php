<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "gaming_laptop");

// Check connection
if ($conn->connect_error) {
    die("Conn   ection failed: " . $conn->connect_error);
}

// Fetch 4 products from the database
$sql = "SELECT * FROM products LIMIT 4";
$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Build Your Gaming PC</title>
    <link rel="stylesheet" href="style.css"> <!-- Link your CSS file -->
</head>

<body>

    <!-- Navigation Bar -->
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

    <!-- Slider -->
    <div class="slider-container">
        <div class="slider">
            <img src="upload/shopping.webp" class="slide active" alt="Slide 1">
            <img src="upload/shopping (1).webp" class="slide" alt="Slide 2">
            <img src="upload/shopping (2).webp" class="slide" alt="Slide 3">
            <button class="prev" onclick="plusSlides(-1)">&#10094;</button>
            <button class="next" onclick="plusSlides(1)">&#10095;</button>
        </div>
    </div>

    <style>
        .slider-container { max-width: 900px; margin: 30px auto 0 auto; position: relative; }
        .slider { position: relative; overflow: hidden; border-radius: 10px; }
        .slider img { width: 100%; display: none; transition: opacity 0.5s; }
        .slider img.active { display: block; }
        .slider .prev, .slider .next {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(0,0,0,0.5); color: #fff; border: none; padding: 10px 16px;
            cursor: pointer; border-radius: 50%; font-size: 22px; z-index: 2;
        }
        .slider .prev { left: 10px; }
        .slider .next { right: 10px; }
    </style>

    <script>
        let slideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const showSlides = (n) => {
            slides.forEach((img, i) => {
                img.classList.remove('active');
                if (i === n) img.classList.add('active');
            });
        };
        function plusSlides(n) {
            slideIndex += n;
            if (slideIndex >= slides.length) slideIndex = 0;
            if (slideIndex < 0) slideIndex = slides.length - 1;
            showSlides(slideIndex);
        }
        // Auto slide
        setInterval(() => { plusSlides(1); }, 4000);
        // Init
        document.addEventListener('DOMContentLoaded', () => { showSlides(slideIndex); });
    </script>

    <!-- Main Content -->
    <div class="container">
        <h2>Top Selling Products</h2>
        <div class="products">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product" onclick="window.location.href='shop.php'" style="cursor:pointer;">
                        <img src="upload/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" width="300">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p class="price">M.R.P : ₹<?php echo number_format($row['price']); ?>/-</p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
        <a href="shop.php" class="view-all">View All Products</a>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h4>Exclusive</h4>
                <ul>
                    <li>Subscribe</li>
                    <li>Get 10% off your first order</li>
                    <li>
                        <div class="subscribe">
                            <input type="email" placeholder="Enter your email">
                            <button  type="submit">Send</button>    
                        </div>
                    </li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Support</h4>
                <ul>
                    <li>2nd Floor, near Indira circle, Rajkot, Gujarat</li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Account</h4>
                <ul>
                    <!-- <li><a href="#">My Account</a></li> -->
                    <li><a href="login.php">Login / Register</a></li>
                    <li><a href="#">Cart</a></li>
                    <!-- <li><a href="#">Favourite</a></li> -->
                    <li><a href="shop.php">Shop</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Quick Link</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms Of Use</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f">f</i></a>
                    <a href="#"><i class="fab fa-instagram">in</i></a>
                    <a href=""><i class="fab fa-twitter">t</i></a>
                    <a href="#"><i class="fab fa-youtube">y</i></a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>

<?php
$conn->close();
?>