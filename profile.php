<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require 'sqlite.php';
$db = new Database();

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
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

    // Yüklenen dosyanın boyutunu kontrol et
    $image_info = getimagesize($_FILES["profile_image"]["tmp_name"]);
    $image_width = $image_info[0];
    $image_height = $image_info[1];
    
    if ($image_width != 300 || $image_height != 300) {
        // Eğer boyut 300x300 değilse, boyutunu ayarla
        $resized_image = imagecreatetruecolor(300, 300);
        $source_image = imagecreatefromjpeg($_FILES["profile_image"]["tmp_name"]); // veya diğer dosya formatları için uygun fonksiyonu kullanın
        imagecopyresized($resized_image, $source_image, 0, 0, 0, 0, 300, 300, $image_width, $image_height);
        
        // Profil fotoğrafını kaydet
        imagejpeg($resized_image, $target_file);
        
        // Bellekten temizle
        imagedestroy($resized_image);
        imagedestroy($source_image);
    } else {
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);
    }

    // Profil fotoğrafını güncelle
    $db->query("UPDATE users SET profile_image = :profile_image WHERE id = :id", [
        'profile_image' => $target_file,
        'id' => $user['id']
    ]);

    // Kullanıcı oturumunu güncelle
    $_SESSION['user']['profile_image'] = $target_file;
    header('Location: profile.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="profile">
            <img src="<?php echo htmlspecialchars($user['profile_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Profil Fotoğrafı" style="width: 300px; height: 300px;">
            <h1><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <form action="profile.php" method="post" enctype="multipart/form-data">
                <label for="profile_image">Profil Fotoğrafını Değiştir (300x300):</label>
                <input type="file" name="profile_image" id="profile_image" required>
                <button type="submit">Yükle</button>
            </form>
        </div>
        <nav>
            <a href="index.php" class="button">Ana Sayfaya Dön</a>
            <a href="logout.php" class="button">Çıkış Yap</a>
        </nav>
    </div>
</body>
</html>
