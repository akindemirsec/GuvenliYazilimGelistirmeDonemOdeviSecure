<?php
session_start();

if (!isset($_SESSION['user']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coupon_code = $_POST['coupon_code'];

    // Kupon kodunu kontrol et ve indirim yap
    if ($coupon_code === 'NEWUSER25') {
        if (!isset($_SESSION['applied_coupons'])) {
            $_SESSION['applied_coupons'] = [];
        }
        
        // Kupon daha önce uygulanmadıysa, fiyata indirimi uygula
        if (!in_array($coupon_code, $_SESSION['applied_coupons'])) {
            $discount = 25;
            $_SESSION['applied_coupons'][] = $coupon_code; // Kupon kodunu kaydet
            $_SESSION['discount'] = $discount; // İndirim miktarını kaydet
        }
    }
}

header('Location: cart.php');
exit;