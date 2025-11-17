<?php

namespace Factapex\Middleware;

use Factapex\Core\Middleware;

class AuthMiddleware extends Middleware {
    
    public function handle($request) {
        if (!isset($_SESSION['user_id'])) {
            // Si es una petición AJAX, devolver JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'No autorizado'
                ]);
                exit;
            }
            
            // Si no, redirigir al login
            header('Location: ' . PUBLIC_PATH . '/login');
            exit;
        }

        // Verificar token de sesión
        if (!$this->validateSessionToken()) {
            session_destroy();
            header('Location: ' . PUBLIC_PATH . '/login');
            exit;
        }

        // Continuar con el siguiente middleware o controlador
        if ($this->next) {
            return $this->next->handle($request);
        }

        return true;
    }

    private function validateSessionToken() {
        if (!isset($_SESSION['token'])) {
            return false;
        }

        // Verificar tiempo de expiración (30 minutos)
        if (isset($_SESSION['last_activity'])) {
            $inactive = time() - $_SESSION['last_activity'];
            if ($inactive > 1800) {
                return false;
            }
        }

        $_SESSION['last_activity'] = time();
        return true;
    }
}