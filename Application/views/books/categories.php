<a href="/library" class="category-link">All</a>
<?php foreach ($categories as $category): ?>
    <a href="?category=<?= urlencode($category['CATEGORY']) ?>" class="category-link">
        <?= htmlspecialchars($category['CATEGORY']) ?>
    </a>
<?php endforeach; ?>