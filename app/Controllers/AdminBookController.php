<?php

namespace App\Controllers;

use App\Models\Book;
use App\Core\Security;
use App\Core\Database;

/**
 * Handles admin CRUD operations for books: listing, creating, and storing.
 */
class AdminBookController extends BaseController
{
    /**
     * Show the admin dashboard with a list of books.
     */
    public function index(): void
    {
        $books = Book::getAll();

        $this->render('admin/books/index', [
            'books' => $books
        ]);
    }

    /**
     * Show the form to add a new book.
     */
    public function create(): void
    {
        $this->render('admin/books/create');
    }

    /**
     * Process the form submission to store a new book.
     */
    public function store(): void
    {
        // CSRF Protection validation
        Security::verifyCsrf();

        // 1. Server-side Validation
        $errors = Book::validate($_POST);

        // 2. If there are errors, show the form again with errors and old input
        if (!empty($errors)) {
            $this->render('admin/books/create', [
                'errors' => $errors,
                'old'    => $_POST
            ]);
            return;
        }

        // 3. Save to database
        try {
            $success = Book::create($_POST);

            if ($success) {
                // Redirect to prevent form resubmission (PRG pattern) and display a success message
                $this->redirect('/admin/books?success=1');
            } else {
                $errors[] = 'Nepodarilo sa uložiť knihu do databázy.';
            }
        } catch (\PDOException $e) {
            if (Database::isDuplicateEntry($e)) {
                $errors[] = 'Táto kniha od tohto autora už v databáze existuje.';
            } else {
                // Log the full error details for the developer (visible via: docker logs -f ebook_web)
                error_log("Database error in AdminBookController::store(): " . $e->getMessage());
                $errors[] = 'Nastala neočakávaná chyba pri ukladaní. Skúste to znova neskôr.';
            }
        }

        // If we reach here, it means save failed, so render the form with errors
        $this->render('admin/books/create', [
            'errors' => $errors,
            'old'    => $_POST
        ]);
    }
}
