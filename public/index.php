<?php

// Start session globally for user authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Simple custom Autoloader to automatically require class files
// This will later be replaced by Composer's autoloader.
spl_autoload_register(function ($class) {
    // Prefix mapping: App\Core\Router becomes /app/Core/Router.php
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; 
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load global helper functions
require_once __DIR__ . '/../app/helpers.php';

// 2. Initialize the application Router
use App\Core\Router;
use App\Controllers\BookController;
use App\Controllers\AuthController;
use App\Controllers\AdminBookController;

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

// 4. Dispatch the request (match the URL and execute the code)
$router->dispatch();
