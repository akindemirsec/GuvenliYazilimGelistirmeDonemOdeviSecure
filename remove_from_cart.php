<?php
session_start();

if (!isset($_SESSION['user']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: login.php');
    exit;
}

// Ürünün id'sini al
$product_id = $_POST['product_id'];

// Sepetten çıkarma işlemini gerçekleştir
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Ana sayfaya yönlendir
header('Location: cart.php');
exit;
?>
