<?php foreach ($books as $book):
    $publicKey = html_entity_decode($book['public_key'] ?? '');
    $cover = html_entity_decode($book['cover_image_path'] ?? '');
    $title = html_entity_decode($book['title'] ?? '');
    $author = html_entity_decode($book['author'] ?? '');
    $ebookPrice = $book['ebook_price'] ?? 0;
    
    $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;
?>
    <div class="bk-card">
        <div class="bk-img">
            <a href="/academic/<?= $publicKey ?>">
                <?php if (!empty($cover)): ?>
                    <img src="/cms-data/academic/covers/<?= $cover ?>" alt="<?= $title ?>">
                <?php else: ?>
                    <div style="width: 100%; height: 100%; min-height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        No Cover
                    </div>
                <?php endif; ?>
            </a>
        </div>
        <div class="bk-details">
            <div>
                <a href="/academic/<?= $publicKey ?>" class="text-decoration-none">
                    <p class="bk-heading">
                        <?= $shortTitle ?>
                    </p>
                </a>
                <?php if ($author): ?>
                    <p class="bk-text-meta">
                        Author: <span class="text-muted"><?= $author ?></span>
                    </p>
                <?php endif; ?>
                <?php if ($ebookPrice > 0): ?>
                    <p class="bk-text-meta">
                        Price: <span class="text-muted">R<?= number_format($ebookPrice, 2) ?></span>
                    </p>
                <?php else: ?>
                    <p class="bk-text-meta">
                        <span class="text-success">FREE</span>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
