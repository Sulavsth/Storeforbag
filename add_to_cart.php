<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bag_id = isset($_POST['bag_id']) ? (int)$_POST['bag_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$bag_id])) {
        $_SESSION['cart'][$bag_id] += $quantity;
    } else {
        $_SESSION['cart'][$bag_id] = $quantity;
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
