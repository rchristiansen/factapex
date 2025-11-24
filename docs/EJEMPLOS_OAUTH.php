/*
 * Ejemplo de uso de Google OAuth en diferentes escenarios
 * 
 * IMPORTANTE: Estos son ejemplos de código. Copia y adapta según tu necesidad.
 */

// ============================================
// EJEMPLO 1: Botón de Login con Google
// ============================================

// En tu vista de login (HTML/PHP):
?>
<a 
    href="/factapex/public/auth/google/login" 
    class="btn-google"
>
    <img src="google-icon.png" alt="Google">
    Continuar con Google
</a>
<?php

// ============================================
// EJEMPLO 2: Verificar si usuario puede usar OAuth
// ============================================

use Factapex\Services\GoogleOAuthService;
use Factapex\Models\User;

$userModel = new User();
$user = $userModel->findByEmail('admin@factapex.com');

if (GoogleOAuthService::canUseGoogleOAuth($user)) {
    echo "Este usuario puede usar Google OAuth";
} else {
    echo "Este usuario solo puede usar login tradicional";
}

// ============================================
// EJEMPLO 3: Obtener información del usuario actual
// ============================================

session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userName = $_SESSION['user_name'];
    $userRole = $_SESSION['user_role'];
    $userProvider = $_SESSION['user_provider'] ?? 'local';
    
    echo "Usuario: $userName\n";
    echo "Rol: $userRole\n";
    echo "Provider: $userProvider\n";
    
    if ($userProvider === 'google') {
        $avatar = $_SESSION['user_avatar'] ?? null;
        if ($avatar) {
            echo "<img src='$avatar' alt='Avatar'>";
        }
    }
}

// ============================================
// EJEMPLO 4: Crear usuario manualmente con Google
// ============================================

$userModel = new User();

$userId = $userModel->create([
    'name' => 'Juan Pérez',
    'email' => 'juan@gmail.com',
    'password' => null, // Sin password para OAuth
    'role' => 'cliente',
    'provider' => 'google',
    'provider_id' => '1234567890',
    'estado' => 'pendiente',
    'avatar' => 'https://lh3.googleusercontent.com/...',
    'email_verified' => 1
]);

echo "Usuario creado con ID: $userId";

// ============================================
// EJEMPLO 5: Vincular cuenta local con Google
// ============================================

$userModel = new User();
$user = $userModel->findByEmail('cliente@factapex.com');

if ($user && $user['provider'] === 'local') {
    $userModel->update($user['id'], [
        'provider' => 'google',
        'provider_id' => '1234567890',
        'avatar' => 'https://...',
        'email_verified' => 1
    ]);
    
    echo "Cuenta vinculada con Google";
}

// ============================================
// EJEMPLO 6: Bloquear usuario
// ============================================

$userModel = new User();
$userId = 5;

$userModel->update($userId, [
    'estado' => 'bloqueado'
]);

echo "Usuario bloqueado";

// ============================================
// EJEMPLO 7: Activar usuario pendiente
// ============================================

$userModel = new User();
$userId = 5;

$userModel->update($userId, [
    'estado' => 'activo'
]);

echo "Usuario activado";

// ============================================
// EJEMPLO 8: Verificar rol en middleware personalizado
// ============================================

function protectRoute($allowedRoles = []) {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    
    $userRole = $_SESSION['user_role'] ?? 'cliente';
    
    if (!in_array($userRole, $allowedRoles)) {
        http_response_code(403);
        die('Acceso denegado');
    }
}

// Uso:
protectRoute(['admin']); // Solo admin
protectRoute(['admin', 'ejecutivo']); // Admin o ejecutivo
protectRoute(['cliente']); // Solo clientes

// ============================================
// EJEMPLO 9: Mostrar información según provider
// ============================================

session_start();

$provider = $_SESSION['user_provider'] ?? 'local';

if ($provider === 'google') {
    ?>
    <div class="badge">
        <img src="google-icon.png" width="16">
        Conectado con Google
    </div>
    <?php
} else {
    ?>
    <div class="badge">
        🔒 Cuenta local
    </div>
    <?php
}

// ============================================
// EJEMPLO 10: Obtener usuarios por estado
// ============================================

$db = Database::getInstance()->getConnection();

// Usuarios pendientes de aprobación
$stmt = $db->query("
    SELECT * FROM users 
    WHERE estado = 'pendiente' 
    ORDER BY created_at DESC
");
$pendingUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($pendingUsers as $user) {
    echo "{$user['name']} ({$user['email']}) - {$user['provider']}\n";
}

// ============================================
// EJEMPLO 11: Logout completo
// ============================================

session_start();

// Limpiar todas las variables de sesión
$_SESSION = [];

// Destruir cookie de sesión
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destruir sesión
session_destroy();

// Redirigir
header('Location: /login');
exit;

// ============================================
// EJEMPLO 12: Validar timeout de sesión
// ============================================

session_start();

$timeout = 7200; // 2 horas en segundos

if (isset($_SESSION['last_activity'])) {
    $elapsed = time() - $_SESSION['last_activity'];
    
    if ($elapsed > $timeout) {
        // Sesión expirada
        session_unset();
        session_destroy();
        header('Location: /login?timeout=1');
        exit;
    }
}

// Actualizar timestamp
$_SESSION['last_activity'] = time();

// ============================================
// EJEMPLO 13: Obtener todos los usuarios Google
// ============================================

$userModel = new User();
$db = Database::getInstance()->getConnection();

$stmt = $db->query("
    SELECT * FROM users 
    WHERE provider = 'google' 
    ORDER BY created_at DESC
");
$googleUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($googleUsers as $user) {
    echo "{$user['name']} - {$user['email']} - Estado: {$user['estado']}\n";
}

// ============================================
// EJEMPLO 14: Contar usuarios por rol y provider
// ============================================

$db = Database::getInstance()->getConnection();

$stmt = $db->query("
    SELECT 
        role, 
        provider, 
        COUNT(*) as total 
    FROM users 
    GROUP BY role, provider
    ORDER BY role, provider
");

$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($stats as $stat) {
    echo "{$stat['role']} ({$stat['provider']}): {$stat['total']} usuarios\n";
}

// ============================================
// EJEMPLO 15: Redirigir según rol después de login
// ============================================

function redirectByRole($role) {
    switch ($role) {
        case 'admin':
            return '/factapex/public/dashboard';
        case 'ejecutivo':
            return '/factapex/public/dashboard';
        case 'cliente':
            return '/factapex/public/dashboard';
        default:
            return '/factapex/public/';
    }
}

// Uso:
$userRole = $_SESSION['user_role'] ?? 'cliente';
$redirect = redirectByRole($userRole);
header("Location: $redirect");
exit;
