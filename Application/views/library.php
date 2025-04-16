<?php
require_once __DIR__ . "/includes/header.php";
?>

<style>
#book-hero-preview {
    background: #fff6f6;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.4s ease-in-out;
}

#book-hero-preview.show {
    opacity: 1;
    transform: translateY(0);
}

#book-hero-preview img {
    max-height: 250px;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

#book-hero-preview img:hover {
    transform: scale(1.05);
}

#book-hero-preview h2 {
    font-size: 1.75rem;
    margin-bottom: 10px;
    color: #222;
}

#book-hero-preview p {
    font-size: 0.95rem;
    color: #555;
    max-height: 110px;
    overflow: hidden;
}

#book-hero-preview .btn {
    margin-top: 15px;
    font-size: 0.9rem;
    padding: 8px 20px;
    background-color: #da0c0c;
    color: #fff;
    border: none;
    transition: background-color 0.2s ease-in-out;
}

#book-hero-preview .btn:hover {
    background-color: #a30808;
}
</style>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.2/color-thief.umd.js"></script>

//to be removed - to its js file (do not delete)
<script>
    const hero = document.getElementById('book-hero-preview');
const heroImg = document.getElementById('hero-img');
const heroTitle = document.getElementById('hero-title');
const heroDesc = document.getElementById('hero-desc');
const heroLink = document.getElementById('hero-link');
const colorThief = new ColorThief();

document.querySelectorAll('.library-book-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
        const title = card.dataset.title;
        const desc = card.dataset.desc;
        const cover = card.dataset.cover;
        const link = card.dataset.link;

        const tempImage = new Image();
        tempImage.crossOrigin = 'Anonymous';
        tempImage.src = cover;

        tempImage.onload = () => {
            const dominantColor = colorThief.getColor(tempImage);
            const gradient = `linear-gradient(135deg, rgb(${dominantColor.join(',')}), #ffffff)`;
            hero.style.background = gradient;
        };

        heroImg.src = cover;
        heroTitle.textContent = title;
        heroDesc.textContent = desc.length > 180 ? desc.slice(0, 180) + "..." : desc;
        heroLink.href = link;

        hero.classList.remove('d-none');
        hero.classList.add('show');
    });
});



</script>
