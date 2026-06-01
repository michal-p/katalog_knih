<div class="page-header">
    <h2>Zoznam všetkých kníh</h2>
    <!-- Print button for the book list (task requirement) -->
    <button class="btn btn-outline" onclick="window.print()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
        Tlačiť zoznam
    </button>
</div>

<?php if (empty($books)): ?>
    <div class="empty-state">
        <div class="empty-icon">📖</div>
        <h3>Žiadne knihy</h3>
        <p>V katalógu sa zatiaľ nenachádzajú žiadne záznamy. Presuňte sa do administrácie a naimportujte prvé e-knihy.</p>
    </div>
<?php else: ?>
    <div class="books-grid">
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <div class="book-card-inner">
                    <div class="book-cover-placeholder">
                        <!-- Display the first letter of the book title -->
                        <span><?php echo htmlspecialchars(substr($book['title'], 0, 1)); ?></span>
                    </div>
                    <div class="book-info">
                        <span class="book-year"><?php echo htmlspecialchars($book['year']); ?></span>
                        <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="book-author"><?php echo htmlspecialchars($book['author']); ?></p>
                        
                        <div class="book-actions">
                            <a href="/books/<?php echo htmlspecialchars($book['id']); ?>" class="btn btn-primary">Zobraziť detail</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
