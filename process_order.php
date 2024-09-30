<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}
function create_order($user_id, $total, $shipping) {
    global $conn;
    $sql = "INSERT INTO orders (user_id, total, shipping, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idd", $user_id, $total, $shipping);
    return $stmt->execute() ? $conn->insert_id : false;
}
function add_order_items($order_id, $cart_items) {
    global $conn;
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    foreach ($cart_items as $item) {
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
}

// Validate and sanitize input
$customer_data = [
    'name' => sanitize_input($_POST['name']),
    'email' => sanitize_input($_POST['email']),
    'address' => sanitize_input($_POST['address']),
    'city' => sanitize_input($_POST['city']),
    'zip' => sanitize_input($_POST['zip'])
];

// Ensure cart is not empty
if (empty($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Your cart is empty']);
    exit;
}

try {
    $order_id = process_order($_SESSION['cart'], $customer_data, $_POST['payment_method'], $_POST['payment_evidence']);
    
    if ($order_id) {
        // Clear the cart after successful order
        unset($_SESSION['cart']);
        echo json_encode(['success' => true, 'message' => 'Order placed successfully', 'order_id' => $order_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to process order']);
    }
} catch (Exception $e) {
    error_log("Order processing error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while processing your order']);
}    echo json_encode(['success' => false, 'message' => 'An error occurred while processing your order']);
