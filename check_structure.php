<?php
require 'vendor/autoload.php';

$pdo = new PDO('mysql:host=127.0.0.1;dbname=db_buku', 'root', '');

echo "=== TABLE STRUCTURE ===\n";

echo "Anggota table:\n";
$stmt = $pdo->query('DESCRIBE anggota');
while($row = $stmt->fetch()) {
    echo "- {$row['Field']} ({$row['Type']})\n";
}

echo "\nUsers table:\n";
$stmt = $pdo->query('DESCRIBE users');
while($row = $stmt->fetch()) {
    echo "- {$row['Field']} ({$row['Type']})\n";
}

echo "\n=== SAMPLE DATA ===\n";
echo "Anggota:\n";
$stmt = $pdo->query('SELECT * FROM anggota LIMIT 3');
while($row = $stmt->fetch()) {
    echo "- ID: {$row['id']}, Nama: {$row['nama']}, NIS: {$row['nis']}\n";
}

echo "\nUsers (siswa only):\n";
$stmt = $pdo->query('SELECT * FROM users WHERE role = "siswa" LIMIT 3');
while($row = $stmt->fetch()) {
    echo "- ID: {$row['id']}, Name: {$row['name']}, Username: {$row['username']}\n";
}