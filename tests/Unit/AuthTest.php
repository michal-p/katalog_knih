<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Core\Auth;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        // Prepare empty environment variables for testing
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
    }

    public function testCheckReturnsFalseWhenUserIsNotLoggedIn()
    {
        $this->assertFalse(Auth::check(), 'Auth::check() should return false when session does not contain user_id.');
    }

    public function testCheckReturnsTrueWhenUserIsLoggedIn()
    {
        $_SESSION['user_id'] = 1;
        $this->assertTrue(Auth::check(), 'Auth::check() should return true when user_id is in session.');
    }

    /**
     * Test that login sets session variables and unsets the CSRF token to prevent session fixation.
     * The token is not regenerated immediately during login, but rather lazily when next requested.
     *
     * @runInSeparateProcess
     */
    public function testLoginSetsSessionVariablesAndClearsCsrfToken()
    {
        // Simulate the state before logging in
        $_SESSION['csrf_token'] = 'old_token';
        
        $user = [
            'id' => 123,
            'username' => 'testuser'
        ];

        $oldSessionId = session_id();

        Auth::login($user);

        $newSessionId = session_id();
        $this->assertNotEquals($oldSessionId, $newSessionId, 'session_regenerate_id() should have been called to prevent session fixation.');

        $this->assertEquals(123, $_SESSION['user_id']);
        $this->assertEquals('testuser', $_SESSION['username']);
        $this->assertArrayNotHasKey('csrf_token', $_SESSION, 'Old CSRF token should be cleared (session fixation protection).');
    }

    /**
     * @runInSeparateProcess
     */
    public function testLogoutClearsSession()
    {
        // Simulate a logged-in user
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'admin';

        Auth::logout();

        $this->assertEmpty($_SESSION, 'All session variables should be cleared after logout.');
        $this->assertEquals(PHP_SESSION_NONE, session_status(), 'Session should be closed (PHP_SESSION_NONE) after session_destroy() is called.');
        
        // Ensure that headers were sent for the cookie destruction (PHPUnit can check this if we suppress the error)
        // Since we are in CLI, headers might not be actually sent, but we can verify the session is empty.
    }
}
