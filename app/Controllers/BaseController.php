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
     * Render an error page.
     *
     * @param int $code HTTP status code (e.g. 404, 500)
     */
    protected function renderError(int $code): void
    {
        View::renderError($code);
    }

    /**
     * Redirect to a specific URL and stop execution.
     *
     * @param string $url The URL to redirect to
     */
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
