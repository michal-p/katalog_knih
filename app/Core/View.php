<?php

namespace App\Core;

class View
{
    /**
     * Render a view template and pass data to it.
     *
     * @param string $template Path to the view file relative to the views directory (e.g., 'books/index')
     * @param array $data Associative array of data to be extracted as variables
     */
    public static function render(string $template, array $data = []): void
    {
        // The extract() function converts array keys into variables
        // For example, ['books' => $booksArray] becomes a $books variable in the template
        extract($data);

        // Build the absolute path to the view file
        $file = __DIR__ . '/../../views/' . $template . '.php';

        if (file_exists($file)) {
            // Include the template file which will render the HTML
            require $file;
        } else {
            die("View template not found: [{$template}]");
        }
    }
}
