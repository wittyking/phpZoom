<?php
$host     = getenv('DB_HOST') ?: 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$port     = getenv('DB_PORT') ?: '5432';
$dbname   = getenv('DB_NAME') ?: 'xxxxxxxxxxxx';
$username = getenv('DB_USER') ?: 'xxxxxxxxxxxx';
$password = getenv('DB_PASSWORD') ?: 'xxxxxxxxxxxxx';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage());
}
?>
