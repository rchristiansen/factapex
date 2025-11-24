<?php
/**
 * Script de ejemplo para crear un usuario ejecutivo
 * Úsalo desde la línea de comandos o inclúyelo en tu código
 */

require_once __DIR__ . '/../config/app.php';

use Factapex\Models\User;

// Función helper para crear ejecutivo
function crearEjecutivo($nombre, $email, $password) {
    $userModel = new User();
    
    // Verificar si el email ya existe
    if ($userModel->findByEmail($email)) {
        return [
            'success' => false,
            'message' => 'El email ya está registrado'
        ];
    }
    
    // Validar password
    if (strlen($password) < 6) {
        return [
            'success' => false,
            'message' => 'La contraseña debe tener al menos 6 caracteres'
        ];
    }
    
    // Crear el ejecutivo
    $userId = $userModel->create([
        'name' => $nombre,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'ejecutivo'
    ]);
    
    if ($userId) {
        return [
            'success' => true,
            'message' => 'Ejecutivo creado exitosamente',
            'user_id' => $userId
        ];
    }
    
    return [
        'success' => false,
        'message' => 'Error al crear el ejecutivo'
    ];
}

// EJEMPLOS DE USO:

// Ejemplo 1: Crear un ejecutivo
$resultado = crearEjecutivo(
    'Carlos Méndez', 
    'carlos@factapex.com', 
    'mipassword123'
);

if ($resultado['success']) {
    echo "✓ Ejecutivo creado con ID: {$resultado['user_id']}\n";
    echo "  Email: carlos@factapex.com\n";
    echo "  Password: mipassword123\n";
} else {
    echo "✗ Error: {$resultado['message']}\n";
}

// Ejemplo 2: Crear múltiples ejecutivos
$ejecutivos = [
    ['nombre' => 'Ana López', 'email' => 'ana@factapex.com', 'password' => 'password123'],
    ['nombre' => 'Roberto Silva', 'email' => 'roberto@factapex.com', 'password' => 'password123'],
    ['nombre' => 'Laura Torres', 'email' => 'laura@factapex.com', 'password' => 'password123'],
];

echo "\n--- Creando múltiples ejecutivos ---\n";
foreach ($ejecutivos as $ej) {
    $resultado = crearEjecutivo($ej['nombre'], $ej['email'], $ej['password']);
    if ($resultado['success']) {
        echo "✓ {$ej['nombre']} creado\n";
    } else {
        echo "✗ {$ej['nombre']}: {$resultado['message']}\n";
    }
}

// Ejemplo 3: Listar todos los ejecutivos
echo "\n--- Listado de ejecutivos ---\n";
$userModel = new User();
$ejecutivos = $userModel->findByRole('ejecutivo');

if (empty($ejecutivos)) {
    echo "No hay ejecutivos registrados\n";
} else {
    foreach ($ejecutivos as $ej) {
        echo "ID: {$ej['id']} | {$ej['name']} ({$ej['email']}) | Creado: {$ej['created_at']}\n";
    }
}
