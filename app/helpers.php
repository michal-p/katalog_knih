<?php

/**
 * Determine if a navigation link should have the 'active' class.
 *
 * @param string $path The expected URL path (e.g., '/')
 * @param bool $exact If true, the match must be exact. If false, it acts as a prefix.
 * @return string Returns 'active' if matched, otherwise an empty string.
 */
function activeLinkCssClass(string $path, bool $exact = false): string
{
    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    // Why do we need the $exact parameter?
    // The home page path is '/'. If we used str_starts_with() for the home page,
    // EVERY single URL (like '/admin') would match, because every URL starts with '/'.
    // Therefore, for the home page, we MUST require an exact match.
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
function asset(string $path): string
{
    $absolutePath = __DIR__ . '/../public/' . ltrim($path, '/');
    $version = '';

    if (file_exists($absolutePath)) {
        $version = '?v=' . filemtime($absolutePath);
    }

    return '/' . ltrim($path, '/') . $version;
}

/**
 * Check if the user is currently authenticated as admin.
 *
 * @return bool
 */
function isAuthenticated(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Enforce authentication. Redirects to login page if user is not authenticated.
 */
function requireAuth(): void
{
    if (!isAuthenticated()) {
        header('Location: /login');
        exit;
    }
}

