<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Book;
use App\Core\Security;

/**
 * Handles admin CRUD operations for books: listing, creating, and storing.
 */
class AdminBookController
{
    /**
     * Show the admin dashboard with a list of books.
     */
    public function index(): void
    {
        $books = Book::getAll();

        View::render('admin/books/index', [
            'books' => $books
        ]);
    }

    /**
     * Show the form to add a new book.
     */
    public function create(): void
    {
        View::render('admin/books/create');
    }

    /**
     * Process the form submission to store a new book.
     */
    public function store(): void
    {
        // CSRF Protection validation
        Security::verifyCsrf();

        // 1. Sanitize and retrieve input
        $title      = trim($_POST['title'] ?? '');
        $author     = trim($_POST['author'] ?? '');
        $year       = trim($_POST['year'] ?? '');
        $annotation = trim($_POST['annotation'] ?? '');
        $rating     = trim($_POST['rating'] ?? '');

        $errors = [];

        // 2. Server-side Validation
        if (empty($title)) {
            $errors[] = 'Názov knihy je povinný.';
        }
        if (empty($author)) {
            $errors[] = 'Autor je povinný.';
        }
        if (empty($year) || !is_numeric($year) || strlen($year) !== 4) {
            $errors[] = 'Rok vydania musí byť platné 4-miestne číslo.';
        }
        if ($rating !== '' && (!is_numeric($rating) || $rating < 1 || $rating > 10)) {
            $errors[] = 'Hodnotenie musí byť číslo od 1 do 10.';
        }

        // 3. If there are errors, show the form again with errors and old input
        if (!empty($errors)) {
            View::render('admin/books/create', [
                'errors' => $errors,
                'old'    => $_POST
            ]);
            return;
        }

        // 4. Save to database
        try {
            $success = Book::create([
                'title'      => $title,
                'author'     => $author,
                'year'       => $year,
                'annotation' => $annotation,
                'rating'     => $rating,
            ]);

            if ($success) {
                // Redirect to prevent form resubmission (PRG pattern) and display a success message
                header('Location: /admin/books?success=1');
                exit;
            } else {
                $errors[] = 'Nepodarilo sa uložiť knihu do databázy.';
            }
        } catch (\PDOException $e) {
            // 1062 is the MySQL error code for Duplicate Entry (Unique Constraint violation)
            if ($e->errorInfo[1] === 1062) {
                $errors[] = 'Táto kniha od tohto autora už v databáze existuje.';
            } else {
                // Log the full error details for the developer (visible via: docker logs -f ebook_web)
                error_log("Database error in AdminBookController::store(): " . $e->getMessage());
                $errors[] = 'Nastala neočakávaná chyba pri ukladaní. Skúste to znova neskôr.';
            }
        }

        // If we reach here, it means save failed, so render the form with errors
        View::render('admin/books/create', [
            'errors' => $errors,
            'old'    => $_POST
        ]);
    }
}
