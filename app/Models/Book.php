<?php

namespace App\Models;

use App\Core\Database;

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
            'title'      => $data['title'],
            'author'     => $data['author'],
            'year'       => (int) $data['year'],
            'annotation' => $data['annotation'] ?: null,
            'rating'     => $data['rating'] !== '' ? (int) $data['rating'] : null,
        ]);
    }
}
