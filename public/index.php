<?php

// Start session globally for user authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Zavedenie Composer Autoloadera
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Initialize the application Router
use App\Core\Router;
use App\Controllers\BookController;
use App\Controllers\AuthController;
use App\Controllers\AdminBookController;
use App\Controllers\ImportController;

$router = new Router();

// 3. Define the application routes
// All routes are protected by default (Default-deny architecture).
// Routes that need to be publicly accessible must have `isPublic: true`.
$router->get('/', [BookController::class, 'index'], isPublic: true);
$router->get('/books/{id}', [BookController::class, 'show'], isPublic: true);

// Admin authentication routes
$router->get('/login', [AuthController::class, 'showLogin'], isPublic: true);
$router->post('/login', [AuthController::class, 'processLogin'], isPublic: true);
$router->get('/logout', [AuthController::class, 'logout'], isPublic: true);

// Admin books management routes
$router->get('/admin/books', [AdminBookController::class, 'index']);
$router->get('/admin/books/create', [AdminBookController::class, 'create']);
$router->post('/admin/books', [AdminBookController::class, 'store']);
$router->post('/admin/books/import', [ImportController::class, 'import']);

// 4. Dispatch the request (match the URL and execute the code)
$router->dispatch();
