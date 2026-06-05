<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Model representing the users table. Handles user lookup for authentication.
 */
class User
{
    /**
     * Find a user by their username.
     *
     * @param string $username The username to search for
     * @return array|null The user record as an associative array, or null if not found
     */
    public static function findByUsername(string $username): ?array
    {
        $db = Database::getConnection();

        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
