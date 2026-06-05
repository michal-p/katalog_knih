<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Book;

/**
 * Handles public-facing book pages: listing all books and showing individual book details.
 */
class BookController
{
    /**
     * Display the list of all books (Homepage).
     */
    public function index(): void
    {
        // 1. Fetch data from the database using the Model
        $books = Book::getAll();

        View::render('books/index', [
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
        $book = Book::getById((int) $id);

        if (!$book) {
            http_response_code(404);
            echo "<h1>404 - Kniha sa nenašla</h1><p>Ľutujeme, ale hľadaná kniha neexistuje.</p>";
            exit;
        }

        View::render('books/show', [
            'book' => $book
        ]);
    }
}
