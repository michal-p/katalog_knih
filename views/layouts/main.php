<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalóg e-kníh</title>
    <!-- Modern Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <!-- Our generated CSS file from Webpack -->
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="app-container">
        <header class="main-header">
            <div class="header-content">
                <div class="logo">
                    <span class="icon">📚</span>
                    <h1>Katalóg e-kníh</h1>
                </div>
                <nav class="main-nav">
                    <a href="/" class="nav-link active">Domov</a>
                    <a href="/admin" class="nav-link">Administrácia</a>
                </nav>
            </div>
        </header>

        <main class="main-content">
            <!-- The specific view template (e.g., index.php) will be injected here -->
            <?php require $contentView; ?>
        </main>

        <footer class="main-footer">
            <p>&copy; <?php echo date('Y'); ?> E-book Catalog. Zkušební úkol.</p>
        </footer>
    </div>
</body>
</html>
