<?php
session_start();
if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $id = (int)$_POST['id'];
    $quantity = (int)$_POST['quantity'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = $quantity;
    }
}
