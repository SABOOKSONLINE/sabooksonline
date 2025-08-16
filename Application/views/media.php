<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/../Config/connection.php";
require_once __DIR__ . "/../models/MediaModel.php";
require_once __DIR__ . "/../controllers/MediaController.php";

require_once __DIR__ . "/layout/sectionHeading.php";

$controller = new MediaController($conn);

$magazines = $controller->getAllMagazines();
$newspapers = $controller->getAllNewspapers();
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php";
    ?>

    <div class="jumbotron jumbotron-md">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
            <div class="jumbo-details">
                <h1 class="display-4"><b>SABO Media</b> Hub</h1>
                <p class="lead mb-4">Read. Discover. Stay Informed.</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="container">
                <?php renderSectionHeading(
                    "Featured Magazines",
                    "Discover the latest issues and trending publications.",
                    "",
                    ""
                ); ?>
            </div>

            <div class="book-cards mt-4" id="magazines">
                <div class="book-card-slide scroll-right">
                    <?php foreach ($magazines as $magazine):
                        $publicKey = $magazine['public_key'];
                        $cover = htmlspecialchars($magazine['cover_image_path']);
                        $title = htmlspecialchars($magazine['title']);

                        $longDescription = htmlspecialchars($magazine['description']);
                        $description = strlen($longDescription) > 200 ? substr($longDescription, 0, 200) . '...' : $longDescription;

                        $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;
                        $publisherId = $magazine['publisher_id'];
                        $publisher = "Publisher"; // You might want to fetch publisher name from another table

                        $language = htmlspecialchars($magazine['language']);
                        $category = htmlspecialchars($magazine['category']);
                        $price = number_format($magazine['price'], 2);
                        $publishDate = date('M j, Y', strtotime($magazine['publish_date']));
                    ?>
                        <div class="bk-card bk-card-lg">
                            <div class="bk-img">
                                <span>
                                    <img src="/cms-data/magazine/covers/<?= $cover ?>" alt="<?= $title ?>" loading="lazy">
                                </span>
                            </div>
                            <div class="bk-details">
                                <div>
                                    <a href="/magazines/<?= $publicKey ?>" class="text-decoration-none">
                                        <p class="bk-heading-xl">
                                            <?= $shortTitle ?>
                                        </p>
                                    </a>
                                    <p class="bk-text-meta">
                                        Published by: <span><?= $publisher ?></span>
                                    </p>
                                    <p class="bk-text-para">
                                        <?= $description ?>
                                    </p>
                                </div>

                                <div class="bk-tags">
                                    <?php if ($category): ?>
                                        <span class="bk-tag bk-tag-black">
                                            <?= html_entity_decode($category) ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($magazine['frequency']): ?>
                                        <span class="bk-tag bk-tag-gray"><?= ucfirst($magazine['frequency']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="book-card-btn btn-right">
                <div>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="container">
                <?php renderSectionHeading(
                    "Latest Newspapers",
                    "Stay informed with today's most important stories.",
                    "",
                    ""
                ); ?>
            </div>

            <div class="book-cards mt-4" id="newspapers">
                <div class="book-card-slide scroll-right">
                    <?php foreach ($newspapers as $newspaper):
                        $publicKey = $newspaper['public_key'];
                        $cover = htmlspecialchars($newspaper['cover_image_path']);
                        $title = htmlspecialchars($newspaper['title']);

                        $longDescription = htmlspecialchars($newspaper['description']);
                        $description = strlen($longDescription) > 200 ? substr($longDescription, 0, 200) . '...' : $longDescription;

                        $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;
                        $publisherId = $newspaper['publisher_id'];
                        $publisher = "Publisher";

                        $price = number_format($newspaper['price'], 2);
                        $publishDate = date('M j, Y', strtotime($newspaper['publish_date']));
                    ?>
                        <div class="bk-card bk-card-lg">
                            <div class="bk-img">
                                <span>
                                    <img src="/cms-data/newspaper/covers/<?= $cover ?>" alt="<?= $title ?>" loading="lazy">
                                </span>
                            </div>
                            <div class="bk-details">
                                <div>
                                    <span class="text-decoration-none">
                                        <p class="bk-heading-xl">
                                            <?= $shortTitle ?>
                                        </p>
                                    </span>
                                    <p class="bk-text-meta">
                                        Published by: <span class="text-muted"><?= $publisher ?></span>
                                    </p>
                                    <p class="bk-text-para">
                                        <?= $description ?>
                                    </p>
                                </div>

                                <div class="bk-tags">
                                    <?php if ($newspaper['category']): ?>
                                        <span class="bk-tag bk-tag-black">
                                            <?= html_entity_decode($newspaper['category']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="book-card-btn btn-right">
                <div>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . "/includes/footer.php"
    ?>

    <?php require_once __DIR__ .  "/includes/scripts.php"
    ?>
</body>