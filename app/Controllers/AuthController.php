<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Core\Security;
use App\Core\Auth;

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
        if (Auth::check()) {
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
        if (Auth::check()) {
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
                // Log the user in
                Auth::login($user);

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
        // Log the user out
        Auth::logout();

        // Redirect to login page
        header('Location: /login');
        exit;
    }
}
