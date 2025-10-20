<?php
function renderStickyBannerSlider($banners)
{
?>
    <div id="stickyBannerCarousel"
        class="carousel slide"
        data-bs-ride="carousel"
        data-bs-interval="5000"
        data-bs-pause="hover">

        <!-- Indicators -->
        <div class="carousel-indicators mb-0">
            <?php foreach ($banners as $index => $banner): ?>
                <button type="button"
                    data-bs-target="#stickyBannerCarousel"
                    data-bs-slide-to="<?= $index ?>"
                    class="<?= $index === 0 ? 'active' : '' ?>"
                    aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                    aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <?php foreach ($banners as $index => $banner): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between p-3 rounded-3 text-md-start text-center bg-dark text-white shadow-sm border border-secondary">
                                    <div class="me-md-3">
                                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($banner['heading']) ?></h5>
                                        <small class="opacity-75"><?= htmlspecialchars($banner['subheading']) ?></small>
                                    </div>
                                    <div class="mt-3 mt-md-0">
                                        <a href="<?= htmlspecialchars($banner['button_link']) ?>" class="btn btn-outline-light btn-lg">
                                            <?= htmlspecialchars($banner['button_text']) ?>
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="position-relative text-white mt-3">
        <div class="d-flex justify-content-center align-items-center">
            <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#manageBannerModal">
                <i class="fas fa-plus me-1"></i>
                <?php if (count($banners) === 0): ?>
                    Add Sticky Banners
                <?php else: ?>
                    Manage Sticky Banners
                <?php endif; ?>
            </button>
        </div>
    </div>

    <!-- Manage Banner Modal -->
    <div class="modal fade" id="manageBannerModal" tabindex="-1" aria-labelledby="manageBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <div class="modal-header">
                    <h5 class="modal-title" id="manageBannerModalLabel">Manage Banners (<?= count($banners) ?> total)</h5>
                    <!-- ✅ Same close button style & placement as book table -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Add Banner Form -->
                    <form id="addBannerForm" class="mb-4" method="POST" action="/admin/pages/home/banners?type=insert&return=<?= $_SERVER['REQUEST_URI'] ?>&banner=sticky">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Header <span class="text-danger">*</span></label>
                                <input type="text" name="heading" class="form-control" placeholder="Enter banner title" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subheader <span class="text-danger">*</span></label>
                                <input type="text" name="subheading" class="form-control" placeholder="Enter banner subtitle" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Button Text <span class="text-danger">*</span></label>
                                <input type="text" name="buttonText" class="form-control" placeholder="e.g. Learn More" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Button Link <span class="text-danger">*</span></label>
                                <input type="url" name="link" class="form-control" placeholder="https://example.com" required>
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-plus me-1"></i> Add Banner
                            </button>
                        </div>
                    </form>

                    <!-- Search Bar -->
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" id="bannerSearch" class="form-control" placeholder="Search banners...">
                    </div>

                    <!-- Existing Banners Table -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-striped bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Header</th>
                                    <th>Subheader</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="bannerList">
                                <?php foreach ($banners as $banner): ?>
                                    <tr>
                                        <td class="align-middle"><small><?= htmlspecialchars($banner['heading']) ?></small></td>
                                        <td class="align-middle"><small><?= htmlspecialchars($banner['subheading']) ?></small></td>
                                        <td class="align-middle">
                                            <a href="/admin/pages/home/banners/<?= $banner['id'] ?>?type=delete&return=<?= $_SERVER['REQUEST_URI'] ?>&banner=sticky" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
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
        // Simple search filter for banner table
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('bannerSearch');
            const rows = document.querySelectorAll('#bannerList tr');

            searchInput.addEventListener('keyup', () => {
                const term = searchInput.value.toLowerCase();
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        });
    </script>
<?php
}
?>