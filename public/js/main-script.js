document.addEventListener("DOMContentLoaded", function () {
    // ===== Auto Scrolling Book Cards =====
    function setupAutoScroll(container, direction = "right") {
        const cards = Array.from(container.children);

        cards.forEach((card) => {
            const clone = card.cloneNode(true);
            container.appendChild(clone);
        });

        const scrollSpeed = 1;
        let isPaused = false;

        if (direction === "left") {
            container.scrollLeft = container.scrollWidth / 2;
        }

        container.addEventListener("mouseenter", () => {
            isPaused = true;
        });
        container.addEventListener("mouseleave", () => {
            isPaused = false;
        });

        function scrollLoop() {
            if (!isPaused) {
                if (direction === "right") {
                    container.scrollLeft += scrollSpeed;
                    if (container.scrollLeft >= container.scrollWidth / 2) {
                        container.scrollLeft = 0;
                    }
                } else {
                    container.scrollLeft -= scrollSpeed;
                    if (container.scrollLeft <= 0) {
                        container.scrollLeft = container.scrollWidth / 2;
                    }
                }
            }
            requestAnimationFrame(scrollLoop);
        }

        setTimeout(scrollLoop, 500);
    }

    document.querySelectorAll(".scroll-right").forEach((el) => {
        setupAutoScroll(el, "right");
    });

    document.querySelectorAll(".scroll-left").forEach((el) => {
        setupAutoScroll(el, "left");
    });

    // ===== Book Slide Button Behavior =====
    document.querySelectorAll(".book-card-btn.btn-right").forEach((btn) => {
        btn.addEventListener("click", () => {
            const cardSlide = btn.previousElementSibling;
            const firstCard = cardSlide.firstElementChild;

            firstCard.classList.add("book-slide");
            cardSlide.appendChild(firstCard);
            cardSlide.lastElementChild.classList.remove("book-slide");
        });
    });

    // ===== Stylish Book Number =====
    const stylishBookNumber = document.querySelectorAll(
        "#stylish-section .book-card-num"
    );
    let counter = 1;
    stylishBookNumber.forEach((card) => {
        card.innerHTML = counter++;
    });

    // ===== Category Collapse Button =====
    const categoryCollapseBtn = document.querySelector(".category-collapse-btn");
    const categoryContainer = document.querySelector(".category-container");

    if (categoryCollapseBtn && categoryContainer) {
        categoryCollapseBtn.addEventListener("click", () => {
            categoryContainer.classList.toggle("category-all");
            categoryCollapseBtn.firstElementChild.classList.toggle("fa-angle-up");
        });
    }

    // ===== Purchase Price Buttons =====
    const bvSelectBtn = document.querySelectorAll(".bv-purchase-select");
    const bvDetails = document.querySelector(".bv-purchase-details");
    const selectedPrice = document.querySelector(".bv-price");
    const bvBuyBtn = document.querySelector(".bv-buy-btn");

    const updatePrice = (value) => {
        const price = parseInt(value);
        if (price === 0) {
            selectedPrice.innerHTML = "FREE";
            selectedPrice.classList.add("bv-text-green");
        } else {
            selectedPrice.innerText = "R" + price;
            selectedPrice.classList.remove("bv-text-green");
        }
    };

    const removePriceDetail = (btn) => {
        const contentAvailable = btn.getAttribute("available");
        bvDetails.style.display = contentAvailable ? "grid" : "none";
    };

    const updateBvBuyBtn = (btn) => {
        bvBuyBtn.childNodes[1].innerText = btn.firstElementChild.innerText;
    };

    const resetBvBuyBtn = () => {
        const bvBuyBtns = document.querySelectorAll(".bv-purchase-details > div");
        bvBuyBtns.forEach((btn) => btn.classList.add("hide"));
    };

    const showPurchaseOption = (optionId) => {
        const btn = document.getElementById(optionId);
        resetBvBuyBtn();
        if (btn && btn.parentElement.classList.contains("hide")) {
            btn.parentElement.classList.remove("hide");
        }
    };

    const removeBvActive = () => {
        bvSelectBtn.forEach((btn) => btn.classList.remove("bv-active"));
    };

    const selectFirstBvBtn = () => {
        if (bvSelectBtn.length > 0) {
            const firstBtn = bvSelectBtn[0];
            firstBtn.classList.add("bv-active");
            const price = firstBtn.getAttribute("price");
            updatePrice(price);
            updateBvBuyBtn(firstBtn);
            removePriceDetail(firstBtn);
            showPurchaseOption(firstBtn.firstElementChild.innerText.toLowerCase());
        }
    };

    selectFirstBvBtn();

    bvSelectBtn.forEach((btn) => {
        btn.addEventListener("click", () => {
            removeBvActive();
            btn.classList.add("bv-active");
            updatePrice(btn.getAttribute("price"));
            updateBvBuyBtn(btn);
            removePriceDetail(btn);
            showPurchaseOption(btn.firstElementChild.innerText.toLowerCase());
        });
    });

    // ===== Rating Star Behavior =====
    const ratingStars = document.querySelectorAll(".rating-star");

    ratingStars.forEach((star) => {
        star.addEventListener("click", function () {
            const rating = parseInt(this.getAttribute("data-rating"));
            const ratingInput = document.getElementById("rating_value");
            ratingInput.value = rating;

            ratingStars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove("far");
                    s.classList.add("fas", "text-warning");
                } else {
                    s.classList.remove("fas", "text-warning");
                    s.classList.add("far");
                }
            });
        });
    });

    const commentForm = document.getElementById("commentForm");

    if (commentForm) {
        commentForm.addEventListener("submit", function (e) {
            const ratingValue = document.getElementById("rating_value").value;
            if (!ratingValue || parseInt(ratingValue) < 1) {
                e.preventDefault();
                alert("Please select a star rating before submitting your review.");
            }
        });
    }
});
