<?php
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="top-bar">
                <a href="index.php" class="logo">Butik</a>
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Ürün ara...">
                    <button type="submit">Ara</button>
                </form>
                <nav>
                    <a href="profile.php">Profil</a>
                    <a href="cart.php">Sepetim</a>
                    <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'editor')): ?>
                        <a href="add.php">Yeni Ürün Ekle</a>
                        <a href="all_products.php">Tüm Ürünler</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>
