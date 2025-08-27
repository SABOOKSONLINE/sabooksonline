<?php
$alerts = [];
if (isset($_SESSION['alerts']) && is_array($_SESSION['alerts'])) {
    $alerts = $_SESSION['alerts'];
    unset($_SESSION['alerts']);
}

$perPage = 10;
$totalBooks = count($books);
$totalPages = ceil($totalBooks / $perPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($totalPages, $currentPage));

$booksForPage = array_slice($books, ($currentPage - 1) * $perPage, $perPage);
?>

<?php if (!empty($alerts)): ?>
    <div class="mb-4">
        <?php foreach ($alerts as $alert): ?>
            <?php
            $alertClass = 'alert-success';
            if ($alert['type'] === 'error') {
                $alertClass = 'alert-danger';
            }
            ?>
            <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($alert['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center">
        <h4 class="fw-bold mb-0">Academic Books Table</h4>
    </div>
    <a href="/dashboards/add/academic"
        class="btn btn-outline-dark">
        <i class="fas fa-plus me-2"></i> Add New
    </a>
</div>

<?php if (empty($books)): ?>
    <div class="alert alert-info">No books found. <a href="/dashboards/add/academic">Add</a> your first book!</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Price</th>
                    <th>Publish Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($booksForPage as $book): ?>

                    <tr>
                        <td>
                            <?php if (!empty($book['cover_image_path'])): ?>
                                <img src="/cms-data/academic/covers/<?= html_entity_decode($book['cover_image_path']) ?>"
                                    alt="Cover" class="img-thumbnail" style="max-width: 80px;">
                            <?php else: ?>
                                <span class="text-muted">No cover</span>
                            <?php endif; ?>
                        </td>
                        <td><?= html_entity_decode($book['title']) ?></td>
                        <td><?= html_entity_decode($book['subject']) ?></td>
                        <td>R <?= number_format($book['price'] ?? 0.00, 2) ?></td>
                        <td><?= !empty($book['publish_date']) ? date('M d, Y', strtotime($book['publish_date'])) : 'N/A' ?></td>
                        <td>
                            <?php if (!empty($book['pdf_path'])): ?>
                                <span class="badge bg-success">Available</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="/dashboards/add/academic?id=<?= $book['id'] ?>&action=edit"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="<?= $book['id'] ?>"
                                    class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="<?= $magazine['id'] ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this magazine?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mt-3">
                <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $currentPage - 1 ?>">Previous</a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i === $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $currentPage + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

<?php endif; ?>