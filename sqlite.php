<?php

class Database {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('sqlite:database.db');
        $this->initialize();
    }

    private function initialize() {
        // SQLite veritabanını oluştur
        $this->createDatabase();
    
        // Admin kullanıcısını ekle
        $this->addUser('admin', 'admin123', 'admin');
    
        // Editor kullanıcısını ekle
        $this->addUser('editor', 'editor123', 'editor');
    }
    
    private function createDatabase() {
        // Kullanıcılar tablosunu oluştur
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            profile_image TEXT,
            role TEXT NOT NULL DEFAULT 'user'
        )");
    
        // Ürünler tablosunu oluştur (eğer yoksa)
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            price REAL NOT NULL,
            description TEXT,
            image TEXT,
            listed INTEGER NOT NULL DEFAULT 1
        )");
    }
    
    private function addUser($username, $password, $role) {
        // Kullanıcıyı veritabanına ekleme
        $userExists = $this->fetch('SELECT * FROM users WHERE username = :username', ['username' => $username]);
        if (!$userExists) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Şifreyi hashle
            $this->query('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)', [
                'username' => $username,
                'password' => $hashed_password,
                'role' => $role
            ]);
        }
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function query($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }

    public function fetch($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
