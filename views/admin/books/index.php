<div class="admin-header">
    <h2>Správa kníh</h2>
    <div class="admin-actions">
        <form action="/admin/books/import" method="POST" style="display: inline;">
            <button type="submit" class="btn btn-outline">
                📥 Importovať z JSON
            </button>
        </form>
        <a href="/admin/books/create" class="btn btn-primary">
            <span class="btn-icon">+</span> Pridať novú knihu
        </a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        Úspešne uložené! Operácia prebehla v poriadku.
    </div>
<?php endif; ?>

<?php if (isset($_GET['imported'])): ?>
    <div class="alert alert-success">
        Import dokončený: <strong><?php echo (int) $_GET['imported']; ?></strong> kníh importovaných,
        <strong><?php echo (int) ($_GET['skipped'] ?? 0); ?></strong> preskočených (už existujú alebo chybné údaje).
    </div>
<?php endif; ?>

<?php if (isset($_GET['import_error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_GET['import_error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['import_errors'])): ?>
    <div class="alert alert-danger">
        <strong>Niektoré záznamy sa nepodarilo importovať:</strong>
        <ul class="error-list" style="margin-top: 0.5rem;">
            <?php foreach ($_SESSION['import_errors'] as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['import_errors']); ?>
<?php endif; ?>

<div class="admin-table-container">
    <?php if (empty($books)): ?>
        <div class="empty-state">
            <div class="empty-icon">📚</div>
            <h3>Zatiaľ tu nie sú žiadne knihy</h3>
            <p>Pridajte svoju prvú knihu do katalógu pomocou tlačidla vyššie.</p>
        </div>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Názov</th>
                    <th>Autor</th>
                    <th>Rok</th>
                    <th>Hodnotenie</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td class="td-muted">#<?php echo htmlspecialchars($book['id']); ?></td>
                        <td class="td-bold"><?php echo htmlspecialchars($book['title']); ?></td>
                        <td class="td-muted"><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['year']); ?></td>
                        <td>
                            <?php if ($book['rating']): ?>
                                <span class="rating-badge">
                                    ★ <?php echo htmlspecialchars($book['rating']); ?>/10
                                </span>
                            <?php else: ?>
                                <span class="rating-none">Nehodnotené</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
