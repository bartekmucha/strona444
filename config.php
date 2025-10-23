<?php
require_once 'session_handler.php';

$host = '10.10.10.X'; // IP serwera MariaDB
$db   = 'sesje';
$user = 'admin';
$pass = '1234';

// Połączenie PDO do bazy (do zapytań)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Błąd połączenia z bazą: " . $e->getMessage());
}

// Obsługa sesji w SQL
$handler = new MySQLSessionHandler($host, $db, $user, $pass);
session_set_save_handler($handler, true);
session_start();
?>
