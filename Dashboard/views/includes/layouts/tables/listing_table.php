<?php
$booksPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalBooks = count($books);
$totalPages = ceil($totalBooks / $booksPerPage);
$startIndex = ($currentPage - 1) * $booksPerPage;

$booksToShow = array_slice($books, $startIndex, $booksPerPage);
$startCount = $startIndex + 1;
$endCount = min($startIndex + $booksPerPage, $totalBooks);
?>

<script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>

<h5 class="mb-3">Showing <?= $startCount ?>–<?= $endCount ?> of <?= $totalBooks ?> matching books</h5>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        Books per page:
        <form method="get" class="d-inline">
            <select name="limit" class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="5" <?= $booksPerPage == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $booksPerPage == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $booksPerPage == 25 ? 'selected' : '' ?>>25</option>
            </select>
            <input type="hidden" name="page" value="1">
        </form>
    </div>
    <div><?= $startCount ?>–<?= $endCount ?> of <?= $totalBooks ?> books</div>
</div>

<table class="table table-bordered table-hover align-middle table-responsive table-bordered rounded">
    <thead class="table-light">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Date Posted</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
            <th>PDF</th>
            <th>Upload</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($books)): ?>
            <tr>
                <td colspan="6" class="text-center">No books available.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($booksToShow as $book): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="/cms-data/book-covers/<?= html_entity_decode($book['COVER']) ?>"
                                class="me-2 rounded shadow-sm"
                                alt="<?= html_entity_decode($book["TITLE"]) ?> Book Cover"
                                width="50" height="75">
                            <div>
                                <a href="/dashboards/listings/<?= $book["CONTENTID"] ?>">
                                    <?= html_entity_decode($book["TITLE"]) ?>
                                </a>
                                <br>
                                <small class="text-muted"><b>ISBN:</b> <?= html_entity_decode($book["ISBN"]) ?></small>
                            </div>
                        </div>
                    </td>
                    <td><?= html_entity_decode($book["AUTHORS"]) ?></td>
                    <td><?= html_entity_decode($book["DATEPOSTED"]) ?></td>
                    <td>
                        <?= $book["RETAILPRICE"] == 0 ? "FREE" : "R " . html_entity_decode($book["RETAILPRICE"]) ?>
                    </td>
                    <td>
                        <span class="badge <?= ($book['STATUS'] ?? 'inactive') === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= html_entity_decode(strtoupper($book["STATUS"])) ?>
                        </span>
                    </td>
                    <td>
                        <a href="/dashboards/listings/delete/<?= $book['CONTENTID'] ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure you want to delete this book?')">
                            Delete
                        </a>
                    </td>
                    <td>
                    <?php if (!empty($book['PDFURL'])): ?>
                        <a href="<?= $book['PDFURL'] ?>" target="_blank">View PDF</a>
                    <?php else: ?>
                        <span>No Content</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button class="btn-upload" onclick="uploadPdf('<?= $book['CONTENTID'] ?>')">Upload PDF</button>
                </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $currentPage - 1 ?>&limit=<?= $booksPerPage ?>">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&limit=<?= $booksPerPage ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $currentPage + 1 ?>&limit=<?= $booksPerPage ?>">Next</a>
        </li>
    </ul>
</nav>
<script>

const widget = cloudinary.createUploadWidget({
    cloudName: 'dapufnac8',
    uploadPreset: 'bookContent',
    resourceType: 'raw',
    clientAllowedFormats: ['pdf'],
    folder: 'books',
    context: {access: "public"},         // 👈 Add this if your preset supports it
    public_id: `book_${contentId}`       // Optional: set filename
}, (error, result) => {
    if (!error && result && result.event === "success") {
        const pdfUrl = result.info.secure_url;

        fetch('../../includes/save-pdf-url.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `contentid=${contentId}&pdf_url=${encodeURIComponent(pdfUrl)}`
        })
        .then(res => res.text())
        .then(response => {
            alert(response);
            location.reload();
        })
        .catch(err => alert("Failed to save PDF URL"));
    }
});


function uploadPdf(contentId) {
    const widget = cloudinary.createUploadWidget({

        cloudName: 'dapufnac8',
        uploadPreset: 'bookContent',
        resourceType: 'raw',
        clientAllowedFormats: ['pdf'],
        folder: 'books',
    

    }, (error, result) => {
        if (!error && result && result.event === "success") {
            const pdfUrl = result.info.secure_url;

            fetch('../../includes/save-pdf-url.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `contentid=${contentId}&pdf_url=${encodeURIComponent(pdfUrl)}`
            })
            .then(res => res.text())
            .then(response => {
                alert(response);
                location.reload();
            })
            .catch(err => alert("Failed to save PDF URL"));
        }
    });

    widget.open();
}
</script>