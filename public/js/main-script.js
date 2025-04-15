// Books cards slider
const bookCardsNextBtn = document.querySelectorAll(".btn-right");

bookCardsNextBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
        let current_slider_id = btn.parentElement.id;

        const book_slider = document.querySelector("#" + current_slider_id);
        book_slider.firstElementChild.firstElementChild.classList.add(
            "book-slide"
        );

        book_slider.firstElementChild.append(
            book_slider.firstElementChild.firstElementChild
        );

        book_slider.firstElementChild.lastElementChild.classList.remove(
            "book-slide"
        );
    });
});

const stylishBookNumber = document.querySelectorAll(
    "#stylish-section .book-card-num"
);

counter = 1;

stylishBookNumber.forEach((card) => {
    card.innerHTML = counter;
    counter++;
});

// Contact form
$(document).ready(function (e) {
    $("#contactforms").on("submit", function (e) {
        $("#status").html(
            '<p class="alert alert-info">Processing your message...</p>'
        );
        e.preventDefault();
        $.ajax({
            url: "message.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                $("#status").html(data);
            },
            error: function () {},
        });
    });
});
