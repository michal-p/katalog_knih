<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    /**
     * Store the single active PDO connection instance.
     * The nullable type (?PDO) means it can be a PDO object or null.
     */
    private static ?PDO $connection = null;

    /**
     * Private constructor to prevent creating multiple instances via 'new'.
     */
    private function __construct() {}

    /**
     * Private clone method to prevent cloning the instance.
     */
    private function __clone() {}

    /**
     * Get the database connection. Creates it if it doesn't exist yet.
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            
            // Load database configuration
            $config = require __DIR__ . '/../../config/database.php';

            // Create the Data Source Name (DSN) string
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

            try {
                // Initialize the PDO connection
                self::$connection = new PDO($dsn, $config['user'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
                    PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements for security against SQL Injection
                ]);
            } catch (PDOException $e) {
                // Terminate application if connection fails
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
