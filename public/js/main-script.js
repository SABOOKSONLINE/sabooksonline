const bookCardsNextBtn = document.querySelectorAll(".btn-right");

// bookCardsNextBtn.forEach((btn) => {
//     btn.addEventListener("click", () => {
//         const current_slider_id = btn.parentElement.id;
//         const book_slider = document.querySelector("#" + current_slider_id);

//         const cardContainer = book_slider.querySelector(".book-card-slide");
//         const firstCard = cardContainer.firstElementChild;

//         const cardStyle = getComputedStyle(firstCard);
//         const cardWidth =
//             firstCard.offsetWidth +
//             parseInt(cardStyle.marginLeft) +
//             parseInt(cardStyle.marginRight);

//         cardContainer.scrollBy({
//             left: cardWidth,
//             behavior: "smooth",
//         });
//     });
// });

const stylishBookNumber = document.querySelectorAll(
    "#stylish-section .book-card-num"
);
let counter = 1;
stylishBookNumber.forEach((card) => {
    card.innerHTML = counter++;
});

const categoryCollapseBtn = document.querySelector(".category-collapse-btn");
const categoryContainer = document.querySelector(".category-container");

if (categoryCollapseBtn && categoryContainer) {
    categoryCollapseBtn.addEventListener("click", () => {
        categoryContainer.classList.toggle("category-all");
        categoryCollapseBtn.firstElementChild.classList.toggle("fa-angle-up");
    });
}

document.addEventListener("DOMContentLoaded", function () {
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

    document.querySelectorAll(".book-card-btn.btn-right").forEach((btn) => {
        btn.addEventListener("click", () => {
            const cardSlide = btn.previousElementSibling;
            const firstCard = cardSlide.firstElementChild;

            firstCard.classList.add("book-slide");
            cardSlide.appendChild(firstCard);
            cardSlide.lastElementChild.classList.remove("book-slide");
        });
    });

    const stylishBookNumber = document.querySelectorAll(
        "#stylish-section .book-card-num"
    );
    let counter = 1;
    stylishBookNumber.forEach((card) => {
        card.innerHTML = counter++;
    });

    const categoryCollapseBtn = document.querySelector(
        ".category-collapse-btn"
    );
    const categoryContainer = document.querySelector(".category-container");

    if (categoryCollapseBtn && categoryContainer) {
        categoryCollapseBtn.addEventListener("click", () => {
            categoryContainer.classList.toggle("category-all");
            categoryCollapseBtn.firstElementChild.classList.toggle(
                "fa-angle-up"
            );
        });
    }
});

const bvSelectBtn = document.querySelectorAll(".bv-purchase-select");
const bvDetails = document.querySelector(".bv-purchase-details");

const print = (value) => {
    console.log(value);
};

let selectedPrice = document.querySelector(".bv-price");
let bvBuyBtn = document.querySelector(".bv-buy-btn");

const updatePrice = (value) => {
    const price = parseInt(value);

    if (price == 0) {
        selectedPrice.innerHTML = "FREE";
        selectedPrice.classList.add("bv-text-green");
    } else {
        selectedPrice.innerText = "R" + price;
    }
};

const selectFirstBvBtn = () => {
    for (let i = 0; i < bvSelectBtn.length; i++) {
        bvSelectBtn[i].classList.add("bv-active");

        const price = bvSelectBtn[i].getAttribute("price");
        updatePrice(price);
        updateBvBuyBtn(bvSelectBtn[i]);
        removePriceDetail(bvSelectBtn[i]);
        // showClickedBtn(bvSelectBtn[i]);
        break;
    }
};

const removePriceDetail = (btn) => {
    const contentAvailable = btn.getAttribute("available");
    if (!contentAvailable) {
        bvDetails.style.display = "none";
    } else {
        bvDetails.style.display = "grid";
    }
};

const updateBvBuyBtn = (btn) => {
    bvBuyBtn.childNodes[1].innerText = btn.firstElementChild.innerText;
};

// const bvMainBtns = document.querySelectorAll(".bv-main-btn");
// const resetBvMainBtn = () => {
//     bvMainBtns.forEach((btn) => {
//         if (!btn.classList.contains("bv-main-btn")) {
//             btn.classList.add("bv-main-btn");
//         }
//     });
// };

// const showClickedBtn = (btn) => {
//     resetBvMainBtn();
//     const bvMainBtn = document.querySelector(
//         "#" + btn.firstElementChild.innerText.toLowerCase()
//     );
//     // bvMainBtn.classList.remove("bv-main-btn");
//     print(bvMainBtn);
// };

const resetBvBuyBtn = () => {
    const bvBuyBtns = document.querySelectorAll(".bv-purchase-details > div");

    bvBuyBtns.forEach((btn) => {
        btn.classList.add("hide");
    });
};

const showPurchaseOption = (optionId) => {
    const btn = document.getElementById(optionId);

    resetBvBuyBtn();

    if (btn && btn.parentElement.classList.contains("hide")) {
        btn.parentElement.classList.remove("hide");
    }
};

selectFirstBvBtn();

const removeBvActive = () => {
    bvSelectBtn.forEach((otherBtns) => {
        otherBtns.classList.remove("bv-active");
    });
};

bvSelectBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
        removeBvActive();
        btn.classList.add("bv-active");

        removePriceDetail(btn);
        updatePrice(btn.getAttribute("price"));
        updateBvBuyBtn(btn);
        showPurchaseOption(btn.firstElementChild.innerText.toLowerCase() + "");
        // showBtn(btn);
    });
});
