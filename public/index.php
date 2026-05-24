<?php

declare(strict_types=1);

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Core\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/category/{id}', [CategoryController::class, 'show']);
$router->get('/post/{id}', [PostController::class, 'show']);

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
