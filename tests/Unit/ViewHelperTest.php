<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\View;

class ViewHelperTest extends TestCase
{
    public function testActiveLinkCssClassReturnsActiveForExactMatch()
    {
        $_SERVER['REQUEST_URI'] = '/admin/books';

        $this->assertEquals('active', View::activeLinkCssClass('/admin/books', true));
        $this->assertEquals('', View::activeLinkCssClass('/admin', true));
    }

    public function testActiveLinkCssClassReturnsActiveForPrefixMatch()
    {
        $_SERVER['REQUEST_URI'] = '/admin/books/create';

        // Since /admin/books is a prefix of /admin/books/create, it should return 'active' (when exact = false)
        $this->assertEquals('active', View::activeLinkCssClass('/admin/books', false));
        $this->assertEquals('', View::activeLinkCssClass('/login', false));
    }

    public function testCsrfFieldGeneratesValidHtmlInput()
    {
        // Run CSRF field generation
        $html = View::csrfField();

        $this->assertStringContainsString('<input type="hidden" name="csrf_token"', $html);
        $this->assertStringContainsString('value="' . $_SESSION['csrf_token'] . '"', $html);
        
        // Ensure that the token was also saved to the session
        $this->assertArrayHasKey('csrf_token', $_SESSION);
    }
}
