<?php
$path = __DIR__ . '/../database/database.sqlite';
if (!file_exists($path)) {
    echo "No existe $path\n";
    exit(1);
}
$pdo = new PDO('sqlite:' . $path);
$stmt = $pdo->query("PRAGMA table_info('users')");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
