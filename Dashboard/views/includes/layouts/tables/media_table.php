<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-4">Manage Magazines</h4>
</div>

<?php if (empty($magazines)): ?>
    <div class="alert alert-info">No magazines found. <a href="/dashboards/add/media">Add</a> your first magazine!</div>
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
                        <td><?= ($magazine['title']) ?></td>
                        <td><?= ($magazine['category']) ?></td>
                        <td><?= number_format($magazine['price'] ?? 0.00, 2) ?></td>
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
                                <a href=""
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