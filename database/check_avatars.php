<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../src/Core/Database.php';

use Factapex\Core\Database;

$db = Database::getInstance()->getConnection();
$stmt = $db->query('SELECT id, name, email, avatar, provider, provider_id FROM users ORDER BY id DESC LIMIT 5');

echo "=== ÚLTIMOS USUARIOS ===\n\n";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']}\n";
    echo "Nombre: {$row['name']}\n";
    echo "Email: {$row['email']}\n";
    echo "Provider: {$row['provider']}\n";
    echo "Provider ID: " . ($row['provider_id'] ?? 'NULL') . "\n";
    echo "Avatar: " . ($row['avatar'] ?? 'NULL') . "\n";
    echo "-------------------\n\n";
}
