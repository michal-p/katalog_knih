<?php

namespace App\Models;

use App\Core\Database;

/**
 * Model representing the books table. Provides static methods for CRUD operations.
 */
class Book
{
    /**
     * Get all books from the database.
     */
    public static function getAll(): array
    {
        $db = Database::getConnection();
        
        // Prepare and execute the SQL query to fetch all books
        $stmt = $db->query("SELECT * FROM books ORDER BY id DESC");
        
        // Fetch and return the results as an array of associative arrays
        return $stmt->fetchAll();
    }

    /**
     * Create a new book record in the database.
     * 
     * @param array $data Associative array with keys: title, author, year, annotation, rating
     * @return bool True on success, false on failure
     */
    public static function create(array $data): bool
    {
        $db = Database::getConnection();
        
        $sql = "INSERT INTO books (title, author, year, annotation, rating) 
                VALUES (:title, :author, :year, :annotation, :rating)";
                
        $stmt = $db->prepare($sql);
        
        return $stmt->execute([
            'title'      => trim($data['title'] ?? ''),
            'author'     => trim($data['author'] ?? ''),
            'year'       => (int) trim($data['year'] ?? ''),
            'annotation' => trim($data['annotation'] ?? '') ?: null,
            'rating'     => trim($data['rating'] ?? '') !== '' ? (int) trim($data['rating'] ?? '') : null,
        ]);
    }

    /**
     * Get a specific book by its ID.
     * 
     * @param int $id The book ID
     * @return array|null The book data as an array, or null if not found
     */
    public static function getById(int $id): ?array
    {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("SELECT * FROM books WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        
        $book = $stmt->fetch();
        
        return $book ?: null;
    }

    /**
     * Validate book data.
     * 
     * @param array $data Raw input data
     * @return array Array of validation error messages (empty if valid)
     */
    public static function validate(array $data): array
    {
        $errors = [];
        
        $title = trim($data['title'] ?? '');
        $author = trim($data['author'] ?? '');
        $year = trim($data['year'] ?? '');
        $rating = trim($data['rating'] ?? '');

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

        return $errors;
    }
}
