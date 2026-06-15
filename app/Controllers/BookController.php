<?php

namespace App\Controllers;

use App\Models\Book;

/**
 * Handles public-facing book pages: listing all books and showing individual book details.
 */
class BookController extends BaseController
{
    /**
     * Display the list of all books (Homepage).
     */
    public function index(): void
    {
        // 1. Fetch data from the database using the Model
        $books = Book::getAll();

        $this->render('books/index', [
            'books' => $books
        ]);
    }

    /**
     * Display the details of a single book.
     * 
     * @param string $id The book ID from the URL (e.g. /books/5)
     */
    public function show(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->renderError(404);
        }

        $book = Book::getById((int) $id);

        if (!$book) {
            $this->renderError(404);
        }

        $this->render('books/show', [
            'book' => $book
        ]);
    }
}
