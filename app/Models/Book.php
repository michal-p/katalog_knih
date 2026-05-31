<?php

namespace App\Models;

use App\Core\Database;
use PDO;

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
}
