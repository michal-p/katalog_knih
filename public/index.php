<?php

// 1. Namespace aliases
use App\Core\Router;
use App\Controllers\BookController;
use App\Controllers\AuthController;
use App\Controllers\AdminBookController;
use App\Controllers\ImportController;

// 2. Start session globally for user authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Load Composer Autoloader
// Note: If you add new classes/namespaces and get "Undefined type" errors,
// make sure to run: docker exec -it ebook_web composer dump-autoload
require_once __DIR__ . '/../vendor/autoload.php';

// 4. Initialize the application Router
$router = new Router();

// 5. Define the application routes
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

// 6. Dispatch the request (match the URL and execute the code)
try {
    $router->dispatch();
} catch (\Exception $e) {
    if ($e->getCode() === 403) {
        \App\Core\View::renderError(403);
    } else {
        error_log($e->getMessage());
        \App\Core\View::renderError(500);
    }
}
