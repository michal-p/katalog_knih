<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Book;

/**
 * Handles bulk import of books from the JSON seed file (database/seed/books.json).
 */
class ImportController
{
    /**
     * Path to the JSON seed file, relative to project root.
     */
    private const SEED_FILE = __DIR__ . '/../../database/seed/books.json';

    /**
     * Process the JSON import and redirect back with results.
     */
    public function import(): void
    {
        // 1. Check if the seed file exists
        if (!file_exists(self::SEED_FILE)) {
            header('Location: /admin/books?import_error=' . urlencode('Súbor books.json nebol nájdený.'));
            exit;
        }

        // 2. Read and decode the JSON file
        $json = file_get_contents(self::SEED_FILE);

        if ($json === false) {
            header('Location: /admin/books?import_error=' . urlencode('Nepodarilo sa prečítať súbor books.json.'));
            exit;
        }

        $books = json_decode($json, true);

        // json_decode returns null if the JSON is malformed
        if ($books === null) {
            header('Location: /admin/books?import_error=' . urlencode('Súbor books.json obsahuje neplatný JSON.'));
            exit;
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        // 3. Iterate over each book and attempt to insert it
        foreach ($books as $index => $bookData) {
            // Validate required fields
            if (empty($bookData['title']) || empty($bookData['author']) || empty($bookData['year'])) {
                $errors[] = "Záznam #" . ($index + 1) . ": chýbajú povinné polia (title, author, year).";
                $skipped++;
                continue;
            }

            try {
                $success = Book::create([
                    'title'      => $bookData['title'],
                    'author'     => $bookData['author'],
                    'year'       => $bookData['year'],
                    'annotation' => $bookData['annotation'] ?? '',
                    'rating'     => $bookData['rating'] ?? '',
                ]);

                if ($success) {
                    $imported++;
                }
            } catch (\PDOException $e) {
                if ($e->errorInfo[1] === 1062) {
                    // Duplicate entry — book already exists, skip silently
                    $skipped++;
                } else {
                    error_log("Import error for book '{$bookData['title']}': " . $e->getMessage());
                    $errors[] = "Záznam '{$bookData['title']}': databázová chyba.";
                    $skipped++;
                }
            }
        }

        // 4. Save errors to session if any occurred to display them after redirect
        if (!empty($errors)) {
            $_SESSION['import_errors'] = $errors;
        }

        // 5. Redirect back with import results via query parameters (PRG pattern)
        $params = http_build_query([
            'imported' => $imported,
            'skipped'  => $skipped,
        ]);

        header('Location: /admin/books?' . $params);
        exit;
    }
}
