<?php
function renderImageCarouselBanner(array $banners, string $carouselId = "myCarousel")
{
?>
    <div id="<?= htmlspecialchars($carouselId) ?>" class="carousel slide my-4" data-bs-ride="carousel" data-bs-interval="5000">
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

        <div class="carousel-inner" style="border-radius: 25px;">
            <?php foreach ($banners as $index => $banner): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <?php if (!empty($banner['banner_image'])): ?>
                        <a href="<?= htmlspecialchars($banner['link']) ?>">
                        <?php endif; ?>

                        <img src="/cms-data/banners/<?= $banner['banner_image'] ?>"
                            class="d-block w-100"
                            alt="Banner Image"
                            style="border-radius: 25px; object-fit: cover; max-height: 500px;">

                        <?php if (!empty($banner['banner_image'])): ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#<?= htmlspecialchars($carouselId) ?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#<?= htmlspecialchars($carouselId) ?>" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

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

    <div class="modal fade" id="manageBannerModal-<?= htmlspecialchars($carouselId) ?>" tabindex="-1" aria-labelledby="manageBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Manage Image Carousel Banners</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <hr class="mt-0 mb-3 mx-3" style="opacity: 0.1;">

                <div class="modal-body">
                    <form id="bannerForm-<?= htmlspecialchars($carouselId) ?>"
                        class="mb-4"
                        method="POST"
                        action="/admin/pages/home/banners?type=insert&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>&banner=page"
                        enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label">Banner Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="banner_image" accept="image/*" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Link URL</label>
                            <input type="url" class="form-control" name="link" placeholder="https://example.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Show Page <span class="text-danger">*</span></label>
                            <select class="form-select" name="show_page" required>
                                <option value="">Select Page</option>
                                <option value="/home">Home</option>
                                <option value="/library">Books</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add Banner
                            </button>
                        </div>
                    </form>



                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-striped bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Link</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $i => $banner): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <img src="/cms-data/banners/<?= $banner['banner_image'] ?>"
                                                alt="Banner Image" style="height: 50px; border-radius: 5px;">
                                        </td>
                                        <td><?= htmlspecialchars($banner['link'] ?? '') ?></td>
                                        <td class="text-center">
                                            <a href="/admin/pages/home/banners/<?= $banner['id'] ?>?type=delete&return=<?= urlencode($_SERVER['REQUEST_URI']) ?>&banner=page" class="btn btn-outline-danger btn-sm">
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
<?php
}
?>