<?php
session_start();
if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}
