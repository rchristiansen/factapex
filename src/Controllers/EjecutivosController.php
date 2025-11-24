<?php

namespace Factapex\Controllers;

use Factapex\Core\Controller;
use Factapex\Models\User;

class EjecutivosController extends Controller {
    
    /**
     * Listar todos los ejecutivos (solo admin)
     */
    public function index() {
        // Verificar que el usuario sea admin
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: ' . PUBLIC_PATH . '/dashboard');
            exit;
        }

        $this->render('ejecutivos/index', [
            'title' => 'Gestión de Ejecutivos - Factapex'
        ]);
    }

    /**
     * Formulario para crear un nuevo ejecutivo (solo admin)
     */
    public function create() {
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: ' . PUBLIC_PATH . '/dashboard');
            exit;
        }

        $this->render('ejecutivos/create', [
            'title' => 'Nuevo Ejecutivo - Factapex'
        ]);
    }

    /**
     * Almacenar un nuevo ejecutivo en la base de datos (solo admin)
     */
    public function store() {
        header('Content-Type: application/json');
        
        // Verificar que el usuario sea admin
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'No autorizado. Solo administradores pueden crear ejecutivos.'
            ]);
            return;
        }

        try {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validación básica
            if (empty($name) || empty($email) || empty($password)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Todos los campos son requeridos'
                ]);
                return;
            }

            // Validar formato de email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Email inválido'
                ]);
                return;
            }

            // Validar longitud de contraseña
            if (strlen($password) < 6) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'La contraseña debe tener al menos 6 caracteres'
                ]);
                return;
            }

            $userModel = new User();
            
            // Verificar si el email ya existe
            if ($userModel->findByEmail($email)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'El email ya está registrado'
                ]);
                return;
            }

            // Crear el usuario ejecutivo
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $userId = $userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => 'ejecutivo'
            ]);

            if ($userId) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Ejecutivo creado exitosamente',
                    'user_id' => $userId
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al crear el ejecutivo'
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Listar ejecutivos en formato JSON (solo admin)
     */
    public function list() {
        header('Content-Type: application/json');
        
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'No autorizado'
            ]);
            return;
        }

        try {
            $userModel = new User();
            $ejecutivos = $userModel->findByRole('ejecutivo');

            echo json_encode([
                'success' => true,
                'data' => $ejecutivos
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener ejecutivos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar un ejecutivo (solo admin)
     */
    public function delete() {
        header('Content-Type: application/json');
        
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'No autorizado'
            ]);
            return;
        }

        try {
            $userId = $_POST['user_id'] ?? 0;

            if (empty($userId)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de usuario requerido'
                ]);
                return;
            }

            $userModel = new User();
            $user = $userModel->findById($userId);

            // Verificar que el usuario existe y es ejecutivo
            if (!$user || $user['role'] !== 'ejecutivo') {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuario no encontrado o no es ejecutivo'
                ]);
                return;
            }

            if ($userModel->delete($userId)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Ejecutivo eliminado exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar el ejecutivo'
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error del servidor: ' . $e->getMessage()
            ]);
        }
    }
}
