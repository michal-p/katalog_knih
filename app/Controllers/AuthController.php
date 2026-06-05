<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Core\Security;

/**
 * Handles user authentication: login form display, credential verification, and logout.
 */
class AuthController
{
    /**
     * Display the login form.
     */
    public function showLogin(): void
    {
        // If already logged in, redirect to admin area
        if (isset($_SESSION['user_id'])) {
            header('Location: /admin/books');
            exit;
        }

        View::render('admin/login');
    }

    /**
     * Process the login form submission.
     */
    public function processLogin(): void
    {
        // If already logged in, redirect to admin area
        if (isset($_SESSION['user_id'])) {
            header('Location: /admin/books');
            exit;
        }

        // CSRF Protection validation
        Security::verifyCsrf();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        // Basic server-side validation
        if (empty($username) || empty($password)) {
            $errors[] = 'Zadajte používateľské meno aj heslo.';
        } else {
            // Fetch user from DB via the User model (MVC pattern)
            $user = User::findByUsername($username);

            // Verify username and bcrypt hashed password
            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent Session Fixation attacks
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to admin dashboard
                header('Location: /admin/books');
                exit;
            } else {
                $errors[] = 'Nesprávne používateľské meno alebo heslo.';
            }
        }

        // Render login view again with errors and old input
        View::render('admin/login', [
            'errors' => $errors,
            'old' => [
                'username' => $username
            ]
        ]);
    }

    /**
     * Process the logout request.
     */
    public function logout(): void
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

        // Redirect to login page
        header('Location: /login');
        exit;
    }
}
