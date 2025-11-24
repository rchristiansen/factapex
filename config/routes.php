<?php
use Factapex\Controllers\AuthController;
use Factapex\Controllers\DashboardController;
use Factapex\Controllers\FacturasController;
use Factapex\Controllers\EjecutivosController;

// Rutas públicas
$router->get('/', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->get('/auth/complete-profile', [AuthController::class, 'completeProfile']);

// Rutas protegidas - Todos los roles autenticados
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/facturas', [FacturasController::class, 'index']);

// Rutas específicas de administrador
$router->get('/ejecutivos', [EjecutivosController::class, 'index']);
$router->get('/ejecutivos/create', [EjecutivosController::class, 'create']);
$router->post('/ejecutivos/store', [EjecutivosController::class, 'store']);
$router->get('/ejecutivos/list', [EjecutivosController::class, 'list']);
$router->post('/ejecutivos/delete', [EjecutivosController::class, 'delete']);
