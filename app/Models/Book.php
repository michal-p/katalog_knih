<?php

namespace App\Models;

use App\Core\Database;

/**
 * Model representing the books table. Provides static methods for CRUD operations.
 */
class Book
{
    /**
     * The fields that are allowed to be mass-assigned.
     * Any key in the input array that is NOT listed here will be silently ignored.
     */
    private const FILLABLE = ['title', 'author', 'year', 'annotation', 'rating'];

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
     * Only fields listed in FILLABLE are accepted — any extra keys in $data
     * are silently discarded here, so callers do not need their own whitelist.
     *
     * @param array $data Raw input array (e.g. $_POST)
     * @return bool True on success, false on failure
     */
    public static function create(array $data): bool
    {
        $db = Database::getConnection();

        // Discard any keys that are not in the allowed list
        $filtered = array_intersect_key($data, array_flip(self::FILLABLE));

        $sql = "INSERT INTO books (title, author, year, annotation, rating)
                VALUES (:title, :author, :year, :annotation, :rating)";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            'title'      => trim($filtered['title']      ?? ''),
            'author'     => trim($filtered['author']     ?? ''),
            'year'       => (int) trim($filtered['year'] ?? ''),
            'annotation' => trim($filtered['annotation'] ?? '') ?: null,
            'rating'     => trim($filtered['rating']     ?? '') !== ''
                                ? (int) trim($filtered['rating'] ?? '')
                                : null,
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
        if (empty($year) || !ctype_digit($year) || strlen($year) !== 4) {
            $errors[] = 'Rok vydania musí byť platné 4-miestne číslo.';
        } elseif ((int) $year < 1000 || (int) $year > 2099) {
            $errors[] = 'Rok vydania musí byť v rozsahu 1000 – 2099.';
        }
        if ($rating !== '' && (
            filter_var($rating, FILTER_VALIDATE_INT) === false
            || (int) $rating < 1
            || (int) $rating > 10
        )) {
            $errors[] = 'Hodnotenie musí byť celé číslo od 1 do 10.';
        }

        return $errors;
    }
}
