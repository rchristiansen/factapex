<?php

namespace Factapex\Core;

class Router {
    private $routes = [];

    public function addRoute($path, $controller, $action, $middlewares = []) {
        $this->routes[$path] = [
            'controller' => $controller,
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch($uri) {
        // Limpiar query string
        $uri = strtok($uri, '?');

        // Si la ruta no existe, mostrar 404
        if (!isset($this->routes[$uri])) {
            $this->render404();
            exit;
        }

        $route = $this->routes[$uri];
        
        // Ejecutar middlewares en cadena
        $this->executeMiddlewares($route['middlewares'], function() use ($route) {
            $controllerName = 'Factapex\\Controllers\\' . $route['controller'];
            $action = $route['action'];

            if (!class_exists($controllerName)) {
                $this->render500("Controlador no encontrado: " . $controllerName);
                exit;
            }

            $controller = new $controllerName();
            
            if (!method_exists($controller, $action)) {
                $this->render500("Acción no encontrada: " . $action);
                exit;
            }

            $controller->$action();
        });
    }

    private function render404() {
        http_response_code(404);
        $viewPath = ROOT_PATH . '/views/errors/404.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            $this->renderBasic404();
        }
    }

    private function render500($message = "Error interno del servidor") {
        http_response_code(500);
        $viewPath = ROOT_PATH . '/views/errors/500.php';
        
        if (file_exists($viewPath)) {
            $errorMessage = $message;
            require_once $viewPath;
        } else {
            $this->renderBasic500($message);
        }
    }

    private function renderBasic404() {
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Página no encontrada</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    margin: 0;
                }
                .container {
                    text-align: center;
                    padding: 40px;
                    background: rgba(0,0,0,0.3);
                    border-radius: 10px;
                }
                h1 { font-size: 120px; margin: 0; }
                p { font-size: 20px; }
                a { 
                    display: inline-block;
                    background: #f97316;
                    color: white;
                    padding: 12px 30px;
                    text-decoration: none;
                    border-radius: 6px;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>404</h1>
                <p>Página no encontrada</p>
                <a href="' . PUBLIC_PATH . '/">Volver al inicio</a>
            </div>
        </body>
        </html>';
    }

    private function renderBasic500($message) {
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>500 - Error del servidor</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    margin: 0;
                }
                .container {
                    text-align: center;
                    padding: 40px;
                    background: rgba(0,0,0,0.3);
                    border-radius: 10px;
                    max-width: 600px;
                }
                h1 { font-size: 120px; margin: 0; }
                p { font-size: 18px; margin: 20px 0; }
                .error {
                    margin: 20px 0;
                    padding: 15px;
                    background: rgba(0,0,0,0.2);
                    border-radius: 5px;
                }
                a {
                    display: inline-block;
                    background: #f97316;
                    color: white;
                    padding: 12px 30px;
                    text-decoration: none;
                    border-radius: 6px;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>500</h1>
                <p>Error interno del servidor</p>
                <div class="error">' . htmlspecialchars($message) . '</div>
                <a href="' . PUBLIC_PATH . '/">Volver al inicio</a>
            </div>
        </body>
        </html>';
    }

    private function executeMiddlewares($middlewares, $finalAction) {
        if (empty($middlewares)) {
            $finalAction();
            return;
        }

        $pipeline = null;
        
        foreach (array_reverse($middlewares) as $middleware) {
            if (is_object($middleware)) {
                $middlewareInstance = $middleware;
            } else {
                $middlewareInstance = new $middleware();
            }
            
            if ($pipeline) {
                $middlewareInstance->setNext($pipeline);
            }
            
            $pipeline = $middlewareInstance;
        }

        $finalMiddleware = new class($finalAction) extends Middleware {
            private $action;
            
            public function __construct($action) {
                $this->action = $action;
            }
            
            public function handle($request) {
                ($this->action)();
            }
        };

        if ($pipeline) {
            $pipeline->setNext($finalMiddleware);
            $pipeline->handle($_REQUEST);
        } else {
            $finalAction();
        }
    }
}
