<?php
/**
 * Iniciar flujo de autenticación con Google
 * Solo para CLIENTES
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
    // Verificar que Google OAuth está configurado
    if (!isGoogleOAuthConfigured()) {
        die('Google OAuth no está configurado. Por favor contacta al administrador.');
    }
    
    // Crear servicio de Google OAuth
    $googleService = new GoogleOAuthService();
    
    // Obtener URL de autenticación
    $authUrl = $googleService->getAuthUrl();
    
    // Guardar referrer para redirigir después
    if (isset($_GET['redirect'])) {
        $_SESSION['oauth_redirect'] = $_GET['redirect'];
    }
    
    // Redirigir a Google
    header('Location: ' . $authUrl);
    exit;
    
} catch (Exception $e) {
    // Error al iniciar OAuth
    $_SESSION['error_message'] = 'Error al conectar con Google: ' . $e->getMessage();
    header('Location: ' . PUBLIC_PATH . '/login');
    exit;
}
