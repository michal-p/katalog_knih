<?php

namespace App\Controllers;

use App\Models\Book;
use App\Core\View;

class BookController
{
    /**
     * Display the list of all books (Homepage).
     */
    public function index(): void
    {
        // 1. Fetch data from the database using the Model
        $books = Book::getAll();

        // 2. Pass the data to the View to render the HTML
        View::render('books/index', [
            'books' => $books
        ]);
    }
}
