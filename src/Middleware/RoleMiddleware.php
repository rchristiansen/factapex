<?php

namespace Factapex\Middleware;

use Factapex\Core\Middleware;

/**
 * Middleware para validar permisos basados en roles
 */
class RoleMiddleware extends Middleware {
    
    /**
     * Roles permitidos por ruta
     */
    private static $rolePermissions = [
        // Rutas de admin
        '/admin' => ['admin'],
        '/ejecutivos' => ['admin'],
        '/ejecutivos/create' => ['admin'],
        '/ejecutivos/store' => ['admin'],
        '/ejecutivos/list' => ['admin'],
        '/ejecutivos/delete' => ['admin'],
        '/configuracion' => ['admin'],
        '/usuarios' => ['admin'],
        
        // Rutas de ejecutivo
        '/clientes' => ['admin', 'ejecutivo'],
        '/reportes' => ['admin', 'ejecutivo'],
        
        // Rutas de todos los autenticados
        '/dashboard' => ['admin', 'ejecutivo', 'cliente'],
        '/facturas' => ['admin', 'ejecutivo', 'cliente'],
        '/documentos' => ['admin', 'ejecutivo', 'cliente'],
        '/riesgo' => ['cliente'],
        '/agenda' => ['admin', 'ejecutivo', 'cliente'],
    ];

    /**
     * Ejecutar el middleware
     */
    public function handle($request) {
        // Verificar que el usuario esté autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . PUBLIC_PATH . '/login');
            exit;
        }

        $userRole = $_SESSION['user_role'] ?? 'cliente';
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remover BASE_PATH de la URI
        $currentPath = str_replace(BASE_PATH . '/public', '', $currentPath);
        
        // Verificar permisos
        if (!$this->hasPermission($currentPath, $userRole)) {
            // Redirigir al dashboard según el rol
            $this->redirectToDashboard($userRole);
            exit;
        }

        return true;
    }

    /**
     * Verificar si el usuario tiene permiso para acceder a la ruta
     */
    private function hasPermission($path, $role) {
        // Si la ruta no está definida en permisos, permitir acceso
        if (!isset(self::$rolePermissions[$path])) {
            return true;
        }

        // Verificar si el rol tiene permiso
        return in_array($role, self::$rolePermissions[$path]);
    }

    /**
     * Redirigir al dashboard según el rol
     */
    private function redirectToDashboard($role) {
        switch ($role) {
            case 'admin':
                header('Location: ' . PUBLIC_PATH . '/dashboard');
                break;
            case 'ejecutivo':
                header('Location: ' . PUBLIC_PATH . '/dashboard');
                break;
            case 'cliente':
            default:
                header('Location: ' . PUBLIC_PATH . '/dashboard');
                break;
        }
    }

    /**
     * Verificar si el usuario tiene uno de los roles especificados
     */
    public static function checkRole($allowedRoles) {
        $userRole = $_SESSION['user_role'] ?? null;
        
        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            http_response_code(403);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                // Respuesta JSON para peticiones AJAX
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'No tiene permisos para realizar esta acción'
                ]);
            } else {
                // Redirección para peticiones normales
                header('Location: ' . PUBLIC_PATH . '/dashboard');
            }
            exit;
        }
        
        return true;
    }

    /**
     * Verificar si el usuario es admin
     */
    public static function isAdmin() {
        return ($_SESSION['user_role'] ?? null) === 'admin';
    }

    /**
     * Verificar si el usuario es ejecutivo
     */
    public static function isEjecutivo() {
        return ($_SESSION['user_role'] ?? null) === 'ejecutivo';
    }

    /**
     * Verificar si el usuario es cliente
     */
    public static function isCliente() {
        return ($_SESSION['user_role'] ?? null) === 'cliente';
    }
}
