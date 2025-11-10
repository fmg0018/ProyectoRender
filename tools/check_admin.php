<?php
require __DIR__ . '/../vendor/autoload.php';
$pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$stmt = $pdo->prepare('SELECT id,name,email,role FROM users WHERE email = ?');
$stmt->execute(['admin@example.com']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($row, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
