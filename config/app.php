<?php

// =======================================================
// Configuración de la aplicación
// =======================================================

// Definir BASE_PATH (sin /public)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', '/factapex');
}

// Definir ROOT_PATH (ruta absoluta del proyecto)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// Definir PUBLIC_PATH (para assets)
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', BASE_PATH . '/public');
}

// Definir ASSETS_PATH (para CSS, JS, imágenes)
if (!defined('ASSETS_PATH')) {
    define('ASSETS_PATH', PUBLIC_PATH . '/assets');
}

// Configuración de la base de datos (opcional, puedes moverlo aquí)
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'factapex');
    define('DB_USER', 'root');
    define('DB_PASS', 'Genius6969@');
}

// Configuración de zona horaria
date_default_timezone_set('America/Lima');

// Configuración de errores (desarrollo vs producción)
if (!defined('APP_ENV')) {
    define('APP_ENV', 'development'); // cambiar a 'production' en producción
}

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// =======================================================
// Autoloader PSR-4 para Factapex
// =======================================================

spl_autoload_register(function ($class) {
    // Solo procesar clases del namespace Factapex
    $prefix = 'Factapex\\';
    $baseDir = ROOT_PATH . '/src/';

    // ¿La clase usa el prefijo Factapex?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Obtener el nombre relativo de la clase
    $relativeClass = substr($class, $len);

    // Convertir namespace a ruta física
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    // Incluir el archivo si existe
    if (file_exists($file)) {
        require $file;
    }
});

// =======================================================
// Helper Functions (opcional)
// =======================================================

/**
 * Generar URL completa
 */
function url($path = '') {
    return PUBLIC_PATH . '/' . ltrim($path, '/');
}

/**
 * Generar ruta de asset
 */
function asset($path = '') {
    return ASSETS_PATH . '/' . ltrim($path, '/');
}

/**
 * Redirigir a una URL
 */
function redirect($path = '/') {
    header('Location: ' . PUBLIC_PATH . '/' . ltrim($path, '/'));
    exit;
}

/**
 * Obtener valor de $_POST de forma segura
 */
function post($key, $default = null) {
    return $_POST[$key] ?? $default;
}

/**
 * Obtener valor de $_GET de forma segura
 */
function get($key, $default = null) {
    return $_GET[$key] ?? $default;
}

/**
 * Escapar HTML
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}


