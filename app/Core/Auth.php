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
}
