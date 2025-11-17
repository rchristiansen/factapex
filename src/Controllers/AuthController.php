<?php

namespace Factapex\Controllers;

use Factapex\Core\Controller;
use Factapex\Models\User;

class AuthController extends Controller {

    public function login() {
        // Si ya está autenticado, redirigir al dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . PUBLIC_PATH . '/dashboard');
            exit;
        }

        $this->render('auth/login', [
            'title' => 'Login - Factapex'
        ]);
    }

    public function authenticate() {
        // Establecer header JSON PRIMERO
        header('Content-Type: application/json');
        
        try {
            // Los middlewares ya validaron CSRF y Rate Limit
            
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validación básica
            if (empty($email) || empty($password)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Email y contraseña son requeridos'
                ]);
                return;
            }

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Crear sesión segura
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'] ?? 'cliente';
                $_SESSION['token'] = bin2hex(random_bytes(32));
                $_SESSION['last_activity'] = time();

                // Actualizar último login
                $userModel->updateLastLogin($user['id']);

                // TODOS van a /dashboard, el DashboardController decide qué mostrar
                echo json_encode([
                    'success' => true,
                    'message' => 'Login exitoso',
                    'redirect' => PUBLIC_PATH . '/dashboard',
                    'role' => $user['role'] ?? 'cliente'
                ]);
            } else {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Email o contraseña incorrectos'
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error del servidor',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . PUBLIC_PATH . '/login');
        exit;
    }

    public function register() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . PUBLIC_PATH . '/dashboard');
            exit;
        }

        $this->render('auth/register', [
            'title' => 'Registro - Factapex'
        ]);
    }

    public function store() {
        header('Content-Type: application/json');
        
        try {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($name) || empty($email) || empty($password)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Todos los campos son requeridos'
                ]);
                return;
            }

            $userModel = new User();
            
            if ($userModel->findByEmail($email)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'El email ya está registrado'
                ]);
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            if ($userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword
            ])) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario registrado exitosamente',
                    'redirect' => PUBLIC_PATH . '/login'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al registrar usuario'
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error del servidor',
                'error' => $e->getMessage()
            ]);
        }
    }
}
