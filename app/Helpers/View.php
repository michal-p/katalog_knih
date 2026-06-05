<?php

namespace App\Helpers;

use App\Core\Security;

/**
 * Provides helpful methods for formatting data in HTML views.
 */
class View
{
    /**
     * Determine if a navigation link should have the 'active' class.
     *
     * @param string $path The expected URL path (e.g., '/')
     * @param bool $exact If true, the match must be exact. If false, it acts as a prefix.
     * @return string Returns 'active' if matched, otherwise an empty string.
     */
    public static function activeLinkCssClass(string $path, bool $exact = false): string
    {
        $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

        if ($exact) {
            return $currentUri === $path ? 'active' : '';
        }

        return str_starts_with($currentUri, $path) ? 'active' : '';
    }

    /**
     * Generate a cache-busted URL for a public asset.
     *
     * @param string $path Relative path from the public directory (e.g., 'assets/css/app.css')
     * @return string The public URL with a version query parameter
     */
    public static function asset(string $path): string
    {
        $absolutePath = __DIR__ . '/../../public/' . ltrim($path, '/');
        $version = '';

        if (file_exists($absolutePath)) {
            $version = '?v=' . filemtime($absolutePath);
        }

        return '/' . ltrim($path, '/') . $version;
    }

    /**
     * Generate a hidden HTML input field containing the CSRF token.
     *
     * @return string
     */
    public static function csrfField(): string
    {
        $token = Security::csrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
