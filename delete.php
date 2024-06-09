<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin' || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: login.php');
    exit;
}

require 'sqlite.php';
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $db->query('DELETE FROM products WHERE id = :id', ['id' => $product_id]);
    $_SESSION['message'] = 'Ürün başarıyla silindi';
    header('Location: index.php');
    exit;
} else {
    header('Location: index.php');
    exit;
}
