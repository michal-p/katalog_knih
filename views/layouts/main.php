<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalóg e-kníh</title>
    <!-- Tu neskôr napojíme náš CSS súbor z Webpacku -->
</head>
<body>
    <header style="background: #333; color: white; padding: 1rem;">
        <h1>📚 Katalóg e-kníh</h1>
        <nav>
            <a href="/" style="color: white; margin-right: 15px;">Domov</a>
            <a href="/admin" style="color: white;">Administrácia</a>
        </nav>
    </header>

    <main style="padding: 2rem;">
        <!-- Tu sa vloží náš konkrétny pohľad (napríklad zoznam kníh) -->
        <?php require $contentView; ?>
    </main>

    <footer style="background: #eee; padding: 1rem; text-align: center; margin-top: 2rem;">
        <p>&copy; <?php echo date('Y'); ?> E-book Catalog.</p>
    </footer>
</body>
</html>
