<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Core\Router;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testGetRouteIsAddedCorrectly()
    {
        $action = function() { return 'test'; };
        $this->router->get('/test', $action, true);

        $routes = $this->getRoutes();

        $this->assertArrayHasKey('GET', $routes);
        $this->assertArrayHasKey('/test', $routes['GET']);
        $this->assertEquals($action, $routes['GET']['/test']['action']);
        $this->assertTrue($routes['GET']['/test']['isPublic']);
    }

    public function testPostRouteIsAddedCorrectly()
    {
        $action = ['TestController', 'testAction'];
        $this->router->post('/submit', $action, false);

        $routes = $this->getRoutes();

        $this->assertArrayHasKey('POST', $routes);
        $this->assertArrayHasKey('/submit', $routes['POST']);
        $this->assertEquals($action, $routes['POST']['/submit']['action']);
        $this->assertFalse($routes['POST']['/submit']['isPublic']);
    }

    public function testDispatchExecutesAction()
    {
        $_SERVER['REQUEST_URI'] = '/test-route';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        $executed = false;
        $this->router->get('/test-route', function() use (&$executed) {
            $executed = true;
        }, true); // true = isPublic

        $this->router->dispatch();
        
        $this->assertTrue($executed, 'Router should execute the action for the matched route.');
    }

    public function testDispatchMatchesDynamicRouteAndPassesParameters()
    {
        $_SERVER['REQUEST_URI'] = '/item/42/edit';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        $capturedId = null;
        $this->router->get('/item/{id}/edit', function($id) use (&$capturedId) {
            $capturedId = $id;
        }, true); // isPublic = true

        $this->router->dispatch();
        
        $this->assertEquals('42', $capturedId, 'Router should extract dynamic parameters and pass them to the action.');
    }

    /**
     * Helper method to retrieve the protected $routes property from Router.
     */
    private function getRoutes(): array
    {
        $reflection = new \ReflectionClass(Router::class);
        $property = $reflection->getProperty('routes');
        return $property->getValue($this->router);
    }
}
