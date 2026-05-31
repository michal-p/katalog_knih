<?php

namespace App\Core;

class Router
{
    /**
     * List of all registered routes and their associated actions/controllers.
     */
    protected array $routes = [];

    /**
     * Register a GET route (e.g., displaying a page or a list of books).
     */
    public function get(string $uri, callable|array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    /**
     * Register a POST route (e.g., submitting a form to add a new book).
     */
    public function post(string $uri, callable|array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    /**
     * Process the incoming request and route it to the correct action.
     */
    public function dispatch(): void
    {
        // Extract the URI path (ignoring query parameters like ?sort=asc)
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Get the HTTP request method (GET or POST)
        $method = $_SERVER['REQUEST_METHOD'];

        // Check if a matching route exists for the given method and URI
        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            
            // If the action is a simple callback function, execute it directly
            if (is_callable($action)) {
                call_user_func($action);
                return;
            }

            // Future logic for calling actual Controller classes (e.g., [BookController::class, 'index']) will go here
        }

        // Return a 404 response if no matching route is found
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1><p>Sorry, the requested route does not exist.</p>";
    }
}
