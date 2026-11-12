<?php
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\TutorController;
use App\Controllers\PetController;
use App\Controllers\AppointmentController;

$router->get('/', [AuthController::class, 'login']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/dashboard', [DashboardController::class, 'index']);

$router->get('/tutores', [TutorController::class, 'index']);
$router->get('/tutores/novo', [TutorController::class, 'create']);
$router->post('/tutores/salvar', [TutorController::class, 'store']);
$router->get('/tutores/editar', [TutorController::class, 'edit']);
$router->post('/tutores/atualizar', [TutorController::class, 'update']);

$router->get('/pets', [PetController::class, 'index']);
$router->get('/pets/novo', [PetController::class, 'create']);
$router->post('/pets/salvar', [PetController::class, 'store']);

$router->get('/agendamentos', [AppointmentController::class, 'index']);
$router->get('/agendamentos/novo', [AppointmentController::class, 'create']);
$router->post('/agendamentos/salvar', [AppointmentController::class, 'store']);
$router->post('/agendamentos/atualizar-data', [AppointmentController::class, 'updateDate']);
