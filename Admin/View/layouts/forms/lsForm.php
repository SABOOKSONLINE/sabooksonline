<?php

function renderAddBookListingSectionForm(array $sections = []): void
{
?>
    <div class="card mb-4 border-1 border-muted mt-4 rounded-4" id="addBookListingSectionForm" style="scroll-margin-top: 155px;">
        <div class="card-body p-4">

            <form action="/admin/books/book-listing-sections/process?type=add-book-listing-section&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>#addBookListingSectionForm" method="POST">
                <div class="row g-2 align-items-end">

                    <!-- Section Name -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Section Name <span class="text-danger">*</span></label>
                        <input type="text" name="section_name" class="form-control" placeholder="Trending Books, Staff Picks..." required>
                        <small class="text-muted d-block mt-1">
                            <i>Title above the book cards.</i>
                        </small>
                    </div>

                    <!-- Order Index -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Order <span class="text-danger">*</span></label>
                        <input type="number" name="order_index" class="form-control" min="1" placeholder="1" required>
                        <small class="text-muted d-block mt-1">Display order on homepage.</small>
                    </div>

                    <!-- Card Type -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Card Type <span class="text-danger">*</span></label>
                        <select name="card_type" class="form-select" required>
                            <option value="standard">Standard</option>
                            <option value="standard-reversed">Standard Reversed</option>
                            <option value="featured">Featured</option>
                            <option value="compact">Compact</option>
                        </select>
                        <small class="text-muted d-block mt-1">Visual style of book cards.</small>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-12 d-flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add
                        </button>
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i> Reset
                        </button>
                    </div>

                </div>
            </form>

            <?php if (!empty($sections)): ?>
                <div id="sectionTags" class="d-flex flex-wrap gap-2 mt-3">

                    <?php foreach ($sections as $section): ?>
                        <span
                            class="badge bg-light text-dark border d-inline-flex align-items-center gap-2 px-3 py-2 draggable-section"
                            data-id="<?= $section['id'] ?>">
                            <i class="fas fa-grip-vertical text-muted me-1"></i>

                            <?= htmlspecialchars($section['section_name']) ?>

                            <form action="/admin/books/book-listing-sections/process?type=delete-book-listing-section&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>#addBookListingSectionForm"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Delete section: <?= addslashes($section['section_name']) ?>?');">

                                <input type="hidden" name="id" value="<?= $section['id'] ?>">

                                <button type="submit"
                                    class="btn-close btn-close-sm ms-1"
                                    style="font-size:10px;">
                                </button>
                            </form>

                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <style>
        .draggable-section {
            cursor: grab;
        }

        .draggable-section:active {
            cursor: grabbing;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        new Sortable(document.getElementById('sectionTags'), {
            animation: 150,
            ghostClass: 'opacity-50',
            handle: '.fa-grip-vertical',

            onEnd: function() {
                const order = [];

                document.querySelectorAll('#sectionTags .draggable-section').forEach((el, index) => {
                    order.push({
                        id: el.dataset.id,
                        order_index: index + 1
                    });
                });

                fetch('/admin/books/book-listing-sections/process?type=reorder-book-listing-sections&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(order)
                });
            }
        });
    </script>
<?php
}
?>