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

// 2. Initialize the application Router
use App\Core\Router;
$router = new Router();

// 3. Define the application routes
// For now, we only setup simple test pages
$router->get('/', function() {
    echo "<h1>Welcome to E-book Catalog!</h1>";
    echo "<p>Our Router is working successfully! Everything is routed through public/index.php.</p>";
});

$router->get('/test', function() {
    echo "<h1>Test Page</h1>";
});

// 4. Dispatch the request (match the URL and execute the code)
$router->dispatch();
