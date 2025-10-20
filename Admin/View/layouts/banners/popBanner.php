<?php
function renderPopupBannerAdminUI(array $banners = [], array $books = []): void
{
?>
    <!-- ---------- Admin Buttons ---------- -->
    <div class="d-flex gap-2 my-3">
        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editPopupBanner">
            Add / Edit Popup Banner
        </button>

        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#manageBannersModal">
            Manage All Banners
        </button>
    </div>

    <!-- ---------- Edit Popup Banner Modal ---------- -->
    <div class="modal fade" id="editPopupBanner" tabindex="-1" aria-labelledby="editPopupBannerLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border shadow-sm rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPopupBannerLabel">Add / Edit Popup Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="popupBannerForm" class="mb-4" method="POST" action="/admin/pages/home/banners?type=insert&return=<?= $_SERVER['REQUEST_URI'] ?>&banner=popup">
                        <div class="row g-3">
                            <!-- Search Book -->
                            <div class="col-md-12">
                                <label class="form-label">Select Book</label>
                                <input list="booksList" name="book_public_key" class="form-control" placeholder="Start typing book title...">
                                <datalist id="booksList">
                                    <?php foreach ($books as $book): ?>
                                        <option value="<?= htmlspecialchars($book['CONTENTID']) ?>">
                                            <?= htmlspecialchars($book['TITLE']) ?> — <?= htmlspecialchars($book['PUBLISHER']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </datalist>
                            </div>


                            <!-- Date / Time Range -->
                            <div class="col-md-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="date_from" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="date_to" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="time_from" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Time</label>
                                <input type="time" name="time_to" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Button Text</label>
                                <input type="text" name="button_text" class="form-control" value="Read Now">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Button Link</label>
                                <input type="url" name="link" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="2" class="form-control"></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Subtext (One per line)</label>
                                <textarea name="subtext" rows="2" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-success">Save Banner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ---------- Manage All Banners Modal ---------- -->
    <div class="modal fade" id="manageBannersModal" tabindex="-1" aria-labelledby="manageBannersLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border shadow-sm rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageBannersLabel">All Popup Banners</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Book</th>
                                    <th>Tag</th>
                                    <th>Date Range</th>
                                    <th>Time Range</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $i => $b): ?>
                                    <?php
                                    $start = strtotime($b['date_from'] . ' ' . $b['time_from']);
                                    $end = strtotime($b['date_to'] . ' ' . $b['time_to']);
                                    $now = time();

                                    $isActive = ($now >= $start && $now <= $end);
                                    $statusText = $isActive ? 'Active' : 'Inactive';
                                    $statusClass = $isActive ? 'success' : 'secondary';
                                    ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($b['TITLE']) ?></td>
                                        <td>Sponsored Ad</td>
                                        <td><?= htmlspecialchars($b['date_from']) ?> → <?= htmlspecialchars($b['date_to']) ?></td>
                                        <td><?= htmlspecialchars($b['time_from']) ?> → <?= htmlspecialchars($b['time_to']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $statusClass ?>">
                                                <?= $statusText ?>
                                            </span>
                                        </td>
                                        <td>
                                            <!-- <button class="btn btn-sm btn-outline-primary">Edit</button> -->
                                            <a href="/admin/pages/home/banners/<?= $b['id'] ?>?type=delete&return=<?= $_SERVER['REQUEST_URI'] ?>&banner=popup" class="btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const select = document.getElementById('bookSelect');
        select.addEventListener('focus', function() {
            select.size = Math.min(select.options.length, 10);
        });
        select.addEventListener('blur', function() {
            setTimeout(() => select.size = 1, 150);
        });

        select.addEventListener('input', function() {
            const filter = select.value.toLowerCase();
            Array.from(select.options).forEach(opt => {
                if (opt.value === "") return;
                opt.style.display = opt.textContent.toLowerCase().includes(filter) ? "" : "none";
            });
        });
    </script>
<?php
}
