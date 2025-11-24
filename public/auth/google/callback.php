<?php
/**
 * Callback de Google OAuth
 * Procesa la respuesta de Google y crea/autentica usuario
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

try {
    // Verificar que tenemos el código de autorización
    if (!isset($_GET['code'])) {
        throw new Exception('No se recibió código de autorización de Google');
    }
    
    $code = $_GET['code'];
    
    // Crear servicio de Google OAuth
    $googleService = new GoogleOAuthService();
    
    // Procesar callback
    $result = $googleService->handleCallback($code);
    
    if (!$result['success']) {
        // Error en autenticación
        $_SESSION['error_message'] = $result['message'];
        
        // Si fue bloqueado (admin/ejecutivo), mostrar mensaje especial
        if (isset($result['blocked']) && $result['blocked']) {
            $_SESSION['error_type'] = 'blocked';
        }
        
        header('Location: ' . PUBLIC_PATH . '/login');
        exit;
    }
    
    // Si es usuario nuevo, redirigir a completar perfil
    if (isset($result['needs_profile']) && $result['needs_profile']) {
        $_SESSION['google_user_data'] = $result['google_data'];
        header('Location: /factapex/public/auth/google/show-complete-profile.php');
        exit;
    }
    
    // Autenticación exitosa
    $user = $result['user'];
    
    // Verificar estado del usuario
    if ($user['estado'] === 'bloqueado') {
        $_SESSION['error_message'] = 'Tu cuenta está bloqueada. Contacta al administrador.';
        header('Location: ' . PUBLIC_PATH . '/login');
        exit;
    }
    
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
    
    // Mensaje de bienvenida
    if (isset($result['is_new']) && $result['is_new']) {
        $_SESSION['success_message'] = '¡Bienvenido! Tu cuenta ha sido creada exitosamente.';
        
        // Si es pendiente, notificar
        if ($user['estado'] === 'pendiente') {
            $_SESSION['info_message'] = 'Tu cuenta está pendiente de aprobación. Algunas funciones pueden estar limitadas.';
        }
    } else {
        $_SESSION['success_message'] = '¡Bienvenido de nuevo, ' . $user['name'] . '!';
    }
    
    // Determinar redirección según rol
    $redirectUrl = $_SESSION['oauth_redirect'] ?? null;
    unset($_SESSION['oauth_redirect']);
    
    if (!$redirectUrl) {
        // Redirigir según rol
        switch ($user['role']) {
            case 'admin':
                $redirectUrl = PUBLIC_PATH . '/dashboard';
                break;
            case 'ejecutivo':
                $redirectUrl = PUBLIC_PATH . '/dashboard';
                break;
            case 'cliente':
            default:
                $redirectUrl = PUBLIC_PATH . '/dashboard';
                break;
        }
    }
    
    // Redirigir
    header('Location: ' . $redirectUrl);
    exit;
    
} catch (Exception $e) {
    // Error general
    $_SESSION['error_message'] = 'Error en autenticación: ' . $e->getMessage();
    header('Location: ' . PUBLIC_PATH . '/login');
    exit;
}
