<?php

// Cargar configuración global
require_once __DIR__ . '/../config/app.php';

// Autoload manual de clases
spl_autoload_register(function ($class) {
    $prefix = 'Factapex\\';
    $base_dir = __DIR__ . '/../src/';

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

use Factapex\Core\Router;
use Factapex\Middleware\AuthMiddleware;
use Factapex\Middleware\CsrfMiddleware;
use Factapex\Middleware\RateLimitMiddleware;

session_start();

$router = new Router();

// ==========================================
// RUTAS PÚBLICAS (sin middleware)
// ==========================================
$router->addRoute('/', 'HomeController', 'index');
$router->addRoute('/login', 'AuthController', 'login');
$router->addRoute('/register', 'AuthController', 'register');

// ==========================================
// RUTAS DE AUTENTICACIÓN (con CSRF y Rate Limit)
// ==========================================
$router->addRoute('/auth/login', 'AuthController', 'authenticate', [
    RateLimitMiddleware::class,
    CsrfMiddleware::class
]);

$router->addRoute('/auth/register', 'AuthController', 'store', [
    RateLimitMiddleware::class,
    CsrfMiddleware::class
]);

// ==========================================
// RUTAS PROTEGIDAS (requieren autenticación)
// ==========================================
$router->addRoute('/dashboard', 'DashboardController', 'index', [
    AuthMiddleware::class
]);

$router->addRoute('/logout', 'AuthController', 'logout', [
    AuthMiddleware::class
]);

$router->addRoute('/facturas', 'FacturasController', 'index', [
    AuthMiddleware::class
]);

// Obtener URI y limpiar
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remover BASE_PATH de la URI (más dinámico)
$uri = str_replace(BASE_PATH . '/public', '', $uri);

// Si la URI está vacía, establecerla como "/"
if (empty($uri) || $uri === '') {
    $uri = '/';
}

// Despachar ruta
$router->dispatch($uri);

