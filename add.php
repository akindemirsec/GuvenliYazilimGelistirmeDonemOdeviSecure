<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'editor')) {
    header('Location: login.php');
    exit;
}

require 'sqlite.php';
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    $success = $db->query('INSERT INTO products (name, price, description, image) VALUES (:name, :price, :description, :image)', [
        'name' => $name,
        'price' => $price,
        'description' => $description,
        'image' => $image
    ]);

    if ($success) {
        if (isset($_POST['ajax'])) {
            echo json_encode(['status' => 'success']);
            exit;
        } else {
            header('Location: index.php');
            exit;
        }
    } else {
        if (isset($_POST['ajax'])) {
            echo json_encode(['status' => 'error', 'message' => 'Ürün eklenemedi']);
            exit;
        } else {
            $error = 'Ürün eklenemedi';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Ürün Ekle</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'add.php',
                    data: $(this).serialize() + '&ajax=true',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Ürün başarıyla eklendi');
                            window.location.href = 'index.php';
                        } else {
                            alert('Ürün eklenemedi: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="add-form">
            <h1>Yeni Ürün Ekle</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <form action="add.php" method="post">
                <label for="name">İsim:</label>
                <input type="text" name="name" id="name" required>
                <label for="price">Fiyat:</label>
                <input type="number" step="0.01" name="price" id="price" required>
                <label for="description">Açıklama:</label>
                <textarea name="description" id="description"></textarea>
                <label for="image">Resim URL'si:</label>
                <input type="text" name="image" id="image">
                <button type="submit">Ekle</button>
            </form>
            <a href="index.php" class="button">Geri Dön</a>
        </div>
    </div>
</body>
</html>
