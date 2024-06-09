<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'editor')) {
    header('Location: login.php');
    exit;
}

require 'sqlite.php';
$db = new Database();

$id = $_GET['id'];
$product = $db->fetch('SELECT * FROM products WHERE id = :id', ['id' => $id]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $listed = isset($_POST['listed']) ? 1 : 0;

    $db->query('UPDATE products SET name = :name, price = :price, description = :description, image = :image, listed = :listed WHERE id = :id', [
        'name' => $name,
        'price' => $price,
        'description' => $description,
        'image' => $image,
        'listed' => $listed,
        'id' => $id
    ]);

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürünü Düzenle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Ürünü Düzenle</h1>
        <form action="" method="post">
            <label for="name">İsim:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            <label for="price">Fiyat:</label>
            <input type="number" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?>" required>
            <label for="description">Açıklama:</label>
            <textarea name="description" id="description"><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            <label for="image">Resim URL'si:</label>
            <input type="text" name="image" id="image" value="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>">
            <label for="listed">Listele:</label>
            <input type="checkbox" name="listed" id="listed" <?php echo $product['listed'] ? 'checked' : ''; ?>>
            <button type="submit">Kaydet</button>
        </form>
        <a href="index.php" class="button">Geri Dön</a>
    </div>
</body>
</html>
