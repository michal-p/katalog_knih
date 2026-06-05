<div class="admin-header">
    <h2>Pridať novú knihu</h2>
    <div class="admin-actions">
        <a href="/admin/books" class="btn btn-outline">
            Späť na zoznam
        </a>
    </div>
</div>

<div class="admin-form-container">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="error-list">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- The form uses POST to safely send data. Note HTML5 validation like 'required' and 'min/max' -->
    <form action="/admin/books" method="POST" class="book-form">
        <?= \App\Helpers\View::csrfField() ?>
        <div class="form-row">
            <div class="form-group">
                <label for="title">Názov knihy *</label>
                <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="author">Autor *</label>
                <input type="text" id="author" name="author" required value="<?php echo htmlspecialchars($old['author'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="year">Rok vydania *</label>
                <input type="number" id="year" name="year" required min="1000" max="2100" value="<?php echo htmlspecialchars($old['year'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="rating">Hodnotenie (1-10)</label>
                <input type="number" id="rating" name="rating" min="1" max="10" value="<?php echo htmlspecialchars($old['rating'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="annotation">Anotácia</label>
            <textarea id="annotation" name="annotation" rows="5"><?php echo htmlspecialchars($old['annotation'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Uložiť knihu
        </button>
    </form>
</div>
