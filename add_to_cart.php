<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user_id'];

    // Check if product already in cart
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Update quantity
        $cart_id = $row['id'];
        $quantity = $row['quantity'] + 1;
        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $quantity, $cart_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Insert new cart item
        $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $insert_stmt->bind_param("ii", $user_id, $product_id);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    $stmt->close();
    // Redirect to cart with success message
    header('Location: cart.php?added=1');
    exit();
} else {
    header('Location: shop.php');
    exit();
}
?>