<?php
/**
 * Agregar columna last_login a la tabla users
 */

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../src/Core/Database.php';

use Factapex\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    // Agregar columna last_login
    $sql = "ALTER TABLE users ADD COLUMN last_login DATETIME NULL AFTER email_verified";
    
    $db->exec($sql);
    
    echo "✅ Columna last_login agregada exitosamente\n";
    
    // Verificar columnas
    echo "\n📋 Columnas actuales de users:\n";
    $result = $db->query("SHOW COLUMNS FROM users");
    while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
        echo "  - {$row['Field']} ({$row['Type']})\n";
    }
    
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false || strpos($e->getMessage(), '1060') !== false) {
        echo "✓ La columna last_login ya existe\n";
        
        // Mostrar columnas actuales
        echo "\n📋 Columnas actuales de users:\n";
        $db = Database::getInstance()->getConnection();
        $result = $db->query("SHOW COLUMNS FROM users");
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            echo "  - {$row['Field']} ({$row['Type']})\n";
        }
    } else {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}
