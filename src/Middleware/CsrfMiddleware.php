<?php

namespace Factapex\Middleware;

use Factapex\Core\Middleware;

class CsrfMiddleware extends Middleware {
    
    public function handle($request) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            
            if (!$this->validateToken($token)) {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Token CSRF inválido'
                ]);
                exit;
            }
        }

        if ($this->next) {
            return $this->next->handle($request);
        }

        return true;
    }

    private function validateToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}