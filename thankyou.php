<?php
session_start();
$name = isset($_SESSION['order_name']) ? htmlspecialchars($_SESSION['order_name']) : '';
$address = isset($_SESSION['order_address']) ? htmlspecialchars($_SESSION['order_address']) : '';
// Optionally clear session data after displaying
unset($_SESSION['order_name'], $_SESSION['order_address']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You for Your Order</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .thankyou-container { max-width: 500px; margin: 80px auto; background: #fff; border-radius: 10px; box-shadow: 0 5px 15px #0001; padding: 40px; text-align: center; }
        .thankyou-title { font-size: 2rem; font-weight: 700; color: #28a745; margin-bottom: 24px; }
        .thankyou-details { font-size: 1.1rem; color: #333; margin-bottom: 32px; }
        .back-btn { background: #007bff; color: #fff; border: none; padding: 10px 28px; border-radius: 5px; cursor: pointer; font-weight: 600; font-size: 1.1em; transition: background 0.2s; }
        .back-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="thankyou-container">
        <div class="thankyou-title">Thank You for Your Order!</div>
        <div class="thankyou-details">
            <strong>Name:</strong> <?php echo $name; ?><br>
            <strong>Address:</strong> <?php echo nl2br($address); ?>
        </div>
        <form action="index.php" method="get">
            <button type="submit" class="back-btn">Back to Home</button>
        </form>
    </div>
</body>
</html>
