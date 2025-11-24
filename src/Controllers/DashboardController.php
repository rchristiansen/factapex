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
        switch ($role) {
            case 'admin':
                $view = 'dashboard/admin';
                $stats = $this->getAdminStats();
                $title = 'Dashboard Admin';
                break;
            case 'ejecutivo':
                $view = 'dashboard/ejecutivo';
                $stats = $this->getEjecutivoStats();
                $title = 'Dashboard Ejecutivo';
                break;
            case 'cliente':
            default:
                $view = 'dashboard/cliente';
                $stats = $this->getClienteStats();
                $title = 'Dashboard Cliente';
                break;
        }

        $this->render($view, [
            'title' => $title . ' - Factapex',
            'user' => [
                'name' => $_SESSION['user_name'] ?? 'Usuario',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $role,
                'avatar' => $_SESSION['user_avatar'] ?? null
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
     * Obtener estadísticas del ejecutivo
     */
    private function getEjecutivoStats() {
        // TODO: Obtener datos reales de la base de datos filtrados por ejecutivo
        $ejecutivoId = $_SESSION['user_id'] ?? 0;
        
        return [
            'clientes_asignados' => 12,
            'facturas_gestionadas' => 45,
            'en_proceso' => 8,
            'volumen_gestionado' => 450000
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

    /**
     * API: Obtener datos del dashboard del ejecutivo
     */
    public function getEjecutivoData() {
        header('Content-Type: application/json');
        
        if ($_SESSION['user_role'] !== 'ejecutivo') {
            http_response_code(403);
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $this->getEjecutivoStats()
        ]);
    }
}
