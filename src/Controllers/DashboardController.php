<?php

namespace Factapex\Controllers;

use Factapex\Core\Controller;

class DashboardController extends Controller {

    /**
     * Dashboard principal - muestra la vista según el rol del usuario
     */
    public function index() {
        $role = $_SESSION['user_role'] ?? 'cliente';

        // Determinar qué vista mostrar según el rol
        $view = ($role === 'admin') ? 'dashboard/admin' : 'dashboard/cliente';

        // Obtener estadísticas según el rol
        $stats = ($role === 'admin') ? $this->getAdminStats() : $this->getClienteStats();

        $this->render($view, [
            'title' => ($role === 'admin' ? 'Dashboard Admin' : 'Dashboard Cliente') . ' - Factapex',
            'user' => [
                'name' => $_SESSION['user_name'] ?? 'Usuario',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $role
            ],
            'stats' => $stats
        ]);
    }

    /**
     * Obtener estadísticas del cliente
     */
    private function getClienteStats() {
        // TODO: Obtener datos reales de la base de datos
        return [
            'facturas_totales' => 12,
            'en_revision' => 3,
            'aprobadas' => 9,
            'monto_total' => 45000
        ];
    }

    /**
     * Obtener estadísticas del administrador
     */
    private function getAdminStats() {
        // TODO: Obtener datos reales de la base de datos
        return [
            'clientes_totales' => 45,
            'facturas_totales' => 234,
            'pendientes_aprobacion' => 18,
            'volumen_total' => 1200000
        ];
    }

    /**
     * API: Obtener datos del dashboard del cliente
     */
    public function getClienteData() {
        header('Content-Type: application/json');
        
        if ($_SESSION['user_role'] !== 'cliente') {
            http_response_code(403);
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $this->getClienteStats()
        ]);
    }

    /**
     * API: Obtener datos del dashboard del admin
     */
    public function getAdminData() {
        header('Content-Type: application/json');
        
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $this->getAdminStats()
        ]);
    }
}
