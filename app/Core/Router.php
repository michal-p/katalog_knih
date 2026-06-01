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
            $this->executeAction($this->routes[$method][$uri]);
            return;
        }

        // Return a 404 response if no matching route is found
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1><p>Sorry, the requested route does not exist.</p>";
    }

    /**
     * Execute the matched route action (either a simple callback or a controller method).
     *
     * @param callable|array $action
     */
    protected function executeAction(callable|array $action): void
    {
        // If the action is a simple callback function (e.g., closure), execute it
        if (is_callable($action)) {
            call_user_func($action);
            return;
        }

        // Handle controller class strings: [BookController::class, 'index']
        if (is_array($action)) {
            [$class, $controllerMethod] = $action;
            if (class_exists($class)) {
                $controller = new $class();
                if (method_exists($controller, $controllerMethod)) {
                    call_user_func([$controller, $controllerMethod]);
                    return;
                }
            }
        }
    }
}
