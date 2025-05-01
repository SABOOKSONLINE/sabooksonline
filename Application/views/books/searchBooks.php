<form class="d-flex me-3" action="library.php" method="GET" id="myForm">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Search by Title, Authors or Publisher"
            name="k" value="<?= htmlspecialchars($_GET['k'] ?? '') ?>">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>

<?php if (isset($keyword)): ?>
    <h5 class="mt-3">Search results for: <strong><?= htmlspecialchars($keyword) ?></strong></h5>
    <?php if (empty($books)): ?>
        <p>No results found.</p>
    <?php else: ?>
        <ul class="list-group mt-2">
            <?php foreach ($books as $book): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($book['title']) ?></strong><br>
                    Author: <?= htmlspecialchars($book['author']) ?><br>
                    Publisher: <?= htmlspecialchars($book['publisher']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>
