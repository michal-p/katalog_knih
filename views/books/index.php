<h2>Zoznam všetkých kníh</h2>

<?php if (empty($books)): ?>
    <p>V katalógu sa zatiaľ nenachádzajú žiadne knihy. Prosím, naimportujte ich v administrácii.</p>
<?php else: ?>
    <ul style="line-height: 1.6;">
        <?php foreach ($books as $book): ?>
            <li>
                <strong><?php echo htmlspecialchars($book['title']); ?></strong> 
                od <?php echo htmlspecialchars($book['author']); ?> 
                (<?php echo htmlspecialchars($book['year']); ?>)
                <br>
                <a href="/books/<?php echo $book['id']; ?>">Zobraziť detail</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
