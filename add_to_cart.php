<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: login.php');
    exit;
}

// Ürünün id'sini al
$product_id = $_POST['product_id'];

// Ürün bilgisini veritabanından al
require 'sqlite.php';
$db = new Database();
$product = $db->fetch('SELECT * FROM products WHERE id = :id', ['id' => $product_id]);

// Ürünü sepete ekle
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = $product;

// Sepete eklendiğine dair bir mesaj göster
$_SESSION['message'] = 'Ürün sepete eklendi.';
header('Location: index.php');
exit;
?>
