# E-Book Catalog

A simple web application for managing an e-book catalog. Built using clean PHP 8 (OOP) without frameworks, a MySQL database, and Docker environment.

## Requirements
- Docker and Docker Compose
- Node.js & NPM (for Webpack compilation)

## Installation & Setup

1. **Clone the repository**
   ```bash
   git clone <repository_url>
   cd katalog_knih
   ```

2. **Configure environment variables**
   Create a `.env` file from the provided template:
   ```bash
   cp .env.example .env
   ```
   *(The default settings inside `.env.example` are preconfigured and work out of the box with Docker. You can change them if needed).*

3. **Install frontend dependencies**
   ```bash
   npm install
   npm run build
   ```

4. **Start the environment via Docker**
   ```bash
   docker-compose up -d
   ```

The application will be available at [http://localhost:8080](http://localhost:8080).
The database runs on port `3307` and automatically imports the schema from `database/schema.sql`.

## Admin Credentials (Default)
To access the admin panel, navigate to `/login`:
- **Username:** `admin`
- **Password:** `admin123`

## Importing Books
For an automatic import of sample data, log into the admin panel and upload the JSON file located at `database/seed/books.json`.

## 🧪 Testing (PHPUnit)
The application includes automated unit tests to verify routing (Router), security (CSRF), authentication (Auth), and template helper functions.

All tests are located in the `tests/` directory. For detailed instructions and testing guides, refer to the **[Testing Guide (docs/testing.md)](docs/testing.md)**.

### Run all tests:
```bash
docker exec -it ebook_web vendor/bin/phpunit
```
