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

    /**
     * Helper method to retrieve the protected $routes property from Router.
     */
    private function getRoutes(): array
    {
        $reflection = new \ReflectionClass(Router::class);
        $property = $reflection->getProperty('routes');
        $property->setAccessible(true);
        return $property->getValue($this->router);
    }
}
