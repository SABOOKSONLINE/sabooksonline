<?php
$activeTab = $_GET['tab'] ?? 'magazines';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center">
        <!-- Tab buttons -->
        <div class="btn-group me-3" role="group">
            <a href="?tab=magazines"
                class="btn btn-<?= $activeTab === 'magazines' ? 'dark' : 'outline-dark' ?>">
                <i class="fas fa-book me-2"></i> Magazines
            </a>
            <a href="?tab=newspapers"
                class="btn btn-<?= $activeTab === 'newspapers' ? 'dark' : 'outline-dark' ?>">
                <i class="fas fa-newspaper me-2"></i> Newspapers
            </a>
        </div>

        <!-- Heading based on active tab -->
        <h4 class="fw-bold mb-0">Manage <?= ucfirst($activeTab) ?></h4>
    </div>

    <!-- Add New Button -->
    <a href="/dashboards/add/media?type=<?= substr($activeTab, 0, -1) ?>"
        class="btn btn-outline-dark">
        <i class="fas fa-plus me-2"></i> Add New
    </a>
</div>

<?php if ($activeTab === 'magazines'): ?>
    <!-- Magazine Table -->
    <?php if (empty($magazines)): ?>
        <div class="alert alert-info">No magazines found. <a href="/dashboards/add/media?type=magazine">Add</a> your first magazine!</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Publish Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($magazines as $magazine): ?>
                        <tr>
                            <td>
                                <?php if (!empty($magazine['cover_image_path'])): ?>
                                    <img src="/cms-data/magazine/covers/<?= htmlspecialchars($magazine['cover_image_path']) ?>"
                                        alt="Cover" class="img-thumbnail" style="max-width: 80px;">
                                <?php else: ?>
                                    <span class="text-muted">No cover</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($magazine['title']) ?></td>
                            <td><?= htmlspecialchars($magazine['category']) ?></td>
                            <td>R <?= number_format($magazine['price'] ?? 0.00, 2) ?></td>
                            <td><?= !empty($magazine['publish_date']) ? date('M d, Y', strtotime($magazine['publish_date'])) : 'N/A' ?></td>
                            <td>
                                <?php if (!empty($magazine['pdf_path'])): ?>
                                    <span class="badge bg-success">Available</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="/dashboards/add/media?id=<?= $magazine['id'] ?>&type=magazine&action=edit"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="/view/magazine/<?= $magazine['id'] ?>"
                                        class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="/dashboards/media/magazine/delete/<?= $magazine['id'] ?>"
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
    <?php endif; ?>

<?php elseif ($activeTab === 'newspapers'): ?>
    <!-- Newspaper Table -->
    <?php if (empty($newspapers)): ?>
        <div class="alert alert-info">No newspapers found. <a href="/dashboards/add/media?type=newspaper">Add</a> your first newspaper!</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Publication Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($newspapers as $newspaper): ?>
                        <tr>
                            <td>
                                <?php if (!empty($newspaper['cover_image_path'])): ?>
                                    <img src="/cms-data/newspaper/covers/<?= htmlspecialchars($newspaper['cover_image_path']) ?>"
                                        alt="Cover" class="img-thumbnail" style="max-width: 80px;">
                                <?php else: ?>
                                    <span class="text-muted">No cover</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($newspaper['title']) ?></td>
                            <td><?= htmlspecialchars($newspaper['category']) ?></td>
                            <td>R <?= number_format($newspaper['price'] ?? 0.00, 2) ?></td>
                            <td><?= !empty($newspaper['publish_date']) ? date('M d, Y', strtotime($newspaper['publish_date'])) : 'N/A' ?></td>
                            <td>
                                <?php if (!empty($newspaper['pdf_path'])): ?>
                                    <span class="badge bg-success">Available</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="/dashboards/add/media?id=<?= $newspaper['id'] ?>&type=newspaper&action=edit"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="/view/newspaper/<?= $newspaper['id'] ?>"
                                        class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="/dashboards/media/newspaper/delete/<?= $newspaper['id'] ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this newspaper?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php endif; ?>