# 🧪 Testing Guide (PHPUnit)

This document serves as an overview and guide for testing in the E-Book Catalog project. It describes the test architecture, configuration, and the process of running tests in the Docker environment.

---

## 🛠️ 1. Testing Architecture

We use **PHPUnit 11** for testing, combined with **Composer PSR-4 autoloading** (which replaced the original ad-hoc autoloader).

We have implemented unit tests that verify the correctness of isolated parts of the system without requiring a live database connection.

### Tested Components:
1. **[SecurityTest.php](file:///Users/michalpuchy/Workspace/katalog_knih/tests/Unit/SecurityTest.php)**
   - Verifies the generation and validation of CSRF (Cross-Site Request Forgery) tokens that protect forms from abuse.
2. **[RouterTest.php](file:///Users/michalpuchy/Workspace/katalog_knih/tests/Unit/RouterTest.php)**
   - Tests URL routing, route registration (GET, POST), and correct parameter handling (e.g., book ID parsing).
3. **[AuthTest.php](file:///Users/michalpuchy/Workspace/katalog_knih/tests/Unit/AuthTest.php)**
   - Verifies user login, session management (`$_SESSION`), password verification, and logged-in state checks.
4. **[ViewHelperTest.php](file:///Users/michalpuchy/Workspace/katalog_knih/tests/Unit/ViewHelperTest.php)**
   - Tests helper functions for templates, primarily `e()` (alias for `htmlspecialchars()`) used as protection against XSS (Cross-Site Scripting).

---

## ⚙️ 2. Test Configuration (`phpunit.xml`)

PHPUnit settings can be found in the root configuration file [phpunit.xml](file:///Users/michalpuchy/Workspace/katalog_knih/phpunit.xml). Key configurations include:
- Defining the test directory `<directory>tests/Unit</directory>`.
- Enabling colored output (`colors="true"`).
- Setting up the test cache directory (`.phpunit.cache`).

---

## 🚀 3. How to Run Tests

Since the entire application runs inside Docker, PHPUnit and all its dependencies are isolated within the web container. We trigger tests remotely from the host machine (your Mac):

### Run all tests:
```bash
docker exec -it ebook_web vendor/bin/phpunit
```

### Run a specific test suite or class:
If you want to run only one specific test file, you can specify the path to it:
```bash
docker exec -it ebook_web vendor/bin/phpunit tests/Unit/RouterTest.php
```

### Filter tests by name (runs only matching test methods):
```bash
docker exec -it ebook_web vendor/bin/phpunit --filter testRegisterGetRoute
```

---

## 💡 4. Important Technical Insights

Keep these specific behaviors in mind when writing or extending tests in this project:

### A. Process Isolation for Sessions and Headers
PHPUnit runs as a single CLI process. If a tested method manipulates sessions (`session_start()`, `session_destroy()`) or sends HTTP headers (e.g., redirecting via `header()`), PHPUnit will throw a "headers already sent" error.
- **Solution:** Add the `/** @runInSeparateProcess */` annotation above test methods that interact with sessions or headers (used in `AuthTest.php`). This tells PHPUnit to run that specific test in a separate PHP process.

### B. Execution Interruption via `exit` or `die`
If tested code calls `exit;` or `die;` directly upon failure, it will immediately terminate the entire PHPUnit process, causing the test to fail silently without output.
- **Solution:** We refactored our security methods (e.g., `Security::verifyCsrf`) to throw an `Exception` instead of calling `exit;` or `View::renderError(403)` directly. The exception is then caught in the global `index.php` try-catch block for production execution, and tested gracefully using `$this->expectException()` in PHPUnit.
