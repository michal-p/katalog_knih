<?php

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

$router = new Router();

// 3. Define the application routes
// Memory optimization: Instead of passing "new BookController()", we pass its class name as string.
// The Router will instantiate it only if the requested URL matches '/'.
$router->get('/', [BookController::class, 'index']);

// 4. Dispatch the request (match the URL and execute the code)
$router->dispatch();
