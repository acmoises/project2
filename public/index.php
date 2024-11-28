<?php

require_once __DIR__ . '/../includes/app.php';

use Controllers\MenuController;
use MVC\Router;

$router = new Router();

$router->get('/', [MenuController::class, 'index']);
$router->get('/create', [MenuController::class, 'create']);
$router->post('/create', [MenuController::class, 'create']);

$router->get('/update', [MenuController::class, 'update']);
$router->post('/update', [MenuController::class, 'update']);

$router->post('/delete', [MenuController::class, 'delete']);



$router->comprobarRutas();