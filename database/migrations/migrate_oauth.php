<?php
/**
 * Migración SQL rápida - Agregar columnas OAuth a tabla users
 * Ejecutar una sola vez
 */

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../src/Core/Database.php';

use Factapex\Core\Database;

try {
    echo "🚀 Ejecutando migración OAuth...\n\n";
    
    $db = Database::getInstance()->getConnection();
    
    // Verificar si las columnas ya existen
    $stmt = $db->query("SHOW COLUMNS FROM users LIKE 'provider'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Las columnas OAuth ya existen\n";
        exit(0);
    }
    
    echo "📝 Agregando columnas OAuth...\n";
    
    // Agregar columnas
    $db->exec("ALTER TABLE `users` 
        ADD COLUMN `provider` ENUM('local', 'google') DEFAULT 'local' AFTER `role`,
        ADD COLUMN `provider_id` VARCHAR(255) NULL AFTER `provider`,
        ADD COLUMN `estado` ENUM('activo', 'pendiente', 'bloqueado') DEFAULT 'activo' AFTER `provider_id`,
        ADD COLUMN `avatar` VARCHAR(500) NULL AFTER `estado`,
        ADD COLUMN `email_verified` TINYINT(1) DEFAULT 0 AFTER `avatar`
    ");
    
    echo "✓ Columnas agregadas\n\n";
    
    // Modificar password para que sea nullable
    $db->exec("ALTER TABLE `users` MODIFY COLUMN `password` VARCHAR(255) NULL");
    echo "✓ Password nullable\n\n";
    
    // Agregar índices
    $db->exec("ALTER TABLE `users` ADD UNIQUE INDEX `idx_provider_id` (`provider`, `provider_id`)");
    echo "✓ Índices agregados\n\n";
    
    // Actualizar usuarios existentes
    $db->exec("UPDATE `users` SET `provider` = 'local' WHERE `provider` IS NULL");
    $db->exec("UPDATE `users` SET `estado` = 'activo' WHERE `estado` IS NULL");
    echo "✓ Usuarios existentes actualizados\n\n";
    
    // Verificar
    $stmt = $db->query("SHOW COLUMNS FROM users");
    echo "📊 Columnas de la tabla users:\n";
    while ($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$col['Field']} ({$col['Type']})\n";
    }
    
    echo "\n✅ Migración completada exitosamente!\n";
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
