<?php
/**
 * Procesar completar perfil de usuario Google
 * SIN COMPOSER
 */

session_start();

// Cargar configuración y autoloader
require_once __DIR__ . '/../../../config/app.php';

// Autoload manual de clases
spl_autoload_register(function ($class) {
    $prefix = 'Factapex\\';
    $base_dir = __DIR__ . '/../../../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

require_once __DIR__ . '/../../../config/google_oauth.php';

use Factapex\Services\GoogleOAuthService;
use Factapex\Middleware\CsrfMiddleware;

try {
    // Verificar método POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }
    
    // Verificar CSRF token
    $token = $_POST['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        throw new Exception('Token CSRF inválido');
    }
    
    // Verificar que hay datos de Google en sesión
    if (!isset($_SESSION['google_user_data'])) {
        throw new Exception('No hay datos de Google. Inicia el proceso nuevamente.');
    }
    
    $googleData = $_SESSION['google_user_data'];
    
    // Validar datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    if (empty($nombre) || empty($apellido) || empty($password)) {
        throw new Exception('Todos los campos son obligatorios');
    }
    
    if ($password !== $password_confirm) {
        throw new Exception('Las contraseñas no coinciden');
    }
    
    if (strlen($password) < 6) {
        throw new Exception('La contraseña debe tener al menos 6 caracteres');
    }
    
    // Crear servicio y usuario
    $googleService = new GoogleOAuthService();
    
    $profileData = [
        'nombre' => $nombre,
        'apellido' => $apellido,
        'password' => $password,
        'password_confirm' => $password_confirm,
        'telefono' => trim($_POST['telefono'] ?? '')
    ];
    
    $result = $googleService->createUserWithProfile($googleData, $profileData);
    
    if (!$result['success']) {
        throw new Exception($result['message']);
    }
    
    // Usuario creado exitosamente
    $user = $result['user'];
    
    // Limpiar datos temporales de Google
    unset($_SESSION['google_user_data']);
    
    // Crear sesión segura
    session_regenerate_id(true);
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_provider'] = $user['provider'];
    $_SESSION['user_avatar'] = $user['avatar'] ?? null;
    $_SESSION['token'] = bin2hex(random_bytes(32)); // Token requerido por AuthMiddleware
    $_SESSION['last_activity'] = time();
    $_SESSION['authenticated'] = true;
    
    // Mensaje de bienvenida
    $_SESSION['success_message'] = '¡Bienvenido! Tu cuenta ha sido creada exitosamente.';
    
    // Debug: Guardar para verificar
    error_log("Usuario creado - ID: {$user['id']}, Email: {$user['email']}, Role: {$user['role']}");
    error_log("Sesión establecida - user_id: {$_SESSION['user_id']}");
    
    // Redirigir al dashboard directamente
    header('Location: /factapex/public/dashboard');
    exit;
    
} catch (Exception $e) {
    // Error
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: /factapex/public/auth/google/show-complete-profile.php');
    exit;
}
