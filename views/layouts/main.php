<?php /** @var string $contentView */ ?>
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
                    
                    <?php if (isAuthenticated()): ?>
                        <a href="/admin/books" class="nav-link <?php echo activeLinkCssClass('/admin/books'); ?>">Správa kníh</a>
                        <a href="/logout" class="nav-link">Odhlásiť sa (<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>)</a>
                    <?php else: ?>
                        <a href="/login" class="nav-link <?php echo activeLinkCssClass('/login'); ?>">Administrácia</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <main class="main-content">
            <!-- The specific view template (e.g., index.php) will be injected here -->
            <?php require $contentView; ?>
        </main>

        <footer class="main-footer">
            <p>&copy; <?php echo date('Y'); ?> Katalóg e-kníh. Skúšobný projekt.</p>
        </footer>
    </div>
</body>
</html>
