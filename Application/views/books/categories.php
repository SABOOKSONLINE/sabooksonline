<?php foreach ($categories as $category): ?>
    <a href="?category=<?= urlencode(htmlspecialchars($category['CATEGORY'])) ?>" class="category-link">
        <?= htmlspecialchars($category['CATEGORY']) ?>
    </a>
<?php endforeach; ?>