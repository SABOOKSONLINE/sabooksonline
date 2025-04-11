<div class="container my-5">
    <div class="row">
        <!-- Book Cover -->
        <div class="col-md-4 text-center">
            <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= htmlspecialchars($book['COVER']) ?>" class="img-fluid rounded shadow" alt="Book Cover">
        </div>

        <!-- Book Details -->
        <div class="col-md-8">
            <h2 class="mb-3"><?= htmlspecialchars(string: $book['TITLE']) ?></h2>

            <p><strong>Author:</strong> <?= htmlspecialchars($book['AUTHORS']) ?></p>
            <p><strong>Publisher:</strong> <?= htmlspecialchars(string: $book['PUBLISHER']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($book['CATEGORY'] ?? 'Unknown') ?></p>

            <hr>

            <div class="bg-light p-3 rounded">
                <h5>BOOK synopsis:</h5>
                <p><?= nl2br(htmlspecialchars($book['DESCRIPTION'] ?? 'No description available.')) ?></p>
            </div>

            <!-- Optional buttons -->
            <div class="mt-4">
                <a href="read.php?q=<?= strtolower($book['CONTENTID']) ?>" class="btn btn-primary">Read Online</a>
                <a href="index.php" class="btn btn-secondary">Back to Books</a>
            </div>
        </div>
    </div>
</div>
