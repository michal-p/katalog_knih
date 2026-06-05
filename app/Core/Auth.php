<?php

namespace App\Core;

/**
 * Handles application-wide authentication checks.
 */
class Auth
{
    /**
     * Check if the user is currently authenticated.
     *
     * @return bool
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Enforce authentication. Redirects to login page if user is not authenticated.
     */
    public static function require(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Log a user in.
     *
     * @param array $user
     */
    public static function login(array $user): void
    {
        // Regenerate session ID to prevent Session Fixation attacks
        session_regenerate_id(true);

        // Regenerate CSRF token to prevent CSRF token fixation
        unset($_SESSION['csrf_token']);

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
    }

    /**
     * Log the current user out.
     */
    public static function logout(): void
    {
        // Clear all session variables
        $_SESSION = [];

        // Destroy the session cookie in the browser
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destroy the session on the server
        session_destroy();
    }
}
