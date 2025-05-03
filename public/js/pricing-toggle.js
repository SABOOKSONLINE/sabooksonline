document.addEventListener("DOMContentLoaded", function () {
    const billingToggle = document.getElementById("billingToggle");

    if (billingToggle) {
        // Check if the toggle exists on the page
        billingToggle.addEventListener("change", function () {
            // Toggle monthly/yearly prices
            document.querySelectorAll(".monthly-price").forEach((el) => {
                el.classList.toggle("d-none", this.checked);
            });
            document.querySelectorAll(".yearly-price").forEach((el) => {
                el.classList.toggle("d-none", !this.checked);
            });

            // Update the trial button links to include billing cycle
            document.querySelectorAll(".btn-red").forEach((btn) => {
                const currentHref = btn.getAttribute("href");
                if (this.checked) {
                    btn.setAttribute(
                        "href",
                        currentHref.split("?")[0] +
                            "?plan=" +
                            btn.getAttribute("href").split("=")[1] +
                            "&billing=yearly"
                    );
                } else {
                    btn.setAttribute(
                        "href",
                        currentHref.split("?")[0] +
                            "?plan=" +
                            btn.getAttribute("href").split("=")[1] +
                            "&billing=monthly"
                    );
                }
            });
        });
    }
});
