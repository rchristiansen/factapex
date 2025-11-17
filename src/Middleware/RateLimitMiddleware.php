<?php

namespace Factapex\Middleware;

use Factapex\Core\Middleware;

class RateLimitMiddleware extends Middleware {
    
    private $maxRequests = 10; // máximo de requests por minuto
    private $timeWindow = 60; // en segundos

    public function handle($request) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = 'rate_limit_' . $ip;

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'count' => 0,
                'start_time' => time()
            ];
        }

        $data = $_SESSION[$key];
        $elapsed = time() - $data['start_time'];

        // Resetear si pasó el tiempo
        if ($elapsed > $this->timeWindow) {
            $_SESSION[$key] = [
                'count' => 1,
                'start_time' => time()
            ];
        } else {
            $_SESSION[$key]['count']++;

            if ($_SESSION[$key]['count'] > $this->maxRequests) {
                header('Content-Type: application/json');
                http_response_code(429);
                echo json_encode([
                    'success' => false,
                    'message' => 'Demasiadas solicitudes. Intenta más tarde.'
                ]);
                exit;
            }
        }

        if ($this->next) {
            return $this->next->handle($request);
        }

        return true;
    }
}