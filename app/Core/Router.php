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
    public function get(string $uri, callable|array $action, bool $isPublic = false): void
    {
        $this->addRoute('GET', $uri, $action, $isPublic);
    }

    /**
     * Register a POST route (e.g., submitting a form to add a new book).
     */
    public function post(string $uri, callable|array $action, bool $isPublic = false): void
    {
        $this->addRoute('POST', $uri, $action, $isPublic);
    }

    /**
     * Helper method to add a route to the internal route collection.
     *
     * @param string $method The HTTP method ('GET' or 'POST')
     * @param string $uri The URI pattern (e.g., '/books')
     * @param callable|array $action The action to execute
     * @param bool $isPublic Whether the route requires authentication (default: false)
     */
    protected function addRoute(string $method, string $uri, callable|array $action, bool $isPublic): void
    {
        $this->routes[$method][$uri] = [
            'action' => $action,
            'isPublic' => $isPublic
        ];
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

        // 1. Direct exact match (e.g., /admin/books)
        if (isset($this->routes[$method][$uri])) {
            $routeData = $this->routes[$method][$uri];
            
            // Secure by default: check authentication if the route is not explicitly public
            if (!$routeData['isPublic']) {
                requireAuth();
            }
            
            $this->executeAction($routeData['action']);
            return;
        }

        // 2. Dynamic route match (e.g., /books/{id})
        if ($this->matchDynamicRoute($uri, $method)) {
            return;
        }
        // Return a 404 response if no matching route is found
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1><p>Sorry, the requested route does not exist.</p>";
    }

    /**
     * Try to match the URI against registered dynamic routes with placeholders.
     *
     * Converts route pattern placeholders (like {id} or {category}) into regular expression
     * capturing groups, extracts their values from the requested URI, and executes the action.
     *
     * Example URLs that this supports:
     * - Pattern: /books/{id} matches /books/42 (params: ['42'])
     * - Pattern: /books/{category}/{id} matches /books/scifi/15 (params: ['scifi', '15'])
     *
     * @param string $uri The requested URL path (e.g., '/books/42')
     * @param string $method The HTTP request method ('GET' or 'POST')
     * @return bool True if a matching route was found and executed, false otherwise
     */
    protected function matchDynamicRoute(string $uri, string $method): bool
    {
        if (!isset($this->routes[$method])) {
            return false;
        }

        foreach ($this->routes[$method] as $routePattern => $routeData) {
            // Convert placeholders like {id} into regular expression capture groups: ([^/]+)
            $regex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $routePattern);
            
            if (preg_match('#^' . $regex . '$#', $uri, $matches)) {
                // 1. Verify security first (Fail-fast principle)
                // Secure by default: check authentication if the route is not explicitly public
                if (!$routeData['isPublic']) {
                    requireAuth();
                }

                // 2. Only after access is confirmed, process data for the Controller
                // Remove the full string match, keeping only the captured parameters
                array_shift($matches);
                
                // Execute the action and pass the captured parameters
                $this->executeAction($routeData['action'], $matches);
                return true;
            }
        }

        return false;
    }

    /**
     * Execute the matched route action (either a simple callback or a controller method).
     *
     * @param callable|array $action
     * @param array $params Dynamic parameters extracted from the URL
     */
    protected function executeAction(callable|array $action, array $params = []): void
    {
        // If the action is a simple callback function (e.g., closure), execute it
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }

        // Handle controller class strings: [BookController::class, 'index']
        if (is_array($action)) {
            [$class, $controllerMethod] = $action;
            if (class_exists($class)) {
                $controller = new $class();
                if (method_exists($controller, $controllerMethod)) {
                    call_user_func_array([$controller, $controllerMethod], $params);
                    return;
                }
            }
        }
    }
}
