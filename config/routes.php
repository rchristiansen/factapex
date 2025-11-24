<?php
use Factapex\Controllers\AuthController;
use Factapex\Controllers\DashboardController;
use Factapex\Controllers\FacturasController;

$router->get('/', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/facturas', [FacturasController::class, 'index']);
