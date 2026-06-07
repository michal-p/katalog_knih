<?php

namespace App\Core;

/**
 * Handles security mechanisms like CSRF protection.
 */
class Security
{
    /**
     * Generate and return a CSRF token. Stores it in the session if not present.
     *
     * @return string
     */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify that the CSRF token from the POST request matches the session token.
     * Exits with a 403 Forbidden error if validation fails.
     */
    public static function verifyCsrf(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $sessionToken = $_SESSION['csrf_token'] ?? '';
        $postToken = $_POST['csrf_token'] ?? '';

        if (empty($sessionToken) || empty($postToken) || !hash_equals($sessionToken, $postToken)) {
            throw new \Exception('Invalid CSRF token', 403);
        }
    }
}
