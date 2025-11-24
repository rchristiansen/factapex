<?php
/**
 * Script de seeding inicial
 * Crea usuarios de prueba para cada rol con password_hash()
 * 
 * IMPORTANTE: Ejecutar solo una vez en la base de datos limpia
 * 
 * Contraseña para todos: "password"
 * Hash generado con: password_hash('password', PASSWORD_DEFAULT)
 * 
 * SIN COMPOSER
 */

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../src/Core/Database.php';

use Factapex\Core\Database;

try {
    echo "🚀 Iniciando seeding de usuarios...\n\n";
    
    $db = Database::getInstance()->getConnection();
    
    // Generar hash para la contraseña "password"
    $passwordHash = password_hash('password', PASSWORD_DEFAULT);
    echo "✓ Password hash generado\n";
    
    // Usuarios a crear
    $usuarios = [
        [
            'name' => 'Administrador Principal',
            'email' => 'admin@factapex.com',
            'password' => $passwordHash,
            'role' => 'admin',
            'provider' => 'local',
            'estado' => 'activo',
            'email_verified' => 1
        ],
        [
            'name' => 'Ejecutivo de Ventas',
            'email' => 'ejecutivo@factapex.com',
            'password' => $passwordHash,
            'role' => 'ejecutivo',
            'provider' => 'local',
            'estado' => 'activo',
            'email_verified' => 1
        ],
        [
            'name' => 'Cliente Demo',
            'email' => 'cliente@factapex.com',
            'password' => $passwordHash,
            'role' => 'cliente',
            'provider' => 'local',
            'estado' => 'activo',
            'email_verified' => 1
        ],
        [
            'name' => 'Cliente Google Test',
            'email' => 'cliente.google@factapex.com',
            'password' => null, // Sin password porque es OAuth
            'role' => 'cliente',
            'provider' => 'google',
            'provider_id' => 'google_test_id_12345',
            'estado' => 'pendiente',
            'avatar' => 'https://via.placeholder.com/150',
            'email_verified' => 1
        ]
    ];
    
    echo "\n📝 Insertando usuarios...\n";
    
    $stmt = $db->prepare("
        INSERT INTO users (
            name, email, password, role, provider, provider_id, 
            estado, avatar, email_verified, created_at
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
            name = VALUES(name),
            role = VALUES(role),
            provider = VALUES(provider),
            estado = VALUES(estado),
            updated_at = NOW()
    ");
    
    foreach ($usuarios as $usuario) {
        $stmt->execute([
            $usuario['name'],
            $usuario['email'],
            $usuario['password'],
            $usuario['role'],
            $usuario['provider'],
            $usuario['provider_id'] ?? null,
            $usuario['estado'],
            $usuario['avatar'] ?? null,
            $usuario['email_verified']
        ]);
        
        echo "  ✓ {$usuario['name']} ({$usuario['email']}) - {$usuario['role']}\n";
    }
    
    echo "\n✅ Seeding completado exitosamente!\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📋 CREDENCIALES DE PRUEBA\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "🔐 Admin:\n";
    echo "   Email: admin@factapex.com\n";
    echo "   Password: password\n\n";
    echo "🔐 Ejecutivo:\n";
    echo "   Email: ejecutivo@factapex.com\n";
    echo "   Password: password\n\n";
    echo "🔐 Cliente (local):\n";
    echo "   Email: cliente@factapex.com\n";
    echo "   Password: password\n\n";
    echo "🔐 Cliente (Google OAuth):\n";
    echo "   Email: cliente.google@factapex.com\n";
    echo "   Provider: Google\n";
    echo "   Estado: Pendiente\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Mostrar usuarios creados
    $stmt = $db->query("SELECT id, name, email, role, provider, estado FROM users ORDER BY role, id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\n📊 Usuarios en base de datos:\n";
    echo str_pad("ID", 5) . str_pad("NOMBRE", 30) . str_pad("EMAIL", 35) . str_pad("ROL", 15) . str_pad("PROVIDER", 12) . "ESTADO\n";
    echo str_repeat("─", 110) . "\n";
    
    foreach ($users as $user) {
        echo str_pad($user['id'], 5) 
            . str_pad($user['name'], 30) 
            . str_pad($user['email'], 35) 
            . str_pad($user['role'], 15) 
            . str_pad($user['provider'], 12) 
            . $user['estado'] . "\n";
    }
    
    echo "\n✨ ¡Todo listo para comenzar!\n\n";
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
