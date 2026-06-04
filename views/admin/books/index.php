<div class="admin-header">
    <h2>Správa kníh</h2>
    <div class="admin-actions">
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
