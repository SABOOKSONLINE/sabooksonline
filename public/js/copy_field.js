// Copy input field script (safe version)
document.addEventListener("DOMContentLoaded", function () {
    const copyButton = document.getElementById("copyLink");

    // Check if the copy button exists before continuing
    if (!copyButton) return;

    copyButton.addEventListener("click", function () {
        const inputGroup = this.closest(".input-group");
        if (!inputGroup) {
            console.warn("No .input-group parent found for copy button.");
            return;
        }

        const linkInput = inputGroup.querySelector("input");
        if (!linkInput || !linkInput.value) {
            console.warn("No input field found or input is empty.");
            return;
        }

        const textToCopy = linkInput.value.trim();
        if (!textToCopy) {
            console.warn("Nothing to copy — input is empty.");
            return;
        }

        // Modern clipboard API (with fallback)
        if (
            navigator.clipboard &&
            typeof navigator.clipboard.writeText === "function"
        ) {
            navigator.clipboard
                .writeText(textToCopy)
                .then(() => {
                    copyButton.innerHTML = '<i class="fas fa-check"></i>';
                    setTimeout(() => {
                        copyButton.innerHTML = '<i class="fas fa-copy"></i>';
                    }, 2000);
                })
                .catch((err) => {
                    console.error("Clipboard API failed:", err);
                    fallbackCopy(textToCopy);
                });
        } else {
            fallbackCopy(textToCopy);
        }
    });

    function fallbackCopy(text) {
        try {
            const tempInput = document.createElement("input");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            const success = document.execCommand("copy");
            document.body.removeChild(tempInput);

            if (success) {
                alert("Link copied!");
            } else {
                alert("Failed to copy link. Please copy manually.");
            }
        } catch (err) {
            console.error("Fallback copy failed:", err);
            alert("Unable to copy text automatically.");
        }
    }
});
