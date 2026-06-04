<?php /** @var array $book */ ?>
<div class="book-detail-container">
    <div class="book-detail-header">
        <a href="/" class="btn btn-outline">
            <span>←</span> Späť na zoznam
        </a>
        <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        <p>od autora <strong><?php echo htmlspecialchars($book['author']); ?></strong></p>
    </div>

    <div class="book-detail-card">
        
        <div class="book-cover-large">
            <span><?php echo htmlspecialchars(substr($book['title'], 0, 1)); ?></span>
        </div>

        <div class="book-meta">
            <div class="meta-badges">
                <span class="badge badge-primary">
                    📅 Rok vydania: <?php echo htmlspecialchars($book['year']); ?>
                </span>
                
                <?php if ($book['rating']): ?>
                    <span class="badge badge-warning">
                        ⭐ Hodnotenie: <?php echo htmlspecialchars($book['rating']); ?>/10
                    </span>
                <?php endif; ?>
            </div>

            <div class="annotation">
                <h3>Anotácia knihy</h3>
                <?php if ($book['annotation']): ?>
                    <p><?php echo htmlspecialchars($book['annotation']); ?></p>
                <?php else: ?>
                    <p class="annotation-empty">Táto kniha zatiaľ nemá pridanú anotáciu.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
