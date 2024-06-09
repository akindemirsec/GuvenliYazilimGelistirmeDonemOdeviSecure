<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    require 'sqlite.php';
    $db = new Database();

    // Kullanıcıyı veritabanından al
    $user = $db->fetch("SELECT * FROM users WHERE username = :username", ['username' => $username]);

    if ($user && password_verify($password, $user['password'])) { // Şifreyi doğrula
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Kullanıcı adı veya şifre yanlış.';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Giriş Yap</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <form action="login.php" method="post">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" name="username" id="username" required>
                <label for="password">Şifre:</label>
                <input type="password" name="password" id="password" required>
                <button type="submit">Giriş Yap</button>
            </form>
            <a href="register.php" class="button">Kayıt Ol</a>
        </div>
    </div>
</body>
</html>
