<?php

namespace App\Core;

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
            die("View template not found: [{$template}]");
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
}
