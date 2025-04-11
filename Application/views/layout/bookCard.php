<div class="item">
    <div class="strip">
        <a href="book.php?q=<?= strtolower($book['CONTENTID']) ?>">
            <figure>
                <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= $book['COVER'] ?>" class="owl-lazy" alt="" width="460" height="310">
            </figure>
        </a>
        <div class="bottom-text">
            <a href="creator?q=<?= strtolower($book['USERID']) ?>" class="text-dark">
                <?= ucwords($book['PUBLISHER']) ?>
                <small class="icon_check_alt text-success" style="font-size:12px"></small>
            </a><br>
            <a href="creator?q=<?= strtolower($book['USERID']) ?>" class="text-dark">
                <?= ucwords(substr($book['AUTHORS'], 0, 20)) ?>
            </a>
        </div>
    </div>
</div>
