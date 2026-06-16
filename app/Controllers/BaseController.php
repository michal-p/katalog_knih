<?php

namespace App\Controllers;

use App\Core\View;

abstract class BaseController
{
    /**
     * Render a view template.
     *
     * @param string $view Path to the view file (e.g. 'admin/books/index')
     * @param array $data Data to extract into variables for the view
     */
    protected function render(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    /**
     * Render an error page and stop execution.
     *
     * @param int $code HTTP status code (e.g. 404, 500)
     */
    protected function renderError(int $code): void
    {
        View::renderError($code);
        exit;
    }

    /**
     * Redirect to a specific URL and stop execution.
     *
     * @param string $url The URL to redirect to
     */
    protected function redirect(string $url): void
    {
        // Sanitize the URL to prevent Header Injection and HTTP Response Splitting attacks.
        // It strips control characters like newlines (\r, \n) which could manipulate HTTP headers.
        $sanitizedUrl = filter_var($url, FILTER_SANITIZE_URL);
        
        header("Location: $sanitizedUrl");
        
        // Always call exit/die after redirection. Sending the 'Location' header only instructs
        // the browser to redirect, but does NOT stop PHP from executing the remaining code.
        exit;
    }
}
