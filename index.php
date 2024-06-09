<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require 'sqlite.php';
$db = new Database();

// Ürünleri veritabanından al
$products = $db->fetchAll('SELECT * FROM products WHERE listed = 1');

$user = $_SESSION['user'];

// Sepete eklendiğine dair mesajı kontrol et
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
unset($_SESSION['message']); // Mesajı temizle
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ana Sayfa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="top-bar">
                <a href="index.php" class="logo">Trendyol</a>
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Ürün ara...">
                    <button type="submit">Ara</button>
                </form>
                <nav>
                    <a href="profile.php">Profil</a>
                    <a href="cart.php">Sepetim</a>
                    <?php if ($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'editor'): ?>
                        <a href="add.php">Yeni Ürün Ekle</a>
                        <a href="all_products.php">Tüm Ürünler</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>
        <main>
            <h1>Ürünler</h1>
            <?php if ($message): ?>
                <p class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <div class="products">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Ürün Resmi">
                        <h2><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p><?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?> TL</p>
                        <p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <form action="add_to_cart.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit">Sepete Ekle</button>
                        </form>
                        <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                            <form action="delete.php" method="post" class="delete-form">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" onclick="return confirm('Bu ürünü silmek istediğinizden emin misiniz?');">Sil</button>
                            </form>
                            <a href="edit.php?id=<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>" class="button">Düzenle</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
