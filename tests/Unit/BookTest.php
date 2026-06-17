<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Book;

class BookTest extends TestCase
{
    public function test_validate_passes_with_valid_data()
    {
        $data = [
            'title' => 'The Lord of the Rings',
            'author' => 'J.R.R. Tolkien',
            'year' => '1954',
            'rating' => '10'
        ];

        $errors = Book::validate($data);

        $this->assertEmpty($errors);
    }

    public function test_validate_fails_with_missing_required_fields()
    {
        $data = [];

        $errors = Book::validate($data);

        $this->assertCount(3, $errors);
        $this->assertContains('Názov knihy je povinný.', $errors);
        $this->assertContains('Autor je povinný.', $errors);
        $this->assertContains('Rok vydania musí byť platné 4-miestne číslo.', $errors);
    }

    public function test_validate_fails_with_invalid_year_format()
    {
        // Letters instead of numbers
        $data = ['title' => 'A', 'author' => 'B', 'year' => 'abcd'];
        $errors = Book::validate($data);
        $this->assertContains('Rok vydania musí byť platné 4-miestne číslo.', $errors);

        // Less than 4 digits
        $data = ['title' => 'A', 'author' => 'B', 'year' => '999'];
        $errors = Book::validate($data);
        $this->assertContains('Rok vydania musí byť platné 4-miestne číslo.', $errors);

        // Floats
        $data = ['title' => 'A', 'author' => 'B', 'year' => '20.5'];
        $errors = Book::validate($data);
        $this->assertContains('Rok vydania musí byť platné 4-miestne číslo.', $errors);
    }

    public function test_validate_fails_with_year_out_of_range()
    {
        // Too far in the past
        $data = ['title' => 'A', 'author' => 'B', 'year' => '0999'];
        $errors = Book::validate($data);
        $this->assertContains(sprintf('Rok vydania musí byť v rozsahu 1000 – %d.', (int)date('Y') + 1), $errors);

        // Far future
        $data = ['title' => 'A', 'author' => 'B', 'year' => '3000'];
        $errors = Book::validate($data);
        $this->assertContains(sprintf('Rok vydania musí byť v rozsahu 1000 – %d.', (int)date('Y') + 1), $errors);
    }

    public function test_validate_fails_with_invalid_rating()
    {
        $baseData = ['title' => 'A', 'author' => 'B', 'year' => '2000'];

        // Float instead of int
        $data = array_merge($baseData, ['rating' => '5.5']);
        $errors = Book::validate($data);
        $this->assertContains('Hodnotenie musí byť celé číslo od 1 do 10.', $errors);

        // Zero
        $data = array_merge($baseData, ['rating' => '0']);
        $errors = Book::validate($data);
        $this->assertContains('Hodnotenie musí byť celé číslo od 1 do 10.', $errors);

        // Out of range > 10
        $data = array_merge($baseData, ['rating' => '11']);
        $errors = Book::validate($data);
        $this->assertContains('Hodnotenie musí byť celé číslo od 1 do 10.', $errors);
        
        // Empty rating is allowed (optional field)
        $data = array_merge($baseData, ['rating' => '']);
        $errors = Book::validate($data);
        $this->assertEmpty($errors);
    }
}
