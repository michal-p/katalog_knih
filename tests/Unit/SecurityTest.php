<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Core\Security;

class SecurityTest extends TestCase
{
    protected function setUp(): void
    {
        // Mock session for testing
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        unset($_SERVER['REQUEST_METHOD']);
        unset($_POST['csrf_token']);
        $_SESSION = [];
    }

    public function testCsrfTokenIsGeneratedAndStoredInSession()
    {
        $token = Security::csrfToken();
        
        $this->assertNotEmpty($token);
        $this->assertEquals(64, strlen($token), 'CSRF token should be 64 characters long (hex string of 32 bytes)');
        $this->assertArrayHasKey('csrf_token', $_SESSION);
        $this->assertEquals($token, $_SESSION['csrf_token']);
    }

    public function testVerifyCsrfPassesForValidToken()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $token = Security::csrfToken();
        $_POST['csrf_token'] = $token;

        // If it doesn't throw an exception, the test passes
        Security::verifyCsrf();
        $this->assertTrue(true);
    }

    public function testVerifyCsrfThrowsExceptionForInvalidToken()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['csrf_token'] = 'valid_token_123';
        $_POST['csrf_token'] = 'invalid_token_456';

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(403);
        $this->expectExceptionMessage('Invalid CSRF token');

        Security::verifyCsrf();
    }
}
