<?php
function renderImageCarouselBanner(array $banners, string $carouselId = "myCarousel")
{
?>
    <div id="<?= htmlspecialchars($carouselId) ?>" class="carousel slide my-4" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- Carousel Indicators -->
        <div class="carousel-indicators">
            <?php foreach ($banners as $index => $banner): ?>
                <button type="button"
                    data-bs-target="#<?= htmlspecialchars($carouselId) ?>"
                    data-bs-slide-to="<?= $index ?>"
                    class="<?= $index === 0 ? 'active' : '' ?>"
                    aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                    aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <!-- Carousel Slides -->
        <div class="carousel-inner" style="border-radius: 25px;">
            <?php foreach ($banners as $index => $banner): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <?php if (!empty($banner['UPLOADED'])): ?>
                        <a href="<?= htmlspecialchars($banner['UPLOADED']) ?>">
                        <?php endif; ?>

                        <img src="/img/<?= htmlspecialchars(str_replace('../../../', '', $banner['IMAGE'])) ?>"
                            class="d-block w-100"
                            alt="<?= htmlspecialchars($banner['SLIDE'] ?? 'Banner Image') ?>"
                            style="border-radius: 25px; object-fit: cover; max-height: 500px;">

                        <?php if (!empty($banner['UPLOADED'])): ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#<?= htmlspecialchars($carouselId) ?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#<?= htmlspecialchars($carouselId) ?>" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Manage Button -->
    <div class="position-relative text-white mt-3">
        <div class="d-flex justify-content-center align-items-center">
            <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#manageBannerModal-<?= htmlspecialchars($carouselId) ?>">
                <i class="fas fa-plus me-1"></i>
                <?php if (count($banners) === 0): ?>
                    Add Carousel Banners
                <?php else: ?>
                    Manage Carousel Banners
                <?php endif; ?>
            </button>
        </div>
    </div>

    <!-- Manage Banner Modal -->
    <div class="modal fade" id="manageBannerModal-<?= htmlspecialchars($carouselId) ?>" tabindex="-1" aria-labelledby="manageBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Manage Image Carousel Banners</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Divider line under title -->
                <hr class="mt-0 mb-3 mx-3" style="opacity: 0.1;">

                <div class="modal-body">
                    <!-- Banner Form -->
                    <form id="bannerForm-<?= htmlspecialchars($carouselId) ?>" class="mb-4">
                        <div class="mb-3">
                            <label class="form-label">Slide Title</label>
                            <input type="text" class="form-control" name="SLIDE" placeholder="Enter slide title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Banner Image</label>
                            <input type="file" class="form-control" name="IMAGE" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link URL</label>
                            <input type="url" class="form-control" name="UPLOADED" placeholder="https://example.com">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add Banner
                            </button>
                        </div>
                    </form>

                    <!-- Table of Existing Banners -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-striped bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Slide</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Link</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $i => $banner): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($banner['SLIDE'] ?? '') ?></td>
                                        <td>
                                            <img src="/img/<?= htmlspecialchars(str_replace('../../../', '', $banner['IMAGE'])) ?>"
                                                alt="<?= htmlspecialchars($banner['SLIDE'] ?? '') ?>" style="height: 50px; border-radius: 5px;">
                                        </td>
                                        <td><?= htmlspecialchars($banner['UPLOADED'] ?? '') ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
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
<?php
}
?>