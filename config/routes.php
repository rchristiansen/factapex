<?php
use Factapex\Controllers\AuthController;
use Factapex\Controllers\DashboardController;

$router->get('/', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->get('/dashboard', [DashboardController::class, 'index']);
