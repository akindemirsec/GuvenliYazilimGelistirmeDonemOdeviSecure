<?php
session_start();
require 'sqlite.php';
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Şifreyi hashleyin
    $profile_image = '';

    // Profil fotoğrafı yükleme işlemi
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

        // Yüklenen dosyanın türünü ve boyutunu kontrol et
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['profile_image']['type'], $allowed_types) || $_FILES['profile_image']['size'] > 500000) {
            die('Geçersiz dosya türü veya boyutu');
        }

        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);
        $profile_image = $target_file;
    }

    // Kullanıcıyı veritabanına ekleme
    $success = $db->query('INSERT INTO users (username, password, profile_image) VALUES (:username, :password, :profile_image)', [
        'username' => $username,
        'password' => $password, // Şifreyi hashlenmiş olarak kaydet
        'profile_image' => $profile_image
    ]);

    if ($success) {
        header('Location: login.php');
        exit;
    } else {
        $error = 'Kayıt başarısız oldu';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="register-form">
            <h1>Kayıt Ol</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <form action="register.php" method="post" enctype="multipart/form-data">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" name="username" id="username" required>
                <label for="password">Şifre:</label>
                <input type="password" name="password" id="password" required>
                <label for="profile_image">Profil Fotoğrafı:</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*">
                <button type="submit">Kayıt Ol</button>
            </form>
            <a href="login.php" class="button">Giriş Yap</a>
        </div>
    </div>
</body>
</html>
