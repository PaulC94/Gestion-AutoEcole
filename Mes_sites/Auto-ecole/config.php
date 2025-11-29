<?php
$host = 'localhost';
$port = 3307;    // TON port MySQL MAMP
$db   = 'autoecole';
$user = 'root';
$pass = 'root';  // MAMP sur Mac = root/root par défaut

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}