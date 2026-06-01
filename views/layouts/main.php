<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalóg e-kníh</title>
    <!-- Our generated CSS file from Webpack -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/app.css'); ?>">
    <!-- Our generated JS file from Webpack -->
    <script src="<?php echo asset('assets/js/app.js'); ?>" defer></script>
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
                    <a href="/" class="nav-link <?php echo activeLinkCssClass('/', true); ?>">Domov</a>
                    <a href="/admin" class="nav-link <?php echo activeLinkCssClass('/admin'); ?>">Administrácia</a>
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
