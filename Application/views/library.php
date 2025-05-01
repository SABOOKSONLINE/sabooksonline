<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/BookModel.php";
require_once __DIR__ . "/../controllers/BookController.php";

$controller = new BookController($conn);
$category = $_GET['category'] ?? null;
$page = $_GET['page'] ?? 1;

// Get keyword and fetch books
$keyword = $_GET['k'] ?? null;
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <?php 
    if($keyword != null) {
        $controller->RenderSearchedBooks($keyword); 
    }
    ?>

    <div class="container py-4">
        <h1 class="fw-bold mb-0">Library</h1>
        <span class="text-muted">The Never-Closing Library</span>

        

        <div class="category-container py-3">
            <div class="">
                <?php
                $controller->renderCategories();
                ?>
            </div>
            <span class="btn category-collapse-btn">
                <i class="fas fa-angle-down"></i>
            </span>
        </div>

        <div class="row py-3">
            <?php
            if ($category) {
                $controller->renderLibraryByCategory($category, $page);
            } else {
                $controller->renderAllBooks($page);
            }
            ?>
        </div>

        <div class="py-3">
            <nav aria-label="Page navigation">
                <?php
                if (!$category) {
                    $controller->renderPagination();
                }
                ?>
            </nav>
        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php"; ?>
    <?php require_once __DIR__ . "/includes/scripts.php"; ?>
</body>


<!-- to be removed - to its js file (do not delete) -->
<!-- <script>
    const hero = document.getElementById('book-hero-preview');
    const heroImg = document.getElementById('hero-img');
    const heroTitle = document.getElementById('hero-title');
    const heroDesc = document.getElementById('hero-desc');
    const heroLink = document.getElementById('hero-link');

    document.querySelectorAll('.library-book-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            const title = card.dataset.title;
            const desc = card.dataset.desc;
            const cover = card.dataset.cover;
            const link = card.dataset.link;

            heroImg.src = cover;
            heroTitle.textContent = title;
            heroDesc.textContent = desc.length > 180 ? desc.slice(0, 180) + "..." : desc;
            heroLink.href = link;

            hero.classList.remove('d-none');
        });
    });
</script> -->