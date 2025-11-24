<?php
/**
 * Middleware de protección para rutas de EJECUTIVO
 * Solo usuarios con role = 'ejecutivo' pueden acceder
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function protectEjecutivo() {
    // Verificar que hay sesión activa
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error_message'] = 'Debes iniciar sesión para acceder a esta página';
        header('Location: /factapex/public/login');
        exit;
    }
    
    // Verificar que el usuario es ejecutivo
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'ejecutivo') {
        $_SESSION['error_message'] = 'No tienes permisos para acceder a esta página';
        header('Location: /factapex/public/dashboard');
        exit;
    }
    
    // Verificar actividad de sesión (timeout 2 horas)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
        session_unset();
        session_destroy();
        header('Location: /factapex/public/login?timeout=1');
        exit;
    }
    
    $_SESSION['last_activity'] = time();
    
    return true;
}

// Auto-ejecutar si se incluye directamente
if (basename($_SERVER['PHP_SELF']) !== 'protect_ejecutivo.php') {
    protectEjecutivo();
}
