<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require 'sqlite.php';
$db = new Database();

// Arama terimini al
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Veritabanında listelenen ürünleri adlarına göre ara
$products = $db->fetchAll("SELECT * FROM products WHERE name LIKE :search_query AND listed = 1", ['search_query' => "%$search_query%"]);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Arama Sonuçları</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Arama Sonuçları</h1>
        <p>Aradığınız kelime: <?php echo htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="products">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Ürün Resmi">
                        <h2><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p><?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?> TL</p>
                        <p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Ürün bulunamadı.</p>
            <?php endif; ?>
        </div>
        <a href="index.php" class="button">Ana Sayfaya Dön</a>
    </div>
</body>
</html>
