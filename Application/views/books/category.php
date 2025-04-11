<?php foreach ($data as $category => $books): ?>
    <h4 class="text-dark"><?= $category ?></h4>
    <div class="book-carousel owl-carousel owl-theme">
        <?php foreach ($books as $book): ?>
            <?php include __DIR__ . '/../partials/bookCard.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

