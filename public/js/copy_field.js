// copy input field script
document.addEventListener("DOMContentLoaded", function () {
    const copyButton = document.getElementById("copyLink");

    copyButton.addEventListener("click", function () {
        const linkInput = this.closest(".input-group").querySelector("input");
        const textToCopy = linkInput.value;

        if (navigator.clipboard) {
            navigator.clipboard
                .writeText(textToCopy)
                .then(() => {
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-copy"></i>';
                    }, 2000);
                })
                .catch((err) => {
                    console.error("Clipboard error:", err);
                    fallbackCopy(textToCopy);
                });
        } else {
            fallbackCopy(textToCopy);
        }
    });

    function fallbackCopy(text) {
        const tempInput = document.createElement("input");
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        alert("Link copied!");
    }
});
