<?php
require_once __DIR__ . "/includes/header.php";

require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/BookModel.php";
require_once __DIR__ . "/../controllers/BookController.php";
require_once __DIR__ . "/../models/BannerModel.php";
require_once __DIR__ . "/../controllers/BannerController.php";
require_once __DIR__ . "/../models/AcademicBookModel.php";
require_once __DIR__ . "/../controllers/AcademicBookController.php";

$bookController = new BookController($conn);
$academicBookController = new AcademicBookController($conn);

?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="jumbotron jumbotron-lg bg-dark text-white">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
            <div class="jumbo-details">
                <h1 class="display-4 fw-bold">Welcome to <b>SABooksOnline</b></h1>
                <div class="row">
                    <div class="col-12 col-md-7">
                        <p class="lead mb-3 text-shadow">
                            The Gateway to South African Literature. Discover stories that reflect the heart of South Africa, from township tales to historic struggles and modern voices.
                        </p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <a class="btn btn-red me-2" href="/library" role="button">
                        Explore Library <i class="fas fa-arrow-right"></i>
                    </a>
                    <a class="btn btn-white" href="/signup" role="button">
                        Publish with Us
                    </a>
                </div>
            </div>
        </div>
    </div>



    <div class="container">
        <?php
        include_once __DIR__ . "/includes/banner.php";
        ?>
    </div>

    <section class="section" id="stylish-section">
        <div class="container-fluid">
            <div class="container">
                <?php renderSectionHeading("Recommended For You", "Your Next Great Read Starts Here.", "Show more", "/library") ?>
            </div>

            <div class="book-cards mt-4" id="recommended">
                <div class="book-card-slide scroll-right">
                    <?php
                    $bookController->renderBookCardByCategory();
                    ?>
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
            <?php renderSectionHeading("Textbooks", "Educational Resources for Learning and Growth.", "Show more", "/academic") ?>

            <div class="row mt-4" id="textbooks">
                <?php
                $academicBooks = $academicBookController->getAllBooks(['sort' => 'newest']);
                $academicBooks = array_slice($academicBooks, 0, 10);
                foreach ($academicBooks as $book):
                    $publicKey = html_entity_decode($book['public_key'] ?? '');
                    $cover = html_entity_decode($book['cover_image_path'] ?? '');
                    $title = html_entity_decode($book['title'] ?? '');
                    $author = html_entity_decode($book['author'] ?? '');
                    $ebookPrice = $book['ebook_price'] ?? 0;
                    $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;
                ?>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="bk-card h-100">
                            <div class="bk-img">
                                <a href="library/academic/<?= $publicKey ?>">
                                    <?php if (!empty($cover)): ?>
                                        <img src="/cms-data/academic/covers/<?= $cover ?>" alt="<?= $title ?>" class="w-100">
                                    <?php else: ?>
                                        <div style="width: 100%; height: 100%; min-height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                            No Cover
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="bk-details">
                                <div>
                                    <a href="library/academic/<?= $publicKey ?>" class="text-decoration-none">
                                        <p class="bk-heading">
                                            <?= $shortTitle ?>
                                        </p>
                                    </a>
                                    <?php if ($author): ?>
                                        <p class="bk-text-meta">
                                            Author: <span class="text-muted"><?= $author ?></span>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($ebookPrice > 0): ?>
                                        <p class="bk-text-meta">
                                            Price: <span class="text-muted">R<?= number_format($ebookPrice, 2) ?></span>
                                        </p>
                                    <?php else: ?>
                                        <p class="bk-text-meta">
                                            <span class="text-success">FREE</span>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container-fluid">
            <div class="container">
                <?php renderSectionHeading("Editor's Choice", "Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.", "Show more", "/library") ?>
            </div>

            <div class="book-cards mt-4" id="editors_choice">
                <div class="book-card-slide scroll-left">
                    <?php
                    $bookController->renderBookCardByCategory("editors choice", 6, true);
                    ?>
                </div>

                <div class="book-card-btn btn-right">
                    <div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require_once __DIR__ . "/includes/mobile.php" ?>

    <?php include_once __DIR__ . "/includes/newsLetter.php" ?>

    <section class="section">
        <div class="container">
            <?php renderSectionHeading("Latest Collections", "Fresh Off the Press — Discover the Newest Reads.", "Show more", "/library") ?>

            <div class="book-cards mt-4" id="latest_collections">
                <div class="book-card-slide">
                    <?php
                    $bookController->renderListingsByCategory("Latest Collections", 10);
                    ?>

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
            <?php renderSectionHeading("Fiction Collections", "Escape Into Stories — Fiction That Moves You.", "Show more", "/library") ?>

            <div class="book-cards mt-4" id="fiction_collections">
                <div class="book-card-slide">
                    <?php
                    $bookController->renderListingsByCategory("fiction collections", 10);
                    ?>

                </div>

                <div class="book-card-btn btn-right">
                    <div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-children">
        <div class="container">
            <div>
                <?php renderSectionHeading("Children's Collections", "Rooted in Wisdom, Growing Through Stories — Empowering African Children to Read and Rise.", "Show more", "/library") ?>

                <div class="book-cards mt-4" id="childrens_collections">
                    <div class="book-card-slide">
                        <?php
                        $bookController->renderListingsByCategory("childrens collection", 10);
                        ?>

                    </div>

                    <div class="book-card-btn btn-right">
                        <div>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . "/includes/payfast.php" ?>
    <?php require_once __DIR__ . "/includes/footer.php" ?>


    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>