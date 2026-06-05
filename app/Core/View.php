<?php

namespace App\Core;

/**
 * Simple template rendering engine.
 * Supports layouts with content injection and variable extraction.
 */
class View
{
    /**
     * Render a view template and pass data to it.
     *
     * @param string $template Path to the view file relative to the views directory (e.g., 'books/index')
     * @param array $data Associative array of data to be extracted as variables
     * @param string $layout Name of the layout wrapper file (defaults to 'main')
     */
    public static function render(string $template, array $data = [], string $layout = 'main'): void
    {
        // The extract() function converts array keys into variables
        extract($data);

        // Build the absolute path to the inner view content
        $contentView = __DIR__ . '/../../views/' . $template . '.php';

        if (!file_exists($contentView)) {
            // We intentionally do NOT use renderError() here to prevent infinite recursion:
            // renderError() → render('errors/error') → template missing → renderError() → ...
            error_log("View template not found: [{$template}]");
            http_response_code(500);
            die('Požadovaná stránka sa nenašla.');
        }

        // Build the absolute path to the layout wrapper
        $layoutFile = __DIR__ . '/../../views/layouts/' . $layout . '.php';

        if (file_exists($layoutFile)) {
            // The layout file will include the $contentView inside its body
            require $layoutFile;
        } else {
            // Fallback if no layout exists
            require $contentView;
        }
    }

    /**
     * Render an error page with the correct HTTP status code.
     * Uses a single universal template (views/errors/error.php) with a lookup map for messages.
     *
     * @param int $code HTTP status code (e.g. 404, 500)
     */
    public static function renderError(int $code): void
    {
        $errors = [
            403 => ['title' => 'Prístup odmietnutý',    'message' => 'Neplatný CSRF token. Z bezpečnostných dôvodov bola požiadavka zamietnutá.'],
            404 => ['title' => 'Stránka sa nenašla',    'message' => 'Ľutujeme, ale stránka, ktorú hľadáte, neexistuje alebo bola presunutá.'],
            500 => ['title' => 'Chyba servera',         'message' => 'Nastala neočakávaná chyba. Skúste to znova neskôr.'],
        ];

        $default = ['title' => 'Chyba', 'message' => 'Nastala neočakávaná chyba.'];
        $error = $errors[$code] ?? $default;

        http_response_code($code);

        self::render('errors/error', [
            'code'    => $code,
            'title'   => $error['title'],
            'message' => $error['message'],
        ]);

        exit;
    }
}
