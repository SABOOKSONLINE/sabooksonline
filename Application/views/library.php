<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>


    <!-- <section class="tags">
        <div class="container py-5 category-container">
            <div class="d-flex flex-wrap justify-content-center">
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
            </div>
        </div>
    </section> -->

    

    


    <div class="container py-4 ">
        <div class="mb-5">
            <h1 class="fw-bold mb-0">Library</h1>
            <span class="text-muted">
                Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.
            </span>
        </div>

        <div id="book-hero-preview" class="row mb-5 align-items-center d-none">
    <div class="col-md-3">
        <img id="hero-img" class="img-fluid rounded" src="" alt="Book Cover">
    </div>
    <div class="col-md-9">
        <h2 id="hero-title" class="fw-bold"></h2>
        <p id="hero-desc" class="text-muted mb-2"></p>
        <a id="hero-link" href="#" class="btn btn-outline-primary">View Book</a>
    </div>
    </div>

        <div class="row">
            <?php
            require_once __DIR__ . "/../../database/connection.php";
            require_once __DIR__ . "/../models/Book.php";
            require_once __DIR__ . "/../controllers/BookController.php";
            allBooks($conn, limit: 10);
            ?>

        </div>
        <div class="py-3">
            <a href="" class="btn btn-red gap">
                Show More
                <i class="fas fa-angle-down"></i>
            </a>

        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>

//to be removed - to its js file (do not delete)
<script>
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

</script>
