<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Book;
use App\Core\Security;
use App\Core\Database;

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
        // CSRF Protection validation
        Security::verifyCsrf();

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
            // Validate fields using the unified Book::validate method
            $validationErrors = Book::validate($bookData);

            if (!empty($validationErrors)) {
                $errors[] = "Záznam #" . ($index + 1) . ": neplatné údaje (" . implode(', ', $validationErrors) . ").";
                $skipped++;
                continue;
            }

            try {
                $success = Book::create($bookData);

                if ($success) {
                    $imported++;
                } else {
                    $errors[] = "Záznam '{$bookData['title']}': nepodarilo sa uložiť (neznáma chyba).";
                    $skipped++;
                }
            } catch (\PDOException $e) {
                if (Database::isDuplicateEntry($e)) {
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
