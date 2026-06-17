# E-Book Catalog

A simple web application for managing an e-book catalog. Built using clean PHP 8 (OOP) without frameworks, a MySQL database, and Docker environment.

## Requirements
- Docker and Docker Compose
- Node.js & NPM (for Webpack compilation)

## 🚀 Installation & Setup

1. **Clone the repository**
   ```bash
   git clone <repository_url>
   cd katalog_knih
   ```

2. **Configure environment variables**
   Create a `.env` file from the template:
   ```bash
   cp .env.example .env
   ```
   *(The default settings inside `.env.example` are preconfigured to work out of the box with Docker. Modify them if necessary).*

3. **Install frontend dependencies**
   Webpack will compile the JavaScript and SCSS assets into the `public/assets/` folder. Use `npm run build` for a one-time build, or `npm run dev` to start Webpack in watch mode (updates automatically on changes):
   ```bash
   npm install
   npm run build  # or 'npm run dev' for active development
   ```

---

### 💻 Option A: Running Local Development

This mode mounts your local directory (volumes) into the container so that any change to PHP files is instantly reflected.

1. **Start the development containers:**
   ```bash
   docker-compose up -d
   ```
2. **Install PHP dependencies (Composer) inside the container:**
   ```bash
   docker exec -it ebook_web composer install
   ```
3. The app will be available at [http://localhost:8080](http://localhost:8080).
4. The database runs on port `3307` and automatically imports the schema from `database/schema.sql`.

---

### 📦 Option B: Running Production Simulation

In this mode, all files are embedded directly into the Docker image, and dependencies are optimized (`--no-dev --optimize-autoloader`). Local file modifications will not be reflected.

1. **Build and start the production containers:**
   ```bash
   docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
   ```
   *(No need to run `composer install` separately, as it is executed automatically during the container build process).*
2. The app will be available at [http://localhost:8080](http://localhost:8080).
3. The database runs on port `3307` and automatically imports the schema from `database/schema.sql`.

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
